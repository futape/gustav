##Instance functions

###`private void initIsDis()`

Checks whether the source file is disabled and initializes the object's [`$isDis` property](#private-bool-isdis).  
For disabled source files no destination file can be created. Moreover they don't appear in the results of [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).  
Source files can be disabled by prepending a `_` to their filenames.

###`private void initDesc()`

Creates an inline, plaintext description for the source file and and initializes the object's [`$desc` property](#private-string-desc).  
If the [`_desc` GvBlock option](Gustav-core-options#_desc) exist, that description is used. Otherwise the description is built from the source file's content.

###`private void initCategory()`

Gets the category of the source file and initializes the object's [`$category` property](#private-string-category).  
The category is taken from the folder structure starting at the root of the source directory and ending at the directory the source file is locating in.  
The resulting array will contain the uppermost category as its first item, that category's sub-category as its second item and so on.  
If the source file is located in the root of the source directory, the category will be an empty array.  
Before getting the category, the source file's path is resolved using [`realpath()`](http://php.net/manual/en/function.realpath.php) to remove symbolic links, occurences of `./` and `../`, as well as sequences of `/`. If `realpath()` fails, the category is set to an empty array.  
The created array may be used for filling a breadcrumb navigation for example.

###`public mixed __call( string $function_name, array $arguments )`

A *magic* overloading function is called when an object's non-reachable function is called.  
This function is used to emulate global getter functions for some of the object's properties. The following getters are available:

<dl>
    <dt><code>getPath()</code></dt>
    <dd>The path of the source file represented by this object (<a href="#private-string-path"><code>$path</code> property</a>).</dd>
    
    <dt><code>getDesc()</code></dt>
    <dd>The source file's description (<a href="#private-string-desc"><code>$desc</code> property</a>).</dd>
    
    <dt><code>getCategory()</code></dt>
    <dd>The category of the source file (<a href="#private-string-category"><code>$category</code> property</a>).</dd>
    
    <dt><code>isDis()</code></dt>
    <dd>Whether the source file is disabled (<a href="#private-bool-isdis"><code>$isDis</code> property</a>).</dd>
</dl>

If any other non-reachable function is called, a [`BadMethodCallException`](http://php.net/manual/en/class.badmethodcallexception.php) is thrown.

<dl>
    <dt><code>$function_name</code></dt>
    <dd>The name of the called function.</dd>
    
    <dt><code>$arguments</code></dt>
    <dd>The arguments passed to the called function.</dd>
</dl>



##Instance properties

###`private string $path`

The path of the source file represented by this object.

###`private GustavContent $content`

The [`GustavContent`](API#gustavcontent) object representing the source file's content.

###`private GustavBlock $block`

A [`GustavBlock`](API#gustavblock) object representing the source file's GvBlock.

###`private bool $isDis`

Whether the source file is disabled.  
For disabled source files no destination file can be created. Moreover they don't appear in the results of [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--) (by default).  
Source files can be disabled by prepending a `_` to their filenames.
    
###`private string $desc`

The description of the source file.  
This may be either the value of the [`_desc` GvBlock option](Gustav-core-options#_desc), if specified, or the description is generated from the source file's content.
    
###`private string[] $category`

The category of the source file.  
An array that contains the single segments of the source file's category path as its item, the uppermost one as its first item, that category's sub-category as its second item and so on.  
If the source file is located in the root of the source directory, this property will be an empty array.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavSrc`'s corresponding Hooks class.