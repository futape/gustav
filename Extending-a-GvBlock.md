Gustav provides the ability to extend a source file. When doing so, the source file's content, as well as its GvBlock is extended.



##Inheriting options

Generally options not defined in the extending source file's GvBlock are inherited. The only exceptions of this behavior are the `_ext` option and `!` options (see below). Also any option starting with `_ext_` like `_ext_content` are not inherited. Unlike for `_ext`, the content of the extending source file's GvBlock's `_ext_content` option can be inherited by defining it in the extending source file's GvBlock as `_ext_content:{{$ext}}`. For more informatio on `$ext` see below (*The $ext templating variable*).

The extension happens before the extended source file's GvBlock is finalized and before template placeholders in that GvBlock options' values are resolved.

Inheriting options can be prevented either by

+   defining the same option in the extending source file's GvBlock or by
+   using a `!` option.

###`!` options

`!` options are options whose names start with a `!` followed by the name of the option that should not be inherited from the extended source file's GvBlock.  
The option can be a Gustav core option, a custom option, a `_default` option or even a `!` option. The latter one, as well as the `_ext` option and options whose names begin with `_ext_` actually doesn't have any effect since they aren't inherited in any way.  
`!` options are removed when finalizing the GvBlock (see *Finalizing a GvBlock*).

###The `$ext` templating variable

For information on this templating variable see *$ext* under *Available variables* in *GvBlock option templating*.