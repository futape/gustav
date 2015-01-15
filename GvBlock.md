##What's that?

*GvBlock* is short for *Gustav Block*. It's a section of a source file defining meta information of the source file, as well as processing information like the used converters and templates.



##Marking up a GvBlock

A GvBlock definition is very strict.  
Generally when talking about *newline character* in the context of a GvBlock definition, the newline character can be `\\n`, `\\r` or even `\\r\\n`.


###Opening a GvBlock

The opening sequence must be located at the beginning of the source file. The only thing that may precede it is `<?php` or `<?` followed by a newline character and `/*`. This may be necessary for PHP source files since the GvBlock definition should be ignored when executing the file as a PHP script.  
The opening sequence looks as shown below.

    -----BEGIN GV BLOCK-----

The only thing that is allowed to precede it on the same line is the opening multiline comment (`/*`). The opening sequence is ended by a newline character.

###Closing a GvBlock

The closing sequence looks very similar to the opening sequence.

    -----END GV BLOCK-----

Like the opening sequence it has to be a standalone line and, if not the last content of the file, is ended by a newline character. The only thing that may precede the end of the line if a closing multiline comment (`*/`).


###The GvBlock body

The body of a GvBlock contains the meta and processor information of the source file. One line defines a single property. Empty lines or lines containing whitespaces only are ignored.  
A property's name is separated from the property's value by a `:`. Whitespaces before and after the name and the value are removed. If a line contains multiple `:`s, only the first one has a special meaning. Properties with out a value (i.e. lines without a `:`) are supported, too. Such properties are called * boolean options*. For information on how those properties are processed, see below. The lines are separated by a newline character.  
The most properties' values are taken literally, but there are some exceptions. For more information on *GvBlock option processing* and *GvBlock templating* see below.  
Properties with invalid names are ignored. Those are:

+   `!`
+   Names containing consecutive occurrences of `_default` only.
+   An empty string



##GvBlock options

Properties of a GvBlock are called *GvBlock options* or just *options* for short.  
Some options have a special meaning in Gustav and are processed differently than others. Those options are called *Gustav core options*. Gustav core options always start with a `_`. Therefore you should make sure that your own, custom options never begin with a `_`, too.

If an options doesn't has any value, it will contain the value `true`. Otherwise its value is treated as a literal string which means that the sequence `\\n`, for example, isn't a newline character as it would be in a double-quoted string in PHP, rather it would be a string of two characters, a backslash (`\\`) and the lowercased letter "N" (`n`). Not all options' values are taken literally. Which options are not is show below under *GvBlock option processing*.


###Gustav core options

+   _templ - Defines the templates to be used when generating the destination file.
+   _conv - Defines the text converters to apply to the source file's content.
+   _dyn - A boolean option. If set, the destination file for that source file will be a dynamic PHP file generating the destination content when requested.
+   ...


###GvBlock option processing

Some Gustav core options require a specific format or content to be converted into another datatype than a string or to be processed in another way. Besides processing their values, these options can also be validated.  
Below are shown the options whose values are processed or validated.

####`array _templ = ""`

If an empty string is specified, the processed value will be an empty array.  
If not an empty string, the string is expected to be similar to the one below.

    html5.site_2015.blog

The templates are separated using `.`s. Whitespaces before and after a template's name are removed and empty template names or those consisting of whitespaces only are removed from the list.  
After removing the empty items at least one item has to be remained.  
All template names that are still in the list must have a corresponding template file.

####`array _conv = <file-extension of the source file>`

...