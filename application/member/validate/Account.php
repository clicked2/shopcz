<?php

namespace app\member\validate;

use think\Validate;
use think\facade\Session;
use think\facade\Cache;
class Account extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'register_type'  => 'check1',
        'username'   => 'require|unique:user',
        'password' => 'require|min:6', 
        'mobile_phone' => 'mobile|ownCount', 
        'email' => 'email|ownCount',
        'type' => 'checkName',
        'code' => 'checkCode',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'username.require'  => '用户名不能为空',
        'username.unique'  => '用户名已被使用',
        'email.email'   => '邮箱格式不正确',
        'password.min' => '密码长度不能少于6位', 
        'password.require' => '密码不能为空', 
        'mobile_phone.mobile' => '手机格式不正确', 
    ];
// 'checkreg' => ['register_type'],
       
    //场景验证设置
    protected $scene = [
        'checkreg' => ['register_type', 'mobile_phone', 'email', 'username', 'password', 'mobile_phone'],
        'checksend' => ['type'],
        'reg' => [''],
        'login' => [''],
        'checkname' => [''],
        'checkphone' => [''],
        'checkmail' => [''],
        'checkmailcode' => [''],
        'checkmobilecode' => [''],
        'checklogin' => [''],
        'loginout' => [''],
        'getpassword' => [''],
        'newpassword' => ['code'],
        'index' => [''],
    ];

    protected function check1($value)
    {
    	return !empty(request()->param('mobile_phone')) && Session::get('code') == request()->param('mobile_code') && $value == 1 || !empty(request()->param('email')) && Session::get('code') == request()->param('send_code') && $value == 0 ? true : '手机或邮箱错误或验证码不正确';
    }
    protected function ownCount($value)
    {
    	return Cache::get($value) < 5 ? true : '今日以获取5次';
    }
    protected function checkName($value)
    {
    	return $value == 1 && preg_match("/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i", request()->param('value')) || $value == 0 && preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/", request()->param('value')) ? true : '手机或邮箱格式不正确';
    }
    protected function checkCode($value)
    {
        return $value == Session::get('code') && request()->param('new_password') == request()->param('confirm_password') && strlen(request()->param('new_password')) >= 6 && Session::get('value') == request()->param('value') ? true : '修改密码错误';
    }
    
    
}
