As stated in *GvBlock option default values*, `_default` options for Gustav core options are removed from the final GvBlock. Also options whose names are starting with `!` are removed.  
Moreover `_default` options' names are normalized. For more information on that topic see *GvBlock option default values*.  
Options that have an  invalid value are removed or replaced with the next default value for that option. If that value is invalid, too, and no more default values are available, the option is removed.