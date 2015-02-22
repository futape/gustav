##Instance functions

###`private void initContent()`

Prepares and finalizes the content and initializes the object's [`$content` property](#private-string-content).

###`public mixed __call( string $function_name, array $arguments )`

A *magic* overloading function is called when an object's non-reachable function is called.  
This function is used to emulate global getter functions for some of the object's properties. The following getters are available:

<dl>
    <dt><code>getPath()</code></dt>
    <dd>The path of the source file containing the content (<a href="#private-string-path"><code>$path</code> property</a>).</dd>
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

The path of the source file containing the content.

###`private string $content`

The content.

###`private GustavBlock $block`

The source file's GvBlock.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavContent`'s corresponding Hooks class.
