Gustav provides an auto-genertion feature. Auto-generating destination files works by using a PHP file generating the destination file upon the requested URL as the 404 error document in the destination directory and its subdirectories to be called when a non-existing destination file is requested.



##Choosing a matching source file

Picking a matching source file works as described below. If no matching source file could be found, a Gustav-error is thrown.

1.  If the `generator_search_recursive` configuration option is set to true, all subdirectories of the source directory are searched for matching source files.  
    Otherwise, The directory to be searched is chosen by using the dirname of the requested URL's path. If that path's last path segment is `index.php` or `index.html` and is not followed by a directory separator, the next upper directory is used instead.
2.  The chosen directory or, if the `generator_search_recursive` configuration option is enabled, the source directory and all of its subdirectories are scanned for matching source files.
3.  A source file matches if it matches `Gustav::query()`'s `dest` filter set to the path described by the URL (relative to the document root).
4.  If a source file whose destination path matches exactly the requested one is found, regardless of whether it's disabled or not, all matching source files whose destination path doesn't match exactly the requested one are removed from the list of matching source files.
5.  Disabled source files are filtered out of the remaining matching source files.
6.  The remaining matching source files are sorted by the folowing rules (important to less important):
    
    1.  The source file's converters' position in the array of the `preferred_convs` configuration option. The smallest one is used.  
        The smaller, the better.
    2.  The source file's last modification time. For more information see PHP's documentation on `filemtime()`.  
        The newer the last change, the better.
7.  The best matching source file is used.



##Creating the destination file

After choosing the best matching source file, a destination file is created upon that source file.  
For more information see *Generating destination files*.

If the destination file couldn't have been created, a Gustav-error is thrown.  
Otherwise the user is redirected to the created destination file. A query string included in the URL of the original request is passed through to the redirect URL.