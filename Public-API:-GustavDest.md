##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If no `GustavSrc` object can be created for the passed path, a `RuntimeException` is thrown.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the source file to create the destination file for. Gets passed to <code>GustavSrc::__construct()</code> which in turn calls <code>GustavBase::path()</code> on the path.</dd>
</dl>

###`string getPath()`

Returns the *real path* of the destination file represented by this object.  
*Real path* means that, unlike a `_dest` GvBlock option (in some cases), the returned path is the destination file's full path and not only the most necessary part of the path, for example the dirname if the destination file's filename would be `index.*`. Moreover, also unlike a `_dest` option, the returned path isn't relative to the document root, rather it's an absolute path, relative to the server root.

###`string getContent()`

Returns the destination content. For more information on *destination content* see *Generating the destination content* in *Generating destination files*.

###`GustavSrc getSrc()`

Returns the `GustavSrc` object for the used source file.  

###`string getPhp()`

Get a PHP destination file's content.

Returns the PHP code for including `GustavGenerator.php`, generating the final destination content dynamically, printing it and setting the `Content-Type` HTTP header field.  
The returned value may be written to a PHP destination file.

Returns the PHP destination file's PHP code.

###`bool createFile()`

Creates the destination file.

Moreover this function creates the directories to place the built file in if they don't exist.  
If the source file is disabled, no destination file is created and `false` is returned.

The content of a PHP destination file, created from a source file whose `_dyn` GvBlock option is set, contains the hardcoded absolute path of the `GustavGenerator.php` file that creates the destination file.  
If that file has been moved to another directory and the destination file is requested, an error will occur.  
All other paths within the destination file's content are only partly hardcoded. For example the final path of the used source file is calculated when the destination file is requested. This gives you the opportunity to change the `src_dir` configuration option after the destination file has been created without getting any errors due to an unexisting source file. This works fine unless you move the used source file into another directory since the path of the source file, relative to the source directory, is hardcoded into the destination file.
     
Returns whether the destination file has been created successfully.