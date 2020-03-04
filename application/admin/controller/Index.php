<?
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
	public function index()
	{
		return view('test');
		self::assign('title', "ECSHOP 管理中心");
		return view('index');
	}
	public function top()
	{
		return view('top');
	}
	public function menu()
	{
		return view('menu');
	}
	public function main()
	{
		return view('main');
	}
}