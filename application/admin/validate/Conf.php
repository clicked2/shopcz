<?php

namespace app\admin\validate;

use think\Validate;

class Conf extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'ename'  => 'require|max:30|unique:conf',
        'cname'  => 'require|max:30|unique:conf',
        'form_type'  => 'require|max:10',
        'conf_type'  => 'require|between:1,2',
        'values'  => 'max:60',
        'value'   => 'max:255',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'ename.require'      => '配置中文名称不能为空',
        'ename.max'          => '配置中文名称长度不能超过30字节',
        'cname.max'          => '配置英文名称长度不能超过30字节',
        'cname.require'      => '配置英文名称不能为空',
        'cname.unique'      =>  '配置英文名称不能已存在',
        'ename.unique'      =>  '配置中文名称不能已存在',
        'form_type.require'  => '表单类型不能为空',
        'form_type.max'      => '表单类型长度不能超过30字节',
        'conf_type.require'  => '配置类型不能为空',
        'conf_type.between'  => '配置类型必须是1或是0',
        'values.max'         => '自定义长度不能超过60字节', 
        'value.max'          => '自定义长度不能超过255字节', 
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['ename', 'cname', 'form_type', 'conf_type', 'values', 'value'],
        'update' => ['ename', 'cname', 'form_type', 'conf_type', 'values', 'value'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
        'conflist' => [''],
        'confsave' => ['ename', 'cname', 'form_type', 'conf_type', 'values', 'value'],
    ];
}
