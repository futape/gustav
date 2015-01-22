##Instance functions

###`private void initPath()`

Builds the real destination path for a source file and initializes the object's [`$path` property](#private-string-path).  
*Real destination path* means that, unlike a GvBlock's [`_dest` option](Gustav-core-options#_dest) (in some cases), the returned path is the destination file's full path and not only the most necessary part of the path, for example, the dirname if the destination file's filename would be `index.*`.  
The returned path isn't relative to the document root, rather it's an absolute path, relative to the server root.

###`private void initContent()`

Builds the final content of the destination file and initializes the object's [`$content` property](#private-string-content).  
For more information see [*Generating destination files*](Generating-destination-files), as well as [*Template files*](Template-files).

###`public mixed __call( string $function_name, array $arguments )`

A *magic* overloading function is called when an object's non-reachable function is called.  
This function is used to emulate global getter functions for some of the object's properties. The following getters are available:

<dl>
    <dt><code>getPath()</code></dt>
    <dd>The path of the destination file (<a href="#private-string-path"><code>$path</code> property</a>).</dd>
    
    <dt><code>getSrc()</code></dt>
    <dd>The <code>GustavSrc</code> object representing the used source file (<a href="#private-gustavsrc-src"><code>$src</code> property</a>).</dd>
    
    <dt><code>getContent()</code></dt>
    <dd>The final destination content of the destination file (<a href="#private-string-content"><code>$content</code> property</a>).</dd>
</dl>

If any other non-reachable function is called, a [`BadMethodCallException`](http://php.net/manual/en/class.badmethodcallexception.php) is thrown.

<dl>
    <dt><code>$function_name</code></dt>
    <dd>The name of the called function.</dd>
    
    <dt><code>$arguments</code></dt>
    <dd>The arguments passed to the called function.</dd>
</dl>



##Instance properties

###`private GustavSrc $src`

A [`GustavSrc`](Dev-API%3a-GustavSrc) object representing the source of this destination file.

###`private string $path`

The path of the destination file.

###`private string $content`

The destination file's final destination content.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavDest`'s corresponding Hooks class.