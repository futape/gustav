##Static functions

###`mixed|null getConf( string $setting )`

Get a configuration option's value.

<dl>
    <dt><code>$setting</code></dt>
    <dd>The configuration option's name.</dd>
</dl>

Returns the configuration option's value or `null` if it doesn't exist.

###`bool setup()`

Sets up Gustav.

Creates the directories specified by the configuration options `src_dir`, `dest_dir` and `templs_dir` if they don't exist and prepares or creates the destination directory's `.htaccess` file to handle the creation of non-existing destination files on request (set `ErrorDocument 404` to `generate.php`) and to show the right files when a directory is requested (`DirectoryIndex`). The directives are appended to the end of the `.htaccess` file.

Returns whether all operations were successful.

###`bool reset()`

Resets Gustav.

Removes all directories, files and symbolic links within the destination directory leaving an empty directory.  
Then calls `Gustav::setup()`.

Returns whether emptying the destination directory and setting up Gustav was successful.

###`string[][] query( [ string|string[] $src_directory = "" [, bool $recursive = true [, array|null $filters = null [, int $filters_operator = Gustav::FILTER_AND [, int $order_by = Gustav::ORDER_PUB [, int $min_match_score = 0 [, bool $include_disabled = false ]]]]]]] )`

Get matching source files.

Creates and returns an array containing the matching source files' paths as keys and the matching items of `$filter`'s `match` item as values.  
By default disabled source files are ignored and are not included in the returned array.

<dl>
    <dt><code>$src_directory</code></dt>
    <dd>The path (relative to the source directory) of the directory to start searching for source files in.</dd>
    
    <dt><code>$recursive</code></dt>
    <dd>If set to <code>true</code>, source files placed in the subdirectories of the specified directory are included, too (if matching the filters).
    
    <dt><code>$filters</code></dt>
    <dd>
        An associative array containing filters a SRC file must match to be included in the resulting array. The following filters are available.
        
