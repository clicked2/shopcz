<?php

namespace app\admin\validate;

use think\Validate;

class Type extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'type_name'  => 'require|unique:type|max:30',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'type_name.require'  => '商品类型名称不能为空',
        'type_name.unique'  => '商品类型名称不能重复',
        'type_name.max'  => '商品类型名称长度不能超过30字节',
        
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['type_name'],
        'update' => ['type_name'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
