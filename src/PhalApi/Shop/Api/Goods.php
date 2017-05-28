<?php
class Api_Goods extends PhalApi_Api {

    public function getRules() {
        return array(
            'snapshot' => array(
                'id' => array('name' => 'id', 'require' => true, 'type' => 'int', 'min' => 1, 'desc' => '商品ID'),
            ),
        );
    }

    /**
     * 获取商品快照信息
     * @desc 获取商品基本和常用的信息
     * @return int      goods_id    商品ID
     * @return string   goods_name  商品名称 
     * @return int      goods_price 商品价格
     * @return string   goods_image 商品图片
     * @exception 406 签名失败
     */
    public function snapshot() {
        /**
        // 模拟的数据
        return array(
            'goods_id' => 1,
            'goods_name' => 'iPhone 7 Plus',
            'goods_price' => 6680,
            'goods_image' => '/images/iphone_7_plus.jpg',
        );
         */

        $domain = new Domain_Goods();
        $info = $domain->snapshot($this->id);
        return $info;
    }
}
