<?
namespace app\index\controller;
use think\Controller;
use app\index\model\Nav as NavModel;
use app\index\model\Conf as ConfModel;
use app\index\model\Category as CategoryModel;
use app\index\model\Goods as GoodsModel;

use app\extend\catetree\Catetree;

use think\facade\Cache;

class Category extends Controller
{ 
		
	public function initialize()
    {
        $nav = new NavModel;
        $conf = new ConfModel;
        $this->config = $conf->_getConf();
        self::assign('conf', $this->config);
        self::assign('nav', $nav->_getNav());
    }
	
	public function index($id = 0)
	{
		return view('category');
	}
	public function search($keywords)
	{
		$cate = db('category')->where('cate_name', $keywords)->find();
		self::assign('id', $cate['id']);
		return view('category');
	}
	public function suggestions($keyword)
	{
		//$list = db('goods')->field('id, goods_name')->whereLike('goods_name', '%'.$keyword.'%')->select();
		$list = [];
		$list[] = ['goods_name' => 'sdfsadf'];
		$list[] = ['goods_name' => 'sdfsadf111'];
		$str = '';
		foreach ($list as $key => $value) {
			$str .= '<div class="left-span">&nbsp;<a href="'.url('./index/category/').'">'.$value['goods_name'].'</a></div>';
		}
		return $str;
	}
	public function GetGoodsList($cateId)
	{
		$cate = new Catetree;
		$cateArr = $cate->childrenids(db('category'), $cateId);
		$cateArr[] = $cateId;
		$fid = '';
		if ( request()->param('filter') ) {
			$fid = 'f.id=fi.filter_id AND '.request()->param('filter');
		} else {
			$fid = 'f.id=fi.filter_id';
		}
		$list = GoodsModel::field('g.*, gp.sm_photo')->alias('g')->join([['goods_filter_item fi' => 'g.id=fi.goods_id'], ['filter f' => $fid], ['goods_photo gp', 'g.id=gp.goods_id']])->where(['g.category_id' => $cateArr])->filter()->select();
		$sms = [];
		foreach($list as $key=>$value) {
			$sms[$value['id']][] = $value['sm_photo'];
		}
		$newList = [];
		foreach ($list as $key => $value) {
			if ( !array_key_exists($value['id'], $newList) ) {
				$value['photos'] =$sms[$value['id']];
				$newList[$value['id']] = $value;
			}
		}
		return json(self::ownPage($newList, 5));
	}
	public function ownPage($data, $per_page, $current=1) {
		$datas = [];
		$i = 0;
		foreach($data as $key=>$value) {
			if ( $i + 1 >= $current && $i < $current * $per_page ) {
				$datas[] = $value;
			}
			$i++;
		}
		
		return ['total' => count($data), 'per_page' => $per_page, 'last_page' => count($data)/$per_page < 0 ? 1 : ceil(count($data)/$per_page), 'data' => $datas, 'current_page' => $current];
	}

	public function getCateInfo()
	{
		$id = request()->param('id');
		$cate = new CategoryModel;
		//获取二级和三级栏目
		$cates = $cate->getCates($id);
		$cat = '';
		foreach ($cates as $key => $value) {
			$cat .= '
				<dl class="dl_fore'.$key.'">
				    <dt><a href="'.url('/index/category/index', ['id' => $value['id']]).'" target="_blank">'.$value['cate_name'].'</a></dt>
				    <dd>';
			    	foreach ($value['children'] as $k => $v) {
			    		$cat .= '<a href="'.url('/index/category/index', ['id' => $v['id']]).'" target="_blank">'.$v['cate_name'].'</a>';
			    	}
			$cat .=	'</dd>
				</dl> 
				<div class="item-brands"><ul></ul></div>
				<div class="item-promotions"></div>
				';
		}

		$arr = db('category_words')->where('category_id', $id)->select();
		$topic = '';
		foreach ($arr as $key => $value) {
			$topic .= '<a href="'.$value['link_url'].'" target="_blank">'.$value['words'].'</a>';
		}
		
		$arrBrands = db('category_brands')->where('category_id', $id)->find();
		$brandsAd = '';
		$brandsAd .= '<div class="cate-brand">';

		foreach (db('brand')->whereIn('id', $arrBrands['brands_id'])->select() as $k => $v) {
			$brandsAd .= '<div class="img"><a href="'.$v['brand_url'].'" target="_blank" title="'.$v['brand_name'].'"><img src="'.url('./static/uploads/'.$v['brand_img'],'', false).'"></a></div>';
		}
		$brandsAd .= '</div>';
		
		$brandsAd .= '
					<div class="cate-promotion">';
		$brandsAd .=    '<a href="'.$arrBrands['pro_url'].'" target="_blank"><img src="'.url('./static/uploads/'.$arrBrands['pro_img'],'', false).'" width="199" height="97"></a>
					</div>';
		$arr = [];
		
		$arr['topic_content'] =  $topic;
		$arr['cat_content'] = $cat;
		$arr['brands_ad_content'] = $brandsAd;
		$arr['cat_id'] = $id;
		return json($arr);
	}
}