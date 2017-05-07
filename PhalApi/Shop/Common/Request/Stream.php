<?php
class My_Request_Stream extends PhalApi_Request {
    
    protected function &getDataBySource($source) {
        if (strtoupper($source) == 'stream') {
            // TODO 处理二进制流
        }

        return parent::getDataBySource($source);
    }
}

