<?php

namespace QuickPdo;


/**
 * QuickPdoInfoTool
 * @author Lingtalfi
 * 2015-12-28
 *
 * A companion tool for QuickPdo to retrieve basic information on database, tables, columns, ...
 *
 */
class QuickPdoInfoTool
{


    /**
     * Return the name of the auto-incremented field, or false if there is none.
     * 
     * 
     * @return false|string
     */
    public static function getAutoIncrementedField($table, $schema = null)
    {
        if (null !== $schema) {
            $table = $schema . '.' . $table;
        }

        if (false !== ($rows = QuickPdo::fetchAll("show columns from $table where extra='auto_increment'"))) {
            return $rows[0]['Field'];
        }
        return false;
    }


    public static function getColumnNames($table, $schema = null)
    {
        /**
         * http://stackoverflow.com/questions/4165195/mysql-query-to-get-column-names
         */
        if (null === $schema) {
            $schema = self::getDatabase();
        }

        if ('mysql' === self::getDriver()) {
            // faster execution...
            // https://www.percona.com/blog/2011/12/23/solving-information_schema-slowness/
            QuickPdo::freeExec("set global innodb_stats_on_metadata=0;");
        }

        $stmt = "
SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA=:schema
AND TABLE_NAME=:table;
";
        if (false !== $rows = QuickPdo::fetchAll($stmt, [
                'schema' => $schema,
                'table' => $table,
            ])
        ) {
            $ret = [];
            foreach ($rows as $row) {
                $ret[] = $row['COLUMN_NAME'];
            }
            return $ret;
        }
        return false;

    }


    public static function getDatabase()
    {
        // http://stackoverflow.com/questions/9322302/how-to-get-database-name-in-pdo
        return QuickPdo::freeQuery("select database()")->fetchColumn();
    }


    public static function getDriver()
    {
        return QuickPdo::getConnection()->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }


    public static function getTables($db)
    {
        QuickPdo::freeExec("use $db;");
        $query = QuickPdo::getConnection()->query('show tables');
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }
}
