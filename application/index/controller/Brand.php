<?
namespace app\index\controller;
use think\Controller;
use app\index\model\Brand as BrandModel;

class Brand extends Controller
{
	public function initialize()
    {
      
    }

	public function index()
	{
		$brand = new BrandModel;
		$brand->sort();
	}
}