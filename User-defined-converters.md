Users have the ability to create custom converters called *User-defined converters* that can be [used](Gustav-core-options#_conv) in [source files](Source-files). Such converters exist in form of a PHP file, a *converter file*, located in a [`ext` directory](Extending-Gustav)'s subdirectory named `conv`.



##The nature of converter files

Converter files must match a few conditions described below.

+   The file-extension must be `php` (lowercased, not important for windows systems).
+   Except for windows systems, unlike hardcoded converters, user-defined converters are treated case-sensitively.
+   A converter file's filename (without file-extension) must not have leading or trailing spaces.
+   The filename (without file-extension) must not be empty.
+   Besides the dot separating the file-extension from the filename, no `.`s can be used in a converter file's name.
+   Converter files must return the name of a converter to be applied to the converter's output.
+   The filename (without file-extension) must not be one of the [hardcoded converters](Converting-source-content#hardcoded-converters)' names (case-insensitive).
+   Converter files should never stop the script execution by calling [`exit`](http://php.net/manual/en/function.exit.php) or by producing an error for example. However, if they do, the printed content is flushed.
+   When defining functions, classes, constants or anything else, keep in mind that the file may be called for multiple times.
+   The converted content that is printed, as well as the returned converter name should be encoded in UTF-8.
+   The [output buffer](http://php.net/manual/en/ref.outcontrol.php) used to read the printed content must not be deactivated. Also any new output buffer that is created must be deactivated again.

A converter file takes the raw, unconverted source content or the output of the previous converter which is available via a variable named `$gv` and prints the converted content. The passed content should always be UTF-8-encoded, but that may vary, even though Gustav requires source files and converter files to be encoded in UTF-8, since these are user-defined files.  
Moreover, as described above, it returns the name of a converter that should be applied to the output. If the output is valid HTML (i.e. no converter should be applied), one of the [hardcoded HTML converter](Converting-source-content#the-html-converter-htmlhtm)'s names can be used as the return value.

Within converter files the [global namespace](http://php.net/manual/en/language.namespaces.global.php) is entered. Other namespaces can be [imported](http://php.net/manual/en/language.namespaces.importing.php) using `use`.  
Usually [`GustavBase`](API#gustavbase) and [`Gustav`](API#gustav), as well as the corresponding [Hooks classes](API#hooks-classes) are already included. Nevertheless, to be sure you may want to include them manually using [`include_once`](http://php.net/manual/en/function.include-once.php) or [`require_once`](http://php.net/manual/en/function.require-once.php).



##*Aliases* for user-defined converters

Gustav doesn't provide an option for defining aliases for user-defined converters. However, there are a few ways to accomplish that.  
One clever solution is to use [`GustavContent`](API#gustavcontent)'s [`convContent()` method](Private-API%3a-GustavContent#string-convcontent-string-content-string-converter--mixed-next_converter--) which is available publically via the `GustavContentHooks` class. To create an alias you have to create a new converter file named like the alias name that should be defined and containing something like the code below. The following code defines an alias for a user-defined converter named `markdown`.

    <?php
    require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "..", "..", "GustavContent.php"));

    use futape\gustav\GustavHooks;

    echo GustavContentHooks::convContent($gv, "markdown", &$nextConv);

    return $nextConv;
    ?>