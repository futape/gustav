The converters to apply to the content are retrieved from the final value of the [`_conv` GvBlock option](Gustav-core-options#_conv). The specified converters are applied from the left to the right.  
A converter may either be a [hardcoded converter](#hardcoded-converters) or a [user-defined one](User-defined-converters).  
A few hardcoded converters [are availabl](#hardcoded-converters). The list of user-defined converters can easily be extended. For more information on that topic see [*User-defined converters*](User-defined-converters).



##Hardcoded converters

Hardcoded converters' names will never be empty and will never contain leading or trailing spaces, nor will they contain any dots.  
A list (string) of all available hardcoded converters' names, separated by dots can be retrieved via the [`Gustav::CONVS` constant](Private-API%3a-Gustav#string-convs).

###The HTML converter: `html|htm`

The hardcoded HTML converter actually doesn't do anything. It's kind of a placeholder or can be used in the [`_conv` option](Gustav-core-options#_conv) to prevent Gustav from using the converter returned by another converter. A list (string) of the available names, separated by dots can be retrieved via the [`Gustav::CONV_HTML` constant](Private-API%3a-Gustav#string-conv_html).

###The plain text converter: `txt|text|plain`

The hardcoded plain text converter [encodes special HTML characters](Private-API%3a-GustavBase#string-escapehtml-string-string-) like `<`, `>`, `&`, `"` and `'` to HTML entities. A list (string) of the available names, separated by dots can be retrieved via the [`Gustav::CONV_TEXT` constant](Private-API%3a-Gustav#string-conv_text).