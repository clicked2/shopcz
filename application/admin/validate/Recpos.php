<?php

namespace app\admin\validate;

use think\Validate;

class Recpos extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'rec_name'  => 'require',
        'rec_type'  => 'between:1,2',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'rec_name.require'  => '推荐位名称不能为空',
        'rec_type.between'  => '推荐位类型编号错误',
      
        
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['rec_name', 'rec_type'],
        'update' => ['rec_name', 'rec_type'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];
}
