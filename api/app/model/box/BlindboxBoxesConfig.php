<?php

namespace app\model\box;

use app\model\BaseModel;

class BlindboxBoxesConfig extends BaseModel
{
    public function zhushi()
    {
        $str = <<<EOL
与原来的格式一直 ，新增goods 分类添加赏品

如果是潮玩赏 、赏种不能重复 、每个赏种里的赏品不能重复、总中奖率为100、有封面图
无限赏 页面不展示赏种传递0就好了 ，只要赏品、赏品不能重复
            {
                "data": [
                    {
                        "tag_id": 1,//
                        "picture_image": "",
                        "probability": "0",
                        "goods": [
                            {
                                "blindbox_id": "14",
                                "tag_id": 1,
                                "goods_id": 3,
                                "goods_name": "霸王洗发水",
                                "goods_image": "http://113.31.103.120/storage/local/20240507/9e360f9c33ac24aba0de88ee5f4f798a.png",
                                "price": "10.00",
                                "recovery_price": "1000.00",
                                "stock": 1,
                                "probability": "100.000"
                            }
                        ]
                    },
                    {
                        "tag_id": 1,
                        "picture_image": "",
                        "probability": "0",
                        "goods": [
                            {
                                "blindbox_id": "14",
                                "tag_id": 1,
                                "goods_id": 1,
                                "goods_name": "小米12spro",
                                "goods_image": "http://113.31.103.120/storage/local/20240507/9e360f9c33ac24aba0de88ee5f4f798a.png",
                                "price": "2999.00",
                                "recovery_price": "199900.00",
                                "stock": 3,
                                "probability": "100.000"
                            }
                        ]
                    }
                ],
                "blindbox_id": 14,
                "play_id": 5
}
EOL;
    }


}
