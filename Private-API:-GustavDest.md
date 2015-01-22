##Static functions

###`string finalizePath( string|string[] $dest_path, array $gvblock [, bool $reverse = false ] )`

Finalizes a destination path by appending `index.` followed by right file extension if only the destination file's dirname has been specified (i.e. the path ends with a directory separator) or just the correct file extension if also a (extension-less) filename has been specified (and `$reverse` is set to `true`).  
The *right file extension* is `php` for PHP destination files and `html` for static ones.
     
<dl>
    <dt><code>$dest_path</code></dt>
    <dd>The unfinalzed path of the destination file. Gets passed to <code>GustavBase::path()</code>.</dd>
    
    <dt><code>$gvblock</code></dt>
    <dd>The source file's GvBlock.</dd>
    
    <dt><code>$reverse</code></dt>
    <dd>
        By default <code>index.&lt;ext&gt;</code> is appended to the passed destination path if a trailing directory separator has been found.  
        If this parameter is set to <code>true</code>, only <code>&lt;ext&gt;</code> is appended if <strong>no</strong> trailing directory separator is found.
    </dd>
</dl>

Returns the finalized destination path.