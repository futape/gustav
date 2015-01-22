As described in *Destination files*, templates are, besides the source files, the second base a destination file is built on. Its function is, as described under *Generating the destination content* in *Generating destination files*, to take a content, either the source content or the one returned by the previous template, and combine it with its own content. By combining multiple templates, a very precisely manageable system can be created.



##The nature of template files

When authoring template files, a few rules have to be taken care of.

+   Besides the dot separating the file-extension from the filename, no `.`s can be used in a template file's name.
+   A template file's name (without file-extenion) must not begin or end with spaces.
+   Except for windows systems, template files are treated case-sensitively.
+   A template file's filename must have a file-extension of `php` (lowercased, not important for windows systems).
+   Such a file must not return anything.
+   The filename (without file-extension) must not be empty.
+   Template files should never stop the script execution by calling `exit` for example.
+   Information about the request like `$_GET`, `$_COOKIE` or `$_SERVER`'s `HTTP_*` items should only be used if the source file using the template is a dynamic one (i.e. the `_dyn` GvBlock option is set).
+   Template files should never define any functions, classes, constants or anything else.
+   The printed content should be encoded in UTF-8.
+   The output buffer used to read the printed content must not be deactivated. Also any new output buffer that is created must be deactivated again.

As described above, a template file takes a content and combines it with its own content. The resulting content is printed.

Within template files the global namespace is entered. Other namespaces can be imported by using `use`.  
Usually `GustavBase` and `Gustav`, as well as the corresponding Hooks classes are already included. Nevertheless, to be sure you may want to include them manually using `include_once` or `require_once`.



##`$gv`

The `$gv` variable is available in a template file and contains the passed content, as well as other useful information. All strings in the array should be encoded in UTF-8, but that may vary. For example, the source file, as well as the converter files that created the passed content are user-defined files and are not forced to follow Gustav's requirements to use a character encoding of UTF-8.  
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

Contains the `GustavDest` object generating the final destination content and calling the template file.

###`src`

The `GustavSrc` object representing the source file of the destination file to be created. Contains, besides others, the GvBlock. This object is also available via `$gv["dest"]->getSrc()`.

###`content`

A string containing the the source content or the content returned by the previous template.

###`templ`

An array containing several information on the used template file (the one currently called).  
The path of the template file, relative to the server root, is available via the `path` item (string), while the `file` item (string, `GustavBase::KEY_FILE`) contains just the filename. Moreover, the `id` item (string) provides the template's ID (i.e. the file's filename without the file-extension). Furthermore, the total number of templates used to create the destination file is available via the `total` item (int) and the position of the current template within all of these templates is contained in the `index` item (int). The index starts counting at 0.