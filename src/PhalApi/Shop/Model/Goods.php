<?php

class Model_Goods {

    public function getSnapshot($goodsId) {
        $allGoods = array(
            1 => array(
                'goods_id' => 1,
                'goods_name' => 'iPhone 7 Plus',
                'goods_price' => 6680,
                'goods_image' => '/images/iphone_7_plus.jpg',
            ),
            2 => array(
                'goods_id' => 2,
                'goods_name' => 'iPhone 6 Plus',
                'goods_price' => 4588,
                'goods_image' => '/images/iphone_6_plus.jpg',
            ),
        );

        return isset($allGoods[$goodsId]) ? $allGoods[$goodsId] : array();
    }
}
