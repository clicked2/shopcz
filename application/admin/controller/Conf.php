<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Conf as ConfModel;
use think\Request;

use think\Db;
use think\facade\Cache;



class Conf extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Conf.'.$this->request->action());
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
		$conf = new ConfModel();
		return !$conf->save($request->param()) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './conf/read');
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
			$catemodel = new ConfModel();
			foreach (request()->param('sort') as $key => $value)
				if ( $value != 'undefined' ) $a[] = ['id' => $key, 'sort' => $value];
			$catemodel->saveAll($a);
		}

		$list = ConfModel::order('sort', 'desc')->paginate(5);
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
		$conf = ConfModel::get($id);
		return !$conf->save( $request->param() ) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './conf/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$conf = ConfModel::get($id);
		if ( !empty($conf->conf_img) ) $conf->removeLogo();
		return $conf->delete() ? json(['id' => $conf->id, 'msg' => 'success']) :
		json(['id' => $conf->id, 'msg' => 'error']);
	}

	public function confList()
	{
		
		return view('conflist');
	}
	public function confSave(Request $request)
	{
		$conf = new ConfModel();
		$param = $request->param();

		$found = false;
		if ( isset($param) ) {
			foreach ($param as $key => $value) {
				if ( is_array($value) ) {
					$found = true;
					$conf->save(['value' => implode(', ', $value)], ['ename' => $key]);
				} else {
					$conf->save(['value' => $value], ['ename' => $key]);
				}
			}
		}

		//checkbox 为空处理
		if ( !$found ) $conf->save(['value' => ''], ['form_type' => 'checkbox']);
		//图片处理
		if ( !empty($_FILES) )
			foreach ($_FILES as $key => $value)
				if ( !empty($value['tmp_name']) )
					$conf->save(['value' => $conf->removeLogo($key)], ['ename' => $key]);

		return self::success('修改成功', './conf/read');
	}
}