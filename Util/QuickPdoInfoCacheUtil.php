<?php

namespace QuickPdo\Util;


/**
 * A cache wrapper/proxy for QuickPdoInfoTool.
 */
class QuickPdoInfoCacheUtil
{

    private $cacheDir;
    private $useCache;

    public function __construct()
    {
        $this->cacheDir = '/tmp/QuickPdoInfoCacheUtil';
        $this->useCache = true;
    }

    public static function create()
    {
        return new static();
    }


    public function cache($useCache)
    {
        $this->useCache = $useCache;
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }





    //--------------------------------------------
    //
    //--------------------------------------------
    public function getAutoIncrementedField($table, $schema = null)
    {
        return $this->getResult(__METHOD__, func_get_args(), "$table-$schema");
    }

    public function getColumnDataTypes($table, $precision = false)
    {
        return $this->getResult(__METHOD__, func_get_args(), $table);
    }

    public function getColumnDefaultValues($table)
    {
        return $this->getResult(__METHOD__, func_get_args(), $table);
    }

    public function getColumnNames($table, $schema = null)
    {
        return $this->getResult(__METHOD__, func_get_args(), "$table-$schema");
    }

    public function getColumnNullabilities($table)
    {
        return $this->getResult(__METHOD__, func_get_args(), $table);
    }

    public function getDatabase()
    {
        return $this->getResult(__METHOD__, func_get_args());
    }

    public function getDatabases($filterMysql = true)
    {
        return $this->getResult(__METHOD__, func_get_args());
    }

    public function getForeignKeysInfo($table, $schema = null)
    {
        return $this->getResult(__METHOD__, func_get_args(), "$table-$schema");
    }

    public function getPrimaryKey($table, $schema = null)
    {
        return $this->getResult(__METHOD__, func_get_args(), "$table-$schema");
    }

    public function getTables($db)
    {
        return $this->getResult(__METHOD__, func_get_args(), $db);
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private function getResult($method, array $args, $cacheHint = null)
    {
        $p = explode('::', $method);
        $method = array_pop($p);
        $f = $this->cacheDir . "/$method-$cacheHint.php";
        if (false === $this->useCache || false === file_exists($f)) {
            $ret = call_user_func_array(['QuickPdo\QuickPdoInfoTool', $method], $args);
            if (!is_dir($this->cacheDir)) {
                mkdir($this->cacheDir, 0777, true);
            }
            $s = serialize($ret);
            file_put_contents($f, $s);
        }

        $s = file_get_contents($f);
        return unserialize($s);
    }

}