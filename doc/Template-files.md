As described in [*Destination files*](Destination-files), templates are, besides the [source files](Source-files), the second base a destination file is built on. Its function is, as described under [*Generating destination files*](Generating-destination-files#generating-the-destination-content), to take a content, either the [source content](Source-content) or the one returned by the previous template, and combine it with its own content. By combining multiple templates, a very precisely manageable system can be created.



##The nature of template files

When authoring template files, a few rules have to be taken care of.

+   Besides the dot separating the file-extension from the filename, no `.`s can be used in a template file's name.
+   A template file's name (without file-extenion) must not begin or end with spaces.
+   Except for windows systems, template files are treated case-sensitively.
+   A template file's filename must have a file-extension of `php` (lowercased, not important for windows systems).
+   Such a file must not return anything.
+   The filename (without file-extension) must not be empty.
+   Template files should never stop the script execution by calling [`exit`](http://php.net/manual/en/function.exit.php) or by producing an error for example. However, if it does, the printed content is flushed.
+   Information about the request like [`$_GET`](http://php.net/manual/en/reserved.variables.get.php), [`$_COOKIE`](http://php.net/manual/en/reserved.variables.cookies.php) or [`$_SERVER`](http://php.net/manual/en/reserved.variables.server.php)'s `HTTP_*` items should only be used if the destination file using the template is a dynamic one (i.e. the [`_dyn` GvBlock option](Gustav-core-options#_dyn) is set).
+   When defining functions, classes, constants or anything else, keep in mind that the file may be called for multiple times.
+   The printed content should be encoded in UTF-8.
+   The [output buffer](http://php.net/manual/en/ref.outcontrol.php) used to read the printed content must not be deactivated. Also any new output buffer that is created must be deactivated again.

As described above, a template file takes a content and combines it with its own content. The resulting content is printed.

Within template files the [global namespace](http://php.net/manual/en/language.namespaces.global.php) is entered. Other namespaces can be [imported](http://php.net/manual/en/language.namespaces.importing.php) by using `use`.  
Usually [`GustavBase`](API#gustavbase) and [`Gustav`](API#gustav), as well as the corresponding [Hooks classes](API#hooks-classes) are already included. Nevertheless, to be sure you may want to include them manually using [`include_once`](http://php.net/manual/en/function.include-once.php) or [`require_once`](http://php.net/manual/en/function.require-once.php).



##`$gv`

The `$gv` variable is available in a template file and contains the passed content, as well as other useful information. All strings in the array should be encoded in UTF-8, but that may vary. For example, the [source file](Source-files) and the [converter files](User-defined-converters) that created the passed content, as well as the template files already called are user-defined files and are not forced to follow Gustav's requirements to use a character encoding of UTF-8.  
An example for an array contained in `$gv` is shown below.

    array(
        "dest"=>[GustavDest object],
        "src"=>[GustavSrc object],
        "content"=>"Hello world.",
        "templ"=>array(
            "path"=>"/usr/www/users/example/templates/html5.php",
            "file"=>"html5.php",
            "id"=>"html5",
            "total"=>3,
            "index"=>0
        )
    )

###`dest`

Contains the [`GustavDest`](API#gustavdest) object generating the final [destination content](Generating-destination-files#generating-the-destination-content) and calling the template file.

###`src`

The [`GustavSrc`](API#gustavsrc) object representing the [source file](Source-files) of the [destination file](Destination-files) to be created. Contains, besides others, the [GvBlock](GvBlock). This object is also available via [`$gv["dest"]->getSrc()`](Public-API%3a-GustavDest#gustavsrc-getsrc).

###`content`

A string containing the the [source content](Source-content) or the content returned by the previous template.

###`templ`

An array containing several information on the used template file (the one currently called).  
The path of the template file, relative to the server root, is available via the `path` item (string), while the `file` item (string, [`GustavBase::KEY_FILE`](Public-API%3a-GustavBase#string-key_file)) contains just the filename. Moreover, the `id` item (string) provides the template's ID (i.e. the file's filename without the file-extension). Furthermore, the total number of templates used to create the [destination file](Destination-files) is available via the `total` item (int) and the position of the current template within all of these templates is contained in the `index` item (int). The index starts counting at 0.