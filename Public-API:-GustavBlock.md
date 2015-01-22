##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a `RuntimeException` is thrown. If the file's content can't be read, a `RuntimeException` is thrown, too.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the source file to build the GvBlock from. Gets passed to <code>GustavBase::path()</code>.</dd>
</dl>

###`string getPath()`

Returns the path of the source file the GvBlock has been extracted from.

###`string getContent()`

Returns the content of the source file with the GvBlock definition stripped away.

###`array|mixed|null get( [ string|null $option = null ] )`

Returns either a single option of the GvBlock or the whole GvBlock as an array.

<dl>
    <dt><code>$option</code></dt>
    <dd>The name of the GvBlock's option whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the whole GvBlock is returned as an array.</dd>
</dl>

The whole GvBlock or just a single option's value.