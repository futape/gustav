Templating is available in all options.  
Template placeholders have the following form.

    "{{" constant "}}"
    "{{$" variable "}}"

`constant` is passed directly to PHP's [`constant()` function](http://php.net/manual/en/function.constant.php).  
`variable` is the name of a variable that is available in the templating context. Expressions like `{{$array["key"]}}` are not supported.

When using namespaced classes or constants, you should always use a [qualified or a fully qualified name](http://php.net/manual/en/language.namespaces.rules.php). An example for a qualified name: `{{myns\MY_CONST}}`; And one for a fully qualified name: `{{\myns\MY_CONST}}`. The qualified name is treated relatively to the [global namespace](http://php.net/manual/en/language.namespaces.global.php). [`constant()`](http://php.net/manual/en/function.constant.php) also supports the use of [class constants](http://php.net/manual/en/language.oop5.constants.php). Thus `{{\futape\gustav\Gustav::ENC}}` is possible, too.

If a variable or constant doesn't exist, the placeholder is kept as it has been defined. Whitespaces wrapping the part between the braces aren't removed.  
Placeholders can be escaped by writing a `\` between the opening braces. The backslash is removed and the placeholder isn't resolved. Backslashes following the first backslash remain.

If not stated differently for an option, as for [`_ext_content`](GvBlock-option-processing#string-_ext_content) for example, an option's value is taken literally. For those options, using <code>{{<a href="http://php.net/manual/en/reserved.constants.php#constant.php-eol">PHP_EOL</a>}}</code> or another constant containing a newline character is the only option to add a linebreak to the value.



##Available variables

The following variables are available in GvBlock option templating.

###`$src_dir`

The path of the source directory, relative to the document root and always ending with a directory separator.

###`$dest_dir`

The path of the destination directory, relative to the document root and always ending with a directory separator.

###`$ext`

This variable is only available when [extending](Extending-a-GvBlock) another source file's GvBlock. The placeholder is replaced with the value of the same option of the extended source file or, if not specified or not a string value, with an empty string. This variable is not available in the [`_ext` option](Gustav-core-options#_ext). This variable is resolved **before** templating or [processing](GvBlock-option-processing) the options of the extended GvBlock.