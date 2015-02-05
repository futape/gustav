##Instance functions

###`void __construct( string|string[] $path, string[][] $search [, int $flags = 0 ] )`

The class's constructor that is called when a new instance is created.  
If no [`GustavSrc`](API#gustavsrc) object can be created, a [`RuntimeException`](http://php.net/manual/en/class.runtimeexception.php) is thrown.  
If everything worked properly, the newly created object is initialized.

Finds the intersections of the source file's properties and the search items and initializes the object's properties containing the found matches and the match score, as well as the source file's properties, HTML encoded and matching parts highlighted.  
Available properties are the source file's filename ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file), title (if defined, [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title)) and tags ([`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)), any other item is ignored.

When comparing the **filename**, this function compares the search items case-sensitively to the source file's [`_dest` GvBlock option](Gustav-core-options#_dest)'s last path segment.  
If the path's basename (with file-extension) is found in the search items, the items is considered to match. If the `_dest` options's value doesn't end with a directory separator, a search item just needs to match the path's filename (without file-extension) to be considered matching. For a matching source file, the score is increased by 1.

When comparing the source file's **title**, the items are searched in the title ([`_title` GvBlock option](Gustav-core-options#_title)) case-insensitively by default, or case-sensitively if the [`GustavMatch::CASE_SENSITIVE`](#int-case_sensitive) flag is set. Moreover the search items are wrapped into word-boundaries. For more information on *word-boundaries* see [`GustavMatch::SPEC_LOW`](#int-spec_low) and [`GustavMatch::SPEC_HIGH`](#int-spec_high).  
If the source file doesn't have a title, the score isn't increased. Otherwise the score is increased by 1 for each item for each occurrence within the title.  
When searching for literal items in the title, this function acts a bit differently: Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitively) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score.  
If the [`GustavMatch::SPEC_HIGH`](#int-spec_high) flag isn't set, a sequence of spaces in a search item or a literal matches one or more whitespaces of any kind in the title.

When comparing the source file's **tags**, the score is increased by 1 for each item found in the source file's tags ([`_tags` GvBlock option](Gustav-core-options#_tags)) (case-insensitively).

When getting the score for the tags, the items are made unique case-insensitively before comparing them with the source file's properties. The same applies to a source file's title, with the only exception that the items are made unique ***case-sensitively*** when the [`GustavMatch::CASE_SENSITIVE`](#int-case_sensitive) flag is set.

Besides discovering the matching items and calculating the match score, this function also highlights the matching items in all supported properties of the source file. The source file's properties are used as values. The values are HTML encoded (if an array, the array's string values are encoded) and matching parts are highlighted using `<mark>`. When highlighting titles, the same word-boundaries and treatment of the character case as for *matching* the title is used.

For more information see [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init).

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the <a href="Source-files">source file</a> whose properties should be matched against the search items. Gets passed to <a href="Public-API%3a-GustavSrc#void-__construct-stringstring-path-"><code>GustavSrc::__construct()</code></a> which in turn calls <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
    
    <dt><code>$search</code></dt>
    <dd>An associative array containing the search items. The array's items should use one of the <a href="Public-API%3a-GustavBase#constants"><code>GustavBase::KEY_*</code> constants</a> as key and an array of strings containing the search items as value. The values may be an array returned by <a href="Public-API%3a-GustavBase#array-highlightmatches-array-plain-string-matches-"><code>GustavBase::processSearchTerm()</code></a> for example.</dd>
    
    <dt><code>$flags</code></dt>
    <dd>A bitmask of the following values: <a href="#int-spec_low"><code>GustavMatch::SPEC_LOW</code></a>, <a href="#int-spec_high"><code>GustavMatch::SPEC_HIGH</code></a>, <a href="#int-case_sensitive"><code>GustavMatch::CASE_SENSITIVE</code></a>. See those constants for more information..</dd>
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
Moreover, the default behavior of matching one or more whitespaces of any kind for a sequence of spaces in a search item or a literal is disabled when using this flag. Instead the item must be found in the title exactly as defined.

###`int CASE_SENSITIVE`

By default, [`GustavMatch::init()`](Dev-API%3a-GustavMatch#private-void-init) matches a source file's title case-insensitively.  
When including this constant's value in the mitmask passed to this class's [constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--)'s third parameter, the title is matched case-sensitively instead.