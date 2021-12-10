<?php

namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'title'  => 'require|unique:article|max:30',
        'link_url'  => 'url',
        'email'   => 'email',
        'keywords'   => 'max:60',
        'description'   => 'max:150',
        'author'   => 'max:10',
        'show_top' => 'between:0,1',
        'show_status' => 'between:0,1',
        'cate_id' => 'require|number',
    
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'title.require'  => '文章名称不能为空',
        'title.unique'  => '文章名称已经存在',
        'title.max'  => '文章名称长度不能超过30字节',
        'keywords.max'  => '关键词长度不能超过60字节',
        'description.max'  => '描述长度不能超过150字节',
        'author.max'  => '作者名长度不能超过10字节',
        'link_url.url' => '外链url不正确',
        'cate_id.number' => '栏目id必须是数字',
        'cate_id.require' => '栏目id不能为空',
        'show_top.between' => '是否置顶只能是0或1',
        'show_status.between' => '是否显示只能是0或1',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['link_url', 'email', 'title', 'keywords', 'description', 'author', 'cate_id', 'show_top', 'show_status'],
        'update' => ['link_url', 'email', 'title', 'keywords', 'description', 'author', 'cate_id', 'show_top', 'show_status'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
        'imglist' => [''],
        'imgdownload' => [''],
        'imgdelete' => [''],
        'imgdeleteall' => [''],
    ];
   
}
