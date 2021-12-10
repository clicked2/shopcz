<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\CategoryAd as CategoryAdModel;
use think\Request;

use think\Db;
use think\facade\Cache;



class CategoryAd extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\CategoryAd.'.$this->request->action());
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
		$categoryad = new CategoryAdModel();
		$arr = $request->param();

		if ( isset($_FILES['img_src']) && !empty($_FILES['img_src']['tmp_name']) )
			$arr['img_src'] = $categoryad->upload();
		
		
		if ( !empty($categoryad->info) ) return self::error('添加失败: '.$categoryad->info);
		
		return !$categoryad->save($arr) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './categoryad/read');
		
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = CategoryAdModel::order('id', 'desc')->field('c.*,ct.cate_name')->alias('c')->join('category ct', 'c.category_id=ct.id')->paginate(5);
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
		$categoryad = CategoryAdModel::get($id);
		$arr = $request->param();

		if ( isset($_FILES['img_src']) && !empty($_FILES['img_src']['tmp_name']) ) 
			$arr['img_src'] = $categoryad->removeLogo();

		if ( !empty($categoryad->info) ) return self::error('修改失败: '.$categoryad->info);
		
		return !$categoryad->save($arr) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './categoryad/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$categoryad = CategoryAdModel::get($id);
		if ( !empty($categoryad->img_src) ) $categoryad->removeLogo();
		return $categoryad->delete() ? json(['id' => $categoryad->id, 'msg' => 'success']) :
		json(['id' => $categoryad->id, 'msg' => 'error']);
	}
}