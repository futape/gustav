##Static functions

###`array highlightMatches( array $plain, string[][] $matches )`

Highlights parts of a source file's title, filename and tags.

Searches for occurences of the items specified using `$matches` and highlights them within the corresponding items of `$plain`.  
Highlights are made by wrapping matching parts into `<mark>`.  
Any item of `$plain`, also the ones that doesn't have an appropriate item within `$matches`, gets HTML encoded.  
For information on how the items are matched against the source file's properties see *GustavSrc::getMatchScore()*.  
The returned value is perfectly suitable for using it to highlight searched keywords in searchresults.

<dl>
    <dt><code>$plain</code></dt>
    <dd>
        An associative array containing the plain values of a source file's properties. The following items are supported.
        
<pre><code>array(
    "title"=&gt;"plain &lt;title&gt; string", //GustavBase::KEY_TITLE
    "tags"=&gt;array("with", "&lt;plain&gt;", "tags"), //GustavBase::KEY_TAGS
    "file"=&gt;"plain filename with extension but with&lt;o&gt;ut dirname" //GustavBase::KEY_FILE
)</code></pre>
        
        Any other item, or its items in case of an array, is casted as a string and is simply HTML encoded.<br />
        The array returned by <code>GustavSrc::getMeta()</code> is perfectly suitable for using it as this parameter's value.
    </dd>
    
    <dt><code>$matches</code></dt>
    <dd>An associative array containing the items to highlight. Use the values of the array returned by <code>Gustav::query()</code> or the array returned by <code>GustavSrc::getMatchScore()</code> as this parameter's value.</dd>
</dl>
     
Returns an associative array which is of the same structure as `$plain`. All items of this array are HTML encoded. Moreover all items defined by `$matches` are highlighted within this array's items.

###`string[] processSearchTerm( string $search_term )`

Splits a search term into parts.

Parts are separated by whitespace characters.  
A part which should be treated as a literal or which consists of multiple words can be marked up by wrapping it into double quotes. All characters within such a literal part are taken literally.  
If you want to mark up a literal double quote, you have to type two double quotes. For example, `one two "three "" four" five` which would result in `array("one", "two", 'three " four', "five")` or `""""` which would result in `array('"')`.  
Empty literals are possible. Empty non-literal parts are removed.  
If no literal has been found in the search term, the entire search term is added to the resulting array as a literal. It gets trimmed and sequences of whitespaces are replaced with one simple space.  
Each part does only exist a single time in the returned array (case-sensitively), regardless of whetehr it's a literal part or not.  
Usecases of this function's returned value may be as value for the second parameter of `GustavSrc::getMatchScore()` or as value for one of `Gustav::query()`'s `match` filter's items. Moreover, you can split a part into single items (mostly relevant for literal parts) by passing the part to `GustavBase::getSearchTermItems()`.

<dl>
    <dt><code>$search_term</code></dt>
    <dd>The search term.</dd>
</dl>

Returns the search term's parts.

###`int|false getDateBegin( [ int|string|null $timestamp = null ] )`

Get the first second of a day.

<dl>
    <dt><code>$timestamp</code></dt>
    <dd>A unix timestamp or a datetime string whose day's first second should be calculated. If set to <code>null</code>, the current time is used.</dd>
</dl>

Returns a unix timestamp representing the first second of the day specified by the passed timestamp or `false` on failure.

###`int|false getDateEnd( [ int|string|null $timestamp = null ] )`

Get the last second of a day.

<dl>
    <dt><code>$timestamp</code></dt>
    <dd>A unix timestamp or a datetime string whose day's last second should be calculated. If set to <code>null</code>, the current time is used.</dd>
</dl>

Returns a unix timestamp representing the last second of the day specified by the passed timestamp or `false` on failure.



##Constants

###`string KEY_TITLE`

An often used key of associative arrays for a source file's title.
    
###`string KEY_TAGS`

An often used key of associative arrays for a source file's tags.
    
###`string KEY_FILE`

An often used key of associative arrays for a source or destination file's filename.