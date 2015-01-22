The converters to apply to the content are retrieved from the final value of the `_conv` GvBlock option. The specified converters are applied from the left to the right. For more information on that option see *Gustav core options*.  
A converter may either be a hardcoded converter or a user-defined one.  
A few hardcoded converters are available. The list of user-defined converters can easily be extended. For more information on that topic see *User-defined converters*.



##Hardcoded converters

Hardcoded converters' names will never be empty and will never contain leading or trailing spaces or dots.  
A list (string) of all available hardcoded converters' names, separated by dots can be retrieved via the `Gustav::CONVS` constant.

###The HTML converter: `html|htm`

The hardcoded HTML converter actually doesn't do anything. It's kind of a placeholder or can be used in the `_conv` option to prevent Gustav from using the converter returned by another converter. A list (string) of the available names, separated by a space can be retrieved via the `Gustav::CONV_HTML` constant.

###The plain text converter: `txt|text|plain`

The hardcoded plain text converter encodes special HTML characters like `<`, `>`, `&`, `"` and `'` to HTML entities. A list (string) of the available names, separated by a space can be retrieved via the `Gustav::CONV_TEXT` constant.