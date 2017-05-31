<?php
/**
 * 日历事件接口类
 */

class Api_Event extends PhalApi_Api {

    public function getRules() {
        return array(
            'post' => array(
                'title' => array('name' => 'title', 'min' => 1, 'max' => 50, 'require' => true, 'desc' => '标题'),
                'content' => array('name' => 'content', 'min' => 1, 'max' => 200, 'require' => true, 'desc' => '内容'),
                'location' => array('name' => 'location', 'max' => 50, 'desc' => '位置信息'),
                'createTime' => array('name' => 'createtime', 'type' => 'date', 'desc' => '发布时间'),
                'tousers' => array('name' => 'tousers', 'type' => 'enum', 'range' => array('0', '1', '2'), 'default' => '1', 'desc' => '事件的权限（0：私有；1：公开；2：共享）'),
            ),

            'space' => array(
                'perpage' => array('name' => 'perpage', 'type' => 'int', 'default' => 20, 'min' => 1, 'max' => 100, 'desc' => '分页数量'),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'desc' => '当前第几页'),
                'createTime' => array('name' => 'createtime', 'type' => 'date', 'desc' => '发布时间'),
            ),

            'operate' => array(
                'id' => array('name' => 'event_id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '事件ID'),
                'state' => array('name' => 'state', 'type' => 'enum', 'require' => true, 'range' => array('0', '1', '2'), 'desc' => '状态（0：已删除；1：未完成；2：已完成）'),
            ),
        );
    }

    public function post() {
        if ($this->userId <= 0) {
            throw new Phalapi_Exception_InternalServerError('用户未登录');
        }

        $newEvent = array(
            'uid'           => $this->userId,
            'title'         => $this->title,
            'content'       => $this->content,
            'location'      => $this->location,
            'createtime'    => $this->createTime,
            'tousers'       => $this->tousers,
        );

        $domain = new Domain_Event();
        $id = $domain->post($newEvent);

        return array('id' => $id);
    }

    public function space() {
        if ($this->userId <= 0) {
            throw new Phalapi_Exception_InternalServerError('用户未登录');
        }

        $domain = new Domain_Event();
        $total = $domain->getSpaceTotal($this->userId, $this->createTime);

        $list = $domain->getSpaceList($this->userId, $this->createTime, $this->perpage, $this->page);
        
        return array(
            'total' => $total, 
            'perpage' => $this->perpage, 
            'page' => $this->page, 
            'list' => $list
        );
    }

    public function operate() {
        if ($this->userId <= 0) {
            throw new Phalapi_Exception_InternalServerError('用户未登录');
        }

        $domain = new Domain_Event();
        $code = $domain->operate($this->userId, $this->id, $this->state);

        return array('code' => $code);
    }
}
