Source files are the base of destination files and they are the main component for building them. They contain the GvBlock definition describing the GvBlock for the source file which contains information about the used templates and converters, as well as meta information of the source file, and a destination file's main content.

Source files are located in the source directory or in one of its subdirectories. For information on the effect that the exact location of a source file has see *\_dest\*\_default* under *System default values* in *GvBlock option default values*.  
They should always have a file-extension. The reason for that is described in *\_conv\_default* under *System default values* in *GvBlock option default values*.

A source file named `__base` has a special function that is described in *\_ext\*\default* under *System default values* in *GvBlock option default values*. Source files that should not act like that should never be named like that. Generally, filenames starting with `__` are reserved by Gustav and should not be used.  

Internally Gustav uses UTF-8 and also creates output encoded in UTF-8. Due to that, source files should always be encoded in UTF-8, too.



###Further reading

+   PHP source files
+   Disabled source files
+   Source content