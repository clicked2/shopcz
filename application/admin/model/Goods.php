<?
namespace app\admin\model;
use think\Model;
use think\Image;

use think\facade\Cache;

//use app\admin\model\Conf;


class Goods extends Model
{
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';
	protected static function init()
	{
		self::event('before_insert', function($query) {
			$info = $query->logoUpload('og_thumb');
			if ( $info ) {
				$dir = substr($info->getSaveName(), 0, strpos($info->getSaveName(), DS));
		 		$path = $info->getFileName();
		 		$query->og_thumb = $dir . DS . $path;
		 		$query->big_thumb = $dir . DS .'big_' . $path;
		 		$query->mid_thumb = $dir . DS .'mid_' . $path;
		 		$query->sm_thumb = $dir . DS .'sm_' . $path;
		 		$image = Image::open('static'. DS .'uploads'. DS .''. $dir . DS . $path);
		 		$bigthumb = Conf::field('value')->where('ename', 'bigthumb')->find();
		 		$image->crop($image->width(), $image->height(), 0, 0, $bigthumb['value'], $bigthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'big_' . $path);
		 		
		 		$midthumb = Conf::field('value')->where('ename', 'midthumb')->find();
		 		$image->crop($image->width(), $image->height(), 0, 0, $midthumb['value'], $midthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'mid_' . $path);

		 		$smthumb = Conf::field('value')->where('ename', 'smthumb')->find();
		 		$image->crop($image->width(), $image->height(), 0, 0, $smthumb['value'], $smthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'sm_' . $path);
			}
			$query->goods_code = time().rand(100000, 999999);


		});

		self::event('after_insert', function($query) {
			$mp = new MemberPrice();
			$arr = [];

			if ( is_array( request()->param('mp') ) ) {
				foreach (request()->param('mp') as $key => $value)
					if ( !empty($value) ) $arr[] = ['price' => $value, 'member_level_id' => $key, 'goods_id' => $query->id];
				$mp->saveAll($arr);
			}


			//插入推荐位
			if ( is_array( request()->param('rec_check') ) ) {
				$arr = [];
				foreach (request()->param('rec_check') as $key => $value) {
					$arr[] = ['recpos_id' => $value, 'value_id' => $query->id, 'value_type' => 1];
				}
				if ( !empty($arr) ) {
					db('rec_item')->insertAll($arr);
				}
			}
			

			//插入商品属性
			if ( !empty(request()->param('type_id')) ) {
				$attrs = [];
				$prices = request()->param('attr_price');
				if ( is_array(request()->param('goods_attr')) ) {
					foreach (request()->param('goods_attr') as $key => $value) {
						if ( !empty($value) ) {
							if ( is_array($value) ) {
								foreach ($value as $k => $v) {
									if ( !empty($v) )
										$attrs[] = ['attr_id' => $key, 'goods_id' => $query->id, 'attr_value' => $v, 'attr_price' => $prices[$key][$k]];
								}
							} else {
								$attrs[] = ['attr_id' => $key, 'goods_id' => $query->id, 'attr_value' => $value];
							}
						}
					}
				}
				
				if ( !empty($attrs) ) {
					$attr = new GoodsAttr();
					$attr->saveAll($attrs);
				}
			}

			//搜索过滤关联
			$arr = [];
			if ( is_array(request()->param('ids')) && is_array(request()->param('values')) ) {
				foreach (request()->param('ids') as $key => $value) {
					if ( array_key_exists($key, request()->param('values')) ) {
						foreach (request()->param('values')[$key] as $k => $v) {
							if ( $v ) {
								$arr[] = ['goods_id' => $query->id, 'filter_id' => $v];
							}
						}
						$arr[] = ['goods_id' => $query->id, 'filter_id' => $value];
					}
				}
			}
			
			if ( !empty($arr) ) {
				db('goods_filter_item')->insertAll($arr);
			}
			
			//插入商品相册
			if ( isset($_FILES['goods_images']) && !empty($_FILES['goods_images']['tmp_name']) ) {
				$photos = new GoodsPhoto();
				$infos = $query->uploads('goods_images');

				$arr = [];
				$bigthumb = Conf::field('value')->where('ename', 'bigthumb')->find();
				$midthumb = Conf::field('value')->where('ename', 'midthumb')->find();
				$smthumb = Conf::field('value')->where('ename', 'smthumb')->find();
				if ( $infos ) {
					foreach ($infos as $key => $value) {
						$dir = substr($value['saveName'], 0, strpos($value['saveName'], DS));
				 		$path = $value['fileName'];
						$arr[] = ['goods_id' => $query->id, 'og_photo' => $dir . DS . $path, 'big_photo' => $dir . DS .'big_' . $path, 'mid_photo' =>  $dir . DS .'mid_' . $path, 'sm_photo' => $dir . DS .'sm_' . $path];
				 		$image = Image::open('static'. DS .'uploads'. DS .''. $dir . DS . $path);

				 		$image->crop($image->width(), $image->height(), 0, 0, $bigthumb['value'], $bigthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'big_' . $path);
				 		$image->crop($image->width(), $image->height(), 0, 0, $midthumb['value'], $midthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'mid_' . $path);
				 		$image->crop($image->width(), $image->height(), 0, 0, $smthumb['value'], $smthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'sm_' . $path);
					}
				}
				$photos->saveAll($arr);
			}
		});
		self::beforeDelete(function($query) {
			//删除图片
			$query->imageDelete();
			//会员价格
			MemberPrice::where('goods_id', $query->id)->delete();
			//推荐位
			db('rec_item')->where(['value_id' => $query->id, 'value_type' => 1])->delete();
			//属性
			GoodsAttr::where('goods_id', $query->id)->delete();

			//搜索过滤关联
			db('goods_filter_item')->where('goods_id', $query->id)->delete();
			
			//相册
			$photo = GoodsPhoto::where('goods_id', $query->id)->select();
			foreach ($photo as $key => $value) {
				if ( !empty($value->sm_photo) ) @unlink('static' . DS . 'uploads' . DS . $value->sm_photo);
				if ( !empty($value->mid_photo) ) @unlink('static' . DS . 'uploads' . DS . $value->mid_photo);
				if ( !empty($value->big_photo) ) @unlink('static' . DS . 'uploads' . DS . $value->big_photo);
			}
			GoodsPhoto::where('goods_id', $query->id)->delete();
		});

		self::beforeUpdate(function($query) {
			$info = $query->logoUpload('og_thumb', true);
			if ( $info ) {
				$dir = substr($info->getSaveName(), 0, strpos($info->getSaveName(), DS));
		 		$path = $info->getFileName();
		 		$query->og_thumb = $dir . DS . $path;
		 		$query->big_thumb = $dir . DS .'big_' . $path;
		 		$query->mid_thumb = $dir . DS .'mid_' . $path;
		 		$query->sm_thumb = $dir . DS .'sm_' . $path;

		 		$bigthumb = Conf::field('value')->where('ename', 'bigthumb')->find();
				$midthumb = Conf::field('value')->where('ename', 'midthumb')->find();
				$smthumb = Conf::field('value')->where('ename', 'smthumb')->find();

		 		$image = Image::open('static'. DS .'uploads'. DS .''. $dir . DS . $path);
		 		$image->crop($image->width(), $image->height(), 0, 0, $bigthumb['value'], $bigthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'big_' . $path);
		 		$image->crop($image->width(), $image->height(), 0, 0, $midthumb['value'], $midthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'mid_' . $path);
		 		$image->crop($image->width(), $image->height(), 0, 0, $smthumb['value'], $smthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'sm_' . $path);
			}
			$query->goods_code = time().rand(100000, 999999);
			
		});

		self::afterUpdate(function($query) {
			$mp = new MemberPrice();
			$arr = [];
			if ( is_array(request()->param('mp')) ) {
				foreach (request()->param('mp') as $key => $value)
					if ( !empty($value) ) $arr[] = ['price' => $value, 'member_level_id' => $key, 'goods_id' => $query->id];
			}

			if ( empty($arr) ) MemberPrice::where('goods_id', $query->id)->delete();
			$mp->saveAll($arr);
			
			//推荐位
			$arr = [];
			if ( is_array(request()->param('rec_check')) ) {
				foreach (request()->param('rec_check') as $key => $value) {
					$arr[] = ['recpos_id' => $value, 'value_id' => $query->id, 'value_type' => 1];
				}
			}

			db('rec_item')->where(['value_id' => $query->id, 'value_type' => 1])->delete();
			if ( !empty($arr) ) {
				db('rec_item')->insertAll($arr);
			}

			//商品属性
			if ( !empty(request()->param('type_id')) ) {
				$attrs = [];
				$prices = request()->param('attr_price');
				if ( is_array(request()->param('goods_attr')) ) {
					foreach (request()->param('goods_attr') as $key => $value) {
						if ( !empty($value) ) {
							if ( is_array($value) ) {
								foreach ($value as $k => $v) {
									if ( !empty($v) ) {
										$a = db('goods_attr')->field('goods_id, attr_value, attr_price')->where('goods_id' , $query->id)->whereLike('attr_value', '%'.$v.'%')->find();
										
										if ( $a && $a['attr_price'] != $prices[$key][$k] ) {
											db('goods_attr')->field('goods_id, attr_value, attr_price')->where('goods_id' , $query->id)->whereLike('attr_value', '%'.$v.'%')->update(['attr_price' => $prices[$key][$k]]);
										} else if ( !$a ) {
											$attrs[] = ['attr_id' => $key, 'goods_id' => $query->id, 'attr_value' => $v, 'attr_price' => $prices[$key][$k]];
										}
									}
								}
							} else {
								$a = db('goods_attr')->field('goods_id, attr_value, attr_id')->where(['goods_id' => $query->id, 'attr_id' => $key])->find();
								if ( $a && $a['attr_value'] != $value ) {
									db('goods_attr')->field('goods_id, attr_value, attr_id')->where(['goods_id' => $query->id, 'attr_id' => $key])->update(['attr_value' => $value]);
								} else if ( !$a ) {
									$attrs[] = ['attr_id' => $key, 'goods_id' => $query->id, 'attr_value' => $value];
								}
							}
						}
					}
				}
				if ( !empty($attrs) ) {
					$attr = new GoodsAttr();
					$attr->saveAll($attrs);
				}
			}
			
			//搜索过滤关联
			$arr = [];
			if ( is_array(request()->param('ids')) && is_array(request()->param('values')) ) {
				foreach (request()->param('ids') as $key => $value) {
					if ( array_key_exists($key, request()->param('values')) ) {
						foreach (request()->param('values')[$key] as $k => $v) {
							if ( $v ) {
								$arr[] = ['goods_id' => $query->id, 'filter_id' => $v];
							}
						}
						$arr[] = ['goods_id' => $query->id, 'filter_id' => $value];
					}
				}
			}
			
			// dump(request()->param('ids'));
			// dump(request()->param('values'));
			// exit;
			db('goods_filter_item')->where(['goods_id' => $query->id])->delete();
			if ( !empty($arr) ) {
				db('goods_filter_item')->insertAll($arr);
			}


			//商品相册
			if ( isset($_FILES['goods_images'])  && !empty($_FILES['goods_images']['tmp_name']) ) {
				$photos = new GoodsPhoto();
				$infos = $query->uploads('goods_images');
				$arr = [];
				$bigthumb = Conf::field('value')->where('ename', 'bigthumb')->find();
				$midthumb = Conf::field('value')->where('ename', 'midthumb')->find();
				$smthumb = Conf::field('value')->where('ename', 'smthumb')->find();

				if ( $infos && is_array($infos) ) {
					foreach ($infos as $key => $value) {
						$dir = substr($value['saveName'], 0, strpos($value['saveName'], DS));
				 		$path = $value['fileName'];
						$arr[] = ['goods_id' => $query->id, 'og_photo' => $dir . DS . $path, 'big_photo' => $dir . DS .'big_' . $path, 'mid_photo' =>  $dir . DS .'mid_' . $path, 'sm_photo' => $dir . DS .'sm_' . $path];


				 		$image = Image::open('static'. DS .'uploads'. DS .''. $dir . DS . $path);
				 		$image->crop($image->width(), $image->height(), 0, 0, $bigthumb['value'], $bigthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'big_' . $path);
				 		$image->crop($image->width(), $image->height(), 0, 0, $midthumb['value'], $midthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'mid_' . $path);
				 		$image->crop($image->width(), $image->height(), 0, 0, $smthumb['value'], $smthumb['value'])->save('static'. DS .'uploads'. DS . $dir . DS . 'sm_' . $path);
						//@unlink('static'. DS .'uploads'. DS .''. $dir . DS . $path);
					}
				}
				$photos->saveAll($arr);
			}
		});
	}

