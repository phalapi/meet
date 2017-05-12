<?php
/**
 * 评论接口服务
 */

class Api_Comment extends PhalApi_Api {

    public function getRules() {
        return array(
            'get' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true),
            ),
            'add' => array(
                'content' => array('name' => 'content', 'require' => true),
            ),
            'update' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true),
                'content' => array('name' => 'content', 'require' => true),
            ),
            'delete' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true),
            ),
        );
    }

    /**
     * 获取评论
     */
    public function get() {
        return array('id' => $this->id, 'content' => '模拟获取评论内容');
    }

    /**
     * 添加评论
     */
    public function add() {
        return array('id' => 1, 'content' => '模拟添加：' . $this->content);
    }

    /**
     * 更新评论
     */
    public function update() {
        return array('id' => $this->id, 'content' => '模拟更新：' . $this->content);
    }

    /**
     * 删除评论
     */
    public function delete() {
        return array('id' => $this->id);
    }
}
