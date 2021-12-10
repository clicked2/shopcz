<?php

namespace app\admin\validate;

use think\Validate;

class CategoryWords extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'words'  => 'require|unique:category_words',
        'link_url'  => 'max:60',
        'category_id'  => 'number',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'words.require'  => '关联词汇不能为空',
        'words.unique'  => '关联词汇重复出现',
        'link_url.max'  => '链接地址长度超过60字节',
        'category_id.number'  => '栏目类型编号错误',
        
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['words', 'link_url', 'category_id'],
        'update' => ['words', 'link_url', 'category_id'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
