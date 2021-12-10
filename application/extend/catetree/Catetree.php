<?
namespace app\extend\catetree;

class Catetree 
{
	public $arr = [];
	public function catetree($cateRes)
	{
		$this->arr = [];
		return $this->sort($cateRes);
	}
	public function sort($cateRes, $pid=0, $level=0)
	{
		foreach ($cateRes as $k => $v) {
			if ( $v['pid'] == $pid ) {
				$v['level'] = $level;
				$this->arr[] = $v;
				$this->sort($cateRes, $v['id'], $level+1);
			}
		}
		return $this->arr;
	}
	//获取一组
	public function catetreeGroup($cateRes, $cateId)
	{
		$arr = [];
		$indexLevel = 0;
		$this->sort($cateRes);
		foreach ($this->arr as $key => $value) {
			if ( $value['id'] == $cateId ) {
				$indexLevel = $value['level'];
				break;
			}
		}
		
		for ($i=0; $i < $indexLevel+1; $i++) { 
			$father = self::find($cateId, $i);
			if ( $father ) {
				$son = self::find1($father['id']);
				if ( $son ) $father['son'] = $son;
				$arr[$i] = $father;
			}
		}
		return $arr;
	}
	public function find($cateId, $level)
	{
		static $result;
		foreach ($this->arr as $key => $value) {
			if ( $value['id'] ==  $cateId && $value['level'] != $level ) {
				$this->find($value['pid'], $level);
			} else if ($value['id'] ==  $cateId && $value['level'] == $level ) {
				$result = $value;
				break;
			}
		}
		return $result;
	}
	public function find1($cateId)
	{
		$result = [];
		foreach ($this->arr as $key => $v) {
			if ( isset($v['pid']) && $v['pid'] == $cateId ) {
				$result[] = $v;
			}
		}
		return $result;
	}

	//获取子栏目id
	public function childrenids($obj, $cateid)	
	{
		$this->arr = [];
		$data = $obj->field('id,pid')->select();
		return $this->_childrenids($data, $cateid);
	}

	private function _childrenids($data, $cateid)
	{
		foreach ($data as $k => $v) {
			if ( $v['pid'] == $cateid ) {
				$this->arr[] = $v['id'];
				$this->_childrenids($data, $v['id']);
			}
		}
		return $this->arr;
	}
}
