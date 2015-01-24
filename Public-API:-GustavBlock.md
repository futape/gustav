##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown. If the file's content can't be read, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown, too.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> to build the <a href="GvBlock">GvBlock</a> from. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

###`string getPath()`

Returns the path of the [source file](Source-files) the [GvBlock](GvBlock) has been extracted from.

###`string getContent()`

Returns the content of the [source file](Source-files) with the [GvBlock definition](GvBlock-definition) stripped away.

###`array|mixed|null get( [ string|null $option = null ] )`

Returns either a single [option](GvBlock-options) of the GvBlock or the whole [GvBlock](GvBlock) as an array.

<dl>
    <dt><code>$option</code></dt>
    <dd>The name of the GvBlock's <a href="GvBlock-options">option</a> whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the whole <a href="GvBlock">GvBlock</a> is returned as an array.</dd>
</dl>

Returns the whole [GvBlock](GvBlock) or just a single [option](GvBlock-options)'s value.