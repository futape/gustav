##Installing Gustav

Installing Gustav is easy-peasy. Actually it's not even a real step.  
The only thing to do is creating a FTP connection to the server or webspace and copying the [downloaded files](#) to the server. Done.



##Setting up Gustav

Setting up Gustav is not much more complicated than installing it.  
First, the [`conf.json`](Gustav-configuration#confjson) file must be created or adjusted.  
The next (and last) step can be done either automatically by simply calling [`Gustav::setup()`](Public-API%3A-Gustav#bool-setup) or manually by creating the directories specified by the [`src_dir`](Gustav-configuration#string-src_dir), [`dest_dir`](Gustav-configuration#string-dest_dir) and [`templs_dir`](Gustav-configuration#string-templs_dir) configuration options and creating a `.htaccess` file in the directory specified by the [`dest_dir` option](Gustav-configuration#string-dest_dir) that contains the following content.

    DirectoryIndex index.html index.php
    DirectorySlash On
    
    ErrorDocument 404 <path of GvDir>/generate.php
    
    <IfModule mod_rewrite.c>
        RewriteEngine On
        Options +FollowSymLinks
        
        RewriteCond %{REQUEST_FILENAME} -d
        RewriteCond %{REQUEST_FILENAME} ^((?:[^/]|/(?!$))*)/?$
        RewriteCond %1/index.html !-f
        RewriteCond %1/index.php !-f
        RewriteRule $ - [R=404,L]
    </IfModule>

`<path of GvDir>` replaced with an absolute or relative URL ((root-)relative URLs are recommended since they don't trigger a redirection) of the directory the downloaded files have been copied to in [*Installing Gustav*](#installing-gustav). Alternatively to [`ErrorDocument`](http://httpd.apache.org/docs/2.4/mod/core.html#errordocument) and the <code>&lt;IfModule <a href="http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html">mod_rewrite.c</a>&gt;</code> block you may want to use [`FallbackResource`](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#fallbackresource) instead. For more information see [*Gustav configuration*](Gustav-configuration#bool-use_fallback_resource--false).  
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