##Static functions

###`void gen( string|string[] $path [, bool $print_content = false [, string|null $redirect_url = null ]] )`

Generates a destination file containing the final destination content or simply prints out that content.  
If the specified path doesn't point on a file, a Gustav-404-error is raised. Also a Gustav-error is raised when the `GustavDest` object for getting the destination information can't be created for that source file.  
If the content gets printed, also a proper value for the `Content-Type` HTTP header field is added to the header.  
If the content gets not printed, but rather a destination file is created, a redirection is done. If the destination file couldn't be created, a Gustav-error is raised, otherwise a success log-entry is made.
     
<dl>
    <dt><code>$path</code></dt>
    <dd>
        The path of the source file for which to generate the destination content, relative to the source directory.<br />
        The source directory's path is prepended to this parameter's value. Gets passed to <code>GustavBase::path()</code>.
    </dd>
    
    <dt><code>$print_content</code></dt>
    <dd>If set to <code>true</code>, no destination file is created. Instead the destination content is printed out directly.</dd>
    
    <dt><code>$redirect_url</code></dt>
    <dd>
        This parameter takes effect only when <code>$print_content</code> is set to <code>false</code>.<br />
        After creating the destination file, a redirection is done.<br />
        If set to <code>null</code>, the client is redirected to the location of the created destination file. If this parameter's value is a string starting with <code>?</code>, the client is redirected to the same location, but this parameter's value is appended to the URL as a query string. If the value is a string not starting with <code>?</code>, the value is considered to be a properly encoded URL, either a <a href="https://tools.ietf.org/html/rfc3986#section-4.2">relative</a> or an <a href="https://tools.ietf.org/html/rfc3986#section-4.3>absolute</a> one, to redirect the client to.
    </dd>
</dl>

###`void genByUrl( string $dest_url [, bool $search_recursive = false [, bool $print_content = false ]] )`

Takes a (not-yet-existing) destination file's URL and creates it or prints out its destinaton content.  
Searches either in the directory specified by the URL path (the destination directory's path (documnt-root-relative) is stripped away and the server-root-relative path of the source directory is prepended) only, or in the whole source directory including all of its subdirectories for a matching source file. *Matching source files* match the `dest` filter of `Gustav::query()`, set to the path of the URL.  
If a source file, whose corresponding `GustavDest` object's `getPath()` method's return value matches the path of the requested destination file, is found, that file is used. Otherwise, the disabled files are filtered out of the matching source files and the most similar source file in the remaining matching soure files is chosen. The most similar source file is discovered as described below (important to less important).

1.  The position of the first converter in the array that has been specified using the `preferred_convs` configuration option that is also used in the source file. If none of the prefered converters is used in the soure file or if no `GustavSrc` object can be created for the source file, the source file is moved to the end of the list.  
    The lower the position, the higher the ranking.
2.  The timestamp of the last modification of the source file's content. The timestamp is retrieved by calling `filemtime()`. If that function fails, the source file is moved to the end of the list.  
    The newer the timestamp, the higher the ranking.

If multiple source files whose corresponding `GustavDest` object's `getPath()` method returns the same path as the requested path were found, the same steps are done to determine the source file to use.  
If no similar source files can be found or if the source file, chosen due to its corresponding `GustavDest` object's `path` property's value, is disabled, a Gustav-404-error is raised.  
If a destination file should be created, the client is redirected to the new file after creating it. The query string of the original request (if any) is passed through to that request.

<dl>
    <dt><code>$dest_url</code></dt>
    <dd>The URL of a (not-yet-existing) destination file to create or to print out its destination content.</dd>
    
    <dt><code>$search_recursive</code></dt>
    <dd>
        If set to <code>true</code>, the whole source directory including all of its subdirectories is searched for a matching source file. Otherwise, only the directory retrieved from the URL path is searched.<br />
        Searching in all directories may be a bit slow and memory-intensive but is more flexible since the destination file doesn't have to be located in the destination directory's subdirectory corresponsing to the source directory's subdirectory the source file is located in.
    </dd>
    
    <dt><code>$print_content</code></dt>
    <dd>If set to <code>true</code>, no destination file is created. Instead the destination content is printed out directly. Gets passed to <code>GustavGenerator::gen()</code>.</dd>
</dl>