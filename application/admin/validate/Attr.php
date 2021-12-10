<?php

namespace app\admin\validate;

use think\Validate;

class Attr extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'attr_name'  => 'require|max:30',
        'attr_type'  => 'between:1,2',
        'attr_values'  => 'max:150',
        'type_id'  => 'require|number',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'attr_name.require'  => '属性名不能为空',
        'attr_name.max'  => '属性名长度不能超过30字节',
        'attr_type.between'  => '属性类型只能是1或2',
        'attr_values.max'  => '属性值长度不能超过150字节',
        'type_id.number'  => '所属类型id必须是数字',
        'type_id.require'  => '所属类型id不能为空',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['attr_name', 'attr_type', 'attr_values', 'type_id'],
        'update' => ['attr_name', 'attr_type', 'attr_values', 'type_id'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
        'getattr' => [''],
    ];
}
