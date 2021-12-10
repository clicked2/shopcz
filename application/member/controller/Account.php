<?
namespace app\member\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Cache;
use think\facade\Cookie;

use app\member\model\User as UserModel;
class Account extends Controller
{
	public function initialize()
	{
		//验证规则
		$validate = self::validate(request()->param(), 'app\member\validate\Account.'.$this->request->action());
		if ( $validate !== true ) return is_array($validate) ? self::error('rule: '.implode(', ', $validate)) : self::error('rule: '. $validate);
	}
	public function index()
	{
		
	}
	public function reg()
	{
		
		return view('reg');
		
	}
	public function checkReg()
	{
		$arr = request()->param();
		$arr['checked'] = 1;
		$arr['register_type'] ? $arr['email_checked'] = 1 : $arr['phone_checked'] = 1;
		
		$user = new UserModel;
		$request = $user->save($arr);
		
		if ( $request ) {
			self::success('msg: 注册成功', './member/account/user');
			//json(['user_id' => $request->id, 'msg' => 'ok' ]);
		} else {
			self::error('msg: 注册失败');
			//json(['error' => 1, 'msg' => 'not' ]);
		}
	}
	public function login()
	{
		if ( request()->param('username') ) {
			$user = new UserModel;
			return $user->login(request()->param('username'), request()->param('password'), request()->param('remember'));
		}

		
		return view('login');
	}
	public function getPassword()
	{
		
		return view('get_password');
	}
	
	public function newPassword()
	{
		$user = new UserModel;
		$value = request()->param('value');
		$user = $user->whereOr(['mobile_phone' => $value, 'email' => $value])->find();
		return $user->save(['password' => request()->param('new_password')]) ? self::success('修改密码成功', './member/account/login') : self::error('修改密码失败');
	}

	public function checkSend()
	{
		$type = request()->param('type');
		$value = request()->param('value');
		$user = new UserModel;
		return $user->sendCode($value, $type);
	}
	public function checkName()
	{
		$result = db('user')->field('username')->where('username', request()->param('username'))->find();
		return $result ? false : true;
	}
	public function checkPhone()
	{
		$result = db('user')->field('mobile_phone')->where('mobile_phone', request()->param('mobile_phone'))->find();
		return $result ? false : true;
	}
	public function checkMail()
	{
		$result = db('user')->field('email')->where('email', request()->param('email'))->find();
		return $result ? false : true;
	}
	public function checkMailCode()
	{
		return Session::get('code') == request()->param('send_code') ? true : false;
	}
	public function checkMobileCode()
	{
		return Session::get('code') == request()->param('mobile_code') ? true : false;
	}
	public function checkLogin()
	{
		if ( !empty(Session::get('uid', 'sc')) ) {
			return json(['uid' => Session::get('uid', 'sc'), 'username' => Session::get('username', 'sc')]);
		} else {
			$name = encrypt(Cookie::get('username', 'sc_'), true);
			$pass = encrypt(Cookie::get('password', 'sc_'), true);
			if ( $name && $pass ) {
				$user = new UserModel;
				$user->login($name, $pass);
			}
			return isset($user) ? json(['uid' => $user->id, 'username' => $user->username]) : json(['uid' => '', 'username' => '']);
		}
	}
	public function loginOut()
	{
		Session::clear('sc');
		Cookie::clear('sc_');
		return self::success('退出成功');
	}

	









	//发送手机验证码
	static function mebiler($mobile, $code)
	{
	    $host = "https://zwp.market.alicloudapi.com";
	    $path = "/sms/sendv2";
	    $method = "GET";
	    $appcode = "9a845576f5b3475e875310e4ac7dc031";
	    $headers = array();
	    array_push($headers, "Authorization:APPCODE " . $appcode);
	    $querys = "content=%E3%80%90%E4%BA%91%E9%80%9A%E7%9F%A5%E3%80%91%E6%82%A8%E7%9A%84%E9%AA%8C%E8%AF%81%E7%A0%81%E6%98%AF123456%E3%80%82%E5%A6%82%E9%9D%9E%E6%9C%AC%E4%BA%BA%E6%93%8D%E4%BD%9C%EF%BC%8C%E8%AF%B7%E5%BF%BD%E7%95%A5%E6%9C%AC%E7%9F%AD%E4%BF%A1&mobile=1343994XXXX";
	    $bodys = "";
	    $url = $host . $path . "?" . $querys;

	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($curl, CURLOPT_FAILONERROR, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_HEADER, true);
	    if (1 == strpos("$".$host, "https://"))
	    {
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	    }
	    $output = curl_exec($curl);
	    return $output;
	}
	//发送邮件
	static function sendMail($email = "", $title ="", $content="")
	{

        include_once '../application/extend/smtp/Smtp.php';

        $smtpserver = "smtp.163.com"; //SMTP服务器

        $smtpserverport = 25; //SMTP服务器端口

        $smtpusermail = "15907291239@163.com"; //SMTP服务器的用户邮箱

        $smtpuser = "15907291239"; //SMTP服务器的用户帐号

        $smtppass = "WKLVHNKWHCJRXKLO"; //SMTP服务器的授权密码

        $smtp = new \Smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.

        $emailtype = "HTML"; //信件类型，文本:text；网页：HTML

        $smtpemailto = $email;

        $smtpemailfrom = $smtpusermail;

        $emailsubject = $title;

        $emailbody = "<p>".$content."</p>";

        //开始发送邮件

       return $smtp->sendmail($smtpemailto, $smtpemailfrom, $emailsubject, $emailbody, $emailtype);
	}

}