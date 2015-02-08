##Static functions

###`void log( string $log_message [, string $log_type = Gustav::LOG_TYPE_WARNING [, bool $force = false ]] )`

Writes to the Gustav [log-file](Log-files).

If a filesize limit for `gustav.log` is set using the [`log_file_max_size` configuration option](Gustav-configuration#stringint-log_file_max_size---1) and the log-file's filesize has already exceeded that limit, the unix timestamp of the last modification of the log-file's content and an unique ID are appended to its filename, resulting in a filename like `gustav.1419426764921.4b3403665fea6.log`, and the log entry is written to a new log-file named `gustav.log`. If the timestamp of the last modification can't be retrieved, the current time is used instead.  
If the [`log_file_max_size` configuration option](Gustav-configuration#stringint-log_file_max_size---1) is set to `-1`, no limit is specified.  
If the [`enable_log` configuration option](Gustav-configuration#bool-enable_log--true) is set to `false`, nothing is written to the log-file.

<dl>
    <dt><code>$log_message</code></dt>
    <dd>The text that should be written to the end of the log-file.</dd>

    <dt><code>$log_type</code></dt>
    <dd>A <code>Gustav::LOG_TYPE_*</code> constant defining the uppercased first word of the log entry.</dd>

    <dt><code>$force</code></dt>
    <dd>By default this function doesn't write anything to the log-file if the <a href="Gustav-configuration#bool-enable_log--true"><code>enable_log</code> configuration option</a> is set to <code>false</code>. However, if this parameter is set to <code>true</code>, it does.</dd>
</dl>

###`void error( [ string|null $log_message = null [, string|int $error_type = Gustav::ERROR_500 ]] )`

Exits the script as a consequence of an error.

Exits the script with a HTTP status code and a possible error log entry in the Gustav [log-file](Log-files).  
If the [`exit_on_error` configuration option](Gustav-configuration#bool-exit_on_error--true) is set to `false`, the execution of the script isn't stopped unless `$error_type` is set to [`Gustav::ERROR_FATAL`](#int-error_fatal) or [`Gustav::ERROR_404`](#string-error_404). In case of `Gustav::ERROR_FATAL`, also a log entry is forced (if a log message is specified), regardless of the setting of the [`enable_log` configuration option](Gustav-configuration#bool-enable_log--true).  
If [`Gustav::ERROR_404`](#string-error_404) is used as value for `$error_type`, a HTML `Content-Type` HTTP response header field is set, the content of the resource specified by the [`404_error_doc` configuration option](Gustav-configuration#string-404_error_doc--) is printed and the script execution is stopped, regardless of the [`exit_on_error` configuration option](Gustav-configuration#bool-exit_on_error--true). For more information on the `404_error_doc` document see [*Gustav configuration*](Gustav-configuration#string-404_error_doc--).

<dl>
    <dt><code>$log_message</code></dt>
    <dd>If not <code>null</code>, an error log entry containing this text is written to the log-file if logging isn't disabled or if a fatal error is raised.

    <dt><code>$error_type</code></dt>
    <dd>A <code>Gustav::ERROR_*</code> constant defining the error type. This may be an error, representing a HTTP status or a Gustav-internal error.
</dl>

###`void success( string $url [, string|null $log_message = null ] )`

Exits the script as a consequence of success and redirects to another location.

Exits the script with the HTTP status code `303 See Other`, a possible log entry in the Gustav [log-file](Log-files) and redirection to the specified URL.

<dl>
    <dt><code>$url</code></dt>
    <dd>A relative or an absolute URL defining the target of the redirection.</dd>

    <dt><code>$log_message</code></dt>
    <dd>If not <code>null</code>, a log entry containing this text is written to the log-file if logging is enabled.</dd>
</dl>

###`bool convExists( string|string[] $converter [, string|null $converters = null ] )`

Checks whether a converter exists.

Checks whether a converter exists within a specified set of converters or within all available converters.  
When searching in [user-defined converters](User-defined-converters), this function compares the converters' names with the specified converter name case-sensitively. [Hardcoded converters](Converting-source-content#hardcoded-converters)' names are compared with the specified converter name case-insensitively. When specifying a set of converter names using the `$converters` parameter, the comparison is done case-insensitively.

<dl>
    <dt><code>$converter</code></dt>
    <dd>The name of the converter to check. Or an array of strings containing names of converters. At least one of them must exist.</dd>

    <dt><code>$converters</code></dt>
    <dd>
        A set of available converters (separated by <code>.</code>s). The value of a <code>Gustav::CONV_*</code> constant or <a href="#string-convs"><code>Gustav::CONVS</code></a> is perfectly suitable for this parameter's value.<br />
        If not <code>null</code>, this function will search case-insensitively for the specified converter name only within the set that has been specified by this parameter. Otherwise the converter is searched within all, user-defined (case-sensitively) and hardcoded (case-insensitively), converters.
    </dd>
</dl>

Returns whether the converter has been found.

###`string[] getHardConv( [ string|null $converter = null ] )`

Get available [hardcoded converters](Converting-source-content#hardcoded-converters).

Get all hardcoded converters or only those whose names match a specified converter name (and the converter's aliases).

<dl>
    <dt><code>$converter</code></dt>
    <dd>If not <code>null</code>, the function returns only converters that have names matching (case-insensitive) this parameter's value. Otherwise all hardcoded converters are returned.</dd>
</dl>

Returns all names of all converters matching the specified converter name, if any, or all available names for all available hardcoded converters if no converter name has been specified.

###`string getHttpUrl( string|string[] $path [, bool $is_url = false [, bool $path_includes_doc_root = true ]] )`

Get an absolute URL for a given path.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path to use as the URL path. May also be an empty string which would result in an URL path of <code>/</code>. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a> and is converted to a properly urlencoded path, directory separators replaced by <code>/</code>.</dd>
    
    <dt><code>$is_url</code></dt>
    <dd>If set to <code>true</code>, the passed <em>path</em> is considered to be a <a href="https://tools.ietf.org/html/rfc3986#section-4.2">relative URL</a> (root-relative, starting with <code>/</code>). If done so, <code>$path_includes_doc_root</code> is ignored entirely and the path isn't converted in any way.</dd>
    
    <dt><code>$path_includes_doc_root</code></dt>
    <dd>If set to <code>false</code>, the document root is prepended to the passed path. It's removed again before converting it to an URL path. This parameter is ignored if <code>$is_url</code> is set to <code>true</code>.</dd>
</dl>

Returns the built URL.



##Constants

###`string LOG_TYPE_WARNING`

The "warning" log type.
    
###`string LOG_TYPE_ERROR`

The "error" log type.
    
###`string LOG_TYPE_SUCCESS`

The "success" log type.

###`string ERROR_404`

The HTTP `404 Not Found` *status code*.

###`string ERROR_500`

The HTTP `500 Internal Server Error` *status code*.

###`int ERROR_FATAL`

A fatal error, stopping the script execution, even if the [configuration](Gustav-configuration#bool-exit_on_error--true) deactivates such behavior.

###`string CONV_HTML`

Possible names for the [hardcoded HTML converter](Converting-source-content#the-html-converter-htmlhtm).

Lowercased names, separated by `.`s.

###`string CONV_TEXT`

Possible names for the [hardcoded plain text converter](Converting-source-content#the-plain-text-converter-txttextplain).

Lowercased names, separated by `.`s.

###`string CONVS`

Possible [converter names](Converting-source-content#hardcoded-converters).

Lowercased names, separated by `.`s.  
Concatenates the values of all documented `Gustav::CONV_*` constants.