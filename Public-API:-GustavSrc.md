##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown. If no [`GustavContent`](API#gustavcontent) object can be created for the passed path, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown, too. The same happens if no [`GustavBlock`](API#gustavblock) object can be created.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> represented by this object. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

###`string getPath()`

Returns the path of the [source file](Source-files) represented by this object.

###`string getDesc()`

Returns the [source file](Source-files)'s plaintext description. The description may contain linebreaks.  
If the [`_desc` GvBlock option](Gustav-core-options#_desc) is set, that option's value is used. Otherwise the description is built from the source file's [content](Source-content).

###`string[] getCategory()`

Returns the category of the [source file](Source-files) to be used for filling a breadcrumb navigation for example.  
The category is taken from the folder structure starting at the root of the source directory and ending at the directory the [source file](Source-files) is locating in. The array contains the uppermost category as its first item, that category's subcategory as its second item and so on. If the [source file](Source-files) is located in the root of the source directory, the category will be an empty array.  
Before getting the category the [source file](Source-files)'s path is resolved using [`realpath()`](http://php.net/manual/en/function.realpath.php) to remove symbolic links, occurences of `./` and `../`, as well as sequences of `/`. If that function fails, the category is set to an empty array.  

###`bool isDis()`

Returns whether the [source file](Source-files) is [disabled](Disabled-source-files).

###`GustavBlock|mixed|null getBlock( [ string|null $option = null ] )`

A global getter function returning the [source file](Source-files)'s [GvBlock](GvBlock) or just one of its [options](GvBlock-options)' values.  
See [`GustavBlock::get()`](Public-API%3a-GustavBlock#arraymixednull-get--stringnull-option--null--) for more information.
 
<dl>
    <dt>$option</dt>
    <dd>The name of the GvBlock's <a href="GvBlock-options">option</a> whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the <a href="API#gustavblock"><code>GustavBlock</code></a> object is returned.</dd>
</dl>

Returns either the whole [GvBlock](GvBlock) or just a single [option](GvBlock-options)'s value.