QuickPdoHelper
=================
2018-01-30




What is it?
-------------------
A utility to help with various QuickPdo related tasks.
 

 

addDateRangeToQuery
----------------
2018-02-28



```php
void        public static function addDateRangeToQuery( str:&q, array:&markers = [], str:dateStart = null, str:dateEnd = null, str:dateCol = null)
```

Decorate the given query and markers to include the date range defined by dateStart and dateEnd.            

 

getActiveMethod
----------------
2018-01-30



```php
false|str:activeMethod     public static function getActiveMethod ( str:method )
```

Return the active method corresponding to the given method.
An active method, in this context, is a method that alters the database state.

With: 
    - method: a pdo method, one of:
            - update
            - replace
            - insert
            - delete
            - count
            - fetchAll
            - fetch
            - freeExec
            - ...
    - activeMethod: one of:
            - create (encompasses insert and replace)            
            - update            
            - delete            
            
            

