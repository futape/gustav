##Instance functions

###`private void initBlock()`

Creates the GvBlock array and initializes the object's [`$block` property](#private-array-block).

###`public mixed __call( string $function_name, array $arguments )`

A *magic* overloading function is called when an object's non-reachable function is called.  
This function is used to emulate global getter functions for some of the object's properties. The following getters are available:

<dl>
    <dt><code>getPath()</code></dt>
    <dd>The path of the source file the GvBlock has been extracted from (<a href="#private-string-path"><code>$path</code> property</a>).</dd>
    
    <dt><code>getContent()</code></dt>
    <dd>The content of the source file with the GvBlock definition stripped away (<a href="#private-string-content"><code>$content</code> property</a>).</dd>
</dl>

If any other non-reachable function is called, a [`BadMethodCallException`](http://php.net/manual/en/class.badmethodcallexception.php) is thrown.

<dl>
    <dt><code>$function_name</code></dt>
    <dd>The name of the called function.</dd>
    
    <dt><code>$arguments</code></dt>
    <dd>The arguments passed to the called function.</dd>
</dl>



##Instance properties

###`private string $path`

The path of the source file the GvBlock has been extracted from.

###`private string $content`

The source file's content with the GvBlock definition stripped away.

###`private array $block`

The GvBlock.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavBlock`'s corresponding Hooks class.