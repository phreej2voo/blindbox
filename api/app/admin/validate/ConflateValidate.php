<?php
namespace app\admin\validate;

use think\Validate;

class ConflateValidate extends Validate
{
    protected $rule = [
        'name|合成标题' => 'require|max:155',
        'activity_time|活动时间' => 'require',
        'goods_id|合成商品ID' => 'require',
        'goods_name|合成商品名称' => 'require',
        'image|合成商品图片' => 'require',
        'price|合成商品价格' => 'require',
        'conflate_val|合成值' => 'require|number',
        'conflate_limit|合成材料限制' => 'require',
        'stock|合成库存' => 'require|number|min:1',
        'sort|排序' => 'require|min:0|max:100',
        'status|状态' => 'require'
    ];
}