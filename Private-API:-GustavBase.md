##Static functions

###`string lstrip( string $string, string $remove )`

This function strips off the specified string from the beginning of another string.
     
<dl>
    <dt><code>$string</code></dt>
    <dd>The string to remove <code>$remove</code> from.</dd>
    
    <dt><code>$remove</code></dt>
    <dd>The string to remove from the beginning of <code>$string</code>.</dd>
</dl>

Returns the stripped string.

###`string realStr( string $string )`

Converts a literal string to a *real* string.

Treats a string as it would be a [double-quoted string](http://php.net/manual/en/language.types.string.php#language.types.string.syntax.double) in PHP. The only exception is that variables aren't resolved. But everything else like meta-characters such as `\n` or `\t` is treated as expected.

<dl>
    <dt><code>string $string</code></dt>
    <dd>A literal string.</dd>
</dl>

Returns the *real* string.

###`string addslashes( string $string [, string|null $chars = null ] )`

Prepends a backslash to specific characters.

Prepends a `\` to the specified characters and the `NUL` byte.  
The result is optimized for using it as a literal string. For example: `eval('return "'.GustavBase::addslashes('foo"bar').";')` which would result in `eval('return "foo\"bar";')`.  
If you wish to use the returned value in a [single quoted string](http://php.net/manual/en/language.types.string.php#language.types.string.syntax.single), you should ensure that you set $chars to `"'\\"` because no other characters need to be escaped. If they would, the resulting string would be `'foo\"bar'` which is a literal string and therfore the backslash (`\`) would remain.  
In case that the backslash character itself isn't contained in `$chars`, a `\` following a sequence of backslashes, which contains an odd number of backslashes or which is the string's first character, and which is followed by a character that is contained in `$chars` or which acts as the last character of the string, is removed from the string because if it wouldn't, the backslash that will be prepended would get consumed by the preceding backslash and therefore the character which should be escaped wouldn't be escaped.

<dl>
    <dt><code>$string</code></dt>
    <dd>A string whose *special* characters should be escaped by a backslash.</dd>
    
    <dt><code>$chars</code></dt>
    <dd>A literal string defining the characters that should be escaped by a backslash. If set to <code>null</code>, <code>'\\"$\''</code> is used.</dd>
</dl>

Returns the passed string containing no more non-escaped *special* characters.

###`string unl( string $string )`

Normalizes newlines. 

Normalizes [various types](http://en.wikipedia.org/wiki/Newline#Representations) of linebreaks. Windows's `\r\n` and Mac OS's `\r` are changed to match Unix's `\n`.

<dl>
    <dt><code>$string</code></dt>
    <dd>A string whose linebreaks should be normalized.</dd>
</dl>

Returns the passed string containing only Unix-like linebreaks.

###`bool strStartsWith( string $string, string $starts_with [, bool $ignore_case = false ] )`

Checks whether a string starts with another specific string.

<dl>
    <dt><code>$string</code></dt>
    <dd>A string whose beginning should be checked against the other string.</dd>
    
    <dt><code>$starts_with</code></dt>
    <dd>The string the beginning of <code>$string</code> should be checked against.</dd>
    
    <dt><code>$ignore_case</code></dt>
    <dd>If set to <code>true</code>, the comparision is done case-insensitively.</dd>
</dl>

Returns whether the beginning matches the specified string.

###`bool strEndsWith( string $string, string $ends_with [, bool $ignore_case = false ] )`

Checks whether a string ends with a specific string.

<dl>
    <dt><code>$string</code></dt>
    <dd>A string whose end should be checked against the other string.</dd>
    
    <dt><code>$ends_with</code></dt>
    <dd>The string the end of <code>$string</code> should be checked against.</dd>
    
    <dt><code>$ignore_case</code></dt>
    <dd>If set to <code>true</code>, the comparision is done case-insensitively.</dd>
</dl>

Returns whether the end matches the specified string.

###`string unescapeHtml( string $html )`

Decodes HTML entities.

Translates all HTML entities to characters. For example, `&amp;` gets converted to `&` and `&quot;` gets converted to `"`.  
For the conversion the value of [`GustavBase::ENC`](#string-enc) is used as charset and [HTML5](http://php.net/manual/en/string.constants.php#constant.ent-html5) is used as document type (as of PHP 5.4).

<dl>
    <dt><code>$html</code></dt>
    <dd>A string containing the HTML code whose HTML entities should be decoded.</dd>
</dl>

Returns the passed HTML code whose HTML entities have been decoded.

###`string escapeHtml( string $string )`

Encodes special HTML characters.

Translates special HTML characters (`"`, `'`, `&`, `<` and `>`) to HTML entities. For example, `&` gets converted to `&amp;` and `"` gets converted to `&quot;`. Already existing HTML entities such as `&quot;` are encoded, too. For example, `&quot;"` gets converted to `&amp;&quot;`.  
For the conversion the value of [`GustavBase::ENC`](#string-enc) is used as charset and [HTML5](http://php.net/manual/en/string.constants.php#constant.ent-html5) is used as document type (as of PHP 5.4). Invalid code unit sequences, also those [invalid in the used document type](http://php.net/manual/en/string.constants.php#constant.ent-disallowed), are [replaced](http://php.net/manual/en/string.constants.php#constant.ent-substitute) with a unicode replacement character (as of PHP 5.4).

<dl>
    <dt><code>$string</code></dt>
    <dd>A string whose special HTML characters should be encoded.</dd>
</dl>

Returns the passed string whose special HTML characters have been encoded.

###`string escapeRegex( string $string )`

Escapes special regular expression characters.

Prepends a backslash to special regular expression characters (`.`, `\`, `+`, `*`, `?`, `[`, `^`, `]`, `$`, `(`, `)`, `{`, `}`, `=`, `!`, `<`, `>`, `|`, `:` and `-`) and the delimiter used by Gustav (`/`).

<dl>
    <dt><code>$string</code></dt>
    <dd>A string whose special regular expression characters should be escaped.</dd>
</dl>

Returns the passed string whose special regular expression characters have been escaped.

###`string templ( string $template [, array $vars = array() [, bool $resolve_constants = true [, bool $unescape_placeholders = true ]]] )`

Resolves placeholders within a template string.

Placeholders have the form `"{{" constant "}}"` or `"{{$" variable "}}"`. `constant` and `variable` are names of constants or additional variables respectively. Placeholders are replaced with the string-representation of the corresponding value.  
Note that the part between the pairs of double braces isn't trimmed. Therefore `{{PHP_EOL}}` isn't the same as `{{ PHP_EOL}}`.  
If a constant or variable isn't defined, the sequence is kept as it has been defined.  
A placeholder can be escaped using a backslash following the first curly brace. The backslash gets removed and the placeholder is kept as it has been defined. If multiple backslashes are contained, only the first one gets removed.  
You may even use [class constants](http://php.net/manual/en/language.oop5.constants.php) in placeholders. When using a namespaced class or constant you should always use a [fully qualified name](http://php.net/manual/en/language.namespaces.rules.php) (`\My_NS\MY_CONST` for example) or a [qualified name](http://php.net/manual/en/language.namespaces.rules.php) (`My_NS\MY_CONST` for example) which is treated relatively to the [global namespace](http://php.net/manual/en/language.namespaces.global.php).  
For more information see [GvBlock option templating](GvBlock-option-templating).

<dl>
    <dt><code>$template</code></dt>
    <dd>A template string.</dd>
    
    <dt><code>$vars</code></dt>
    <dd>An associative array containing additional variables, using their names as keys.</dd>
    
    <dt><code>$resolve_constants</code></dt>
    <dd>If set to <code>false</code>, constants are not taken into account when resolving templates.</dd>
    
    <dt><code>$unescape_placeholders</code></dt>
    <dd>If set to <code>false</code>, backslashes are not removed from escaped placeholders.</dd>
</dl>

Returns the passed string with resolved placeholders.

###`string plain2html( string $plain )`

Converts plain text to HTML.

Replaces 2 subsequent spaces with a combination of a non-breaking and a simple space, horizontal tabs with 4 subsequent non-breaking spaces, spaces that follow on a newline with a non-breaking space and newline characters with `<br />` so that all whitespaces are visible when using it in HTML without wrapping the text into `<pre>` or using [CSS](https://developer.mozilla.org/en-US/docs/Web/CSS/white-space).  
Moreover this function replaces special HTML characters with HTML entities.

<dl>
    <dt><code>$plain</code></dt>
    <dd>The plain text to convert to HTML.</dd>
</dl>

Returns the passed text ready for usage in HTML.

###`string inline( string $text [, bool $is_html = true [, bool $use_semantic_meaning = true]] )`

Inlines a text or a HTML code.

Removes HTML tags of a HTML code on basis of their display and semantic meaning (if enabled), decodes HTML entities and inlines the resulting plain text. *Inlining* means that whitespaces that aren't simple spaces (such as horizontal tabs or linefeed characters) are replaced with one.  
If the content is treated as HTML code, sequences of whitespaces are stripped to 1 space.  
For more information read the comments in the source code of this function.

<dl>
    <dt><code>$text</code></dt>
    <dd>The plain text or HTML code to inline.</dd>
    
    <dt><code>$is_html</code></dt>
    <dd>Defines whether the passed text is a HTML code. If set to <code>true</code>, HTML tags within the passed HTML code are removed and HTML entities are decoded to real characters. Moreover sequences of whitespaces are stripped to 1 space. The described behavior affects only tags and entities that are not placed within a <code>&lt;plaintext&gt;</code> element.</dd>
    
    <dt><code>$use_semantic_meaning</code></dt>
    <dd>If set to <code>true</code>, HTML tags within the passed HTML code are, unlike removing them only because of their display, treated on basis of their semantic meaning.</dd>
</dl>

Returns the passed text or HTML code converted to an inline plain text.

###`mb_strtoupper()`

This function is just an alias for the corresponsing multibyte string function.  
When calling this function without specifying a value for the last parmeter, the character encoding definition, that parameter is set to the character encoding used by Gustav ([`GustavBase::ENC`](#string-enc)).  
This has the same effect as setting the MB character encoding globally using [`mb_internal_encoding()`](http://php.net/manual/en/function.mb-internal-encoding.php). The difference is that by doing it this way the user can still define his own global MB character encoding without interrupting Gustav.  
For more information see [mb_strtoupper()](http://php.net/manual/en/function.mb-strtoupper.php).

###`mb_strtolower()`

This function is just an alias for the corresponsing multibyte string function.  
When calling this function without specifying a value for the last parmeter, the character encoding definition, that parameter is set to the character encoding used by Gustav ([`GustavBase::ENC`](#string-enc)).  
This has the same effect as setting the MB character encoding globally using [`mb_internal_encoding()`](http://php.net/manual/en/function.mb-internal-encoding.php). The difference is that by doing it this way the user can still define his own global MB character encoding without interrupting Gustav.  
For more information see [mb_strtolower()](http://php.net/manual/en/function.mb-strtolower.php).

###`mb_strlen()`

This function is just an alias for the corresponsing multibyte string function.  
When calling this function without specifying a value for the last parmeter, the character encoding definition, that parameter is set to the character encoding used by Gustav ([`GustavBase::ENC`](#string-enc)).  
This has the same effect as setting the MB character encoding globally using [`mb_internal_encoding()`](http://php.net/manual/en/function.mb-internal-encoding.php). The difference is that by doing it this way the user can still define his own global MB character encoding without interrupting Gustav.  
For more information see [mb_strlen()](http://php.net/manual/en/function.mb-strlen.php).

###`mb_strpos()`

This function is just an alias for the corresponsing multibyte string function.  
When calling this function without specifying a value for the last parmeter, the character encoding definition, that parameter is set to the character encoding used by Gustav ([`GustavBase::ENC`](#string-enc)).  
This has the same effect as setting the MB character encoding globally using [`mb_internal_encoding()`](http://php.net/manual/en/function.mb-internal-encoding.php). The difference is that by doing it this way the user can still define his own global MB character encoding without interrupting Gustav.  
For more information see [mb_strpos()](http://php.net/manual/en/function.mb-strpos.php).

###`mb_substr()`

This function is just an alias for the corresponsing multibyte string function.  
When calling this function without specifying a value for the last parmeter, the character encoding definition, that parameter is set to the character encoding used by Gustav ([`GustavBase::ENC`](#string-enc)).  
This has the same effect as setting the MB character encoding globally using [`mb_internal_encoding()`](http://php.net/manual/en/function.mb-internal-encoding.php). The difference is that by doing it this way the user can still define his own global MB character encoding without interrupting Gustav.  
For more information see [mb_substr()](http://php.net/manual/en/function.mb-substr.php).

###`mb_substr_count()`

This function is just an alias for the corresponsing multibyte string function.  
When calling this function without specifying a value for the last parmeter, the character encoding definition, that parameter is set to the character encoding used by Gustav ([`GustavBase::ENC`](#string-enc)).  
This has the same effect as setting the MB character encoding globally using [`mb_internal_encoding()`](http://php.net/manual/en/function.mb-internal-encoding.php). The difference is that by doing it this way the user can still define his own global MB character encoding without interrupting Gustav.  
For more information see [mb_substr_count()](http://php.net/manual/en/function.mb-substr-count.php).

###`preg_match_all()`

This function is just an alias for the corresponsing PCRE function.  
The differences between that function and this function are the following.

+   The parameter defining the flags defaults to [`PREG_SET_ORDER`](http://php.net/manual/en/pcre.constants.php#constant.preg-set-order), not [`PREG_PATTERN_ORDER`](http://php.net/manual/en/pcre.constants.php#constant.preg-pattern-order) as it does natively.
+   The native PCRE function doesn't support [capturing of offsets](http://php.net/manual/en/pcre.constants.php#constant.preg-offset-capture) in multibyte strings properly. It captures the number of bytes preceding the match, rather than the number of characters. This function fixes that problem.
+   The parameter accepting a variable to contain the matches is not required.

For more information see [preg_match_all()](http://php.net/manual/en/function.preg-match-all.php).

###`preg_match()`

This function is just an alias for the corresponsing PCRE function.  
The differences between that function and this function are the following.

+   The native PCRE function doesn't support [capturing of offsets](http://php.net/manual/en/pcre.constants.php#constant.preg-offset-capture) in multibyte strings properly. It captures the number of bytes preceding the match, rather than the number of characters. This function fixes that problem.

For more information see [preg_match()](http://php.net/manual/en/function.preg-match.php).

###`string[] arrayUnique( string[] $strings [, bool $lowercase_strings = false ] )`

Makes an array to contain only one occurrence of a string.

The strings are compared case-insensitively and only the first occurrence of a string is kept (in its original case).  
The returned array's keys aren't preserved. The array is reindexed using numeric keys.

<dl>
    <dt><code>$strings</code></dt>
    <dd>An array containig strings that should be made unique.</dd>
    
    <dt><code>$lowercase_strings</code></dt>
    <dd>If set to <code>true</code>, all items are lowercased.</dd>
</dl>

Returns the passed array containing unique strings only.

###`string stripPath( string|string[] $path [, string|string[]|null $remove_path = null ] )`

Strips away a path from the beginning of another path.  
First a trailing directory separator is removed from the end of `$remove_path`. After that the two paths are compared. If they match, a directory separator is returned.  
Otherwise, a directory separator is appended to `$remove_path` again and the resulting path is stripped away from the beginning of `$path`.  
The returned path always has a leading directory separator. The passed paths should have one, too. If they haven't, one is prepended.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path to remove the other one from. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
    
    <dt><code>$remove_path</code></dt>
    <dd>
        The path to remove from the beginning of <code>$path</code>. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.<br />
        Is set to <code>null</code>, the value of <a href="http://php.net/manual/en/reserved.variables.server.php"><code>$_SERVER["DOCUMENT_ROOT"]</code></a> is used.
    </dd>
</dl>

Returns the stripped path.

###`string path( string[]|string $path_segment [, string[]|string $path_segment [, string[]|string ... ]] )`

Fixes or builds a path.

This function is similar to PHP's [`realpath()` funtion](http://php.net/manual/en/function.realpath.php). But unlike that function, this function doesn't take the real filesystem into account.  
Takes a variable number of arguments, being either arrays or strings containing path segments to be joined using the [`DIRECTORY_SEPARATOR` constant](http://php.net/manual/en/dir.constants.php#constant.directory-separator).  
Various actions to fix a broken path are performed:

+   Multiple subsequent directory separators are stripped down to one.
+   Occurrences of `.` path segments are removed.
+   Occurrences of `..` path segments are removed together with their preceding path segment. If no preceding path segment exist, since the `..` is the first path segment, nothing is removed.

The returned path always starts with a directory separator.  
Calling this function with empty arrays as the only parameters will return a directory separator (string).

<dl>
    <dt><code>$path_segment</code></dt>
    <dd>An array of strings containing path segments or a string representing a single path segment.</dd>
</dl>

Returns a path built from the passed path segments.

###`string path2url( string|string[] $path [, bool $path_includes_doc_root = true ] )`

Converts an OS-specific path into an URL path (i.e. a relative URL).

Replaces directory separators in the passed path with `/` (essential for windows users) and calls [`rawurlencode()`](http://php.net/manual/en/function.rawurlencode.php) on the path segments. Moreover this function removes the document root from the beginning of the path and appends a `/` to the URL path if the path points on a directory.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path to convert to an URL path. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
    
    <dt><code>$path_includes_doc_root</code></dt>
    <dd>If set to <code>false</code>, the document root is prepended to the path. It's removed again before converting it.</dd>
</dl>

Returns an URL path built from the passed path.

###`string url2path( string $url_path [, bool $prepend_doc_root = true ] )`

Converts an URL path into an OS-specific path.

Replaces all occurences of `/` within the passed path with a directory separator (essential for windows users) and calls [`rawurldecode()`](http://php.net/manual/en/function.rawurldecode.php) on the path. The returned path always has a leading directory separator.

<dl>
    <dt><code>$url_path</code></dt>
    <dd>The URL path to convert to an OS-specific one.</dd>
    
    <dt><code>$prepend_doc_root</code></dt>
    <dd>If set to <code>true</code>, the path of the document root is prepended.</dd>
</dl>

Returns an OS-specific path built from the passed URL path.

###`int short2byte( string $shorthand )`

Converts a shorthand byte value into a real byte value.

You can get the most information on this topic in the PHP's [FAQ on using PHP](http://php.net/manual/en/faq.using.php#faq.using.shorthandbytes).  
Additionally this function accepts spaces between the number and the symbol as well as spaces wrapping the whole shorthand value.  
Moreover it supports invalid symbols by ignoring them and treating the specified number as bytes.  
If no number is specified, `0` is used instead unless a valid symbol is included, in that case `1` (multiplied by the symbol) is used.  
The case of the symbol doesn't matter, it is handles case-insensitively.
Negative numbers aren't supported. Instead `0` is used and the whole term is treated as a symbol. Since no symbol starting with `-` exists, the function would return `0`.

<dl>
    <dt><code>$shorthand</code></dt>
    <dd>The shorthand byte value.</dd>
</dl>

Returns the calculated number of bytes described by the shorthand value.

###`bool|null checkIni( string $option, bool|null|mixed $value )`

Checks an INI option's value.

Compares the value of an INI option with a specified value.  
If the option doesn't exist, `null` is returned.  
If a boolean value has been passed to `$value`, `true` is returned if the option's value matches the passed one. The values `on` and `1` match `true` while `off` and `0` (sometimes [`ini_get()`](http://php.net/manual/en/function.ini-get.php) also returns an empty string) match `false`.  
When comparing the option's value with `null`, `true` is returned if the option contains a null value ([`ini_get()`](http://php.net/manual/en/function.ini-get.php) returns an empty string).  
Any other value passed to `$value` is casted as a string and gets compared to the option's value.  
In any other case this function returns `false`.

<dl>
    <dt><code>$option</code></dt>
    <dd>The INI option's name.</dd>
    
    <dt><code>$value</code></dt>
    <dd>The value to check the option's value against.</dd>
</dl>

Returns whether the option's values matches the specified one or `null` if the option doesn't exist.

###`void header( string $header [, int|null $status_code = null ] )`

Checks whether the header [has already been sent](http://php.net/manual/en/function.headers-sent.php) back to the client and, if not, adds the specified HTTP header field to the header. Already defined header fields are overwritten.

<dl>
    <dt><code>$header</code></dt>
    <dd>The header field and value to be appended to the HTTP response header.</dd>
    
    <dt><code>$status_code</code></dt>
    <dd>If not <code>null</code>, the status code of the response is set to this parameter's value.</dd>
</dl>

###`array getSuperglobals()`

Returns all [supergloabls](http://php.net/manual/en/language.variables.superglobals.php).

The returned value is an associative array containing the superglobals' names as keys and their values as values. Usually these should be the following variables.

+   [`$GLOBALS`](http://php.net/manual/en/reserved.variables.globals.php)
+   [`$_SERVER`](http://php.net/manual/en/reserved.variables.server.php)
+   [`$_GET`](http://php.net/manual/en/reserved.variables.get.php)
+   [`$_POST`](http://php.net/manual/en/reserved.variables.post.php)
+   [`$_FILES`](http://php.net/manual/en/reserved.variables.files.php)
+   [`$_COOKIE`](http://php.net/manual/en/reserved.variables.cookies.php)
+   [`$_REQUEST`](http://php.net/manual/en/reserved.variables.request.php)
+   [`$_ENV`](http://php.net/manual/en/reserved.variables.environment.php)

If currently a session is running, also [`$_SESSION`](http://php.net/manual/en/reserved.variables.session.php) is available as a superglobal.

Returns an associative array containing the superglobals.

###`array validateVars( array $variables )`

Validates variable names.

Takes an associative array of variables and checks whether their names (the array's keys) are valid variable names. If they aren't, they are filtered out of the array.  
[Superglobals](http://php.net/manual/en/language.variables.superglobals.php)' names are considered to be invalid.  
For more information on variables and how valid variable names look like see the [PHP documentation](http://php.net/manual/en/language.variables.basics.php) on variables.

<dl>
    <dt><code>$variables</code></dt>
    <dd>An associative array containing the variables' names as keys and their values as values.</dd>
</dl>

Returns the passed array with the invalid variables filtered out.

###`string|false readFile( string|string[] $path [, bool $is_url = false [, array|mixed|null $execution_arguments = null [, bool $use_cache = true ]]] )`

Reads a file's content.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the file to read the content from. If <code>$is_url</code> is set to <code>false</code>, this value is passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>. If it's set to <code>true</code>, this value should be an absolute URL. If not an URL and if the specified file doesn't exist, <code>false</code> is returned.</dd>
    
    <dt><code>$is_url</code></dt>
    <dd>When setting this parameter to <code>true</code>, <code>$path</code> sould be am absolute URL. If set to <code>true</code>, the file is read using <a href="http://php.net/manual/en/function.file-get-contents.php"><code>file_get_contents()</code></a>, regardless of the value of <code>$execution_arguments</code>. In fact, that parameter is igroned when this parameter is set to <code>true</code>. If <a href="http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen"><code>allow_url_fopen<code></a> isn't enabled, <code>false</code> is returned.</dd>
    
    <dt><code>$execution_arguments</code></dt>
    <dd>
        Setting this parameter to a value different than <code>null</code> means that the file is executed before getting it's content. This is done by using <a href="http://php.net/manual/en/function.include.php"><code>include</code></a> (instead of <a href="http://php.net/manual/en/function.file-get-contents.php"><code>file_get_contents()</code></a>) and does therfore work only with PHP files, otherwise no execution will happen and the file's content is used as defined.<br />
        If an array is passed to this parameter, each of the array's values whose key is a <a href="Private-API%3a-GustavBase#array-validatevars-array-variables-">valid variablename</a> is made available as a variable named like the key and can be used inside of the included file. Also <a href="http://php.net/manual/en/language.variables.superglobals.php">superglobals</a> are available in the included file.<br />
        An <code>include<code>d file should not return any value.<br />
        If <code>$is_url</code> is set to <code>true</code>, this parameter is ignored completely.
    </dd>
    
    <dt><code>$use_cache</code></dt>
    <dd>If set to <code>true</code>, the content is written into and taken from (if present) a <a href="Dev-API%3a-GustavBase#private-string-readfilecache">Gustav-internal cache</a>. Using the cache is only available when <code>$is_url</code> is set to <code>false</code> and <code>$execution_arguments</code> is set to <code>null</code>.</dd>
</dl>

Returns the file's content on success or `false` on failure.

###`file_put_contents()`

This function is just an alias for PHP's [`file_put_contents()`](http://php.net/manual/en/function.file-put-contents.php) function.  
The differences between that function and this function are the following.

+   Unexisting directories in the dirname of the path are created.
+   No warnings are raised.

###`bool rm( string|string[] $path )`

Deletes a file, directory or a symbolic link.
If the item, passed to this function, doesn't exist, the operation is considered to be successful.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the item to remove. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

Returns whether the item has been removed successfully.

###`string[] scandir( string|string[] $path [, int $types = GustavBase::SCANDIR_TYPES ] )`

Scans a directory.

Returns an array containing absolute paths of all items found in the specified directory (direct children only, not general descendants) matching one of the specified types.  
`.` and `..` items are filtered out.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the directory to scan. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
    
    <dt><code>$types</code></dt>
    <dd>A bitmask consisting of several <code>GustavBase::SCANDIR_TYPE_*</code> constants specifying which kinds of items should be included in the returned array.</dd>
</dl>

Returns an array containing paths of all items found in the specified directory matching one of the specified types.

###`bool cleandir( string|string[] $path )`

Empties a directory.

Deletes all symbolic links, files, as well as subdirectories of a directory recursively.  
Deletes as much items as it can. If the deletion of one item fails it won't stop its task. However, it will mark the operation as failed.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the directory to be emptied. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

Returns whether emptying the directory was successful.

###`bool mkdir( string|string[] $path )`

Creates a directory.

Creates a directory recursively which means that every non-existing directory within the dirname of the specified directory is created, too.  
Every directory is created using the mode `777`. However, already existing directories may have another mode.

<dl>
    <dt><code>$path</code></dt>
    <dd>The path of the directory to create. Gets passed to <a href="#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
</dl>

Returns whether creating the directories was successful.



##Constants

###`string ENC`

The character encoding used by Gustav for generating the destination files, declaring the charset in the `Content-Type` HTTP header field, reading the source files and working with MB functions.  
Must be one of the [IANA charset names](http://www.iana.org/assignments/character-sets/character-sets.xhtml) compatible with [`htmlspecialchars()`](http://php.net/manual/en/function.htmlspecialchars.php), [`html_entity_decode()`](http://php.net/manual/en/function.html-entity-decode.php), [`htmlentities()`](http://php.net/manual/en/function.htmlentities.php) and [`get_html_translation_table()`](http://php.net/manual/en/function.get-html-translation-table.php), as well as the MB functions' [supported encodings](http://php.net/manual/en/mbstring.supported-encodings.php).

###`int SCANDIR_TYPE_FILE`

Include files.

If included in the bitmask of [`GustavBase::scandir()`](#string-scandir-stringstring-path--int-types--gustavbasescandir_types--)'s second parameter that function's returned array will contain files (if there are some).
    
###`int SCANDIR_TYPE_DIR`

Include directories.

If included in the bitmask of [`GustavBase::scandir()`](#string-scandir-stringstring-path--int-types--gustavbasescandir_types--)'s second parameter that function's returned array will contain directories (if there are some).
    
###`int SCANDIR_TYPE_LINK`

Include symbolic links.

If included in the bitmask of [`GustavBase::scandir()`](#string-scandir-stringstring-path--int-types--gustavbasescandir_types--)'s second parameter that function's returned array will contain symbolic links (if there are some).
    
###`int SCANDIR_TYPES`

Include all types of items.

Use this constant as value for [`GustavBase::scandir()`](#string-scandir-stringstring-path--int-types--gustavbasescandir_types--)'s second parameter, to get an array containing items of all supported types (if there are some).
