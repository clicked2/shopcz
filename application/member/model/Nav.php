<?
namespace app\member\model;

use think\Model;

class Nav extends Model
{
	public function _getNav()
	{
		$list = self::order('sort', 'desc')->select();
		$newList = [];
		foreach ($list as $key => $value) {
			if ( $value['pos'] == 1 ) {
				$newList['top'][] = $value;
			} else if ( $value['pos'] == 2 ) {
				$newList['mid'][] = $value;
			}
		}
		return $newList;
	}
}