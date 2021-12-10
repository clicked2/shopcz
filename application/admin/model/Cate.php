<?
namespace app\admin\model;
use think\Model;

class Cate extends Model
{
	protected $readonly = [
		'create_type', 'allow_son', 'pid'
	];
}