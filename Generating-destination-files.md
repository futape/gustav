When creating destination files several other parts like the destination path and the destination content must be generated, too.  
Destination files exist in two forms, the ***static destination file*** and the dynamic ***PHP destination file***. Latter happens to be when the `_dyn` GvBlock option is set.



##Generating the destination content

The destintion content is created by passing the source content to a template file which takes that content and combines it with its own content. The resulting content is passed to the next template file. The template files are retrieved from the `_templ` GvBlock option and are processed from the right to the left. If no templates have been specified, the destination content is the same as the source content.  
For more information see *array \_templ* in *GvBlock option processing* and *Template files*.



##Generating the destination path

The destination path is built upon the value of the GvBlock's `_dest` option.  
If the value ends with a directory separator, `index.php` for PHP destination file or `index.html` for static destination files is appended to the path.  
Since the path of the document root is prepended to the path, the resulting path is always relative to the server root.  
For more information see *string \_dest* in *GvBlock option processing* and *\_dest\*\_default* under *System default values* in *GvBlock option default values*.



##Creating the destination file

Upon the destination content the destination file is built and is located at the location the destination path points on.  
This is true as long as the destination file is a static destination file. If it is a PHP destination file, a PHP code generating the destination content dynamically when requested using the `GustavGenerator` class is used instead as the destination file's content.  
Generating destination files for disabled source files is not 