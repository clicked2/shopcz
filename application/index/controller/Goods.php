<?
namespace app\index\controller;
use think\Controller;
use app\index\model\Nav as NavModel;
use app\index\model\Conf as ConfModel;
use app\index\model\Goods as GoodsModel;

class Goods extends controller
{
	public function initialize()
    {
        $nav = new NavModel;
        $conf = new ConfModel;
        $this->config = $conf->_getConf();
        self::assign('conf', $this->config);
        self::assign('nav', $nav->_getNav());
    }
	public function index($id=0)
	{
		self::assign('id', $id);
		
		return view('goods');
	}
	public function getProduct($goodsId='', $goods_attr1='', $goods_attr2='')
	{
		$str = $goods_attr1.', '.$goods_attr2;
		$result = db('product')->field('goods_number')->where('goods_id', $goodsId)->where('goods_attr', $str)->find();
		return empty($result) ? json(['error' => 1]) : json(['error' => 0, 'result' => $result['goods_number']]);
	}
}