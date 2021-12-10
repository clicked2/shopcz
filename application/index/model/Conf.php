<?
namespace app\index\model;

use think\Model;

class Conf extends Model
{
	public function _getConf()
	{
		$conf = self::field('ename, value')->select();
		$confs = [];
		foreach ($conf as $key => $value) {
			$confs[$value['ename']] = $value['value'];
		}
		return $confs;
	}

}