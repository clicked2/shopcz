<?
namespace app\index\model;

use think\Model;

class Goods extends Model
{
	public function scopeFilter($query)
	{
		//品牌
		if ( request()->param('brand_id') ) {
			$query->where('g.brand_id', request()->param('brand_id'));
		}
		//价格
		$price = request()->param('price');
		if ( $price ) {
			$arr = [];
			if ( count($price) == 1 ) {
				$arr[] = substr($price[0], 0, strpos($price[0], ' - '));
				$arr[] = substr($price[0], strpos($price[0], ' - ')+3, strlen($price[0]));
			} else if ( count($price) == 2 ) {
				$arr = $price;
			}
			$query->whereBetween('g.shop_price', $arr);
		}
	}
}