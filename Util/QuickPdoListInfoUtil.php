<?php

namespace QuickPdo\Util;


use QuickPdo\QuickPdo;

class QuickPdoListInfoUtil
{

    private $querySkeleton;
    private $queryCols;


    /**
     * If null, means all columns allowed
     */
    private $allowedSorts;
    private $allowedFilters;


    public function __construct()
    {
        $this->querySkeleton = "";
        $this->queryCols = [];
        //
        $this->allowedFilters = null;
        $this->allowedSorts = null;
    }

    public static function create()
    {
        return new static();
    }

    public function setQuerySkeleton($querySkeleton)
    {
        $this->querySkeleton = $querySkeleton;
        return $this;
    }

    public function setQueryCols(array $queryCols)
    {
        $this->queryCols = $queryCols;
        return $this;
    }

    public function setAllowedSorts(array $allowedSorts)
    {
        $this->allowedSorts = $allowedSorts;
        return $this;
    }

    public function setAllowedFilters(array $allowedFilters)
    {
        $this->allowedFilters = $allowedFilters;
        return $this;
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    public function execute(array $params = [])
    {
        $params = array_replace([ // data coming from the user
            "sort" => [],
            "filters" => [],
            "page" => 1,
            "nipp" => 20,
        ], $params);

        $markers = [];

        //--------------------------------------------
        // CONF
        //--------------------------------------------
        $q = $this->querySkeleton;


        $allowedSort = $this->allowedSorts;
        $allowedFilter = $this->allowedFilters;

        $sort = $params['sort'];
        $filters = $params['filters'];
        $page = $params['page'];
        $nipp = (int)$params['nipp'];


        //--------------------------------------------
        // REQUEST
        //--------------------------------------------


        if ($page < 1) {
            $page = 1;
        }


        // FILTERING
        //--------------------------------------------
        $realFilters = [];
        if ($filters) {
            foreach ($filters as $col => $value) {
                if (null === $allowedFilter || in_array($col, $allowedFilter, true)) {
                    $realFilters[$col] = $value;
                }
            }
        }
        if ($realFilters) {
            if (false === stripos($q, 'where ')) {
                $q .= " where ";
            } else {
                $q .= ' and ';
            }
            $c = 0;
            foreach ($realFilters as $col => $value) {
                if (0 !== $c) {
                    $q .= " and ";
                }
                $marker = "mark$c";
                $q .= "$col like :$marker";
                $markers[$marker] = '%' . str_replace(['%', '_'], ['\%', '\_'], $value) . '%';
                $c++;
            }
        }


        // COUNT QUERY
        //--------------------------------------------
        $qCount = sprintf($q, 'count(*) as count');
        $nbItems = (int)QuickPdo::fetch($qCount, $markers, \PDO::FETCH_COLUMN);


        // SORT
        //--------------------------------------------
        $realSorts = [];
        if ($sort) {
            foreach ($sort as $col => $dir) {
                if (null === $allowedSort || in_array($col, $allowedSort, true)) {
                    if ('asc' === $dir || 'desc' === $dir) {
                        $realSorts[$col] = $dir;
                    }
                }
            }
            if ($realSorts) {
                $q .= " order by ";
                $c = 0;
                foreach ($realSorts as $col => $dir) {
                    if (0 !== $c) {
                        $q .= ', ';
                    }
                    $q .= "$col $dir";
                    $c++;
                }
            }
        }

        // LIMIT
        //--------------------------------------------
        $maxPage = 1;
        if ($nbItems > 0 && $nipp > 0) {
            $maxPage = ceil($nbItems / $nipp);
            if ($maxPage > 0) {
                if ($page < 1) {
                    $page = 1;
                }
                if ($page > $maxPage) {
                    $page = $maxPage;
                }

                $offset = ($page - 1) * $nipp;
                $q .= " limit $offset, $nipp";
            }
        }


        $q = sprintf($q, self::getQueryColsAsString($this->queryCols));


        $rows = QuickPdo::fetchAll($q, $markers);


        return [
            'rows' => $rows,
            'page' => $page,
            'sort' => $realSorts,
            'filters' => $realFilters,
            'nbItems' => $nbItems,
            'nbPages' => $maxPage,
            'nipp' => $nipp,
        ];
    }


    //--------------------------------------------
    //
    //--------------------------------------------
    private static function getQueryColsAsString(array $queryCols)
    {
        $queryCols = array_map(function ($v) {

            if (false !== stripos($v, "concat")) {
                return $v;
            }

            if (false === strpos($v, '`')) {

                $q = preg_split('! as !u', $v, 2);
                if (count($q) > 1) {
                    $p = explode('.', $q[0], 2);
                    if (count($p) > 1) {
                        $s = $p[0] . '.`' . $p[1] . '` as ' . $q[1];
                    } else {
                        $s = $p[0] . '.`' . $p[1] . '`';
                    }
                } else {
                    $p = explode('.', $q[0], 2);
                    if (count($p) > 1) {
                        $s = $p[0] . '.`' . $p[1] . '`';
                    } else {
                        $s = '`' . $p[0] . '`';
                    }
                }
                return $s;
            } else {
                // the user takes care of escaping manually
                return $v;
            }
        }, $queryCols);

        $s = implode(", ", $queryCols);
        return $s;
    }
}