<pre><code>array(
    "match"=&gt;array(
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
                Calls <code>GustavSrc::getMatchScore()</code> for each of this array's items passing the item's key to <code>getMatchScore()</code>'s first parameter and its value to its second parameter. Each <code>match</code> filter is considered to be a single, standalone filter. If an empty array is passed to one of this array's items, that item is ignored.<br />
                If the calculated match score for an item is greater than 0, the source file is considered to match that item of the filter.<br />
                Using an unsupported <code>match</code> filter (i.e. an unsupported key) won't match any source files. Supported keys are <code>file</code> (<code>GustavBase::KEY_FILE</code>), <code>title</code> (<code>GustavBase::KEY_TITLE</code>), <code>tags</code> (<code>GustavBase::KEY_TAGS</code>). For more information see <code>GustavSrc::getMatchScore()</code>.
            </dd>

            <dt><code>string[][]|(string|bool)[] prop</code></dt>
            <dd>
                Accepts any GvBlock option. Use the option's name as key and the value to match the option's value against as value. Each <code>prop</code> filter is considered to be a single, standalone filter.<br />
                If a boolean is set as value, this function checks whether a source file's GvBlock contains the option, if <code>true</code>, or not, if <code>false</code>.<br />
                Any other type of value is converted to an array or is left as it is if it's already an array. The same applies to the corresonding property's value. If (originally) an empty array has been specified, the filter matches only if the property's (original) value is an empty array, too. Otherwise this function looks for intersections (case-sensitive for strings) of the property's array (original value or converted) and the filter's array (original value or converted). If the array isn't empty, only one intersection needs to be found, to consider the source file to match this filter. This corresponds to the OR operator. This behavior cannot be changed using this function's <code>$filters_operator</code> parameter.<br />
                If the option isn't contained in the GvBlock at all, the source file doesn't match this filter's property.
            </dd>

            <dt><code>string[] conv</code></dt>
            <dd>
                This filter is an extended version of <code>"prop"=&gt;array("_conv"=&gt;array())</code>.<br />
                Unlike the <code>prop</code> version, this filter doesn't only match source files that use one of the <em>specified</em> converter names but also matches such that use a different name (an alias) for the same converter. Moreover the comparision is done case-insensitively. This applies only to hardcoded converters. User-defined converters are compared case-sensitively and without any consideration of aliases.<br />
                If an empty array is passed to this filter, the filter is ignored.<br />
                For example, <code>"conv"=&gt;array("text", "md")</code> would match all source files using any name of the hardcoded text converter (<code>Gustav::CONV_TEXT</code>, case-insensitive) or a user-defined markdown converter named <code>md</code> (case-sensitive) but not a converter named <code>markdown</code> since <code>md</code> is a user-defined converter and can therefore not have any aliases.
            </dd>

            <dt><code>string|string[] dest</code></dt>
            <dd>
                This filter is an extended version of <code>"prop"=&gt;array("_dest"=&gt;array())</code>.<br />
                In addition to the <code>prop</code> version, this filter also takes the result of <code>GustavDest::getPath()</code> for the source file into account.<br />
                A source file matches this filter if its GvBlock's <code>_dest</code> option's value matches the filter value or if the option's value ends with a directory separator and the filter value matches that value with the trailing directory separator stripped away.<br />
                Source files, whose corresponding <code>GustavDest</code> object's <code>getPath()</code> method's return value matches the filter value are considered to be successfully matched, too.<br />
                This filter expects a string value containing a full path, including the filename, or just the dirname of a destination file's path (assuming that its filename is <code>index.*</code>). The path has to be relative to the document root. It is passed to <code>GustavBase::path()</code>.
            </dd>

            <dt><code>int newer_than</code></dt>
            <dd>If the <code>_pub</code> option of a source file's GvBlock is set and is greater than the value passed to this filter, the file matches the filter.</dd>

            <dt><code>int older_than</code></dt>
            <dd>If the <code>_pub</code> option of a source file's GvBlock isn't set or if it's lower than or equal to the value passed to this filter, the file matches the filter.</dd>
        </dl>
             
        If set to <code>null</code>, a default filter of <code>array("prop"=&gt;array("_hidden"=&gt;false), "older_than"=&gt;time())</code> is used.<br />
        To get all source files without filtering them, use an empty array or one containing unsupported items only.
    </dd>

    <dt><code>$filters_operator</code></dt>
    <dd>
        Use one of the <code>Gustav::FILTER_*</code> constants as this parameter's value.<br />
        If set to <code>Gustav::FILTER_AND</code>, a source file must match all specified (and supported) filters to be included in the resulting array. Setting this parameter to <code>Gustav::FILTER_OR</code> means that a source file must match at least one (supported) filter to be included in the resulting array.
    </dd>

    <dt><code>$order_by</code></dt>
    <dd>
        Defines how to sort the matching source files. Use one of the <code>Gustav::ORDER_*</code> constants as this parameter's value.<br />
        If set to <code>Gustav::ORDER_PUB</code> or <code>Gustav::ORDER_PUB_ASC</code>, source files whose GvBlock doesn't contain the <code>_pub</code> option are moved to the end of the returned array, regardless of whether the sorting is ascending or descending.
    </dd>

    <dt><code>$min_match_score</code></dt>
    <dd>
        Defines a percentage value relative to the highest match score of all matching source files. Source files whose match score is lower than the defined minimum percentage of the maximum match score are ignored and removed from the resulting array. Setting this parameter to <code>0</code>, disables this filter and keeps all matching source files in the array.<br />
        Theoretically this parameter's value can be any number, even a float. However, regarding to this documentation, only an integer value is supported.
    </dd>

    <dt><code>$include_disabled</code></dt>
    <dd>If set to <code>true</code>, disabled source files are no longer ignored.</dd>
</dl>

Returns an associative array whose keys are the matching source files' paths and whose values are associative arrays whose keys are the supported keys of the `match` filter and whose values are arrays containing the corresponding matching items. If no `match` filter exists, an empty array is used as value instead.

###`int[] getTags()`

Get all available tags.

Tags that differ only in their letters' cases are merged and the version with the most occurrences in all source files' tags is used.  
The returned array contains only tags used by at least one source file matching the default filter of `Gustav::query()`.

Returns an associative array containing the tags' names as keys and their numbers of occurrences as values. The tags are ordered by their numbers of occurrences.

###`array[] getCategories()`

Get all available categories.

The returned array contains only the categories containing (directly or within their subcategories/-directories) at least one source file matching the default filter of `Gustav::query()`.

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
    <dt></code>bool root</code></dt>
    <dd><code>true</code> for the destination directory's root, ortherwise <code>false</code>.</dd>

    <dt><code>int count</code></dt>
    <dd>The number of source files that are direct children of this directory and that are matching the default filter of <code>Gustav::query()</code>.</dd>

    <dt><code>string name</code></dt>
    <dd>The name of the category (i.e. its filename).</dd>

    <dt><code>array[] sub</code></dt>
    <dd>The category's subategories. The array has the same structure as the one above.</dd>
</dl>



##Constants

###`string CONF_DEST_DIR`

The name of the configuration option specifying the path of the destination directory.

###`string CONF_SRC_DIR`

The name of the configuration option specifying the path of the source directory.

###`string CONF_TEMPLS_DIR`

The name of the configuration option specifying the path of the directory containing the template files.

###`string CONF_404_DOC`

The name of the configuration option specifying the URL (relative or absolute) of the error document for *404 Not Found* errors.

###`string CONF_PREFERRED_CONVS`

The name of the configuration option specifying a list of preferred converters, used for deciding which source file to use when auto-generating a destination file and no unambiguous source file could be figured out.

###`string CONF_LOG_MAX_SIZE`

The name of the configuration option specifying the maximum file size of a log-file.

###`string CONF_EXIT_ON_ERROR`

The name of the configuration option specifying whether to stop the execution of a script when a Gustav-specific error happens.

###`string CONF_CHECK_STATUS`

The name of the configuration option specifying whether to check Gustav for a proper setup when including `Gustav.php`.

###`string CONF_SITE_URL`

The name of the configuration option specifying an absolute URL of the site Gustav is running on.

###`string CONF_ENABLE_LOG`

The name of the configuration option specifying whether to write errors, warnings or success messages to the log-file.

###`string CONF_GEN_SEARCH_RECURSIVE`

The name of the configuration option specifying whether to search in all subdirectories of the source directory when generating a destination file using `GustavGenerator::genByUrl()` (i.e. `generate.php`).

###`string CONF_REPLACE_DIR_SEP`

The name of the configuration option specifying a character to replace with the directory separator (`DIRECTORY_SEPARATOR`). If an empty string, nothing is replaced.

###`int ORDER_MATCH`

Orders matching source files by their match scores (descending).

###`int ORDER_MATCH_ASC`

Orders matching source files by their match scores (ascending).

###`int ORDER_PUB`

Orders matching source files by their dates of publication (descending).

###`int ORDER_PUB_ASC`

Orders matching source files by their dates of publication (ascending).

###`int ORDER_NONE`

Don't order matching source files.

###`int FILTER_OR`

Filters source files using the OR operator.

###`int FILTER_AND`

Filters source files using the AND operator.