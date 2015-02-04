Gustav provides an auto-generation feature. Auto-generating [destination files](Destination-files) works by using a PHP file [generating](Generating-destination-files) the destination file upon the requested URL as the 404 error document in the destination directory and its subdirectories to be called when a non-existing destination file is requested.



##Choosing a matching source file

Picking a matching [source file](Source-files) works as described below. If no matching source file could be found, a Gustav-error is thrown.

1.  If the [`generator_search_recursive` configuration option](Gustav-configuration#bool-generator_search_recursive--false) is set to `true`, all subdirectories of the source directory are searched for matching source files.  
    Otherwise, The directory to be searched is chosen by using the dirname of the requested URL's path. If that path's last path segment is `index.php` or `index.html` and is not followed by a directory separator, the next upper directory is used instead.
2.  The chosen directory or, if the [`generator_search_recursive` configuration option](Gustav-configuration#bool-generator_search_recursive--false) is enabled, the source directory and all of its subdirectories are scanned for matching source files (including [disabled source files](Disabled-source-files) ond those that are located in a `__hidden` directory).
3.  A source file matches if it matches [`Gustav::query()`](Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--)'s `dest` filter set to the path described by the URL (relative to the document root).
4.  If a source file whose destination path matches exactly the requested one is found, regardless of whether it's [disabled(Disabled-source-files) or not, all matching source files whose destination path doesn't match exactly the requested one are removed from the list of matching source files.
5.  [Disabled source files](Disabled-source-files) are filtered out of the remaining matching source files.
6.  The remaining matching source files are sorted by the following rules (important to less important):
    
    1.  The source file's converters' position in the array of the [`preferred_convs` configuration option](Gustav-configuration#string-preferred_convs--). The smallest one is used.  
        The smaller, the better.
    2.  The source file's last modification time. For more information see PHP's documentation on [`filemtime()`](http://php.net/manual/en/function.filemtime.php).  
        The newer the last change, the better.
7.  The best matching source file is used.



##Creating the destination file

After choosing the best matching [source file](Source-files), a destination file is [created](Generating-destination-files) upon that source file.

If the destination file couldn't have been [created](Generating-destination-files#creating-the-destination-file), a Gustav-error is thrown.  
Otherwise the user is redirected to the created [destination file](Destination-files). A query string included in the URL of the original request is passed through to the redirect URL.



##Further reading

+   [`GustavGenerator::genByUrl()`](Public-API%3a-GustavGenerator#void-genbyurl-string-dest_url--bool-search_recursive--false--bool-print_content--false--)