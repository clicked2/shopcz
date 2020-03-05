<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\MemberLevel as MemberLevelModel;
use think\Request;

use think\facade\Cache;



class MemberLevel extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$request = request();

		$validate = self::validate($request->param(), 'app\validate\MemberLevel.'.$this->request->action());
		if ( $validate !== true ) return self::error('rule: '.implode(', ', $validate));
	}
	/**
	* 显示资源列表 GET
	*
	* @return \think\Response
	*/
	public function index()
	{
		return 'memberlevel index';
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
		$memberLevel = new MemberLevelModel();
		$memberLevel->addData($request);
		return !$memberLevel->save() ? self::error('添加失败: ' . lang('data_insert')) : self::success('添加成功');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = MemberLevelModel::paginate(4);
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
		$level =  MemberLevelModel::get($id);
		$level->addData($request);
		return !$level->save() ? self::error('修改失败: ' . lang('data_insert')) : self::success('修改成功', './member/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$member = MemberLevelModel::get($id);
		if ( $member ) {
			if ( $member->delete() ) {
				return json(['id' => $goods->id,'msg' => 'success']);
			}
		}
		return json(['error'=> lang('delete_error')]);
	}

}