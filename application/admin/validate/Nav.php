<?php

namespace app\admin\validate;

use think\Validate;

class Nav extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'nav_name'  => 'require',
        'nav_url'  => 'require|url',
        'open'  => 'number',
        'pos'  => 'number',
        'sort'  => 'number',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'nav_name.require'  => '导航名称不能为空',
        'nav_url.require'  => '导航地址不能为空',
        'nav_url.url'  => '导航地址格式不正确',
        'open.number'  => '打开方式只能是数字编号',
        'pos.number'  => '定位只能是数字编号',
        'sort.number'  => '排序必须为数字',
        
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['nav_name', 'nav_url', 'open', 'pos', 'sort'],
        'update' => ['nav_name', 'nav_url', 'open', 'pos', 'sort'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
