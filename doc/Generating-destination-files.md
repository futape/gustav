When [creating destination files](#creating-the-destination-file) several other parts like the [destination path](#generating-the-destination-path) and the [destination content](#generating-the-destination-content) must be generated, too.  



##Generating the destination content

The destintion content is created by passing the [source content](Source-content) to a [template file](Template-files) which takes that content and combines it with its own content. The resulting content is passed to the next template file. The template files to use are retrieved from the [`_templ` GvBlock option](Gustav-core-options#_templ) and are processed from the right to the left. If no templates have been specified, the destination content is the same as the source content.  
For more information see [*GvBlock option processing*](GvBlock-option-processing#array-_templ).



##Generating the destination path

The destination path is built upon the value of the GvBlock's [`_dest` option](Gustav-core-options#_dest).  
If the value ends with a directory separator, `index.php` for PHP destination file or `index.html` for static destination files is appended to the path.  
Since the path of the document root is prepended to the path, the resulting path is always relative to the server root.  
For more information see [*GvBlock option processing*](GvBlock-option-processing#string-_dest) and [*GvBlock option default values*](GvBlock-option-default-values#_dest_default).



##Creating the destination file

Upon the [destination content](#generating-the-destination-content) the [destination file](Destination-files) is built and is located at the location the [destination path](#generating-the-destination-path) points on.  
This is true as long as the destination file is a static destination file. If it is a PHP destination file, a PHP code generating the [destination content](#generating-the-destination-content) dynamically when requested using the [`GustavGenerator` class](API#gustavgenerator) is used instead as the destination file's content.  
Generating destination files for [disabled source files](Disabled-source-files) is not possible.  
For more information see [`GustavDest::createFile()`](Public-API%3a-GustavDest#bool-createfile).