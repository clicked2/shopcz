<?
namespace app\admin\model;
use think\Model;


class Attr extends Model
{
	public function setAttrValuesAttr($value)
	{
		return str_replace('，', ',', $value);
	}
	public function getAttrTypeAttr($value)
	{
		$status = [1 => '单选', 2 => '唯一'];
		return $status[$value];
	}

}