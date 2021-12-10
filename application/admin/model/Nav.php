<?
namespace app\admin\model;
use think\Model;


class Nav extends Model
{
	public function getOpenAttr($value)
	{
		$status = [1 => '新窗口打开', 2 => '所在页打开'];
		return $status[$value];
	}
	public function getPosAttr($value)
	{
		$status = [1 => '顶部导航', 2 => '中间导航', 3 => '底部导航'];
		return $status[$value];
	}
	
}