<?php

namespace QuickPdo\Mysql;

/*
 * LingTalfi 2016-02-12
 * 
 * http://dev.mysql.com/doc/refman/5.7/en/error-messages-server.html
 * 
 */
use QuickPdo\QuickPdoInfoTool;

class QuickPdoExceptionTool
{


    public static function isDuplicateEntry(\PDOException $e)
    {
        $sqlstate = $e->errorInfo[0];
        $driverCode = $e->errorInfo[1];
        if ('23000' === $sqlstate) {
            $driver = QuickPdoInfoTool::getDriver();
            if ("mysql" === $driver && 1062 === $driverCode) {
                return true;
            }
            else {
                throw new \Exception("Driver not implemented: $driver");
            }
        }
        return false;
    }
}
