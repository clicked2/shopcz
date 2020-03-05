<?
namespace app\admin\model;
use think\Model;


class MemberLevel extends Model
{
	protected $type = [
		// 'jifen_top' => 'integer',
		// 'jifen_bottom' => 'integer'
	];

	//添加入库数据数据
	public function addData($request)
	{
		$this->level_name = $request->param('level_name');
		$this->jifen_top = $request->param('jifen_top');
		$this->jifen_bottom = $request->param('jifen_bottom');
	}

}