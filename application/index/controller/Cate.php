<?
namespace app\index\controller;
use app\index\model\Cate as CateModel;
use think\Controller;
use app\index\model\Nav as NavModel;
use app\index\model\Conf as ConfModel;

use app\extend\catetree\Catetree;

class Cate extends Controller
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
		
		$catetree = new Catetree;
		$ids = $catetree->childrenids(db('cate'), $id);
		$ids[] = $id;
		
		$list = $cate->field('g.*, a.*')->alias('g')->join('article a', 'g.id=a.cate_id')->where(['a.cate_id' => $ids])->select();
	
		self::assign('cateTitle', $cate->field('cate_name')->find($id));
		
		self::assign('dir', $cate->dir($id));
		
		self::assign('list', $list);
		
		self::assign('comCates', $comCates);
		self::assign('helpCate', $helpCate);

		return view('cate');
		
	}


}