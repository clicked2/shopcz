<?
namespace app\admin\model;
use think\Model;


class Type extends Model
{
	public function attr()
	{
		return $this->hasMany('attr');
	}
	
}