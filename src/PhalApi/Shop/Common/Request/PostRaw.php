<?php
class My_Request_PostRaw extends PhalApi_Request{
    public function __construct($data = NULL) {
        parent::__construct($data);

        $this->post = json_decode(file_get_contents('php://input'), TRUE);
    }  
}
