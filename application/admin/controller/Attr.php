<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Attr as AttrModel;
use think\Request;

use think\Db;
use think\facade\Cache;

class Attr extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Attr.'.$this->request->action());
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
		$attr = new AttrModel();

		return !$attr->save($request->param()) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './type/read');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = AttrModel::alias('a')->field('a.*, t.type_name')->join('type t', 'a.type_id=t.id')->where('t.id', request()->param('type_id'))->order('a.id', 'desc')->paginate(5, false, ['query' => request()->param()]);
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
		$attr = AttrModel::get($id);
		return !$attr->save($request->param()) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './type/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$attr = AttrModel::get($id);
		return $attr->delete() ? json(['id' => $attr->id, 'msg' => 'success']) :
		json(['id' => $attr->id, 'msg' => 'error']);
	}

	public function getAttr()
	{
		$attr = new AttrModel();
		return json($attr->where('type_id', request()->param('type_id'))->select());
	}
}