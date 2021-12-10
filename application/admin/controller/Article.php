<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Article as ArticleModel;
use think\Request;
use app\extend\catetree\Catetree;

use think\Db;
use think\facade\Cache;


class Article extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\admin\validate\Article.'.$this->request->action());
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
		self::assign('list', $cate->catetree(db('cate')->select()));
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
		$article = new ArticleModel();
		$arr = $request->param();

		if ( isset($_FILES['thumb']) && !empty($_FILES['thumb']['tmp_name']) )
			$arr['thumb'] = $article->upload();
		
		if ( !empty($article->info) ) return self::error('添加失败: '.$article->info);
		
		return !$article->save($arr) ? self::error('添加失败: 添加数据失败') :
		self::success('添加成功', './article/read');
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = ArticleModel::field('a.*, c.cate_name')->alias('a')->join('cate c', 'a.cate_id=c.id')->order('a.id', 'desc')->paginate(5);
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
		$cate = new Catetree();
		self::assign('list', $cate->catetree(db('cate')->select()));
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
		$article = ArticleModel::get($id);
		$arr = $request->param();

		if ( isset($_FILES['thumb']) && !empty($_FILES['thumb']['tmp_name']) ) 
			$arr['thumb'] = $article->removeLogo();

		if ( !empty($article->info) ) return self::error('修改失败: '.$article->info);
		
		return !$article->save($arr) ? self::error('修改失败: 修改数据失败') :
		self::success('修改成功', './article/read');
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
		$article = ArticleModel::get($id);
		if ( !empty($article->thumb) ) $article->removeLogo();
		return $article->delete() ? json(['id' => $article->id, 'msg' => 'success']):
		json(['id' => $article->id, 'msg' => 'error']);
	}


	public function imgList()
	{
		$list = self::getImgfile();
		self::assign('list', $list);
		return view('imgList');
	}
	public function imgDownload(Request $request)
	{
		return download('..'. DS .'..'. DS .'ueditor'. DS .$request->param('path'), md5(time()), true);
	}
	public function imgDelete()
	{
		if ( file_exists('..'. DS .'..'. DS .'ueditor'. DS .request()->param('path')) ) {
			@unlink('..'. DS. '..' .DS. 'ueditor'. DS.request()->param('path'));
			return json(['msg' => 'success']);
		} 
		return json(['path' => request()->param('path'), 'msg' => '删除失败']);
	}
	public function imgDeleteAll()
	{
		$list = self::getImgfile();

		foreach ($list as $key => $value) {
			@unlink('..'. DS .'..' .DS .'ueditor'. DS .$value);
		}
		return json(['msg' => 'success']);
	}

	public function getImgfile()
	{
		$files = getFiles('..'. DS .'..'. DS .'ueditor');
		$list = [];
		foreach ($files as $key => $value) {
			$imgs = getFiles('..'. DS .'..'. DS .'ueditor'. DS .$value);
			foreach ($imgs as $k => $v) {
				if ( !db('article')->field('content')->whereLike('content', '%'.$value.'/'.$v.'%')->find() 
					&& !db('goods')->field('goods_des')->whereLike('goods_des', '%'.$value.'/'.$v.'%')->find() ) {
					$list[] = $value. DS .$v;
				}
			}
		}
		return $list;
	}
}