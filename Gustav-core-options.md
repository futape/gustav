When describing the individual options, always the processed value is implied.



##Valued options

*Valued options* must have a value (string). In a GvBlock definition the option's name is always separated from the option's value by a `:`.

###`_templ`

Defines the templates to be used when generating the destination file. The templates are applied from right to left.

###`_conv`

Defines the text converters to apply to the source file's content. The converters are applied from left to right.

###`_tags`

Defines the tags the source file is associated with.

###`_dest`

Defines the path the destination file should be located. If ending with a directory separator, the destination file will be named `index.html` or `index.php` and will be located in the specified directory. The path is relative to the document root.

###`_ext`

Defines the source file to extend. The source file will inherit GvBlock options (if not prevented) and may inherit the content of the specified source file. The path is relative to the document root.

###`_pub`

Defines the unix timestamp of the publication date and time of the document. For example, this option is used by `Gustav::query()`.

###`_title`

Defines the title of the document.

###`_desc`

Defines a (short) description of the document. For example, this option is used by `GustavSrc::initDesc()`.



##Boolean options

Unlike valued options, boolean options doesn't have to have a value. They may have one, but they can also be defined without separating the option's name from the option's value using a `:`. If no value have been specified, `true` is used instead. Boolean options' value aren't processed or validated.  
Since a boolean option's value may be a string or `true`, you should check its value against `null`. For example:

    if(!is_null($gvblock->get("_dyn"))){
        //...
    }

###`_dyn`

If set, the destination file for the source file will be a dynamic PHP file generating the destination content when requested.

###`_hidden`

If set, the source file doesn't appear in results of `Gustav::query()` when using the default filter.  
Also it won't be taken into account in `Gustav::getTags()` and `Gustav::getCategories()`.



##Compound options

*Compound options* are similar to boolean options. They accept values but they don't require one. Unlike boolean options, they may be processed or validated.  
For information on the processing of an individual option's value see *GvBlock option processing*.

###`_ext_content`

Defines the string separating the content of the extended source file and the extending source file when concatenating those.