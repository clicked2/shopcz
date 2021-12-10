<?php

namespace app\admin\validate;

use think\Validate;

class Alternate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'img_src'  => 'max:60',
        'link_url'   => 'max:60',
        'title' => 'require|max:60', 
        'status' => 'between:0,1', 
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'  => '标题不能为空',
        'title.max'  => '标题长度不能超过60字节',
        'status.between'   => '状态不正确',
        'link_url.max' => '链接地址长度不能超过60', 
        'img_src.max' => '图片地址长度不能超过60', 
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['title', 'status', 'link_url', 'img_src'],
        'update' => ['title', 'status', 'link_url', 'img_src'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
