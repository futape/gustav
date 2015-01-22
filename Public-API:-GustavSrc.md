##Instance functions

###`void __construct( string|string[] $path )`

The class's constructor that is called when a new instance is created.  
If the passed path doesn't point on a file, a `RuntimeException` is thrown. If no `GustavContent` object can be created for the passed path, a `RuntimeException` is thrown, too. The same happens if no `GustavBlock` object can be created.  
If everything worked properly, the newly created object is initialized.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the source file represented by this object. Gets passed to <code>GustavBase::path()</code>.</dd>
</dl>

###`string getPath()`

Returns the path of the source file represented by this object.

###`string getDesc()`

Returns the source file's plaintext description. The description may contain linebreaks.  
If the `_desc` GvBlock option is set, that option's value is used. Otherwise the description is built from the source file's content.

###`string[] getCategory()`

Returns the category of the source file to be used for filling a breadcrumb navigation for example.  
The category is taken from the folder structure starting at the root of the source directory and ending at the directory the source file is locating in. The array contains the uppermost category as its first item, that category's subcategory as its second item and so on. If the source file is located in the root of the source directory, the category will be an empty array.  
Before getting the category the source file's path is resolved using `realpath()` to remove symbolic links, occurences of `./` and `../`, as well as sequences of `/`. If that function fails, the category is set to an empty array.  

###`bool isDis()`

Returns whether the source file is disabled. For more information see *Disabled source files*.  

###`GustavBlock|mixed|null getBlock( [ string|null $option = null ] )`

A global getter function returning the source file's GvBlock or just one of its options' values.  
See `GustavBlock::get()` for more information.
 
<dl>
    <dt>$option</dt>
    <dd>The name of the GvBlock's option whose value should be returned. If it doesn't exist, <code>null</code> is returned. If set to <code>null</code>, the <code>GustavBlock</code> object is returned.</dd>
</dl>

Returns either the whole GvBlock or just a single option's value.

###`array getMeta()`

Get meta information of the source file.  
The returned array is perfectly suitable as value for the first parameter of `GustavBase::highlightMatches()`.

Returns an associative array containing the source file's meta information. The returned array contains the following items.

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
    <dt><code>string[] tags</code> (<code>GustavBase::KEY_TAGS</code>)</dt>
    <dd>Result of <code>GustavSrc::getBlock("_tags")</code></dd>.

    <dt><code>string[] category</code></dt>
    <dd>Result of <code>GustavSrc::getCategory()</code>.</dd>
    
    <dt><code>string desc</code></dt>
    <dd>Result of <code>GustavSrc::getDesc()</code>.</dd>

    <dt><code>string file</code> (<code>GustavBase::KEY_FILE</code>):</dt>
    <dd>Last path segment retrieved from the <code>_dest</code> GvBlock option.</dd>

    <dt><code>string path</code></dt>
    <dd>The <code>_dest</code> GvBlock option converted to a relative URL (root-relative).</dd>

    <dt><code>string url</code></dt>
    <dd>The <code>_dest</code> GvBlock option converted to an absolute URL.</dd>

    <dt><code>string src</code></dt>
    <dd>The path of the source file, relative to the server root.</dd>

    <dt><code>string title</code> (<code>GustavBase::KEY_TITLE</code>)</dt>
    <dd>The <code>_title</code> GvBlock option's value. This item is only available if that option is set.</dd>

    <dt><code>int pub</code></dt>
    <dd>The <code>_pub</code> GvBlock option's value. This item is only available if that option is set.</dd>

    <dt><code>string pub_rss</code></dt>
    <dd>The <code>_pub</code> GvBlock option's value, formatted using <code>DATE_RSS</code>. This item is only available if that option is set.</dd>
</dl>

###`int getMatchScore( string $member, string[] $search_items [, mixed &$matches ] )`

Calculates how well a source file matches a set of search items.  
Compares the items against a source file's filename (`GustavBase::KEY_FILE`), title (if defined, `GustavBase::KEY_TITLE`) or tags (`GustavBase::KEY_TAGS`).

When comparing the filename, this function compares case-sensitively. If the entire filename (incl. extension) is found in the items, the score is 2, if only the filename with the extension stripped away is found, the score is 1.  
The filename is matched against the last path segment of the source file's `_dest` GvBlock option.

When comparing the source file's title, the items are searched in the title case-insensitively and wrapped into word-boundaries.
*Word-boundaries* are not simple `\b` RegEx escape sequences. The following cases are considered to be word-bounderies.

+   `\b` RegEx escape sequence, combined with the `u` (UTF-8/localized) RegEx modifier. Matches any non-alphanumeric character.
+   A `_` character.
+   Any digit, if the adjacent character is not a digit.
+   Any non-digit, if the adjacent character is a digit.

If the source file doesn't have a title, the score is 0. Otherwise the score is increased by 1 for each item for each occurrence within the title.  
When searching for literal items in the title, this function acts a bit differently:  
Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitive) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score.  
The single items are extracted by passing the literal to `GustavBase::getSearchTermItems()`.

When comparing the source file's tags, the score is increased by 1 for each item found in the source file's tags (case-insensitively).

When getting the score for the title or tags, the items are made unique case-insensitively before comparing them with the source file's properties.

<dl>
    <dt><code>$member</code></dt>
    <dd>Defines for which property the match score should be calculated. Use one of the <code>GustavBase::KEY_*</code> constants as this parameter's value.</dd>

    Use one of the GustavBase::KEY_* constants as this parameter's value.
    <dt><code>$search_items</code></dt>
    <dd>The items to match the source file's properties against. Use an array returned by <code>GustavBase::processSearchTerm()</code> as this parameter's value.</dd>

    <dt><code>&amp;$matches</code></dt>
    <dd>A variable passed to this parameter will contain an array (<code>string[]</code>) containing the found items. When comparing tags or titles, the array's values will be lowercased.</dd>
</dl>

Returns the calculated match score.