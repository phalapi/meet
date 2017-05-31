<?php
/**
 * 资源接口类
 */

class Api_Resource extends PhalApi_Api {

    public function getRules() {
        return array(
            'uploadImg' => array(
                'img' => array(
                    'name' => 'img', 
                    'type' => 'file', 
                    'require' => true, 
                    'max' => 2097152, // 2M = 2 * 1024 * 1024, 
                    'range' => array('image/jpeg', 'image/png'), 
                    'ext' => 'jpeg,jpg,png', 
                    'desc' => '待上传的图片文件',
                ),
            ),
        );
    }

    public function uploadImg() {
        $rs = array('code' => 0, 'url' => '');

        $tmpName = $this->img['tmp_name'];

        $name = md5($this->img['name']);
        $ext = strrchr($this->img['name'], '.');
        $imgPath = sprintf('%s/Public/upload/%s%s', API_ROOT, $name, $ext);

        if (move_uploaded_file($tmpName, $imgPath)) {
            $rs['code'] = 1;
            $rs['url'] = sprintf('//%s/upload/%s%s', $_SERVER['SERVER_NAME'], $name, $ext);
        }

        return $rs;
    }
}
