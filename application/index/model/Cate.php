<?
namespace app\index\model;
use think\Model;

class Cate extends Model
{

	public function getComCate()
	{
		$cate = self::where(['create_type' => 5, 'pid' => 0])->select();
		foreach ($cate as $key => $value) {
			$cate[$key]['children'] = self::where('pid', $value['id'])->select();
		}
		return $cate;
	}
	public function shopHelpCate()
	{
		return self::where(['create_type' => 3])->select();
	}
	public function dir($id)
	{
		return self::_dir(db('cate')->select(), $id);
	}
	public function _dir($data, $id)
	{
		static $arr = [];
		foreach ($data as $key => $value) {
			if ( $value['id'] == $id ) {
				$arr[] =  $value;
				self::_dir($data, $value['pid']);
			}
		}
		return array_reverse($arr);
	}
}