<?
namespace app\admin\model;
use think\Model;


class CategoryAd extends Model
{
	public function getPositionAttr($value)
	{
		$status = ['a' => '最左边', 'b' => '右上', 'c' => '右下'];
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