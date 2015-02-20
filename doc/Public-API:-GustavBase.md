##Static functions

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
