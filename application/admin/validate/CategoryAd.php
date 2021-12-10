<?php

namespace app\admin\validate;

use think\Validate;

class CategoryAd extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'category_id'  => 'number',
        'link_url'   => 'max:60',
        'img_src'   => 'max:60',
        'position'   => 'max:1',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'category_id.number'  => '关联栏目必须是数字编号',
        'link_url.max'  => '关联链接长度不能超过60字节',
        'img_src.max'  => '关联图片长度不能超过60字节',
        'position.max'  => '关联定位长度不能超过1字节',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['category_id', 'link_url', 'img_src', 'position'],
        'update' => ['category_id', 'link_url', 'img_src', 'position'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
