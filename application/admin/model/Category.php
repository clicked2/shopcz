<?
namespace app\admin\model;
use think\Model;

class Category extends Model
{
	protected $readonly = [
		
	];
	protected static function init()
	{
		self::afterInsert(function($query) {
			//首页推荐位关联
			$arr = [];
			if ( is_array(request()->param('rec_check')) ) {
				foreach (request()->param('rec_check') as $key => $value) {
					$arr[] = ['recpos_id' => $value, 'value_id' => $query->id, 'value_type' => 2];
				}
			}
			if ( !empty($arr) ) {
				db('rec_item')->insertAll($arr);
			}

			//搜索过滤关联
			$arr = [];
			
			if ( is_array(request()->param('ids')) && is_array(request()->param('values')) ) {
				foreach (request()->param('ids') as $key => $value) {
					if ( array_key_exists($key, request()->param('values')) ) {
						foreach (request()->param('values')[$key] as $k => $v) {
							if ( $v ) {
								$arr[] = ['category_id' => $query->id, 'filter_id' => $v];
							}
						}
						$arr[] = ['category_id' => $query->id, 'filter_id' => $value];
					}
				}
			}
			if ( !empty($arr) ) {
				db('category_filter_item')->insertAll($arr);
			}

		});
		self::afterDelete(function($query) {
			//首页推荐位关联
			db('rec_item')->where(['value_id' => $query->id, 'value_type' => 2])->delete();

			//搜索过滤关联
			db('category_filter_item')->where('category_id', $query->id)->delete();
		});
		self::afterUpdate(function ($query) {
			//首页推荐位关联
			$arr = [];
			if ( is_array(request()->param('rec_check')) ) {
				foreach (request()->param('rec_check') as $key => $value) {
					$arr[] = ['recpos_id' => $value, 'value_id' => $query->id, 'value_type' => 2];
				}
			}

			db('rec_item')->where(['value_id' => $query->id, 'value_type' => 2])->delete();
			if ( !empty($arr) ) {
				db('rec_item')->insertAll($arr);
			}

			//搜索过滤关联
			$arr = [];
			if ( is_array(request()->param('ids')) && is_array(request()->param('values')) ) {
				foreach (request()->param('ids') as $key => $value) {
					if ( array_key_exists($key, request()->param('values')) ) {
						foreach (request()->param('values')[$key] as $k => $v) {
							if ( $v ) {
								$arr[] = ['category_id' => $query->id, 'filter_id' => $v];
							}
						}
						$arr[] = ['category_id' => $query->id, 'filter_id' => $value];
					}
				}
			}
			// dump(request()->param('ids'));
			// dump(request()->param('values'));
			// exit;
			db('category_filter_item')->where(['category_id' => $query->id])->delete();
			if ( !empty($arr) ) {
				db('category_filter_item')->insertAll($arr);
			}
		});
	}
	public function upload()
	{
		$file = request()->file('cate_img');
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
		$path = 'static/uploads/'.$this->cate_img;
		if ( !empty($this->cate_img) ) @unlink($path);
		return self::upload();
	}
}