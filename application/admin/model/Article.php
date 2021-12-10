<?
namespace app\admin\model;
use think\Model;

use think\facade\Cache;


class Article extends Model
{
	//修改创建时间列名
	protected $createTime = 'addtime';
	//自动写入时间
	protected $autoWriteTimestamp = 'datetime';
	//禁止修改项
	protected $readonly = ['addtime'];

	public function getShowStatusAttr($value)
	{
		$status = [0 => '../static/admin/images/error.png', 1 => '../static/admin/images/right.png'];
		return $status[$value];
	}
	public function getShowTopAttr($value)
	{
		$status = [0 => '../static/admin/images/error.png', 1 => '../static/admin/images/right.png'];
		return $status[$value];
	}
	public function getLinkUrlAttr($value)
	{
		 return empty($value) ? '../static/admin/images/error.png' : '../static/admin/images/right.png';
	}
	public function setBrandUrlAttr($value)
	{
		return !preg_match('/http.*?:\/\//is', $value) && $value ? 'http://'.$value : $value;
	}

	public function upload()
	{
		$file = request()->file('thumb');
		if( $file ){
	        $info = $file->validate([
	        	'size' => 1024*1024,
	        	'ext'  => 'jpg,png,gif,jpeg'
	        ])->move('static/uploads');
	        if ($info) {
	            return $info->getSaveName();
	        } else {
	            $this->info = $file->getError();
	        }
	    }
	}
	public function removeLogo()
	{
		$path = 'static/uploads/'.$this->thumb;
		@unlink($path);
		return self::upload();
	}
	
}