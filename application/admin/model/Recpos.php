<?
namespace app\admin\model;
use think\Model;


class Recpos extends Model
{
	public function getRecTypeAttr($value)
	{
		$status = [1 => '商品', 2 => '分类'];
		return $status[$value];
	}

	
}