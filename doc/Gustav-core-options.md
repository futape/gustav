When describing the individual options, always the [processed value](GvBlock-option-processing) is implied.



##Valued options

*Valued options* must have a value (string). In a [GvBlock definition](GvBlock-definition#the-gvblock-body) the option's name is always separated from the option's value by a `:`.

###`_templ`

Defines the templates to be used when [generating the destination file](Generating-destination-files). The templates are applied from right to left.

###`_conv`

Defines the [text converters](Converting-source-content) to apply to the [source file](Source-files)'s content. The converters are applied from left to right.

###`_tags`

Defines the tags the source file is associated with.

###`_dest`

Defines the path the destination file [should be located](Generating-destination-files#generating-the-destination-path). If ending with a directory separator, the destination file will be named `index.html` or `index.php` and will be located in the specified directory. The path is relative to the document root.

###`_ext`

Defines the [source file](Source-files) to extend. The source file will [inherit GvBlock options](Extending-a-GvBlock) (if not prevented) and may [inherit the content](Extending-source-content) of the specified source file. The path is relative to the document root.

###`_pub`

Defines the unix timestamp of the publication date and time of the document. For example, this option is used by [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`_title`

Defines the title of the document.

###`_desc`

Defines a (short) description of the document. For example, this option is used by [`GustavSrc::initDesc()`](Dev-API%3a-GustavSrc#private-void-initdesc).



##Boolean options

Unlike [valued options](#valued-options), boolean options doesn't have to have a value. They may have one, but they can also be [defined](GvBlock-definition#the-gvblock-body) without separating the option's name from the option's value using a `:`. If no value have been specified, `true` is used instead. Boolean options' value aren't [processed or validated](GvBlock-option-processing).  
Since a boolean option's value may be a string or `true`, you should check its value against `null`. For example:

    if(!is_null($gvblock->get("_dyn"))){
        //...
    }

###`_dyn`

If set, the [destination file](Destination-files) for the source file will be a dynamic PHP file [generating the destination content](Generating-destination-files#generating-the-destination-content) when requested.

###`_hidden`

If set, the [source file](Source-files) doesn't appear in results of [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--) when using the default filter.  
Also it won't be taken into account in [`Gustav::getTags()`](Public-API%3a-Gustav#int-gettags) and [`Gustav::getCategories()`](Public-API%3a-Gustav#array-getcategories).



##Compound options

*Compound options* are similar to [boolean options](#boolean-options). They accept values but they don't require one. Unlike boolean options, they may be [processed or validated](GvBlock-option-processing).

###`_ext_content`

Defines the string separating the content of the extended source file and the extending source file when [concatenating those](Extending-source-content).