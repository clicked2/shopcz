<?
namespace app\admin\model;
use think\Model;


class Alternate extends Model
{
	public function getStatusAttr($value)
	{
		$status = [0 => '../static/admin/images/error.png', 1 => '../static/admin/images/right.png'];
		return $status[$value];
	}

	public function upload()
	{
		$file = request()->file('img_src');
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
		$path = 'static/uploads/'.$this->img_src;
		@unlink($path);
		return self::upload();
	}
	
}