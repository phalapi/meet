<?php

class Common_Response_Result extends PhalAPi_Response_JSON {

    public function getResult() {
        $newRs = array();

        $oldRs = parent::getResult();
        if ($oldRs['ret'] >= 200 && $oldRs['ret'] <= 299) {
            $newRs = $oldRs['data'];
        } else {
            $newRs['status'] = $oldRs['ret'];
            $newRs['errormsg'] = $oldRs['msg'];
        }

        if (isset($oldRs['debug']) && is_array($newRs)) {
            $newRs['debug'] = $oldRs['debug'];
        }

        return $newRs;
    }
}

