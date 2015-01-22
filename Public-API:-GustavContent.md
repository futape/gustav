##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a `RuntimeException` is thrown. If the file's GvBlock can't be processed, a `RuntimeException` is thrown, too.
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the source file containing the content. Gets passed to <code>GustavBase::path()</code>.</dd>
</dl>

###`string getPath()`

Returns the source file's path.

###`GustavBlock|mixed|null getBlock( [ string|null $option = null ] )`

A global getter function returning the source file's GvBlock or just one of its options' values.  
See `GustavBlock::get()` for more information.
 
<dl>
    <dt>$option</dt>
    <dd>The name of the GvBlock's option whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the <code>GustavBlock</code> object is returned.</dd>
</dl>

Returns either the whole GvBlock or just a single option's value.

###`string get()`

Returns the finalized source content. See *string finalizeContent()* for more information.