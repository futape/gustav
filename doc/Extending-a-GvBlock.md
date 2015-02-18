Gustav provides the ability to extend a [source file](Source-files). When doing so, the source file's [content](Extending-source-content), as well as its [GvBlock](GvBlock) is extended.



##Inheriting options

Generally [options](GvBlock-options) that aren't defined in the extending source file's GvBlock are inherited. The only exceptions of this behavior are the [`_ext` option](Gustav-core-options#_ext) and [`!` options](#-options). Also any option starting with `_ext_` like [`_ext_content`](Gustav-core-options#_ext_content) are not inherited. Unlike for [`_ext`](Gustav-core-options#_ext), the value of the extending source file's GvBlock's [`_ext_content` option](Gustav-core-options#_ext_content) can be inherited by defining it in the extending source file's [GvBlock](GvBlock) as `_ext_content:{{$ext}}`. For more informatio on `$ext` [see below](#the-ext-templating-variable).

The extension happens before the extended source file's [GvBlock](GvBlock) is [finalized](Finalizing-a-GvBlock) and before template placeholders in that GvBlock's options' values are [resolved](GvBlock-option-templating).

Inheriting options can be prevented either by

+   defining the same option in the extending source file's [GvBlock](GvBlock) or by
+   using a [`!` option](#-options).

###`!` options

`!` options are options whose names start with a `!` followed by the name of the option that should not be inherited from the extended source file's GvBlock.  
The option can be a [Gustav core option](Gustav-core-options), a custom option, a [`_default` option](GvBlock-option-default-values) or even a `!` option. The latter one, as well as the [`_ext` option](Gustav-core-options#_ext) and options whose names begin with `_ext_` actually doesn't have any effect since they aren't inherited in any way.  
`!` options are removed when [finalizing](Finalizing-a-GvBlock) the GvBlock.

###The `$ext` templating variable

For information on this [templating variable](GvBlock-option-templating#available-variables) see [*GvBlock option templating*](GvBlock-option-templating#ext).