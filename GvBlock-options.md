Properties of a [GvBlock](GvBlock) are called *GvBlock options* or just *options* for short.  
Options' names are treated case-sensitively.

Some options have a special meaning in Gustav and are processed differently than others. Those options are called [*Gustav core options*](Gustav-core-options). Gustav core options always start with a `_`. Therefore you should make sure that your own, custom options never begin with a `_`, too.

If an option doesn't has any value, it will contain the value `true`. Otherwise its value is treated as a literal string which means that the sequence `\n`, for example, isn't a linefeed character as it would be in a double-quoted string in PHP, rather it would be a string of two characters, a backslash (`\`) and the lowercased letter `n`. Not all options' values are taken literally. Which options are not is shown in [*GvBlock option processing*](GvBlock-option-processing).



##Further reading

+   [GvBlock option templating](GvBlock-option-templating)
+   [GvBlock option default values](GvBlock-option-default-values)
+   [Extending a GvBlock](Extending-a-GvBlock)
+   [Gustav core options](Gustav-core-options)
+   [GvBlock option processing](GvBlock-option-processing)
+   [Finalizing a GvBlock](Finalizing-a-GvBlock)
+   [Required GvBlock options](Required-GvBlock-options)