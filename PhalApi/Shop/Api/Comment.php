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
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'desc' => '评论ID'),
                'content' => array('name' => 'content', 'require' => true, 'desc' => '待更新的评论内容'),
                'author' => array('name' => 'author', 'default' => 'nobody', 'desc' => '评论作者'),
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
        return array('id' => $this->id, 'content' => '模拟获取：评论内容');
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
        return array('id' => $this->id, 'content' => '模拟删除：评论内容');
    }
}
