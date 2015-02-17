##Static functions

###`string[] processSearchTerm( string $search_term )`

Splits a search term into parts.

Parts are separated by whitespace characters.  
A part which should be treated as a literal or which consists of multiple words can be marked up by wrapping it into double quotes. All characters within such a literal part are taken literally.  
If you want to mark up a literal double quote, you have to type two double quotes. For example, `one two "three "" four" five` which would result in `array("one", "two", 'three " four', "five")` and `""""` which would result in `array('"')`.  
Empty items, literals and simple items, are removed.  
If no literal has been found in the search term, the entire search term is added to the resulting array as a literal. It gets trimmed and sequences of whitespaces are replaced with one simple space.  
Each part does only exist a single time in the returned array (case-sensitively), regardless of whetehr it's a literal part or not.  
Usecases of this function's returned value may be as value for an item of the array passed to the second parameter of the [`GustavMatch` constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--) or as value for one of [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--)'s `match` filter's items. Moreover, you can split a part into single items (mostly relevant for literal parts) by passing the part to [`GustavMatch::getSearchTermItems()`](Private-API%3a-GustavMatch#string-getsearchtermitems-string-search_term_part-).

<dl>
    <dt><code>$search_term</code></dt>
    <dd>The search term.</dd>
</dl>

Returns the search term's parts.



##Instance functions

###`void __construct( string|string[] $path, string[][] $search [, int $flags = 0 ] )`

The class's constructor that is called when a new instance is created.  
If no [`GustavSrc`](API#gustavsrc) object can be created, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown.  
If everything worked properly, the newly created object is initialized.

Finds the intersections of the source file's properties and the search items and initializes the object's properties containing the found matches and the match score, as well as the source file's properties, HTML encoded and matching parts highlighted.  
Available properties are the source file's filename ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file), title (if defined, [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title)) and tags ([`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)), any other item is ignored.

When comparing the **filename**, this function compares the search items case-sensitively to the source file's [`_dest` GvBlock option](Gustav-core-options#_dest)'s last path segment.  
If the path's basename (with file-extension) is found in the search items, the item is considered to match. If the `_dest` options's value doesn't end with a directory separator, a search item just needs to match the path's filename (without file-extension) to be considered matching.  
If the `_dest` option's value equals a directory separator, an item `/` matches that source file.  
For a matching source file, the score is increased by 1.

When comparing the source file's **title**, the items are searched in the title ([`_title` GvBlock option](Gustav-core-options#_title)) case-insensitively by default, or case-sensitively if the [`GustavMatch::CASE_SENSITIVE`](#int-case_sensitive) flag is set. Moreover the search items are wrapped into word-boundaries. For more information on *word-boundaries* see [`GustavMatch::SPEC_LOW`](#int-spec_low) and [`GustavMatch::SPEC_HIGH`](#int-spec_high).  
If the source file doesn't have a title, the score isn't increased. Otherwise the score is increased by 1 for each item for each occurrence within the title.  
When searching for literal items in the title, this function acts a bit differently: Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitively) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score.  
If the [`GustavMatch::LITERAL_SPACES`](#int-literal_spaces) flag isn't set, a sequence of spaces in a search item or a literal matches one or more whitespaces of any kind in the title.

When comparing the source file's **tags**, the score is increased by 1 for each item found in the source file's tags ([`_tags` GvBlock option](Gustav-core-options#_tags)) (case-insensitively).

When getting the score for the tags or the title, the items are made unique case-insensitively (i.e. they are lowercased) before comparing them with the source file's properties. If the [`GustavMatch::CASE_SENSITIVE`](Public-API%3a-GustavMatch#int-case_sensitive) flag is set, the items are not lowercased before making them unique when the title is matched. If lowercased, the items of the array returned by [`GustavMatch::getMatches()`](#string-getmatches) corresponding to these properties will contain lowercased items, too.

Besides discovering the matching items and calculating the match score, this function also highlights the matching items in all supported properties of the source file. The source file's properties are used as values. The values are HTML encoded (if an array, the array's string values are encoded) and matching parts are highlighted using `<mark>`. When highlighting titles, the same word-boundaries and treatment of the character case as for *matching* the title is used.

For more information see [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init).

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> whose properties should be matched against the search items. Gets passed to <a href="Public-API%3a-GustavSrc#void-__construct-stringstring-path-"><code>GustavSrc::__construct()</code></a> which in turn calls <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
    
    <dt><code>$search</code></dt>
    <dd>An associative array containing the search items. The array's items should use one of the <a href="Public-API%3a-GustavBase#constants"><code>GustavBase::KEY_*</code> constants</a> as key and an array of strings containing the search items as value. Empty items of the passed array's values are removed. The values may be an array returned by <a href="Public-API%3a-GustavMatch#string-processsearchterm-string-search_term-"><code>GustavMatch::processSearchTerm()</code></a> for example.</dd>
    
    <dt><code>$flags</code></dt>
    <dd>A bitmask of the following values: <a href="#int-spec_low"><code>GustavMatch::SPEC_LOW</code></a>, <a href="#int-spec_high"><code>GustavMatch::SPEC_HIGH</code></a>, <a href="#int-case_sensitive"><code>GustavMatch::CASE_SENSITIVE</code></a> and <a href="#int-literal_spaces"><code>GustavMatch::LITERAL_SPACES</code></a>. See those constants for more information.</dd>
</dl>

###`GustavSrc getSrc()`

Returns the [`GustavSrc`](API#gustavsrc) object for the source file whose properties should be matched against the search items.

###`string[][] getMatches()`

Returns the search items matching the source file's properties.

###`int getScore()`

Returns the match score calculated from the matching search items.

###`array getHighlight()`

Returns the properties of the source file that correspond to the supported properties. The values are HTML encoded and matching parts are highlighted using `<mark>`.



##Constants

###`int SPEC_LOW`

By default, [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init) uses a custom word-boundary when matching a source file's title. The *custom word-boundary* consideres, besides the `\b` RegEx escape sequence, `_`s and digits having an adjacent non-digit character, as well as non-digits having an adjacent digit character to separate words.  
When including this constant's value in the mitmask passed to this class's [constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--)'s third parameter, no word-boundary is used at all. The searched term can occur everywhere in the title. This behavior is more loose than the default one.

###`int SPEC_HIGH`

By default, [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init) uses a custom word-boundary when matching a source file's title. The *custom word-boundary* consideres, besides the `\b` RegEx escape sequence, `_`s and digits having an adjacent non-digit character, as well as non-digits having an adjacent digit character to separate words.  
When including this constant's value in the mitmask passed to this class's [constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--)'s third parameter, a word-boundary definition of `\b` is used instead which is a bit more strict than the default one.

###`int CASE_SENSITIVE`

By default, [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init) matches a source file's title case-insensitively.  
When including this constant's value in the mitmask passed to this class's [constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--)'s third parameter, the title is matched case-sensitively instead.

###`int LITERAL_SPACES`

By default, [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init) matches any number of any kind of whitespace for a space in a search item or a literal when matching a source file's title.
When including this constant's value in the mitmask passed to this class's [constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--)'s third parameter, that behavior is disabled. Instead the item must be found in the title exactly as defined.
