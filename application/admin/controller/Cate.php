<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Cate as CateModel;
use think\Request;

use think\Db;
use think\facade\Cache;
use app\extend\catetree\Catetree;



class Cate extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Cate.'.$this->request->action());
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
		$data = db('cate')->order('sort', 'desc')->select();
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
		$cate = new CateModel();
		$arr = $request->param();

		return !$cate->save($arr) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './cate/read');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		if ( request()->param('sort') ) {
			$catemodel = new CateModel();
			$rules = request()->param('sort');
			if ( is_array($rules) ) {
				foreach ($rules as $key => $value)
					if ( $value != 'undefined' ) $a[] = ['id' => $key, 'sort' => $value];
				$catemodel->saveAll($a);
			}
		}

		$cate = new Catetree();
		self::assign('list', $cate->catetree(db('cate')->order('sort', 'desc')->select()));
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
		$data = db('cate')->order('sort', 'desc')->select();
		$cate = $cate->catetree($data);

		self::assign('list', $cate);
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
		$cate = CateModel::get($id);
		$arr = $request->param();

		return !$cate->save($arr) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './cate/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		if ( in_array($id, array_keys([24 => '系统', 25 => '网店信息', 27 => '网店帮助分类'])) )
		   return json(['id' => $id, 'msg' => '系统栏目不能删除']);
		
		$catetree = new Catetree();
		$result = $catetree->childrenids(db('cate'), $id);
		$result[] = $id;
		$ids = [];
		$thumbs = db('article')->field('id, thumb')->where(['cate_id' => $result])->select();
		foreach ($thumbs as $key => $value) {
			if ( !empty($value['thumb']) ) {
				if ( file_exists('static/uploads/' . $value['thumb']) ) 
					@unlink('static/uploads/' . $value['thumb']);
			}
			$ids[] = $value['id'];
		}
		db('article')->delete($ids);
	 	return CateModel::destroy($result) ? json(['id' => $id, 'msg' => 'success']) :
	 	json(['id' => $id, 'msg' => 'error']);
	}
}