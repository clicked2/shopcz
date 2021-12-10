<?php

namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'brand_name'  => 'require|unique:brand',
        'brand_url'   => 'url',
        'brand_description' => 'max:255', 
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'brand_name.require'  => '品牌名称不能为空',
        'brand_name.unique'  => '品牌名称已经存在',
        'brand_url.url'   => 'url地址不正确',
        'brand_description.max' => '长度不能超过255', 
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['brand_name', 'brand_url', 'brand_description'],
        'update' => ['brand_name', 'brand_url', 'brand_description'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
