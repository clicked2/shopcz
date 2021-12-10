<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Goods as GoodsModel;
use think\Request;
use app\extend\catetree\Catetree;

use think\Db;
use think\facade\Cache;

class Goods extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Goods.'.$this->request->action());
		if ( $validate !== true ) return self::error('rule: '.implode(', ', $validate));
	}
	/**
	* 显示资源列表 GET
	*
	* @return \think\Response
	*/
	public function index()
	{
		return view('index/index');
	}

	/**
	* 显示添加商品页 GET
	*
	* @param \think\Request $request
	* @return \think\Response
	*/
	public function add()
	{
		$cate = new Catetree();
        $list = $cate->catetree(db('category')->order('sort', 'desc')->select());

		self::assign('cate_list', $list);
		return view('add');
	}
	
	/**
	*  添加新的商品 POST 
	*
	* @param \think\Request $request
	* @return \think\Response
	*/
	public function save(Request $request)
	{
		$goods = new GoodsModel();

		if ( !empty($goods->info) ) return self::error('添加失败: 添加数据失败');

		return !$goods->save($request->param()) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './goods/read');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = GoodsModel::field('g.*, c.cate_name, b.brand_name, t.type_name, SUM(p.goods_number) gn')->alias('g')->join([['category c', 'g.category_id=c.id'], ['brand b', 'g.brand_id=b.id', 'LEFT'], ['type t', 'g.type_id=t.id', 'LEFT'], ['product p', 'g.id=p.goods_id', 'LEFT']])->group('g.id')->order('id', 'desc')->paginate(5);

		self::assign('list', $list);
		return view('read');
	}
	//sum('p.goods_number')->
	/**
	* 显示编辑资源单页 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function edit($id)
	{
		$cate = new Catetree();
        $list = $cate->catetree(db('category')->order('sort', 'desc')->select());

		self::assign('cate_list', $list);
		self::assign('id', $id);
		return view('edit');
	}

	/**
	* 保存更新的资源 PUT
	*
	* @param \think\Request $request
	* @param int $id
	* @return \think\Response
	*/
	public function update(Request $request, $id)
	{
		$goods = GoodsModel::get($id);
		
		return !$goods->save($request->param()) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './goods/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$goods = GoodsModel::get($id);
		
		return $goods->delete() ? json(['id' => $goods->id, 'msg' => 'success']) :
		json(['id' => $goods->id, 'msg' => 'error']);
	}
	public function product($id)
	{
		if ( $id ) {
			$datas = db('goods_attr')->field('g.id, g.attr_value, a.attr_name')->alias('g')->join('attr a', "g.attr_id=a.id")->where(['g.goods_id' => $id, 'a.attr_type' => '1'])->select();
			$list = [];
			foreach ($datas as $key => $value) {
				$list[$value['attr_name']][] = $value;
			}
			self::assign('list', $list);
			self::assign('id', $id);
			return view('product');
		}
	}
	public function productSubmit($id)
	{
		if ( $id ) {
			$list = [];
			if ( is_array(request()->param('goods_num')) ) {
				foreach (request()->param('goods_num') as $key => $value) {
					$arr = [];
					if ( is_array(request()->param('goods_attr')) ) {
						foreach (request()->param('goods_attr') as $k => $v) {
							if ( empty($v[$key]) ) {
								$arr = [];
								break;
							}
							$arr[] = $v[$key];
						}
					}
					
					if ( !empty($arr) ) {
						$bool = false;
						foreach ($list as $k => $v) {
							if ( in_array(implode(', ', $arr), $v) ) $bool = true;
						}
						if ( !$bool )
							$list[] = ['goods_id' =>  $id, 'goods_number' => $value, 'goods_attr' => implode(', ', $arr)];
					}
				}
			}
			if ( !empty($list) ) {
				db('product')->where('goods_id', $id)->delete();
				if ( db('product')->insertAll($list) ) self::success('添加成功', './goods/read');
			}
		}
	}
	public function deletePhoto($id)
	{
		$photo = db('goods_photo')->where('id', $id)->find();
		if ( $photo ) {
			if ( isset($photo['sm_photo']) ) @unlink('static' . DS . 'uploads' . DS . $photo['sm_photo']);
			if ( isset($photo['mid_photo']) ) @unlink('static' . DS . 'uploads' . DS . $photo['mid_photo']);
			if ( isset($photo['big_photo']) ) @unlink('static' . DS . 'uploads' . DS . $photo['big_photo']);
			if ( db('goods_photo')->where('id', $id)->delete() ) {
				return json(['id' => $id, 'msg' => 'success']);
			}
		}
		return json(['id' => $goods->id, 'msg' => 'error']);
	}
	public function getGoodsAttr($id)
	{
		return json(db('goods_attr')->where('goods_id', $id)->select());
	}
	public function deleteAttr($id='', $name='')
	{
		return json(db('goods_attr')->where(['goods_id' => $id, 'attr_value' => $name])->delete());
	}

}