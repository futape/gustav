The content of another source file can be extended by using the `_ext` GvBlock option.

If the content of the extending source file is empty or constists of whitespaces only, the content of the extended source file is used instead. The inherited content isn't convertered using the converters of the extended source file but it is using the ones of the extending source file.  
This form of extension is called ***transparent extension***.

If the extending source file's content is **not** empty and does not consist of whitespaces only, no content is inherited, unless the `_ext_content` GvBlock option is set. If it is and if the contents of the two source files are both not empty nor do the extending source file's one consist of whitespaces only, the contents are concatenated, separated by the value of that option.  
Before checking the extended source file's content and before concatenating it with the one of the extending source file, it is converted using the converters of the extended source file.  
The content of the extending source file is convertered using its own converters, too. The value of the `_ext_content` option isn't.  
This form of extension is called ***isolated extension***.

If a source file, *src1*, extended by a source file, *src0*, which matches the conditions for a *transparent extension*, also extends another source file, *src2*, that source file's content (*src2*) is not converted using its own converters, unless the two source files, *src1* and *src2*, match the conditions for an *isolated extension*. In the latter case, the content of *src2* is converted using the converters of *src2* while *src1*'s one, as well as the value of *src1*'s `_ext_content` option is not converted at all. Later, both contents of *src1* and *src2*, concatenated, as well as the value used to concatenate them (`_ext_content`) are converted using the converters of *src0* (see information on *transparent extension*). This means that the content of *src2* is converted twice.

As described above, Gustav supports multi-level extension. When this happens, a source file that is extended by a source file that should be extended, is extended first before extending the source file that should actually be extended. The same would apply to a source file that is extended by the source file that is extended by the extended source file, and so on.