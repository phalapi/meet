<?php

class Domain_Goods {

    /**
     * @return array 快照信息
     */
    public function snapshot($goodsId) {
        $model = new Model_Goods();
        $info = $model->getSnapshot($goodsId);

        if (empty($info) || $info['goods_price'] <= 0) {
            return array();
        }

        return $info;
    }
}
