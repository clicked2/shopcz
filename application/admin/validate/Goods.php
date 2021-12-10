<?php

namespace app\admin\validate;

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
        'goods_name'  => 'require',
        'market_price'  => 'require|float',
        'shop_price'  => 'require|float',
        'on_sale'  => 'between:0,1',
        'category_id'  => 'require',
        'goods_weight'  => 'require|float',
    ];
 
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'goods_name.require'  => '商品名称不能为空',
        'market_price.require'  => '市场价格不能为空',
        'shop_price.require'  => '商品价格不能为空',
        'on_sale.between'  => '是否上架只能是0或1',
        'category_id.require'  => '栏目不能为空',
        'goods_weight.require'  => '重量不能为空',
        'market_price.float'   => '市场价格格式不正确',
        'shop_price.float'   => '商品价格格式不正确',
        'goods_weight.float'   => '重量格式不正确',
    ];

    //场景验证设置
    protected $scene = [
        'save' => ['goods_name', 'market_price', 'shop_price', 'on_sale', 'category_id', 'goods_weight'],
        'update' => ['goods_name', 'market_price', 'shop_price', 'on_sale', 'category_id', 'goods_weight'],
        'read' => [''],
        'edit' => [''],
        'delete' => [''],
        'add' => [''],
        'index' => [''],
        'product' => [''],
        'productsubmit' => [''],
        'deletephoto' => [''],
        'getgoodsattr' => [''],
        'deleteattr' => [''],
    ];
}
