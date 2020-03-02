<?php

namespace app\validate;

use think\Validate;

class Goods extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'goods_name'   => 'require|max:150',
        'market_price' => 'float|between:0,1e9',
        'shop_price'   => 'float|between:0,1e9',
        'goods_desc'   => 'max:2048',
        'ios'          => 'checkIos',
        'is_on_sale'   => 'checkSale',
        'fp'           => 'isnum',
        'sp'           => 'isnum',
        'gn'           => 'max:150',
        'fa'           => 'max:20',
        'ta'           => 'max:20',
        'odby'         => 'max:20',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'goods_name.require'  => '商品名称不能为空',
        'goods_name.max'      => '商品长度不能超过150字节',
        'market_price.float' => '市场价格必须是数字',
        'shop_price.float'   => '价格必须是数字',
        'market_price.between' => '市场价格最大一亿',
        'shop_price.between'   => '价格最大一亿',
        'goods_desc.max'      => '商品介绍长度不能超过2KB',
        'gn.max'              => '搜索商品长度不能超过150字节',
        'fa.max'              => '时间长度不能超过20字节',
        'ta.max'              => '时间长度不能超过20字节',
        'odby.max'            => '排序条件长度不能超过20字节',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['market_price', 'shop_price', 'goods_name', 'is_on_sale', 'goods_desc'],
        'update' => ['market_price', 'shop_price', 'goods_name', 'is_on_sale', 'goods_desc'],
        'read' => ['ios', 'gn', 'fa', 'ta', 'fp', 'sp'],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
    ];

    protected function checkSale($value)
    {
        return $value == '1' || $value == '0' ? true : '是否上架不正确';
    }
    protected function checkIos($value)
    {
        return $value == '1' || $value == '0' || $value == ''? true : '是否上传不正确';
    }
    protected function isnum($value)
    {
        return $value == '' || is_numeric($value) && $value <= 1e9 ? true : "价格不是有效数字";
    }

}
