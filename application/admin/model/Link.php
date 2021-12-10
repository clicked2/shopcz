<?
namespace app\admin\model;
use think\Model;


class Link extends Model
{
	public function getStatusAttr($value)
	{
		$status = [0 => '../static/admin/images/error.png', 1 => '../static/admin/images/right.png'];
		return $status[$value];
	}
	public function getTypeAttr($value)
	{
		$status = [1 => "文字", 2 => "图片"];
		return $status[$value];
	}

	public function upload()
	{
		$file = request()->file('logo');
		if( $file ){
	        $info = $file->validate([
	        	'size' => 1024*1024,
	        	'ext'  => 'jpg,png,gif,jpeg,bmp'
	        ])->move('static/uploads');
	        if($info){
	            return $info->getSaveName();
	        }else{
	            $this->info = $file->getError();
	        }
	    }
	}
	public function removeLogo()
	{
		$path = 'static/uploads/'.$this->logo;
		@unlink($path);
		return self::upload();
	}
	
}