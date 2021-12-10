<?
namespace app\admin\model;
use think\Model;


class CategoryBrands extends Model
{
	public function upload()
	{
		$file = request()->file('pro_img');
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
		$path = 'static/uploads/'.$this->pro_img;
		@unlink($path);
		return self::upload();
	}

	public function getBrandsIdAttr($value)
	{
		$arr = [];
		foreach (db('brand')->field('brand_name')->whereIn('id', $value)->select() as $key => $value) {
			$arr[] = $value['brand_name'];
		}
		return implode(', ', $arr);
	}
}