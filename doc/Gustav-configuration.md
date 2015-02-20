Gustav provides several [configuration options](#configuration-options), short *options*, to adjust its behavior.



##`conf.json`

Gustav is configured using a file named `conf.json` located in the same directory as the PHP files defining the [Gustav classes](API#gustav-classes).  
That file's content must be encoded in UTF-8 and must be valid [JSON](http://json.org) describing an object containing the [configuration options](#configuration-options)' names as keys and their values as values.



##Configuration options

All available configuration options are listed below, grouped by function and category.

    bool enable_log = true
    ____ __________   ____
     |         |       |
    Expected   |     Default value
    datatype   |
              Configuartion option

How an option's value is processed is described for each option individually. If no default value is specified (i.e. no `=`), the configuration option is required. An option's specified default won't be processed.

###Location options

Options defining URLs or paths of files and directories.

####`string dest_dir`

The directory the [destination files](Destination-files) are located in, if not specified differently. The value describes the path of that directory. If the path doesn't start with a directory separator, one is prepended. The path is considered to be relative to the document root. The path, with the document root prepended, must point on a directory that should be located beneath the document root. The destination directory should not be located underneath the [source directory](#string-src_dir) and should also not be the same one. If not a string, the value is casted as one.

####`string src_dir`

The directory the [source files](Source-files) are located in. The value describes the path of that directory. If the path doesn't start with a directory separator, one is prepended. The path is considered to be relative to the document root. The path, with the document root prepended, must point on a directory.  
The document root can be left by using `..` path segments. However, this is an experimental feature in isn't recommended in any way.  
The source directory should not be located underneath the [destination directory](#string-dest_dir) and should also not be the same one.  
If not a string, the value is casted as one.

####`string templs_dir`

The directory the [template files](Template-files) are located in. The value describes the path of that directory. If the path doesn't start with a directory separator, one is prepended. The path is considered to be relative to the document root. The path, with the document root prepended, must point on a directory.  
The document root can be left by using `..` path segments.  
If not a string, the value is casted as one.

####`string 404_error_doc = "/"`

A relative URL (root-relative) referencing a local HTML document whose content is printed when a Gustav-404-error is thrown.  
If the URL points on a PHP file (i.e. the referenced file's file-extension equals `php`), that file is executed as a PHP script and the printed content is used. The file may even set its own HTTP headers like the response's `Content-Type`. Therefore that script may also print non-HTML content. Within the script superglobals like [`$_SERVER`](http://php.net/manual/en/reserved.variables.server.php) containing, beside other, information aobut the *original* request can be accessed.  
If not a string, the value is casted as one.

####`string site_url = <requested site>`

When Gustav provides or uses absolute URLs, this option's value is used to built them. If the option is specified, it must describe an [absolute URL](https://tools.ietf.org/html/rfc3986#section-4.3). It is casted as a string, if not already one, and trailing `/`s are removed. If it's not defined, the absolute URL is built upon the `Host` HTTP request header field or, if that isn't available, the server's configuration options defining its name and the port. If the request has been made using HTTPS, `https` is used as the URL's protocol, otherwise, `http` is used.

###Log and error options

####`bool enable_log = true`

Whether Gustav should write to the [log file](Log-files). If disabled, nothing is logged. Regardless of this option's value, Gustav-fatal-errors always cause a log-entry. If not a boolean, the value is casted as one.

####`string|int log_file_max_size = -1`

The maximum file size of a [log file](Log-files). If that size is exceeded, the log file is renamed to `gustav.<last modification>.<random string>.log` and a new `gustav.log` file is created. A value lower than 0 means that no size limit is specified. Other integer values are considered to be the limit in bytes. A string value is treated as a [shothand byte value](http://php.net/manual/en/faq.using.php#faq.using.shorthandbytes). If not an integer, the value is casted as a string.  
Unlike the parser used by PHP for shorthand byte values in the `php.ini` file, Gustav's parser accepts spaces between the number and the symbol, as well als spaces wrapping the whole value. These are simply ignored. Negative numbers aren't supported, instead the whole value would be considered to be a symbol and `0` would be used as number. If no number has been specified, `1` is used if a valid symbol has been specified, otherwise `0` is used. The final byte value is calculated as follows: `bytes = number * 1024 ^ symbol-value`. `symbol-value` can be `1` for `k`, `2` for `m` and `3` for `g`. For any other (invalid) symbol, `symbol-value` would be `0`.

####`bool exit_on_error = true`

By default, Gustav calls [`exit`](http://php.net/manual/en/function.exit.php) if a Gustav-error is thrown. If this option is set to `false`, it doesn't. Disabling this behavior is not recommended since the code often assumes that when throwing a Gustav-error the script execution is stopped. If the script is executed on, many (non-Gustav-)errors may be thrown and log messages may be logged for multiple times. Regardless of this option's value, Gustav-fatal-errors always stop the script execution. If not a boolean, the value is casted as one.

####`bool check_status = true`

Defines whether Gustav should check whether everything is properly set up when initializing the [`Gustav` class](API#gustav). The following things are checked.

+   Do all configuration options exist? If not, a log-entry is created. In case of the [`src_dir`](#string-src_dir), [`dest_dir`](#string-dest_dir) and [`templs_dir`](#string-templs_dir) options a Gustav-error is thrown instead.
+   Do the configuration option values' datatypes match the expected ones? If not, a log-entry is created.
+   Are the [`src_dir`](#string-src_dir), [`dest_dir`](#string-dest_dir) and [`templs_dir`](#string-templs_dir) options pointing on a directory? If not, a Gustav-error is thrown.
+   Does a `.htaccess` file exist in the root of the destination directory? If not, a log-entry is created.
+   Does the PHP file handling the [auto-generation of destination files](Automatic-generation-of-destination-files) exist? If not, a log-entry is created.
+   If the [`exit_on_error` configuration option](#bool-exit_on_error--true) is disabled, a log-entry is created.
+   Is PHP's [`open_basedir` configuration option](http://php.net/manual/en/ini.core.php#ini.open-basedir) set to a null value (i.e. all files can be read)? If not, a log-entry is created.
+   Is PHP's [`allow_url_fopen` configuration option](http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen) enabled? If not, a log-entry is created.

Regardless of this option's value, the following checks are always executed.

+   Does the [`conf.json` file](#confjson) exist? If not a Gustav-fatal-error is thrown.
+   Does the [`conf.json` file](#confjson) describe an object? If not a Gustav-fatal-error is thrown.

If not a boolean, the value is casted as one.

###Auto-generation options

For more information on how the configuration options in this category are used see [*Automatic generation of destination files*](Automatic-generation-of-destination-files#choosing-a-matching-source-file).

####`bool generator_search_recursive = false`

If set to `true`, Gustav searches for matching [source files](Source-files) in the source directory and all of its subdirectories. Otherwise, only the directory matching the dirname of the URL path is searched. If not a boolean, the value is casted as one.

####`string[] preferred_convs = []`

Defines the preferred converters used for choosing a [source file](Source-files) to use when [auto-generating a destination file](Automatic-generation-of-destination-files). If not an array, a new array with the specified value as its only item is created and used as value. The array should contain string values. These strings are trimmed. If an item isn't a string, it is casted as one. The string describes a converter name. If the string matches one of a [hardcoded converter](Converting-source-content#hardcoded-converters)'s names (case-insensitively), the string is replaced with an array containing all of that converter's names. Otherwise, it is left as defined, even if no converter with the specified name exists.

###Miscellaneous options

####`string replace_directory_separator = ""`

Defines the character to replace with the OS's directory separator when [processing the GvBlock options](GvBlock-option-processing). This is done for paths defined by the [`_ext`](Gustav-core-options#_ext) and [`_dest`](Gustav-core-options#_dest) options. A non-string value is casted as a string. If set to an empty string, nothing is replaced. The specified string is truncated to one character.

####`bool use_fallback_resource = false`

If set to `true`, Apache's [`FallbackResource` directive](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#fallbackresource) is used instead of [`ErrorDocument`](http://httpd.apache.org/docs/2.4/mod/core.html#errordocument) and [`mod_rewrite`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html) when creating the destination directory's `.htaccess` file using [`Gustav::setup()`](Public-API%3a-Gustav#bool-setup).  
Use that directive carefully since [it tends to be a bit buggy](https://bugs.launchpad.net/ubuntu/+source/apache2/+bug/1168030) and keep in mind that it is available in Apache 2.2.16+ only.  
If not a boolean, the value is casted as one.