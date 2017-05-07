<?php

class Common_DB_MSServer extends PhalApi_DB_NotORM {

    protected function createPDOBy($dbCfg) {
        $dsn = sprintf('odbc:Driver={SQL Server};Server=%s,%s;Database=%s;',
            $dbCfg['host'],
            $dbCfg['port'],
            $dbCfg['name']
        );

        $pdo = new PDO(
            $dsn,
            $dbCfg['user'],
            $dbCfg['password']
        );

        return $pdo;
    }
}

