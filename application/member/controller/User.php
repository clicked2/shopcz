<?
namespace app\member\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Cache;

use app\member\model\User as UserModel;
use app\member\model\Nav as NavModel;
use app\member\model\Conf as ConfModel;
class User extends Controller
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