You can easily specify default values for [options](GvBlock-options) to be used when the specified value is [invalid](GvBlock-option-processing) or not set at all.  
Default values can be specified by appending `_default` to the end of the option's name. For example, `_conv_default` would be the default value for the [`_conv` option](Gustav-core-options#_conv).  
A default value specified using `_conv_default_default` would be less important than the one above, `_conv_default_default_default` would be less important than `_conv_default_default` and so on.  
In case of [`_conv`](Gustav-core-options#_conv) the value is [validated](GvBlock-option-processing#array-_conv) and the (next) default value is used if the value is invalid. For options that are not validated like [boolean Gustav core options](Gustav-core-options#boolean-options) and custom options the default values must be handled manually.  
If the actual option isn't defined, the default values are used instead.

The [final GvBlock](Finalizing-a-GvBlock) doesn't contain any `_default` options for [Gustav core options](Gustav-core-options) while the ones for custom options have been normalized. *Normalized* means that the names of `_default` options are made as short as possible by removing unnecessary occurrences of `_default`. For example, if `foo:1`, `foo_default_default:2` and `foo_default_default_default_default_default:3` were specified, the final GvBlock will contain the options `foo:1`, `foo_default:2` and `foo_default_default:3`.



##System default values

For some [Gustav core options](Gustav-core-options) default values are available without specifying them. Some are considered to be more important than the manually defined default values while others are considered to be less important. However, a system default value is never more important than the actual option's value.  
The available system default values are listed below. `_conv_default`, for example, means that the system default value is more important than manually defined default values, whereas `_templ*_default`, for example, means that it is less important than manually defined default values.  
See [*GvBlock option processing*](GvBlock-option-processing) for more information on how the default values are proessed.

###`_conv_default`

The default value is set to the file-extension of the [source file](Source-files). If the file doesn't has a file-extension, an empty string is used. This may be very handy since converter names often match the file-extensions. In case of [hardcoded converters](Converting-source-content#hardcoded-converters) even the letters' case doesn't matter. For example, for [the hardcoded html converter](Converting-source-content#the-html-converter-htmlhtm) the names `html` and `htm` are available.

###`_templ*_default`

The default value is set to an empty string.

###`_tags*_default`

The default value is set to an empty string.

###`_dest*_default`

The default value is set to the filename (without file-extension) of the [source file](Source-files) with a leading `_` that is not the filename's last character stripped away, followed by a directory separator.

###`_ext*_default`

The default value is set to the path of a file named `__base` located in the same directory as the [source file](Source-files), unless the source file *is* that `__base` file.  
The path of a `__base` file in the parent directory of the source file's directory is added as the value for `_ext*_default_default` which means that it is less important than the one located in the source file's directory.  
For a `__base` file in the next upper directory the same is done as `_ext*_default_default_default`, and so on, up to the root of the source directory.

All of the paths added as a system default value for [`_ext`](Gustav-core-options#_ext) are relative to the source directory.