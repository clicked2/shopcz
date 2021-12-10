<?
namespace app\admin\model;
use think\Model;


class Order extends Model
{
	protected $autoWriteTimestamp = true;
 		
	protected $type = [
		'create_time' => 'datetime Y-m-d',
	];
	function getPaymentAttr($value)
	{
		$tatus = [0 => '未知', 1 => '支付宝', 2 => '微信', 3 => '余额'];
		return $tatus[$value];
	}
	function getOrderStatusAttr($value)
	{
		$tatus = [0 => '未确认', 1 => '已确认'];
		return $tatus[$value];
	}
	function getPayStatusAttr($value)
	{
		$tatus = [0 => '未支付', 1 => '已支付'];
		return $tatus[$value];
	}
	function getPostStatusAttr($value)
	{
		$tatus = [0 => '未发货', 1 => '已发货', 2 => '已退款'];
		return $tatus[$value];
	}

	function scopeOwnOrder($query)
	{
		foreach (request()->param() as $key => $value) {
			if ( $value === '0' || $value && $key != 'id' &&  $key != 'page' ) {
				$query->where($key, $value);
			}
		}
	}
}