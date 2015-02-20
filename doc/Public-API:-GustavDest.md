##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If no [`GustavSrc`](API#gustavsrc) object can be created for the passed path, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> to create the <a href="Destination-files">destination file</a> for. Gets passed to <a href="Public-API%3a-GustavSrc#void-__construct-stringstring-path-"><code>GustavSrc::__construct()</code></a> which in turn calls <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a> on the path.</dd>
</dl>

###`string getPath()`

Returns the [*real path*](Generating-destination-files#generating-the-destination-path) of the [destination file](Destination-files) represented by this object.  
*Real path* means that, unlike a [`_dest` GvBlock option](Gustav-core-options#_dest) (in some cases), the returned path is the [destination file](Destination-files)'s full path and not only the most necessary part of the path, for example the dirname if the destination file's filename would be `index.*`. Moreover, also unlike a [`_dest` option](Gustav-core-options#_dest), the returned path isn't relative to the document root, rather it's an absolute path, relative to the server root.

###`string getContent()`

Returns the [destination content](Generating-destination-files#generating-the-destination-content).

###`GustavSrc getSrc()`

Returns the [`GustavSrc`](API#gustavsrc) object for the used [source file](Source-files).  

###`string getPhp()`

Get a PHP destination file's content.

Returns the PHP code for including [`GustavGenerator.php`](API#gustavgenerator), generating the final [destination content](Generating-destination-files#generating-the-destination-content) dynamically, printing it and setting the `Content-Type` HTTP header field.  
The returned value may be written to a PHP destination file.

Returns the PHP destination file's PHP code.

###`bool createFile()`

[Creates](Generating-destination-files#creating-the-destination-file) the [destination file](Destination-files).

Moreover this function creates the directories to place the built file in if they don't exist.  
If the [source file](Source-files) is [disabled](Disabled-source-files), no [destination file](Destination-files) is created and `false` is returned.  
If a destination file `index.php` or `index.html` is created, other `index.php` and `index.html` files located in the same directory are removed.

The content of a PHP destination file, created from a [source file](Source-files) whose [`_dyn` GvBlock option](Gustav-core-options#_dyn) is set, contains the hardcoded absolute path of the [`GustavGenerator.php` file](API#gustavgenerator) that [creates](Generating-destination-files#creating-the-destination-file) the [destination file](Destination-files).  
If that file has been moved to another directory and the [destination file](Destination-files) is requested, an error will occur.  
All other paths within the [destination file](Destination-files)'s content are only partly hardcoded. For example the final path of the used [source file](Source-files) is calculated when the [destination file](Destination-files) is requested. This gives you the opportunity to change the [`src_dir` configuration option](Gustav-configuration#string-src_dir) after the [destination file](Destination-files) has been created without getting any errors due to an unexisting [source file](Source-files). This works fine unless you move the used [source file](Source-files) into another directory since the path of the [source file](Source-files), relative to the source directory, is hardcoded into the [destination file](Destination-files).
     
Returns whether the [destination file](Destination-files) has been created successfully.