<?
namespace app\index\model;
use think\Model;

use think\facade\Session;
use think\facade\Cookie;

use think\Db;

class Flow extends Model
{
	
	public function addCart()
	{
		//添加cookies
		$goods = json_decode(request()->param('goods'), 1);
		
		$newArr = self::getNewCookies(isset($goods['goods_id']) ? $goods['goods_id'] : 0);
		$newArr[] = $goods;
	
		Cookie::set('data', serialize($newArr), [
			'expire' => 60 * 60 * 24 * 30,
			'prefix' => 'cart_'
		]);
		if ( Session::get('uid', 'sc') ) {
			//添加到数据库
			$goods['user_id'] = Session::get('uid', 'sc');
			$cart = new Cart;
			$cart->where(['goods_id' => isset($goods['goods_id']) ? $goods['goods_id'] : 0 , 'user_id' => Session::get('uid', 'sc')])->delete();
			$cart->save($goods);
			return true;
		}
		return false;
	}
	public function deleteCart($goods_id)
	{
		//remove cookies
		$newArr = self::getNewCookies($goods_id);
	
		Cookie::set('data', serialize($newArr), [
			'expire' => 60 * 60 * 24 * 30,
			'prefix' => 'cart_'
		]);
		if ( Session::get('uid', 'sc') ) {
			// remove to base
			$cart = new Cart;
			$cart->where(['goods_id' => $goods_id, 'user_id' => Session::get('uid', 'sc')])->delete();
		}
		return true;
	}
	public function getNewCookies($goods_id)
	{
		$cartArr = unserialize(cookie::get('data', 'cart_'));
		$newArr = [];
		if ( $cartArr !== false ) {
			foreach ($cartArr as $key => $value) {
				if ( $value['goods_id'] != $goods_id ) {
					$newArr[] = $value;
				} 
			}
		}
		return $newArr;
	}
	public function getCookieData($goods_id)
	{
		$cartArr = unserialize(cookie::get('data', 'cart_'));
		$newArr = [];
		if ( $cartArr !== false ) {
			foreach ($cartArr as $key => $value) {
				if ( $value['goods_id'] == $goods_id ) {
					$newArr[] = $value;
					break;
				} 
			}
		}
		return $newArr;
	}
	public function getFlow2Goods()
	{
		Cookie::clear('list', 'goods_');
		Cookie::set('list', serialize(request()->param()), ['prefix' => 'goods_']);
		return 'ok';
	}
	public function getCartNumber()
	{
		if ( Session::get("uid", "sc") ) {
		    $list = db("cart")->field('goods_id')->where("user_id", Session::get("uid", "sc"))->select();
		} else {
		    $list = unserialize(Cookie::get('data', 'cart_'));
		}
		return $list ? count($list) : 0;
	}
	public function getSumNumber($val, $number)
	{
		$product = db('product')->field('goods_number')->where('goods_attr', $val)->find();
		if ( $product ) {
			if ( $number <= $product['goods_number']  ) {
				return json(['msg' => '有货']);
			} else {
				return json(['msg' => '库存不足']);
			}
		} else {
			return json(['msg' => '属性错误']);
		}
	}
	public function dataInsert()
	{
		if ( Session::get('uid', 'sc') ) {
			//setup address
			$address = new Address;
			$arr = request()->param();
			$uid = Session::get('uid', 'sc');
			$arr['user_id'] =  $uid;
			$address_existed = db('address')->where('user_id', $uid)->find();
			!$address_existed ? $address->save($arr) : Address::get($address_existed['id'])->save($arr);

			$list = unserialize(Cookie::get('list', 'goods_'));
			
			$productMinus = [];

			$orderGoodsIndex = 0;
			$arr['out_trade_no'] = time().rand(111111, 999999);
			foreach ($list as $key => $value) {
				$datas = json_decode($value, 1);
				$goodsData = self::getCookieData($datas['goodsId']);
				if ( $goodsData ) {
					//setup product goods stta str
					$attrstr = '';
					if ( isset($goodsData[0]) ) {
						foreach ($goodsData[0]['attrs'] as $k => $v) {
							if ( !$k ) {
								$attrstr .= $v['goods_attr_id'].', ';
							} else {
								$attrstr .= $v['goods_attr_id'];
							}
						}
						//calc price pass

						//setup product
						$product = Product::where('goods_attr', $attrstr)->find();
						if ( $product ) {
							$number = (int)$product['goods_number'] - (int)$goodsData[0]['cart_number'];
							if ( $number >= 0 ) {
								$productMinus[] = ['pid' => $product['id'], 'minusNum' => $goodsData[0]['cart_number']];
								Product::get($product['id'])->save(['goods_number' => Db::raw('goods_number - '.$goodsData[0]['cart_number'])]);
							} else {
								//此处应该把其他减完的商品在加上去 返回一个下单失败 库存不足;
								foreach ($productMinus as $key => $value) {
									Product::get($value['pid'])->save(['goods_number' => Db::raw('goods_number + '.$value['minusNum'])]);
								}
								return '库存不足';
							}
						}

					

						//add order_goods data
						$order_goods_data = [];
						//goodsId
						$order_goods_data[$orderGoodsIndex]['goods_id'] = $goodsData[0]['goods_id'];
						$goods = Goods::get($order_goods_data[$orderGoodsIndex]['goods_id'])->field('goods_name, shop_price, market_price')->find();
						//goodsName
						$order_goods_data[$orderGoodsIndex]['goods_name'] = $goods['goods_name'];
						//goodsPrice pass
						$order_goods_data[$orderGoodsIndex]['goods_price'] = $goods['shop_price'];
						//goods market Price pass
						$order_goods_data[$orderGoodsIndex]['goods_market_price'] = $goods['market_price'];
						//goods sum 
						$order_goods_data[$orderGoodsIndex]['goods_sum'] = $goodsData[0]['cart_number'];
						//goods attrs 
						$order_goods_data[$orderGoodsIndex]['goods_attrs'] = $goodsData[0]['attrs'];
						
					}
				}
				$orderGoodsIndex++;
			}
			
			// insert order
			$order = new Order;
			$order->save($arr);
			foreach ($order_goods_data as $key => $value) {
				//goods orderId
				$order_goods_data[$key]['order_id'] = $order->id;
			}
			
			//insert order_goods
			$orderGoods = new OrderGoods;
			$orderGoods->saveAll($order_goods_data);
			return 'ok';
		}
	}
}