##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown. If no [`GustavContent`](API#gustavcontent) object can be created for the passed path, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown, too. The same happens if no [`GustavBlock`](API#gustavblock) object can be created.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> represented by this object. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

###`string getPath()`

Returns the path of the [source file](Source-files) represented by this object.

###`string getDesc()`

Returns the [source file](Source-files)'s plaintext description. The description may contain linebreaks.  
If the [`_desc` GvBlock option](Gustav-core-options#_desc) is set, that option's value is used. Otherwise the description is built from the source file's [content](Source-content).

###`string[] getCategory()`

Returns the category of the [source file](Source-files) to be used for filling a breadcrumb navigation for example.  
The category is taken from the folder structure starting at the root of the source directory and ending at the directory the [source file](Source-files) is locating in. The array contains the uppermost category as its first item, that category's subcategory as its second item and so on. If the [source file](Source-files) is located in the root of the source directory, the category will be an empty array.  
Before getting the category the [source file](Source-files)'s path is resolved using [`realpath()`](http://php.net/manual/en/function.realpath.php) to remove symbolic links, occurences of `./` and `../`, as well as sequences of `/`. If that function fails, the category is set to an empty array.  

###`bool isDis()`

Returns whether the [source file](Source-files) is [disabled](Disabled-source-files).

###`GustavBlock|mixed|null getBlock( [ string|null $option = null ] )`

A global getter function returning the [source file](Source-files)'s [GvBlock](GvBlock) or just one of its [options](GvBlock-options)' values.  
See [`GustavBlock::get()`](Public-API%3a-GustavBlock#arraymixednull-get--stringnull-option--null--) for more information.
 
<dl>
    <dt>$option</dt>
    <dd>The name of the GvBlock's <a href="GvBlock-options">option</a> whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the <a href="API#gustavblock"><code>GustavBlock</code></a> object is returned.</dd>
</dl>

Returns either the whole [GvBlock](GvBlock) or just a single [option](GvBlock-options)'s value.

###`array getMeta()`

Get meta information of the [source file](Source-files).  
The returned array is perfectly suitable as value for the first parameter of [`GustavBase::highlightMatches()`](Public-API%3a-GustavBase#array-highlightmatches-array-plain-string-matches-).

Returns an associative array containing the [source file](Source-files)'s meta information. The returned array contains the following items.

    array(
        "tags"=>array("one tag", "of the SRC file", "another tag"),
        "category"=>array("Technology", "Apple", "iOS"),
        "desc"=>"This is an inline description of the SRC file.",
        "file"=>"dest-file",
        "path"=>"/dest/dest-file/",
        "url"=>"http://example.com/dest/dest-file/",
        "src"=>"/usr/www/users/example/src/dest-file.md",
        "title"=>"The SRC file's title",
        "pub"=>1383130658,
        "pub_rss"=>"Wed, 30 Oct 2013 11:57:38 +0100"
    )

<dl>
    <dt><code>string[] tags</code> (<a href="Public-API%3a-GustavBase#string-key_tags"><code>GustavBase::KEY_TAGS</code></a>)</dt>
    <dd>Result of <a href="#gustavblockmixednull-getblock--stringnull-option--null--"><code>GustavSrc::getBlock("_tags")</code></a></dd>.

    <dt><code>string[] category</code></dt>
    <dd>Result of <a href="#string-getcategory"><code>GustavSrc::getCategory()</code></a>.</dd>
    
    <dt><code>string desc</code></dt>
    <dd>Result of <a href="#string-getdesc"><code>GustavSrc::getDesc()</code></a>.</dd>

    <dt><code>string file</code> (<a href="Public-API%3a-GustavBase#string-key_file"><code>GustavBase::KEY_FILE</code></a>):</dt>
    <dd>Last path segment retrieved from the <a href="Gustav-core-options#_dest"><code>_dest</code> GvBlock option</a>.</dd>

    <dt><code>string path</code></dt>
    <dd>The <a href="Gustav-core-options#_dest"><code>_dest</code> GvBlock option</a> converted to a relative URL (root-relative).</dd>

    <dt><code>string url</code></dt>
    <dd>The <a href="Gustav-core-options#_dest"><code>_dest</code> GvBlock option</a> converted to an absolute URL.</dd>

    <dt><code>string src</code></dt>
    <dd>The path of the <a href="Source-files">source file</a>, relative to the server root.</dd>

    <dt><code>string title</code> (<a href="Public-API%3a-GustavBase#string-key_title"><code>GustavBase::KEY_TITLE</code></a>)</dt>
    <dd>The <a href="Gustav-core-options#_title"><code>_title</code> GvBlock option</a>'s value. This item is only available if that option is set.</dd>

    <dt><code>int pub</code></dt>
    <dd>The <a href="Gustav-core-options#_pub"><code>_pub</code> GvBlock option</a>'s value. This item is only available if that option is set.</dd>

    <dt><code>string pub_rss</code></dt>
    <dd>The <a href="Gustav-core-options#_pub"><code>_pub</code> GvBlock option</a>'s value, formatted using <a href="http://php.net/manual/en/class.datetime.php#datetime.constants.rss"><code>DATE_RSS</code></a>. This item is only available if that option is set.</dd>
</dl>

###`int getMatchScore( string $member, string[] $search_items [, mixed &$matches ] )`

Calculates how well a [source file](Source-files) matches a set of search items.  
Compares the items against a [source file](Source-files)'s filename ([`GustavBase::KEY_FILE`](Public-API%3a-GustavBase#string-key_file)), [title](Gustav-core-options#_title) (if defined, [`GustavBase::KEY_TITLE`](Public-API%3a-GustavBase#string-key_title)) or [tags](Gustav-core-options#_tags) ([`GustavBase::KEY_TAGS`](Public-API%3a-GustavBase#string-key_tags)).

When comparing the filename, this function compares case-sensitively. If the entire filename (incl. file-extension) is found in the items, the score is 2, if only the filename with the file-extension stripped away is found, the score is 1.  
The filename is matched against the last path segment of the [source file](Source-files)'s [`_dest` GvBlock option](Gustav-core-options#_dest).

When comparing the [source file](Source-files)'s [title](Gustav-core-options#_title), the items are searched in the title case-insensitively and wrapped into word-boundaries.
*Word-boundaries* are not simple `\b` [RegEx escape sequences](http://php.net/manual/en/regexp.reference.escape.php). The following cases are considered to be word-bounderies.

+   `\b` [RegEx escape sequence](http://php.net/manual/en/regexp.reference.escape.php), combined with the `u` (UTF-8/localized) [RegEx modifier](http://php.net/manual/en/reference.pcre.pattern.modifiers.php). Matches any non-alphanumeric character.
+   A `_` character.
+   Any digit, if the adjacent character is not a digit.
+   Any non-digit, if the adjacent character is a digit.

If the [source file](Source-files) doesn't have a title, the score is 0. Otherwise the score is increased by 1 for each item for each occurrence within the title.  
When searching for literal items in the title, this function acts a bit differently:  
Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitively) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score.  
The single items are extracted by passing the literal to [`GustavBase::getSearchTermItems()`](Private-API%3a-GustavBase#string-getsearchtermitems-string-search_term_part-).

When comparing the [source file](Source-files)'s [tags](Gustav-core-options#_tags), the score is increased by 1 for each item found in the [source file](Source-files)'s tags (case-insensitively).

When getting the score for the title or tags, the items are made unique case-insensitively before comparing them with the [source file](Source-files)'s properties.

<dl>
    <dt><code>$member</code></dt>
    <dd>Defines for which property the match score should be calculated. Use one of the <code>GustavBase::KEY_*</code> constants as this parameter's value.</dd>
    
    <dt><code>$search_items</code></dt>
    <dd>The items to match the <a href="Source-files">source file</a>'s properties against. Use an array returned by <a href="Public-API%3a-GustavBase#string-processsearchterm-string-search_term-"><code>GustavBase::processSearchTerm()</code></a> as this parameter's value.</dd>
    
    <dt><code>&amp;$matches</code></dt>
    <dd>A variable passed to this parameter will contain an array (<code>string[]</code>) containing the found items. When comparing tags or titles, the array's values will be lowercased.</dd>
</dl>

Returns the calculated match score.