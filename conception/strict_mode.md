Strict mode
==============
2016-02-12




Since 1.16.0, we add the strict mode feature to the QuickPdo class.
 
Why?
---------
 
 
With pdo, you have three ways to handle errors: 
 
- exception 
- warning (php error)
- silent (return false) 


That's cool, but now from the developer's point of view, one cannot make an assumption about the chosen mode,
so the code generally tends to be quite verbose, for instance to fetch rows, I usually do this:


```php
if(false !== ($rows=QuickPdo::fetchAll("table"))){
    // okay ...
}
else{
    throw new \Exception("Could not fetch the rows");    
}
```


Actually, the else part might vary, depending on the application requirements, but basically, there is often
an else.

The idea of strictMode is to throw an exception no matter what, so that we can do a one liner like this:


```php
$rows = QuickPdo::fetchAll("table");
// okay
```


Note, from by experience, QuickPdo::fetchAll never returned false without triggering a pdo error (or an exception 
depending on the mode), so the one liner seems usable.
 
By default though, the strict mode is not enabled: to ensure backward compatibility, and because that's not 
necessarily the best strategy (although it's my personal favourite in some cases).

BUT,
beware that if you do so, it's risky: you have to know exactly what your code is doing, especially where the 
processing of the php code is going.

By that I mean that you have to assume that nested code could possibly set your strict mode flag to false,
or nasty things like that.

So, therefore, I tend to use it only for local projects, or in production if I know all the code.
The worst thing that might happen is that you assert that the following line will throw an exception
if it fails:

```php
$rows = QuickPdo::fetchAll("table");
```

But it doesn't. So your code below this line assumes that $rows is an array, but you have a false boolean.
Generally, it's not dramatic, because php stops you at some point, but you never know.

So, just be aware of what you are doing...



