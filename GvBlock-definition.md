A GvBlock definition is very strict.  
It consists of three parts.

+   [The opening sequence](#the-opening-sequence),
+   [the GvBlock body](#the-gvblock-body) and
+   [the closing sequence](#the-closing-sequence).

The [opening sequence](#the-opening-sequence), as well as the [closing sequence](#the-closing-sequence) must take up a whole line. Each of the two has a few exceptions of this rule.  
Between the opening and the closing sequence the [GvBlock body](#the-gvblock-body) is located.



##The opening sequence

The opening sequence must be located at the beginning of a [source file](Source-files)'s content. The only thing that may precede it is `<?php` or `<?` followed by a linebreak and `/*`. This may be necessary for [PHP source files](PHP-source-files) since the GvBlock definition should be ignored when executing the file as a PHP script.  
The opening sequence looks like shown below.

    -----BEGIN GV BLOCK-----

The only thing that is allowed to precede it on the same line is the opening multiline comment (`/*`). The opening sequence is ended by a linebreak.



##The closing sequence

The closing sequence looks very similar to the [opening sequence](#the-opening-sequence).

    -----END GV BLOCK-----

Like the opening sequence it takes up a whole line and, if not the last content of the file, is ended by a linebreak. The only thing that may precede the end of the line is a closing multiline comment (`*/`).



##The GvBlock body

The body of a GvBlock contains the meta and processor information of the [source file](Source-files). One line defines a single property aka. [*option*](GvBlock-options). Empty lines or lines containing whitespaces only are ignored.  
A property's name is separated from the property's value by a `:`. Whitespaces before and after the name and the value are removed. If a line contains multiple `:`s, only the first one has a special meaning. Properties without a value (i.e. lines without a `:`) are allowed, too. Such properties are called *boolean options*. The lines are separated by linebreaks.  
The most properties' values are taken literally, but there are some exceptions. For more information have a look at [*GvBlock option processing*](GvBlock-option-processing) and [*GvBlock templating*](GvBlock-option-templating).  
Properties with invalid names are ignored. Those are:

+   `!`
+   Names containing consecutive occurrences of `_default` only.
+   An empty string



##Example GvBlock

    -----BEGIN GV BLOCK-----
    _title: Hello World
    _pub: 2015-01-16T11:50:00+01:00
    _tags: test, hello world, ipsum
    _desc: Nothing serious, just an ipsum document.
    
    _conv: txt
    _templ: html5.blog
    -----END GV BLOCK-----