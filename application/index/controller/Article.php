<?
namespace app\index\controller;
use think\Controller;
use app\index\model\Cate as CateModel;
use app\index\model\Nav as NavModel;
use app\index\model\Conf as ConfModel;

class Article extends Controller
{
	public function initialize()
    {
        $nav = new NavModel;
        $conf = new ConfModel;
        $this->config = $conf->_getConf();
        self::assign('conf', $this->config);
        self::assign('nav', $nav->_getNav());
    }

	public function index($id=0)
	{
		$cate = new CateModel;
		
		$comCates = $cate->getComCate();
		$helpCate = $cate->shopHelpCate();
		
		$list = db('article')->where('id', $id)->select();
		if ( $list ) self::assign('dir', $cate->dir($list[0]['cate_id']));

		
		self::assign('list', $list);
		
		self::assign('comCates', $comCates);
		self::assign('helpCate', $helpCate);

		
		return view('article');
	}
}