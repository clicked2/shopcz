<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Goods as GoodsModel;
use think\Request;

use think\Db;
use think\facade\Cache;

use app\admin\model\Brand as BrandModel;


class Goods extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$request = request();
		$validate = self::validate($request->param(), 'app\validate\Goods.'.$this->request->action());
		if ( $validate !== true ) return self::error('rule: '.implode(', ', $validate));
	}
	/**
	* 显示资源列表 GET
	*
	* @return \think\Response
	*/
	public function index()
	{
		
		return 'Goods index';
	}

	/**
	* 显示添加商品页 GET
	*
	* @param \think\Request $request
	* @return \think\Response
	*/
	public function add()
	{
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
		//上传logo 添加数据
		if ( !$goods->logoUpload() ) return self::error('添加失败: ' . $goods->info);

		if( !$goods->save() ) {
			self::error('添加失败: ' . lang('data_insert'));
		} else {
			//关联新增 会员价格
			!$goods->addMemberPrice($request) ? self::error('添加失败: ' . lang('data_insert')) :
			self::success('添加成功');
		}

	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$request = request();
		//链式查询
		$list = GoodsModel::hasWhere('brand', function($query) {
					$request = request();
					$query->where('brand.brand_name', 'like', '%'.$request->param('bn').'%');
				})->withSearch(['goods_name', 'priceBetween' => 'pb', 'createTime' => 'ct'], [
					'goods_name' => $request->param('gn'),
					'pb' => [$request->param('fp') ? $request->param('fp') : '0', $request->param('sp') ? $request->param('sp') : (string)1e9],
					'ct' => [$request->param('fa') ? $request->param('fa') : '1970-1-1', $request->param('ta') ? $request->param('ta') : date('Y-m-d H:i:s')]
				])->resultorder($request->param('odby'))
				->paginate(1);
		self::assign('list', $list);
		return view('read');
	}

	/**
	* 显示编辑资源单页 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function edit($id)
	{
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
		if ( !$goods->logoUpload(true) ) return self::error('修改失败: ' . $goods->info);
		return !$goods->save() ? self::error('修改失败: ' . lang('data_insert')) : self::success('修改成功', './goods/read');
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
		if ( $goods ) {
			if ( $goods->delete() ) {
				$goods->imageDelete();
				return json(['id' => $goods->id,'msg' => 'success']);
			}
		}
		return json(['error'=> lang('delete_error')]);
		
	}

}