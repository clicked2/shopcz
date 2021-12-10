<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Category as CategoryModel;
use think\Request;

use think\Db;
use think\facade\Cache;
use app\extend\catetree\Catetree;



class Category extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Category.'.$this->request->action());
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
		$data = db('category')->order('sort', 'desc')->select();
		$cate = $cate->catetree($data);
	
		self::assign('list', $cate);

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
		$cate = new CategoryModel();
		$arr = $request->param();
	
		if ( isset($_FILES['cate_img']) && $_FILES['cate_img']['tmp_name'] )
			$arr['cate_img'] = $cate->upload();

		return !$cate->save($arr) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './category/read');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		if ( request()->param('sort') && is_array(request()->param('sort')) ) {
			$categorymodel = new CategoryModel();
			foreach (request()->param('sort') as $key => $value)
				if ( $value != 'undefined' ) $a[] = ['id' => $key, 'sort' => $value];
			$categorymodel->saveAll($a);
		}

		$cate = new Catetree();
		self::assign('list', $cate->catetree(db('category')->order('sort', 'desc')->select()));
		self::assign('type', [1 => "系统分类", 2 => "帮助分类", 3 => "网店帮助", 4 => "网店信息", 5 => "普通分类"]);
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
		$cate = new Catetree();

		self::assign('list', $cate->catetree(db('category')->order('sort', 'desc')->select()));
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
		$cate = CategoryModel::get($id);
		$arr = $request->param();

		if ( isset($_FILES['cate_img']) && $_FILES['cate_img']['tmp_name'] )
			$arr['cate_img'] = $cate->removeLogo();
		
		return !$cate->save($arr) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './category/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$catetree = new Catetree();
		$result = $catetree->childrenids(db('category'), $id);
		$result[] = $id;
		//删除缩略图
		$cateImgs = db('category')->field('id, cate_img')->where(['id' => $result])->select();
		foreach ($cateImgs as $key => $value) {
			@unlink('static/uploads/' . $value['cate_img']);
		}

	 	return CategoryModel::destroy($result) ? json(['id' => $id, 'msg' => 'success']) :
	 	json(['id' => $id, 'msg' => 'error']);
	}
}