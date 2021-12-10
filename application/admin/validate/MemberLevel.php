<?php

namespace app\admin\validate;

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
        'level_name'  => 'require|unique:member_level',
        'bom_point'  => 'number',
        'top_point'  => 'number',
        'rate'  => 'gtHunderd',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'level_name.require'  => '会员级别名称不能为空',
        'level_name.unique'  => '会员级别名称不能重复',
        'bom_point.number'  => '积分下限必须是数字',
        'top_point.number'  => '积分上限必须是数字',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['level_name', 'bom_point', 'top_point', 'rate'],
        'update' => [''],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];

    protected function gtHunderd($value)
    {
        return $value >= 1 && $value <= 100 || empty($value) ? true : '折扣率不正确 可以填空或1-100';
    }
}
