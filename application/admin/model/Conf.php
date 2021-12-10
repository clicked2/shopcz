<?
namespace app\admin\model;
use think\Model;


class Conf extends Model
{
	public function upload($name)
	{
		$file = request()->file($name);
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
	public function removeLogo($name)
	{
		$conf = db('conf')->field('value')->where('ename', $name)->find();
		$path = 'static/uploads/'.$conf['value'];
		if (!empty($conf['value']) ) @unlink($path);
		return self::upload($name);
	}
	public function setValuesAttr($value)
	{
		return str_replace('，', ',', $value);
	}
}