<?
namespace app\admin\model;
use think\Model;


class Filter extends Model
{
	
	public function getTypeAttr($value)
	{
		$status = [0 => '未知', 1 => '过滤名', 2 => '过滤值'];
		return $status[$value];
	}
}