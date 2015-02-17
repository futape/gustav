##Static functions

###`string[] getSearchTermItems( string $search_term_part )`

Splits a search term's part into single items.

Splits a string at whitespace characters. Empty items are removed and each item does only exist a single time within the returned array (case-sensitively).  
This function's purpose is not to split a search term entered by an user into parts, rather to split such parts into single items. To split a user-entered search term use [`GustavMatch::processSearchTerm()`](Public-API%3a-GustavMatch#string-processsearchterm-string-search_term-) instead.

<dl>
    <dt><code>$search_term_part</code></dt>
    <dd>A search term's part.</dd>
</dl>

Returns the single search term items.
