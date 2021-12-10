<?php

namespace app\admin\validate;

use think\Validate;

class CategoryBrands extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'brands_id'  => 'require',
        'category_id'   => 'number|require',
        'pro_url' => 'max:60|require', 
        'pro_img' => 'max:60', 
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'brands_id.require'  => '品牌id不能为空',
        'category_id.require'  => '栏目id不能为空',
        'category_id.number'  => '栏目id必须是数字',
        'pro_url.max'   => 'url地址不能超过60字节',
        'pro_img.max' => '长度不能超过60字节', 
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['brands_id', 'category_id', 'pro_url', 'pro_img'],
        'update' => ['brands_id', 'category_id', 'pro_url', 'pro_img'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
