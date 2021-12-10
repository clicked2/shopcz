<?
namespace app\member\controller;
use think\Controller;

use app\member\model\User as UserModel;
use app\index\model\Nav as NavModel;
use app\index\model\Conf as ConfModel;


class Index extends Controller
{
	public function initialize()
	{
		$nav = new NavModel;
        $conf = new ConfModel;
        $this->config = $conf->_getConf();
        self::assign('conf', $this->config);
        self::assign('nav', $nav->_getNav());
	}
	public function index()
	{
		return view('index');
	}


}