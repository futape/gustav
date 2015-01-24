Destination files are the generated output of a static-site generator like Gustav. They are build upon the [source contents](Source-content) and [template files](Template-files).  
They exist in two forms, the ***static destination file*** and the dynamic ***PHP destination file***. Latter happens to be when the [`_dyn` GvBlock option](Gustav-core-options#_dyn) is set. Static destination files always have a file-extension of `html`, while PHP destination file have a file-extension of `php`.  
Destination files are located in the destination directory if not specified differently by the [`_dest` GvBlock option](Gustav-core-options#_dest).  



##Further reading

+   [Generating destination files](Generating-destination-files)
+   [Automatic generation of destination files](Automatic-generation-of-destination-files)