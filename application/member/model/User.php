<?
namespace app\member\model;
use think\Model;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\Cache;

class User extends Model
{
	protected $autoWriteTimestamp = true;
	protected $createTime = 'create_time';

	public function setPasswordAttr($value)
	{return md5($value);
	}
	public function loginName($name)
	{
		return self::whereOr(['username' => $name, 'email' => $name, 'mobile_phone' => $name])->find();
	}
	public function login($name, $pass, $remember = '')
	{
		$bool = self::loginName($name);
		
		if ( $bool && md5($pass) == $bool->password ) {
			Session::set('uid', $bool->id, 'sc');
			Session::set('username', $bool->username, 'sc');
			$level = MemberLevel::where('bom_point', '<=', $bool->points)->where('top_point', '>=', $bool->points)->find();
			Session::set('level_id', $level['id'], 'sc');
			Session::set('level_rate', $level['rate'], 'sc');

			if ( $remember ) {
				Cookie::set('username', encrypt($bool->username), [
					'expire' => 30*24*60*60,
					'prefix' => 'sc_',
				]);
				Cookie::set('password', encrypt(md5($pass)), [
					'expire' => 30*24*60*60,
					'prefix' => 'sc_',
				]);
			}
			return json([
				'error' => 0,
			]);
		} else {
			return json([
				'error' => 1,
				'message' => '<i class="iconfont icon-minus-sign"></i>用户名或者密码错误'
			]);
		}
	}
	public function mobileExist($mobile)
	{
		return self::where('mobile_phone', $mobile)->find();
	}
	public function sendCode($value, $type)
	{
		$code = (string)rand(111111, 999999);
		$bool = false;
		if ( $type == 0 ) {
			//手机验证
			//$bool = self::mebiler($value, $code);
			$bool = true;
		} else if ( $type == 1 ) {
			//邮箱验证
			$bool = self::sendMail($value, "测试邮箱注册验证", '你的验证码是: ' . $code . ' 请尽快验证');
		}
		if ( $bool ) {
			Session::set('code', $code);
			Session::set('value', $value);

			Cache::set($value, Cache::get($value)+1);
			return json(['msg' => $code]);
		}
		return json(['msg' => 'error']);
	}


}