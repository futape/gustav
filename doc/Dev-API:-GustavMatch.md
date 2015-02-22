##Instance functions

###`private void initSearch( string[][] $search )`

Initializes the array containing the search items ([`$search`](#private-string-search)).  
Empty string values are filtered out.

<dl>
    <dt><code>$search</code></dt>
    <dd>
        An associative array containing the search items.<br />
        The array's items should use one of the <a href="Public-API%3a-GustavBase#constants"><code>GustavBase::KEY_*</code></a> constants as key and an array of strings containing the search items as value.
    </dd>
</dl>



###`private void initRegex()`

Initializes the word-boundary ([`$reWordBoundary`](#private-string-rewordboundary)) and modifier ([`$reMod`](#private-string-remod)) used in regular expressions based on the passed flags.

###`private void init()`

Finds the intersections of the source file's properties and the search items and initializes the object's properties containing the found matches ([`$matches`](#private-string-matches)) and the match score ([`$score`](#private-int-score)), as well as the source file's properties, HTML encoded and matching parts highlighted ([`$highlight`](#private-array-highlight)).  
Available properties are the source file's filename ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file)), title (if defined, [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title)) and tags ([`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)).

When comparing the **filename**, this function compares the search items case-sensitively to the source file's [`_dest` GvBlock option](Gustav-core-options#_dest)'s last path segment.  
If the path's basename (with file-extension) is found in the search items, the items is considered to match. If the `_dest` options's value doesn't end with a directory separator, a search item just needs to match the path's filename (without file-extension) to be considered matching.  
If the `_dest` option's value equals a directory separator, an item `/` matches that source file.  
For a matching source file, the score is increased by 1.

When comparing the source file's **title**, the items are searched in the title case-insensitively ([`_title` GvBlock option](Gustav-core-options#_title)) by default, or case-sensitively if the [`GustavMatch::CASE_SENSITIVE`](Public-API%3a-GustavMatch#int-case_sensitive) flag is set. Moreover the search items are wrapped into word-boundaries and spaces within the items may not be take literally. For more information see [`GustavMatch::SPEC_LOW`](Public-API%3a-GustavMatch#int-spec_low) and [`GustavMatch::SPEC_HIGH`](Public-API%3a-GustavMatch#int-spec_high).  
If the source file doesn't have a title, the score isn't increased. Otherwise the score is increased by 1 for each item for each occurrence within the title.
When searching for literal items in the title, this function acts a bit differently: Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitively) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score. These items are also added to the [`$matches` array](#private-string-matches). The single items are extracted by passing the literal to [`GustavMatch::getSearchTermItems()`](Private-API%3a-GustavMatch#string-getsearchtermitems-string-search_term_part-).  
If the [`GustavMatch::LITERAL_SPACES`](#int-literal_spaces) flag isn't set, a sequence of spaces in a search item or a literal matches one or more whitespaces of any kind in the title.

When comparing the source file's **tags**, the score is increased by 1 for each item found in the source file's tags ([`_tags` GvBlock option](Gustav-core-options#_tags)) (case-insensitively).

When getting the score for the tags or the title, the items are made unique case-insensitively (i.e. they are lowercased) before comparing them with the source file's properties. If the [`GustavMatch::CASE_SENSITIVE`](Public-API%3a-GustavMatch#int-case_sensitive) flag is set, the items are not lowercased before making them unique when the title is matched.

[`$matches`](#private-string-matches) Will contain only the supported items ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file), [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title), [`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)), any other items are ignored. If the search items have been lowercased (see above), this array's corresponding item's string values will be, too.

[`$highlight`](#private-array-highlight) will contain all supported items (see above) whose corresponding source-file-property is set using the source file's properties as values. The values are HTML encoded (if an array, the array's string values are encoded) and matching parts are highlighted using `<mark>`. When highlighting titles, the same word-boundaries and treatment of the character case as for *matching* the title is used. The array will look like the one below.

    array(
        "tags"=>array("fish &amp; chips", "<mark>1 and 2</mark>"), //GustavBase::KEY_TAGS
        "file"=>"/blog/category/<mark>hello-world</mark>", //GustavBase::KEY_FILE
        "title"=>"Hello <mark>World</mark>" //GustavBase::KEY_TITLE
    )

The `title` item is only available if the corresponding GvBlock option is set. That item is retrieved from the [`_title` GvBlock option](Gustav-core-options#_title). The `tags` item corresponds to the [`_tags` GvBlock option](Gustav-core-options#_tags), while the [`_dest` GvBlock option](Gustav-core-options#_dest) is used as the value of the `file` item. However, directory separators within that option's value are replaced by `/`s and trailing ones are removed.

###`public mixed __call( string $function_name, array $arguments )`

A *magic* overloading function is called when an object's non-reachable function is called.  
This function is used to emulate global getter functions for some of the object's properties. The following getters are available:

<dl>
    <dt><code>getSrc()</code></dt>
    <dd>The <a href="API#gustavsrc"><code>GustavSrc</code></a> object for the source file whose properties should be matched against the search items. (<a href="#private-gustavsrc-src"><code>$src</code></a> property).</dd>
    
    <dt><code>getMatches()</code></dt>
    <dd>The search items matching the source file's properties (<a href="#private-string-matches"><code>$matches</code></a> property).</dd>
    
    <dt><code>getScore()</code></dt>
    <dd>The match score calculated from the matching search items (<a href="#private-int-score"><code>$score</code></a> property).</dd>
    
    <dt><code>getHighlight()</code></dt>
    <dd>The properties of the source file that correspond to the supported properties. The values are HTML encoded and matching parts highlighted using `<mark>` (<a href="#private-array-highlight"><code>$highlight</code></a> property).</dd>
</dl>

If any other non-reachable function is called, a [`BadMethodCallException`](http://php.net/manual/en/class.badmethodcallexception.php) is thrown.

<dl>
    <dt><code>$function_name</code></dt>
    <dd>The name of the called function.</dd>
    
    <dt><code>$arguments</code></dt>
    <dd>The arguments passed to the called function.</dd>
</dl>



##Instance properties

###`private GustavSrc $src`

The source file whose properties should be matched against the search items.
    
###`private string[][] $search`

The search items to compare with the source file's properties.

###`private int $flags`

The flags adjusting the behavior when matching the source file's properties against the search items.

###`private string $reWordBoundary`

A regular expression defining the word-boundary wrapping the search items when matching the source file's title.

###`private string $reMod`

The RegEx modifiers used when matching the source file's title.

###`private string[][] $matches`

The search items matching the source file's properties.  
Will contain the supported items ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file), [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title), [`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)) only, any other item is ignored.

###`private int $score`

The match score calculated from the matching search items.

###`private array $highlight`

The properties of the source file that correspond to the supported properties. The values are HTML encoded and matching parts highlighted using `<mark>`.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavMatch`'s corresponding Hooks class.
