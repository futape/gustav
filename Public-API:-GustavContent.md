##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown. If the file's [GvBlock](GvBlock) can't be processed, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown, too.
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> containing the content. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

###`string getPath()`

Returns the [source file](Source-files)'s path.

###`GustavBlock|mixed|null getBlock( [ string|null $option = null ] )`

A global getter function returning the [source file](Source-files)'s [GvBlock](GvBlock) or just one of its [options](GvBlock-options)' values.  
See [`GustavBlock::get()`](Public-API%3a-GustavBlock#arraymixednull-get--stringnull-option--null--) for more information.
 
<dl>
    <dt>$option</dt>
    <dd>The name of the GvBlock's <a href="GvBlock-options">option</a> whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the <a href="API#gustavblock"><code>GustavBlock</code></a> object is returned.</dd>
</dl>

Returns either the whole [GvBlock](GvBlock) or just a single [option](GvBlock-options)'s value.

###`string get()`

Returns the finalized [source content](Source-content). See [`GustavContent::finalizeContent()`](Private-API%3a-GustavContent#string-finalizecontent-string-content-array-gvblock--bool-convert_content--true--) for more information.