	//上传图片 成功返回路径
	public function logoUpload($imgName, $update = false)
	{
		if (  isset($_FILES[$imgName]) && !empty($_FILES[$imgName]['tmp_name']) ) {
			$file = request()->file($imgName);

			$info = $file->validate([
				'size' => 1024*1024,
				'ext'  => 'jpg,png,gif,jpeg'
	 		])->move('static'. DS .'uploads');
			
			if ( $info ) {
				if ( $update ) self::imageDelete(); //删除图片
				return $info;
			} else {
	 			$this->info = $file->getError();
	 		}
		}
		return false;
	}
	//删除图片
	public function imageDelete() {
		$logoPath = $this->og_thumb;
		$date = substr($logoPath, 0, 9);
		$path = substr($logoPath, 9, strlen($logoPath));
		@unlink('static' . DS . 'uploads' . DS . $logoPath);
		@unlink('static' . DS . 'uploads' . DS . $date . DS . 'sm_' . $path);
		@unlink('static' . DS . 'uploads' . DS . $date . DS . 'mid_' . $path);
		@unlink('static' . DS . 'uploads' . DS . $date . DS . 'big_' . $path);
	}

	//商品相册
	public function uploads($imgName)
	{
		$files =request()->file()[$imgName];
		$arr = [];
		foreach ($files as $file) {
			$info = $file->validate([
				'size' => 1024*1024,
				'ext'  => 'jpg,png,gif,jpeg'
	 		])->move('static'. DS .'uploads');
			if ( $info ) {
				$arr[] = ['saveName' => $info->getSaveName(), 'fileName' => $info->getFileName()];
			} else {
				return $file->getError();
			}
		}
		return $arr;
	}

	public function getOnSaleAttr($value)
	{
		$status = [0 => '../static/admin/images/error.png', 1 => '../static/admin/images/right.png'];
		return $status[$value];
	}
}