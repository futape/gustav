##Static functions

###`mixed|null getConf( string $setting )`

Get a [configuration option](Gustav-configuration)'s value.

<dl>
    <dt><code>$setting</code></dt>
    <dd>The configuration option's name.</dd>
</dl>

Returns the configuration option's value or `null` if it doesn't exist.

###`bool setup()`

Sets up Gustav.

Creates the directories specified by the configuration options [`src_dir`](Gustav-configuration#string-src_dir), [`dest_dir`](Gustav-configuration#string-dest_dir) and [`templs_dir`](Gustav-configuration#string-templs_dir) if they don't exist and prepares or creates the destination directory's `.htaccess` file to [handle the creation](Automatic-generation-of-destination-files) of non-existing destination files on request (set [`ErrorDocument 404`](http://httpd.apache.org/docs/2.4/mod/core.html#errordocument) to `generate.php` and setting a few [`mod_rewrite`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html)-specific directives) and to show the right files when a directory is requested ([`DirectoryIndex`](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#directoryindex), as well as [`DirectorySlash`](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#directoryslash)). The directives are appended to the end of the `.htaccess` file.  
If the [`use_fallback_resource` configuration options](Gustav-configuration#bool-use_fallback_resource--false) is set tu `true`, Apache's [`FallbackResource` directive](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#fallbackresource) is used instead of `ErrorDocument`, `mod_rewrite`.

Returns whether all operations were successful.

###`bool reset()`

Resets Gustav.

Removes all directories, files and symbolic links within the destination directory leaving an empty directory.  
Then calls [`Gustav::setup()`](#bool-setup).

Returns whether emptying the destination directory and setting up Gustav was successful.

###`string[] query( [ string|string[] $src_directory = "" [, bool $recursive = true [, array|null $filters = null [, int $filters_operator = Gustav::FILTER_AND [, int $order_by = Gustav::ORDER_PUB [, int $min_match_score = 0 [, bool $include_disabled = false [, $include_hidden_directory = false ]]]]]]]] )`

Get matching [source files](Source-files).

Creates and returns an array of matching source files' paths.  
By default [disabled source files](Disabled-source-files), as well as source files for which no [`GustavSrc`](API#gustavsrc) object can be created, and source files located in a directory called `__hidden` or in one of its subdirectories are ignored and are not included in the returned array.

<dl>
    <dt><code>$src_directory</code></dt>
    <dd>The path (relative to the source directory) of the directory to start searching for source files in.</dd>
    
    <dt><code>$recursive</code></dt>
    <dd>If set to <code>true</code>, source files placed in the subdirectories of the specified directory are included, too (if matching the filters).
    
    <dt><code>$filters</code></dt>
    <dd>
        An associative array containing filters a source file must match to be included in the resulting array. The following filters are available.
        
<pre><code>array(
    "match"=&gt;array(
        "flags"=>0,
        "file"=&gt;array(),
        "title"=&gt;array(),
        "tags"=&gt;array()
    ),
    "prop"=&gt;array(
        "_hidden"=&gt;true,
        "_templ"=&gt;array("file_uses_this_templates", "or_this_one", "or_both")
    ),
    "conv"=&gt;array("text", "md"),
    "dest"=&gt;"/dest/category/hello-world/",
    "newer_than"=&gt;time(),
    "older_than"=&gt;time()
)</code></pre>

        <dl>
            <dt><code>string[][] match</code></dt>
            <dd>
                Creates a <a href="API#gustavmatch"><code>GustavMatch</code></a> object for the source file using this filter's value as value for that class's <a href="Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--">constructor</a>'s second parameter and this filter's <code>flags</code> item (if specified) as its third parameter's value. A source file is considered to match this filter if it matches at least one of the filter's items (i.e. the match score is greater than 0). If an empty array is passed to one of this array's items or if the item's key is invalid, that item is ignored. If the passed array doesn't contain any valid items or if no <a href="API#gustavmatch"><code>GustavMatch</code></a> object can be created, the entire filter is ignored.<br />
                Supported keys are <code>file</code> (<a href="Public-API%3a-GustavBase#string-key_file"><code>GustavBase::KEY_FILE</code></a>), <code>title</code> (<a href="Public-API%3a-GustavBase#string-key_title"><code>GustavBase::KEY_TITLE</code></a>) and <code>tags</code> (<a href="Public-API%3a-GustavBase#string-key_tags"><code>GustavBase::KEY_TAGS</code></a>).
            </dd>

            <dt><code>string[][]|(string|bool)[] prop</code></dt>
            <dd>
                Accepts any <a href="GvBlock-options">GvBlock option</a>. Use the option's name as key and the value to match the option's value against as value. Each <code>prop</code> filter is considered to be a single, standalone filter.<br />
                If a boolean is set as value, this function checks whether a source file's <a href="GvBlock">GvBlock</a> contains the option, if <code>true</code>, or not, if <code>false</code>.<br />
                Any other type of value is converted to an array or is left as it is if it's already an array. The same applies to the corresonding options's value. If (originally) an empty array has been specified, the filter matches only if the options's (original) value is an empty array, too. Otherwise this function looks for intersections (case-sensitively for strings) of the options's array (original value or converted) and the filter's array (original value or converted). If the array isn't empty, only one intersection needs to be found, to consider the source file to match this <code>prop</code> filter's item. This corresponds to the <a href="#int-filter_or">OR operator</a>. This behavior cannot be changed using this function's <code>$filters_operator</code> parameter.<br />
                If the option isn't contained in the GvBlock at all, the source file doesn't match this filter's item.
            </dd>

            <dt><code>string[] conv</code></dt>
            <dd>
                This filter is an extended version of <code>"prop"=&gt;array("_conv"=&gt;array())</code>.<br />
                Unlike the <code>prop</code> version, this filter doesn't only match source files that use one of the <em>specified</em> converter names but also matches such that use a different name (an alias) for the same converter. Moreover the comparision is done case-insensitively. This applies only to <a href="Converting-source-content#hardcoded-converters">hardcoded converters</a>. <a href="User-defined-converters">User-defined converters</a> are compared case-sensitively and without any consideration of aliases.<br />
                For example, <code>"conv"=&gt;array("text", "md")</code> would match all source files using any name of the <a href="Converting-source-content#the-plain-text-converter-txttextplain">hardcoded text converter</a> (<a href="Private-API%3a-Gustav#string-conv_text><code>Gustav::CONV_TEXT</code></a>, case-insensitively) or a user-defined markdown converter named <code>md</code> (case-sensitively) but not a converter named <code>markdown</code> since <code>md</code> it's a user-defined converter and can therefore not have any aliases.<br />
                If an empty array is passed to this filter, the filter is ignored.
            </dd>

            <dt><code>string|string[] dest</code></dt>
            <dd>
                This filter is an extended version of <code>"prop"=&gt;array("_dest"=&gt;array())</code>.<br />
                In addition to the <code>prop</code> version, this filter also takes the result of <a href="Public-API%3a-GustavDest#string-getpath"><code>GustavDest::getPath()</code></a> for the source file into account.<br />
                A source file matches this filter if its GvBlock's <a href="Gustav-core-options#_dest"><code>_dest</code> option</a>'s value matches the filter value or if that option's value ends with a directory separator and the filter value matches that value with the trailing directory separator stripped away.<br />
                Source files, whose corresponding <a href="API#gustavdest"><code>GustavDest</code></a> object's <a href="Public-API%3a-GustavDest#string-getpath"><code>getPath()</code></a> method's return value matches the filter value are considered to be successfully matched, too.<br />
                If the source file's <code>_dest</code> option doesn't match and if no <code>GustavDest</code> object can be created for that file, the source file doesn't match this filer.<br />
                This filter expects a string value containing a full path, including the filename, or just the dirname of a <a href="Generating-destination-files#generating-the-destination-path">destination file's path</a> (assuming that its filename is <code>index.*</code>). The path has to be relative to the document root. It is passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.
            </dd>

            <dt><code>int newer_than</code></dt>
            <dd>If the <a href="Gustav-core-options#_pub"><code>_pub</code> option</a> of a source file's GvBlock is set and is greater than the value passed to this filter, the file matches the filter.</dd>

            <dt><code>int older_than</code></dt>
            <dd>If the <a href="Gustav-core-options#_pub"><code>_pub</code> option</a> of a source file's GvBlock isn't set or if it's lower than or equal to the value passed to this filter, the file matches the filter.</dd>
        </dl>
             
        If set to <code>null</code>, a default filter of <code>array("prop"=&gt;array("_hidden"=&gt;false), "older_than"=&gt;time())</code> is used.<br />
        To get all source files without filtering them, use an empty array or one containing unsupported items only.
    </dd>

    <dt><code>$filters_operator</code></dt>
    <dd>
        Use one of the <code>Gustav::FILTER_*</code> constants as this parameter's value.<br />
        If set to <a href="#int-filter_and"><code>Gustav::FILTER_AND</code></a>, a source file must match all specified (and supported) filters to be included in the resulting array. Setting this parameter to <a href="#int-filter_or"><code>Gustav::FILTER_OR</code></a> means that a source file must match at least one (supported) filter to be included in the resulting array.
    </dd>

    <dt><code>$order_by</code></dt>
    <dd>
        Defines how to sort the matching source files. Use one of the <code>Gustav::ORDER_*</code> constants as this parameter's value.<br />
        If set to <a href="#int-order_pub"><code>Gustav::ORDER_PUB</code></a> or <a href="#int-order_pub_asc"><code>Gustav::ORDER_PUB_ASC</code></a>, source files whose GvBlock doesn't contain the <a href="Gustav-core-options#_pub"><code>_pub</code> option</a> are moved to the end of the returned array, regardless of whether the sorting is ascending or descending.
    </dd>

    <dt><code>$min_match_score</code></dt>
    <dd>
        Defines a percentage value relative to the highest match score of all matching source files. Source files whose match score is lower than the defined minimum percentage of the maximum match score are ignored and removed from the resulting array. Setting this parameter to <code>0</code>, disables this filter and keeps all matching source files in the array.<br />
        Theoretically this parameter's value can be any number, even a float. However, regarding to this documentation, only an integer value is supported.
    </dd>

    <dt><code>$include_disabled</code></dt>
    <dd>If set to <code>true</code>, <a href="Disabled-source-files">disabled source files</a> are no longer ignored.</dd>
    
    <dt><code>$include_hidden_directory</code></dt>
    <dd>If set to <code>true</code>, source files located in a <code>__hidden</code> directory or in one of its subdirectories are no longer ignored.</dd>
</dl>

Returns an array containing the matching source files' paths.

###`GustavMatch[] search( string $search_term [, string $directory = "" [, bool $search_recursive = true [, int|null $search_members = null [, int $match_flags = 0 [, int $min_score = 0 ]]]]] )`

Search for [source files](Source-files) matching a search term.

The matching source files are ordered by their match scores. [Disabled source files](Disabled-source-files) and such located in a `__hidden` directory are ignored. Besides the `match` filter, the default filter for [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--) is used.

<dl>
    <dt><code>$search_term</code></dt>
    <dd>The search term to search for in the source files' properties.</dd>
    
    <dt><code>$directory</code></dt>
    <dd>The path of the directory to search in for matching source files. The path is treated relatively to the source directory and is passed to <a href="#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--"><code>Gustav::query()</code></a> which in turn calls <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a> on the path.</dd>
    
    <dt><code>$search_recursive</code></dt>
    <dd>Specifies whether to include all subdirectories of the specified directory when searching for source files.</dd>
    
    <dt><code>$search_members</code></dt>
    <dd>
        Defines the source-file-properties to match the search term items against. The value for this parameter should be a bitmask of <a href="#constants"><code>Gustav::SEARCH_*</code></a> constants.<br />
        If set to <code>null</code>, a value of <code><a href="#int-search_tags">Gustav::SEARCH_TAGS</a>|<a href="#int-search_title">Gustav::SEARCH_TITLE</a>|<a href="#int-search_file">Gustav::SEARCH_FILE</a></code> is used instead.
    </dd>
    
    <dt><code>$match_flags</code></dt>
    <dd>The flags passed to the <a href="Public-API%3a-GustavMatch#void-__construct-stringstring-path-string-search--int-flags--0--"><code>GustavMatch</code> constructor</a>.</dd>
    
    <dt><code>$min_score</code></dt>
    <dd>Defines a percentage value, relative to the highest match score of all matching source files. Source files whose match score is lower than the specified minimum percentage are removed from the resulting array.</dd>
</dl>

Returns an array of [`GustavMatch`](API#gustavmatch) object for the matching source files.

###`int[] getTags()`

Get all available [tags](Gustav-core-options#_tags).

Tags that differ only in their letters' cases are merged and the version with the most occurrences in all [source files](Source-files)' tags is used.  
The returned array contains only tags used by at least one source file matching the default filter of [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

Returns an associative array containing the tags' names as keys and their numbers of occurrences as values. The tags are ordered by their numbers of occurrences.

###`array[] getCategories()`

Get all available [categories](Public-API%3a-GustavSrc#string-getcategory).

The returned array contains only the categories containing (directly or within their subcategories/-directories) at least one [source file](Source-files) matching the default filter of [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

Returns an array containing the categories' relative URL (root-relative) as keys and arrays containing information on the category as well as its subcategories as values. The categories are ordered by their names. An array returned by this function looks like the following.

    array(
        "/blog/"=>array(
            "root"=>true,
            "count"=>3,
            "name"=>"blog",
            "sub"=>array()
        )
    )

<dl>
    <dt><code>bool root</code></dt>
    <dd><code>true</code> for the destination directory's root, ortherwise <code>false</code>.</dd>

    <dt><code>int count</code></dt>
    <dd>The number of source files that are direct children of this directory and that are matching the default filter of <a href="#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--"><code>Gustav::query()</code></a>.</dd>

    <dt><code>string name</code></dt>
    <dd>The name of the category (i.e. its filename).</dd>

    <dt><code>array[] sub</code></dt>
    <dd>The category's subategories. The array has the same structure as the one above.</dd>
</dl>



##Constants

###`string CONF_DEST_DIR`

The name of the [configuration option](Gustav-configuration#string-dest_dir) specifying the path of the destination directory.

###`string CONF_SRC_DIR`

The name of the [configuration option](Gustav-configuration#string-src_dir) specifying the path of the source directory.

###`string CONF_TEMPLS_DIR`

The name of the [configuration option](Gustav-configuration#string-templs_dir) specifying the path of the directory containing the template files.

###`string CONF_404_DOC`

The name of the [configuration option](Gustav-configuration#string-404_error_doc--) specifying the URL of the error document used for Gustav-404-errors.

###`string CONF_PREFERRED_CONVS`

The name of the [configuration option](Gustav-configuration#string-preferred_convs--) specifying a list of preferred converters, used for [deciding which source file to use](Automatic-generation-of-destination-files#choosing-a-matching-source-file) when auto-generating a destination file and no unambiguous source file could be figured out.

###`string CONF_LOG_MAX_SIZE`

The name of the [configuration option](Gustav-configuration#stringint-log_file_max_size---1) specifying the maximum file size of a [log-file](Log-files).

###`string CONF_EXIT_ON_ERROR`

The name of the [configuration option](Gustav-configuration#bool-exit_on_error--true) specifying whether to stop the execution of a script when a Gustav-specific error happens.

###`string CONF_CHECK_STATUS`

The name of the [configuration option](Gustav-configuration#bool-check_status--true) specifying whether to [check Gustav](Dev-API%3a-Gustav#public-void-init) for a proper setup when including [`Gustav.php`](API#gustav).

###`string CONF_SITE_URL`

The name of the [configuration option](Gustav-configuration#string-site_url--requested-site) specifying an absolute URL of the site Gustav is running on.

###`string CONF_ENABLE_LOG`

The name of the [configuration option](Gustav-configuration#bool-enable_log--true) specifying whether to write errors, warnings and success messages to the [log-file](Log-files).

###`string CONF_GEN_SEARCH_RECURSIVE`

The name of the [configuration option](Gustav-configuration#bool-generator_search_recursive--false) specifying whether to search in all subdirectories of the source directory when [generating a destination file](Automatic-generation-of-destination-files) using [`GustavGenerator::genByUrl()`](Public-API%3a-GustavGenerator#void-genbyurl-string-dest_url--bool-search_recursive--false--bool-print_content--false--) (i.e. `generate.php`).

###`string CONF_REPLACE_DIR_SEP`

The name of the [configuration option](Gustav-configuration#string-replace_directory_separator--) specifying a character to replace with the directory separator ([`DIRECTORY_SEPARATOR`](http://php.net/manual/en/dir.constants.php#constant.directory-separator)). If an empty string, nothing is replaced.

###`string CONF_FALLBACK_RESOURCE`

The name of the configuration option, specifying whether to use Apache's [`FallbackResource` configuration option](http://httpd.apache.org/docs/2.4/mod/mod_dir.html#fallbackresource). If disabled, a combination of [`mod_rewrite`](http://httpd.apache.org/docs/2.4/mod/mod_rewrite.html) and [`ErrorDocument`](http://httpd.apache.org/docs/2.4/mod/core.html#errordocument) is used instead.

###`int ORDER_NONE`

Don't order matching source files. See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int ORDER_MATCH`

Orders matching source files by their match scores (descending). See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int ORDER_MATCH_ASC`

Orders matching source files by their match scores (ascending). See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int ORDER_PUB`

Orders matching source files by their dates of publication (descending). See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int ORDER_PUB_ASC`

Orders matching source files by their dates of publication (ascending). See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int ORDER_RAND`

Orders matching source files randomly. See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int FILTER_OR`

Filters source files using the OR operator. See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int FILTER_AND`

Filters source files using the AND operator. See [`Gustav::query()`](#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--).

###`int SEARCH_TAGS`

Search for search term items in source files' tags. See [`Gustav::search()`](#gustavmatch-search-string-search_term--string-directory----bool-search_recursive--true--intnull-search_members--null--int-match_flags--0--int-min_score--0--).

###`int SEARCH_TITLE`

Search for search term items in source files' titles. See [`Gustav::search()`](#gustavmatch-search-string-search_term--string-directory----bool-search_recursive--true--intnull-search_members--null--int-match_flags--0--int-min_score--0--).

###`int SEARCH_FILE`

Search for search term items in destination files' filenames. See [`Gustav::search()`](#gustavmatch-search-string-search_term--string-directory----bool-search_recursive--true--intnull-search_members--null--int-match_flags--0--int-min_score--0--).
