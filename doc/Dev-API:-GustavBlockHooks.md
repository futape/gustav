##Static functions

###`mixed __callStatic( string $function_name, array  $arguments )`

A *magic* overloading function that gets called when a class's non-reachable function is called.  
This function is used to make all `protected static` functions of the Gustav class this class inherits from publically available.  
If a non-existing function is called, a [`BadMethodCallException`](http://php.net/manual/en/class.badmethodcallexception.php) is thrown.

<dl>
    <dt><code>$function_name</code></dt>
    <dd>The name of the called function.</dd>
    
    <dt><code>$arguments</code></dt>
    <dd>The arguments passed to the called function.</dd>
</dl>

###`parseBlock()`

This function is defined explicitly just to make [passing variables by reference](http://php.net/manual/en/language.references.pass.php) working.  
For more information on the corresponding function see [`GustavBlock::parseBlock()`](Private-API%3a-GustavBlock#stringtrue-parseblock-string-content-).
