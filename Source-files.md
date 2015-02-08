Source files are the base of [destination files](Destination-files) and they are the main component for building them. They contain the [GvBlock definition](GvBlock-definition) describing the GvBlock for the source file which contains information about the used [templates](Gustav-core-options#_templ) and [converters](Gustav-core-options#_conv), as well as meta information of the source file, and a destination file's main content.

Source files are located in the source directory or in one of its subdirectories. For information on the effect that the exact location of a source file has see [*GvBlock option default values*](GvBlock-option-default-values#_dest_default).  
They should always have a file-extension. The reason for that is described in [*GvBlock option default values*](GvBlock-option-default-values#_conv_default).

A source file named `__base` has a special function that is described in [*GvBlock option default values*](GvBlock-option-default-values#_ext_default). Source files that should not act like that should never be named like that. Generally, filenames starting with `__` are reserved by Gustav and should not be used.  
Like other source files, also a `__base` file's GvBlock must contain all [required options](Required-GvBlock-options). Since other source files may inherit from this file, it may be useful to specify the [`_conv`](Gustav-core-options#_conv) option as `_conv_default`, sothat the extended source file's [system default value](GvBlock-option-default-values#_conv_default) for that options supersedes the inherited value.

Internally Gustav uses UTF-8 and also creates output encoded in UTF-8. Due to that, source files should always be encoded in UTF-8, too.



###Further reading

+   [PHP source files](PHP-source-files)
+   [Disabled source files](Disabled-source-files)
+   [Source content](Source-content)