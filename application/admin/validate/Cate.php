<?php

namespace app\admin\validate;

use think\Validate;

class Cate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
       'cate_name'  => 'require|max:20',
       'create_type'   => 'between:1,5',
       'show_nav' => 'between:0,1',
       'allow_son' => 'between:0,1',
       'keywords'  => 'max:100',
       'description'  => 'max:150',

    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'cate_name.require'  => '系统栏目不能为空',
        'cate_name.max'  => '项目名不能超过20字节',
        'keywords.max'  => '关键词不能超过100字节',
        'description.max'  => '项目描述不能超过150字节',
        "allow_son.between" => '允许子分类必须是0或1',
        "show_nav.between" => '显示导航栏必须是0或1',
        "create_type.between" => '分类类型必须是1-5之间的数',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['cate_name', 'keywords', 'description', 'show_nav', 'allow_son', 'create_type'],
        'update' => ['cate_name', 'keywords', 'description', 'show_nav'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];

}
