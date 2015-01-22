##Installing Gustav

Installing Gustav is easy-peasy. Actually it's not even a real step.  
The only thing to do is creating a FTP connection to the server or webspace and copying the downloaded files to the server. Done.  
Gustav is compatible with PHP 5.3+.



##Setting up Gustav

Setting up Gustav is not much more complicated than installing it.  
First, the [`conf.json`](Gustav-configuration) file must be created or adjusted.  
The next (and last) step can be done either automatically by simply calling [`Gustav::setup()`](Public-API%3A-Gustav#bool-setup) or manually by creating the directories specified by the `src_dir`, `dest_dir` and `templs_dir` configuration options and creating a `.htaccess` file in the directory specified by the `dest_dir` option that contains the following content.

    DirectoryIndex index.html index.php
    ErrorDocument 404 <path of GvDir>/generate.php

`<path of GvDir>` replaced with an absolute or relative URL of the directory the downloaded files have been copied to in [*Installing Gustav*](#installing-gustav).  
Now, everything should work properly. Learn how to use Gustav in the [next step](#using-gustav).



##Using Gustav

Using Gustav is a joy since it is *that* easy.

1.  Write.
2.  Upload.
3.  *No third step.*

For more information on source files see [*Source files*](Source-files).



##Exploring Gustav

Theoretically this is everything you need to know for publishing your documents on the web using Gustav.  
However, Gustav has so much more to offer. [Go and explore](Home) the Gustav universe!