<?
namespace app\admin\controller;
use think\Controller;
use app\admin\model\Order as OrderModel;
use think\Request;

use think\Db;
use think\facade\Cache;


class Order extends Controller
{
	//开启批量验证
	protected $batchValidate = true;

	public function initialize()
	{
		
	}
	/**
	* 显示资源列表 GET
	*
	* @return \think\Response
	*/
	public function index()
	{
		return view('index/index');
	}

	/**
	* 显示添加商品页 GET
	*
	* @param \think\Request $request
	* @return \think\Response
	*/
	public function add()
	{
		
	}
	
	/**
	*  添加新的商品 POST 
	*
	* @param \think\Request $request
	* @return \think\Response
	*/
	public function save(Request $request)
	{
		
	}

	/**
	* 列表显示与搜索 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function read($id)
	{
		$list = OrderModel::ownorder()->order('id', 'desc')->paginate(5, false, ['query' => request()->param()]);
		self::assign('list', $list);
		return view('read');
	}

	/**
	* 显示编辑资源单页 GET
	*
	* @param int $id
	* @return \think\Response
	*/
	public function edit($id)
	{
		
	}

	/**
	* 保存更新的资源 PUT
	*
	* @param \think\Request $request
	* @param int $id
	* @return \think\Response
	*/
	public function update(Request $request, $id)
	{
		
	}
	/**
	* 删除指定资源 DELETE
	*
	* @param int $id
	* @return \think\Response
	*/
	public function delete($id)
	{
	
	}

	public function orderSelect()
	{
		return view('order_select');
	}
	public function export()
	{
		//filter data
		$order = db('order');
		foreach (request()->param() as $key => $value) {
			if ( $key != 'id' && $key != 'page' ) {
				$order->where($key, $value);
			}
		}
	
		//download
		$ids = $order->field('id')->select();
		$newIds = [];
		foreach ($ids as $key => $value) {
			$newIds[] = $value['id'];
		}
		$newOrder = OrderModel::select();
	
		$str = '';
		foreach ($newOrder as $key => $value) {
			if ( in_array($value['id'], $newIds) ) {
				$str.= $value->user_id.'----'.$value->out_trade_no.'----'.$value->goods_total_price.'----'.$value->order_total_price.'----'.$value->payment.'----'.$value->distibution.'----'.$value->order_status.'----'.$value->pay_status.'----'.$value->name.'----'.$value->phone.'----'.$value->province.'----'.$value->city.'----'.$value->county.'----'.$value->address.'----'.$value->postscript.'----'.$value->create_time.PHP_EOL;
			}
		}
		
		return download($str, 'download.txt', true);
	}
}
