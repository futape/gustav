*PHP source files* are source files with a `php` file-extension. The file-extension is checked case-insensitively.  
When using PHP source files it's very important to specify a converter since the file-extension `php` used as system default value for the `_conv` option isn't a valid name for any hardcoded converter and might not be available as a user-defined converter.  
For information on how the source content is read from PHP source files see *Reading source content*.  
How to define a GvBlock in PHP source files is described in *GvBlock definition*.

A few rules have to be followed. They are listet below.

+   PHP source files should never stop the script execution by calling `exit` for example.
+   Information about the request like `$_GET`, `$_COOKIE` or `$_SERVER`'s `HTTP_*` items should only be used if the source file is a dynamic one (i.e. the `_dyn` GvBlock option is set).
+   Such files should never define any functions, classes, constants or anything else.
+   The output buffer used to read the printed content must not be deactivated. Also any new output buffer that is created must be deactivated again.

Within PHP source files the global namespace is entered. Other namespaces can be imported by using `use`.  
Usually `GustavBase` and `Gustav`, as well as the corresponding `Hooks` classes are already included. Nevertheless, to be sure you may want to include them manually using `include_once` or `require_once`.