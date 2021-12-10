<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Recpos as RecposModel;
use think\Request;

use think\Db;
use think\facade\Cache;

class Recpos extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Recpos.'.$this->request->action());
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
		$recpos = new RecposModel();

		return !$recpos->save($request->param()) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './recpos/read');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = RecposModel::order('id', 'desc')->paginate(5);
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
		$recpos = RecposModel::get($id);

		return !$recpos->save($request->param()) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './recpos/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$recpos = RecposModel::get($id);

		return $recpos->delete() ? json(['id' => $recpos->id, 'msg' => 'success']) :
		json(['id' => $recpos->id, 'msg' => 'error']);
	}
}