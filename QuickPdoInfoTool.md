QuickPdoInfoTool
=================
2015-12-28





What is it?
-------------------


It's a companion for the [QuickPdo](https://github.com/lingtalfi/QuickPdo) tool.
 




What are the new methods?
------------------------

- [getColumnNames](https://github.com/lingtalfi/QuickPdo/blob/master/QuickPdoInfoTool.md#getcolumnnames)
- [getDatabase](https://github.com/lingtalfi/QuickPdo/blob/master/QuickPdoInfoTool.md#getdatabase)
- [getDriver](https://github.com/lingtalfi/QuickPdo/blob/master/QuickPdoInfoTool.md#getdriver)
- [getTables](https://github.com/lingtalfi/QuickPdo/blob/master/QuickPdoInfoTool.md#gettables)

 
 
 
How to use
---------------




This is an extension of QuickPdo, so be sure to read
the [QuickPdo documentation](https://github.com/lingtalfi/QuickPdo),
then you can use the methods in the next section.
Here is a quick overview example:

```php
<?php


use QuickPdo\QuickPdo;
use QuickPdo\QuickPdoInfoTool;


require_once "bigbang.php";


define("MYSQL_DBNAME", "the_database");
define("PDOCONF_DSN", "mysql:dbname=" . MYSQL_DBNAME . ";host=127.0.0.1");
define("PDOCONF_USER", "root");
define("PDOCONF_PASS", "");


QuickPdo::setConnection(
    PDOCONF_DSN,
    PDOCONF_USER,
    PDOCONF_PASS,
    array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
        PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    )
);


a(QuickPdoInfoTool::getDriver()); // mysql
a(QuickPdoInfoTool::getDatabase()); // the_database
a(QuickPdoInfoTool::getColumnNames('users')); // [id, email, pass, rib, active]
```


Note:
    since QuickPdoInfoTool uses QuickPdo under the hood,
    it also shares its [error handling system](https://github.com/lingtalfi/QuickPdo/blob/master/README.md#how-to-use).


Methods
===========


getColumnNames
-------------
2015-12-28


```php
array|false    getColumnNames ( str:table, str:schema=null )
```

Return the column names of a given table.
Return false in case of failure.


getDatabase
-------------
2015-12-28


```php
string    getDatabase ( )
```

Return the database name from existing connection


getDriver
-------------
2015-12-28


```php
string    getDriver ( )
```

Return the driver (mysql for instance) from the existing connection





getTables
-------------
2016-01-26


```php
array    getTables ( str:database )
```

Return the array of tables for the given database


