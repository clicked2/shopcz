<?php

namespace app\admin\validate;

use think\Validate;

class Filter extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'  => 'require|max:30',
        'type'  => 'between:1,2',
        'filter'  => 'number',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require'  => '名称不能为空',
        'name.max'  => '名称长度不能超过30字节',
        'type.between'  => '类型格式不对',
        'filter.number' => '过滤id不对',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['name', 'type', 'filter'],
        'update' => ['name', 'type', 'filter'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
        'getfiltervalues' => [''],
    ];
}
