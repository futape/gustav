Gustav is written in [PHP](http://php.net) and designed for running on an [Apache server](http://httpd.apache.org) powered by any OS.



##PHP requirements

Since Gustav relies, among other, on [OOP 5](http://php.net/manual/en/language.oop5.php), [namespaces](http://php.net/manual/en/language.namespaces.php) and [anonymous functions](http://php.net/manual/en/functions.anonymous.php), Gustav requires **PHP 5.3+**.  
Moreover, you should make sure that PHP's [`open_basedir` configuration option](http://php.net/manual/en/ini.core.php#ini.open-basedir) doesn't exclude important directories like the templates directory, the source directory or the GvDir. Also the [`allow_url_fopen` PHP configuration option](http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen) should be enabled.



##Apache requirements

Gustav uses the [`DirectorySlash` directive](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#directoryslash) which is available as of **Apache 2.0.51**.  
If you remove that directive from the `.htaccess` file, Gustav may also work fine on Apache versions below 2.0.51.  
Other directives used by Gustav are

+   [`DirectoryIndex`](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#directoryindex)

and, if the [`use_fallback_resource` configuration option](Gustav-configuration#bool-use_fallback_resource--false) is set to `true`,

+   [`FallbackResource`](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#fallbackresource)

or otherwise,

+   [`ErrorDocument`](http://httpd.apache.org/docs/2.4/mod/core.html#errordocument),
+   [`Options`](http://httpd.apache.org/docs/2.4/mod/core.html#options),
+   [`<IfModule>`](http://httpd.apache.org/docs/2.4/mod/core.html#ifmodule),
+   [`RewriteEngine`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html#rewriteengine),
+   [`RewriteCond`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html#rewritecond) and
+   [`RewriteRule`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html#rewriterule).

`FallbackResource` is available as of Apache 2.2.16.  
If not setting `use_fallback_resource` to `true`, Apache should be compiled with [`mod_rewrite`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html) (Extension, by default not included) or that module should be loaded dynamically using [`LoadModule`](http://httpd.apache.org/docs/2.4/mod/mod_so.html#loadmodule). If it's not, no errors or something similar will occur, but auto-generating a non-existing destination file for a requested URL similar to `http://example.com/blog/category/article-1/` won't work if that directory already exists since, instead of a `404 Not Found` status, a `403 Forbidden` status is returned (if no directory index is generated) which doesn't trigger `generate.php`. If `mod_rewrite` is available, setting the `FollowSymLinks` option in a `.htaccess` file must be enabled using <code><a href="http://httpd.apache.org/docs/2.4/mod/core.html#allowoverride">AllowOverride</a> Options=FollowSymLinks</code> for example.  
`ErrorDocument` requires `FileInfo` permissons in `.htaccess` files, while `DirectoryIndex` and `DirectorySlash`, as well as `FallbackResource` require `Indexes` permissons set, for example, using <code><a href="http://httpd.apache.org/docs/2.4/mod/core.html#allowoverride">AllowOverride</a> FileInfo Indexes</code>.  
Moreover, [`mod_dir`](http://httpd.apache.org/docs/2.4/mod/mod_dir.html) must be compiled into Apache (Base, by default included) or must be loaded dynamically using [`LoadModule`](http://httpd.apache.org/docs/2.4/mod/mod_so.html#loadmodule) to provide support for `DirectoryIndex`, `DirectorySlash` and `FallbackResource`.



##Tested systems

###Working

+   Apache 2.2.22, PHP 5.3.29