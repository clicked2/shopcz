<?
namespace app\index\model;
use think\Model;

class Brand extends Model
{
	public function sort()
	{
		$arr = self::select();
		for ($i=0; $i < count($arr); $i++) { 
			self::swap($arr, rand(0, count($arr)-1), rand(0, count($arr)-1));
		}
		dump($arr);
	}

	public function swap($arr, $i, $j)
	{
		$temp = $arr[$i];
		$arr[$i] = $arr[$j];
		$arr[$j] = $temp;
	}
}