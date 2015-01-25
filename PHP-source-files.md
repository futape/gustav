*PHP source files* are [source files](Source-files) with a `php` file-extension. The file-extension is checked case-insensitively.  
When using PHP source files it's very important to [specify a converter](Gustav-core-options#_conv) since the file-extension `php` used as [system default value](GvBlock-option-default-values#_conv_default) for the [`_conv` option](Gustav-core-options#_conv) isn't a valid name for any [hardcoded converter](Converting-source-content#hardcoded-converters) and might not be available as a [user-defined converter](User-defined-converters).  
For information on how the [source content](Source-content) is read from PHP source files see [*Reading source content*](Reading-source-content).  
How to define a [GvBlock](GvBlock) in PHP source files is described in [*GvBlock definition*](GvBlock-definition).

A few rules have to be followed. They are listet below.

+   PHP source files should never stop the script execution by calling [`exit`](http://php.net/manual/en/function.exit.php) for example.
+   Information about the request like [`$_GET`](http://php.net/manual/en/reserved.variables.get.php), [`$_COOKIE`](http://php.net/manual/en/reserved.variables.cookies.php) or [`$_SERVER`](http://php.net/manual/en/reserved.variables.server.php)'s `HTTP_*` items should only be used if the [destination file](Destination-files) is a dynamic one (i.e. the [`_dyn` GvBlock option](Gustav-core-options) is set).
+   When defining functions, classes, constants or anything else, keep in mind that the file may be called for multiple times.
+   The [output buffer](http://php.net/manual/en/ref.outcontrol.php) used to read the printed content must not be deactivated. Also any new output buffer that is created must be deactivated again.

Within PHP source files the [global namespace](http://php.net/manual/en/language.namespaces.global.php) is entered. Other namespaces can be [imported](http://php.net/manual/en/language.namespaces.importing.php) using `use`.  
Usually [`GustavBase`](API#gustavbase) and [`Gustav`](API#gustav), as well as the corresponding [Hooks classes](API#hooks-classes) are already included. Nevertheless, to be sure you may want to include them manually using [`include_once`](http://php.net/manual/en/function.include-once.php) or [`require_once`](http://php.net/manual/en/function.require-once.php).