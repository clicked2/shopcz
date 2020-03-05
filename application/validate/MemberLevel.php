<?php

namespace app\validate;

use think\Validate;

class MemberLevel extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'level_name'    => 'require|max:150',
        'jifen_top'     => 'number|between:0,1e9',
        'jifen_bottom'  => 'number|between:0,1e9',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'level_name.require'   => '级别名称不能为空',
        'level_name.max'       => '级别名称长度不能超过150字节',
        'jifen_top.number'     => '积分上限必须是数字',
        'jifen_top.between'    => '积分上限最大不能超过一亿 最大值是16777215',
        'jifen_bottom.number'  => '积分下限必须是数字',
        'jifen_bottom.between' => '积分下限最大不能超过一亿 最大值是16777215',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['level_name', 'jifen_top', 'jifen_bottom'],
        'update' => ['level_name', 'jifen_top', 'jifen_bottom'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];

}
