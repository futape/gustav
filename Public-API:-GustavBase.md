##Static functions

###`string[] processSearchTerm( string $search_term )`

Splits a search term into parts.

Parts are separated by whitespace characters.  
A part which should be treated as a literal or which consists of multiple words can be marked up by wrapping it into double quotes. All characters within such a literal part are taken literally.  
If you want to mark up a literal double quote, you have to type two double quotes. For example, `one two "three "" four" five` which would result in `array("one", "two", 'three " four', "five")` and `""""` which would result in `array('"')`.  
Empty literals are possible. Empty non-literal parts are removed.  
If no literal has been found in the search term, the entire search term is added to the resulting array as a literal. It gets trimmed and sequences of whitespaces are replaced with one simple space.  
Each part does only exist a single time in the returned array (case-sensitively), regardless of whetehr it's a literal part or not.  
Usecases of this function's returned value may be as value for an item of the array passed to the second parameter of the [`GustavMatch` constructor](Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--) or as value for one of [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--)'s `match` filter's items. Moreover, you can split a part into single items (mostly relevant for literal parts) by passing the part to [`GustavBase::getSearchTermItems()`](Private-API%3a-GustavBase#string-getsearchtermitems-string-search_term_part-).

<dl>
    <dt><code>$search_term</code></dt>
    <dd>The search term.</dd>
</dl>

Returns the search term's parts.

###`int|false getDateBegin( [ int|string|null $timestamp = null ] )`

Get the first second of a day.

<dl>
    <dt><code>$timestamp</code></dt>
    <dd>A unix timestamp or a <a href="http://php.net/manual/en/datetime.formats.php">datetime string</a> whose day's first second should be calculated. If set to <code>null</code>, the current time is used.</dd>
</dl>

Returns a unix timestamp representing the first second of the day specified by the passed timestamp or `false` on failure.

###`int|false getDateEnd( [ int|string|null $timestamp = null ] )`

Get the last second of a day.

<dl>
    <dt><code>$timestamp</code></dt>
    <dd>A unix timestamp or a <a href="http://php.net/manual/en/datetime.formats.php">datetime string</a> whose day's last second should be calculated. If set to <code>null</code>, the current time is used.</dd>
</dl>

Returns a unix timestamp representing the last second of the day specified by the passed timestamp or `false` on failure.



##Constants

###`string KEY_TITLE`

An often used key of associative arrays for a [source file](Source-files)'s title.
    
###`string KEY_TAGS`

An often used key of associative arrays for a [source file](Source-files)'s tags.
    
###`string KEY_FILE`

An often used key of associative arrays for a [source](Source-files) or [destination file](Destination-files)'s filename.