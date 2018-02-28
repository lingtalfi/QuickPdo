<?php


namespace QuickPdo\Helper;


use QuickPdo\QuickPdoStmtTool;

class QuickPdoHelper
{

    private static $quickPdoMethods = [
        'update' => "update",
        'replace' => 'create',
        'insert' => 'create',
        'delete' => 'delete',
    ];


    public static function addDateRangeToQuery(&$q, array &$markers = [], $dateStart = null, $dateEnd = null, $dateCol = null)
    {
        if (null === $dateCol) {
            $dateCol = 'date';
        }

        $queryHasWhere = QuickPdoStmtTool::hasWhere($q);


        if (null !== $dateStart && null !== $dateEnd) {
            if (false === $queryHasWhere) {
                $q .= " where ";
            } else {
                $q .= " and ";
            }

            $q .= "($dateCol >= :date_start and $dateCol <= :date_end)";
            $markers["date_start"] = $dateStart;
            $markers["date_end"] = $dateEnd;
        } elseif (null !== $dateStart || null !== $dateEnd) {
            if (false === $queryHasWhere) {
                $q .= " where ";
            } else {
                $q .= " and ";
            }


            if (null !== $dateStart) {
                $q .= "$dateCol >= :date_start";
                $markers["date_start"] = $dateStart;
            } else {

                $q .= "$dateCol <= :date_end";
                $markers["date_end"] = $dateEnd;
            }
        }
    }


    public static function getActiveMethod($method)
    {
        if (array_key_exists($method, self::$quickPdoMethods)) {
            return self::$quickPdoMethods[$method];
        }
        return false;
    }

}