<?
namespace app\common\model;
use think\Model;

class Category extends Model
{

	public function getCates($id)
	{
		$cates = db('category')->where('pid', $id)->select();
		foreach ($cates as $key => $value) {
			$cates[$key]['children'] = db('category')->where('pid', $value['id'])->select();
		}
		return $cates;
	}
}