##Instance functions

###`private void initRegex()`

Initializes the word-boundary ([`$reWordBoundary`](#string-rewordboundary)) and modifier ([`$reMod`](#string-remod)) used in regular expressions based on the passed flags.

###`private void init()`

Finds the intersections of the source file's properties and the search items and initializes the object's properties containing the found matches ([`$matches`](#string-matches)) and the match score ([`$score`](#int-score)), as well as the source file's properties, HTML encoded and matching parts highlighted ([`$highlight`](#array-highlight)).  
Available properties are the source file's filename ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file)), title (if defined, [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title)) and tags ([`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)).

When comparing the **filename**, this function compares case-sensitively. If the entire filename (incl. extension) is found in the items, the score is 2, if only the filename with the extension stripped away is found, the score is 1.  
The filename is matched against the last path segment of the source file's [`_dest` GvBlock option](Gustav-core-options#_dest).

When comparing the source file's **title**, the items are searched in the title case-insensitively by default, or case-sensitively if the [`GustavMatch::CASE_SENSITIVE`](Public-API%3a-GustavMatch#int-case_sensitive) flag is set. Moreover the search items are wrapped into word-boundaries. For more information on *word-boundaries* see [`GustavMatch::SPEC_LOW`](Public-API%3a-GustavMatch#int-spec_low) and [`GustavMatch::SPEC_HIGH`](Public-API%3a-GustavMatch#int-spec_high).  
If the source file doesn't have a title, the score is 0. Otherwise the score is increased by 1 for each item for each occurrence within the title.
When searching for literal items in the title, this function acts a bit differently: Additionally to the number of occurrences of the entire literal, for each of the literal's unique (case-insensitively) single items the product of the number of occurrences of the whole literal within the title and the number of occurrences of the single item within the literal is added to the score. These items are also added to the [`$matches` array](#string-matches). The single items are extracted by passing the literal to [`GustavBase::getSearchTermItems()`](Private-API%3a-GustavBase#string-getsearchtermitems-string-search_term_part-).

When comparing the source file's **tags**, the score is increased by 1 for each item found in the source file's tags (case-insensitively).

When getting the score for the tags, the items are made unique case-insensitively before comparing them with the source file's properties. The same applies to a source file's title, with the only exception that the items are made unique ***case-sensitively*** when the [`GustavMatch::CASE_SENSITIVE`](Public-API%3a-GustavMatch#int-case_sensitive) flag is set.

[`$matches`](#string-matches) Will contain the supported items ([`GustavBase::KEY_FILE`](Public-API%3s-GustavBase#string-key_file), [`GustavBase::KEY_TITLE`](Public-API%3s-GustavBase#string-key_title), [`GustavBase::KEY_TAGS`](Public-API%3s-GustavBase#string-key_tags)) only, any other item is ignored.

[`$highlight`](#array-highlight) will contain the supported items only (see above). Moreover, items whose corresponding source file's property isn't set are removed. The properties' values are HTML encoded (if an array, the array's items are encoded) and matching parts are highlighted using `<mark>`. When highlighting titles, the same word-boundaries and treatment of the character case as for *matching* the title is used.

###`public mixed __call( string $function_name, array $arguments )`

A *magic* overloading function is called when an object's non-reachable function is called.  
This function is used to emulate global getter functions for some of the object's properties. The following getters are available:

<dl>
    <dt><code>getSrc()</code></dt>
    <dd>The <a href="API#gustavsrc"><code>GustavSrc</code></a> object for the source file whose properties should be matched against the search items. (<a href="#private-gustavsrc-src"><code>$src</code></a> property).</dd>
    
    <dt><code>getMatches()</code></dt>
    <dd>The search items matching the source file's properties (<a href="#string-matches"><code>$matches</code></a> property).</dd>
    
    <dt><code>getScore()</code></dt>
    <dd>The match score calculated from the matching search items (<a href="#int-score"><code>$score</code></a> property).</dd>
    
    <dt><code>getHighlight()</code></dt>
    <dd>The source file's searched properties, HTML encoded and matching parts highlighted using `<mark>` (<a href="#array-highlight"><code>$highlight</code></a> property).</dd>
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

The source file's searched properties, HTML encoded and matching parts highlighted using `<mark>`.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavMatch`'s corresponding Hooks class.