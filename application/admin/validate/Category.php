<?php

namespace app\admin\validate;

use think\Validate;

class Category extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
       'cate_name'  => 'require|max:30|unique:category',
       'keywords' => 'max:150',
       'description' => 'max:255',
       'show_cate'  => 'between:0,1',
       'sort'  => 'number',
       'pid'  => 'number',
       'iconfont'  => 'max:30',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'cate_name.require'  => '分类名称不能为空',
        'cate_name.max'  => '分类名称不能超过30字节',
        'cate_name.unique'  => '分类名不能重复',
        'keywords.max'  => '关键词不能超过150字节',
        'description.max'  => '项目描述不能超过255字节',
        "show_cate.between" => '示导航栏必须是0或1',
        'sort.number'   => '排序必须是数字',
        'pid.number'   => '上级id必须是数字',
        'iconfont.max'   => '图标名称长度不能超过30字节',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['cate_name', 'keywords', 'description', 'show_cate', 'pid', 'iconfont'],
        'update' => ['cate_name', 'keywords', 'description', 'show_cate', 'pid', 'sort', 'iconfont'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];

}
