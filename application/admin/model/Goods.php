<?
namespace app\admin\model;
use think\Model;
use think\Image;
use think\facade\Cache;

use app\admin\model\Brand as BrandModel;


class Goods extends Model
{
	//自动写入时间
	protected $autoWriteTimestamp = 'datetime';
	//修改创建时间列名
	protected $createTime = 'addtime';
	//自动添加数据
	protected $insert = [
		'is_delete' => 0
	];
	//设置只读字段
	protected $readonly = [
		'addtime'
	];
	//设置类型转换
	protected $type = [
		'is_on_sale' => 'integer',
		'is_delete' => 'integer',
	];

	//反向关联品牌表
	public function brand()
	{
		return $this->belongsTo('brand');
	}

	//设置全局范围查询
	protected function base($query)
	{
		$request = request();
		if ( $request->param('ios') != "" ) {
			$status = (int)$request->param('ios');
			$query->where('is_on_sale', '=', $status);
		}
	}


	//创建一个是否上架的获取器 将10改成是否
	public function getIsOnSaleAttr($value)
	{
		$status = [0 => '否', 1 => '是'];
		return $status[$value];
	}
	//创建一个是否删除的获取器 将10改成是否
	public function getIsDeleteAttr($value)
	{
		$status = [0 => '否', 1 => '是'];
		return $status[$value];
	}
	

	//创建一个搜索器 模糊搜索商品名称
	public function searchGoodsNameAttr($query, $value)
	{
		$query->where('goods_name', 'like', '%'.$value.'%');
	}
	//创建一个搜索器 模糊搜索价格区间
	public function searchPriceBetweenAttr($query, $value)
	{
		$query->whereBetween('shop_price', $value);
	}
	//创建一个搜索器 模糊搜索时间区间
	public function searchCreateTimeAttr($query, $value)
	{
		$query->whereBetweenTime('addtime', $value[0], $value[1]);
	}
	//创建一个查询范围 模糊搜索品牌名
	public function scopeBrandName($query, $name)
	{
		
		$user = $this->hasWhere('brand', function($brand) {
			$brand->where('brand.brand_name', 'like', '%苹%');
		})->select();
		// $user = $this->hasWhere('brand', ['id' => "1"])->select();
		var_dump($user);
	}

	//创建一个查询范围 用来做排序 
	public function scopeResultOrder($query, $value)
	{
		switch ($value) {
			case '':
				$query->order('addtime', 'desc'); //默认
				break;
			case 'id_desc':
				$query->order('addtime', 'desc');
				break;
			case 'id_asc':
				$query->order('addtime');
				break;
			case 'price_desc':
				$query->order('shop_price', 'desc');
				break;
			case 'price_asc':
				$query->order('shop_price');
				break;	
			default:
				# code...
				break;
		}
	}

	//上传图片 成功返回真
	public function logoUpload($update = false)
	{
		$request = request();
		if ( empty($_FILES['logo']['tmp_name']) ) {
			if ( !$update ) {
				$this->info = lang('logo_empty');
				return false;
			}
		} else {
			//图片处理
			$file = \think\facade\Request::file('logo');
			$info = $file->validate([
				'size' => 1024*1024,
				'ext'  => 'jpg,png,gif,jpeg'
	 		])->move('static/uploads');
			
			if ( $info ) {
				if ( $update ) {
					//删除图片
					self::imageDelete();
				}
				//上传图片
				self::imageUpload($info);
			} else {
	 			$this->info = $file->getError();
	 			return false;
	 		}
		}
		//设置准备入库字段
		$this->goods_name = $request->param('goods_name');
		$this->market_price = $request->param('market_price');
		$this->shop_price = $request->param('shop_price');
		$this->goods_desc = $request->param('goods_desc');
		$this->is_on_sale = $request->param('is_on_sale');
 		$this->brand_id = $request->param('brand_id');

 		return true;
	}
	public function imageUpload($info)
	{
		$dir = substr($info->getSaveName(), 0, strpos($info->getSaveName(), "\\"));
 		$path = $info->getFileName();
 		$this->logo = $dir . '/' . $path;
 		$image = Image::open('static/uploads/'. $dir . '/' . $path);
 		$image->crop($image->width(), $image->height(), 0, 0, 700, 700)->save('static/uploads/'. $dir . '/mbig_' . $path);
 		$this->mbig_logo = $dir . '/mbig_' . $path;
 		$image->crop($image->width(), $image->height(), 0, 0, 350, 350)->save('static/uploads/'. $dir . '/big_' . $path);
 		$this->big_logo = $dir . '/big_' . $path;
 		$image->crop($image->width(), $image->height(), 0, 0, 150, 150)->save('static/uploads/'. $dir . '/mid_' . $path);
 		$this->mid_logo = $dir . '/mid_' . $path;
 		$image->crop($image->width(), $image->height(), 0, 0, 50, 50)->save('static/uploads/'. $dir . '/sm_' . $path);
 		$this->sm_logo = $dir . '/sm_' . $path;
	}
	public function imageDelete() {
		//删除图片
		$logoPath = $this->logo;
		$date = substr($logoPath, 0, 9);
		$path = substr($logoPath, 9, strlen($logoPath));
		unlink('static/uploads/'.$logoPath);
		unlink('static/uploads/'.$date.'/sm_'.$path);
		unlink('static/uploads/'.$date.'/mid_'.$path);
		unlink('static/uploads/'.$date.'/big_'.$path);
		unlink('static/uploads/'.$date.'/mbig_'.$path);
	}
}