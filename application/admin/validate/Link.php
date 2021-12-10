<?php

namespace app\admin\validate;

use think\Validate;

class Link extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title'  => 'require|unique:link',
        'link_url'   => 'url',
        'description' => 'max:255',
        'type' => 'between:1,2',
        'status' => 'between:0,1',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
       'title.require' => '标题不能为空',
       'title.unique' => '标题不能重复',
       'description.max' => '描述长度不能超过255字节',
       'type.between' => '显示类型必须是1或者2',
       'status.between' => '状态必须是0或者1',
       'link_url.url' => '外链地址不正确'
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['title', 'description', 'type', 'status', 'link_url'],
        'update' => ['title', 'description', 'type', 'status', 'link_url'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
