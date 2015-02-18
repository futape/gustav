##Static functions

###`void gen( string|string[] $path [, bool $print_content = false [, string|null $redirect_url = null ]] )`

[Generates](Generating-destination-files) a [destination file](Destination-files) or prints out the [destination content](Generating-destination-files#generating-the-destination-content).  
If the specified path doesn't point on a file, a Gustav-404-error is raised. Also a Gustav-error is raised when the [`GustavDest`](API#gustavdest) object for getting the destination information can't be created for that [source file](Source-files).  
If the [content](Generating-destination-files#generating-the-destination-content) gets printed, also a HTML `Content-Type` HTTP response header field is added to the header. Moreover, the script execution is stopped immediately after printing the destination content.  
If the [content](Generating-destination-files#generating-the-destination-content) gets not printed, but rather a [destination file](Destination-files) is [created](Generating-destination-files#creating-the-destination-file), a redirection is done. If the [destination file](Destination-files) couldn't be created, a Gustav-error is raised, otherwise a success log-entry is made.
     
<dl>
    <dt><code>$path</code></dt>
    <dd>
        The path of the <a href="Source-files">source file</a> for which to <a href="Generating-destination-files#generating-the-destination-content">generate the destination content</a>, relative to the source directory.<br />
        The source directory's path is prepended to this parameter's value. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.
    </dd>
    
    <dt><code>$print_content</code></dt>
    <dd>If set to <code>true</code>, no <a href="Destination-files">destination file</a> is <a href="Generating-destination-files#creating-the-destination-file">created</a>. Instead the <a href="Generating-destination-files#generating-the-destination-content">destination content</a> is printed out directly.</dd>
    
    <dt><code>$redirect_url</code></dt>
    <dd>
        This parameter takes effect only when <code>$print_content</code> is set to <code>false</code>.<br />
        After <a href="Generating-destination-files#creating-the-destination-file">creating the destination file</a>, a redirection is done.<br />
        If set to <code>null</code>, the client is redirected to the <a href="Generating-destination-files#generating-the-destination-path">location</a> of the created destination file. If this parameter's value is a string starting with <code>?</code> or <code>#</code>, the client is redirected to the same location, but this parameter's value is appended to the URL as a query string or a fragment identifier (or both in case of <code>?foo=bar#anchor</code>). If the value is a string **not** starting with one of these characters, the value is considered to be a properly encoded URL, either a <a href="https://tools.ietf.org/html/rfc3986#section-4.2">relative</a> or an <a href="https://tools.ietf.org/html/rfc3986#section-4.3">absolute</a> one, to redirect the client to.
    </dd>
</dl>

###`void genByUrl( string $dest_url [, bool $search_recursive = false [, bool $print_content = false ]] )`

Takes a (not-yet-existing) [destination file](Destination-files)'s URL and [creates](Generating-destination-files#creating-the-destination-file) it or prints out its [destinaton content](Generating-destination-files#generating-the-destination-content).

Searches either in the directory specified by the URL path (the destination directory's path (document-root-relative) is stripped away and the server-root-relative path of the source directory is prepended) only, or in the whole source directory including all of its subdirectories for a matching source file (including [disabled source files](Disabled-source-files) and those that are located in a `__hidden` directory). *Matching source files* match the `dest` filter of [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--), set to the path of the URL. Source files for which no [`GustavSrc`](API#gustavsrc) object can be created are ignored.  
If [source files](Source-files), whose corresponding [`GustavDest`](API#gustavdest) objects' [`getPath()` methods](Public-API%3a-GustavDest#string-getpath) return values match the path of the requested [destination file](Destination-files), are found, the set of matching source files is reduced to these files.  
The [disabled files](Disabled-source-files) are filtered out of the set of matching source files and the most similar [source file](Source-files) is chosen by following the steps below (important to less important).

1.  The position of the first converter in the array that has been specified using the [`preferred_convs` configuration option](Gustav-configuration#string-preferred_convs--) that is also used in the [source file](Source-files). If none of the prefered converters is used in the [source file](Source-files) or if no [`GustavSrc`](API#gustavsrc) object can be created for the [source file](Source-files), the source file is moved to the end of the list.  
    The lower the position, the higher the ranking.
2.  The timestamp of the last modification of the [source file](Source-files)'s content. The timestamp is retrieved by calling [`filemtime()`](http://php.net/manual/en/function.filemtime.php). If that function fails, the [source file](Source-files) is moved to the end of the list.  
    The newer the timestamp, the higher the ranking.

If no matching [source files](Source-files) could be found, a Gustav-404-error is raised.

If a [destination file](Destintion-files) should be [created](Generating-destination-files#creating-the-destination-file), the client is redirected to the new file after creating it. The query string (`?...`, if any), as well the fragment identifier (`#...`, if any) of the original request are passed through to that request.

<dl>
    <dt><code>$dest_url</code></dt>
    <dd>The relative URL (root-relative) of a (not-yet-existing) <a href="Destintion-files">destination file</a> to create or to print out its <a href="Generating-destination-files#generating-the-destination-content">destinaton content</a>.</dd>
    
    <dt><code>$search_recursive</code></dt>
    <dd>
        If set to <code>true</code>, the whole source directory including all of its subdirectories is searched for a matching <a href="Source-files">source file</a>. Otherwise, only the directory retrieved from the URL path is searched.<br />
        Searching in all directories may be a bit slow and memory-intensive but it's more flexible since the <a href="Destination-files">destination file</a> doesn't have to be located in the destination directory's subdirectory corresponsing to the source directory's subdirectory the <a href="Source-files">source file</a> is located in.
    </dd>
    
    <dt><code>$print_content</code></dt>
    <dd>If set to <code>true</code>, no <a href="Destintion-files">destination file</a> is <a href="Generating-destination-files#creating-the-destination-file">created</a>. Instead the <a href="Generating-destination-files#generating-the-destination-content">destination content</a> is printed out directly. Gets passed to <a href="#void-gen-stringstring-path--bool-print_content--false--stringnull-redirect_url--null--"><code>GustavGenerator::gen()</code></a>.</dd>
</dl>