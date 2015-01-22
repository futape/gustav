<dl>
    <dt><em>Linebreak</em>, <em>Newline</em></dt>
    <dd>
        Gustav always accepts any kind of linebreaks, being either <code>\n</code>, <code>\r\n</code> or <code>\r</code>. <em>Linebreak</em> or <em>Newline</em> mean a linebreak in any of these forms.<br />
        However, <em>newline character</em> or <code>\n</code>, for example, doesn't mean any of the linebreaks above, rather it means the line feed character (<code>0x0a</code>) specifically.
    </dd>
    
    <dt><em>Dirname</em></dt>
    <dd>The part of a path describing the directory. See <a href="http://php.net/manual/en/function.dirname.php"><code>dirname()</code></a> for more information.</dd>
    
    <dt><em>Basename</em></dt>
    <dd>Usually the full filename of a path, including the file-extension. See <a href="http://php.net/manual/en/function.basename.php"><code>basename()</code></a> for more information.</dd>
    
    <dt><em>Filename</em></dt>
    <dd>The filename of a path. If how the file-extension is handled is not explicitly defined, this is just a synonym for <em>Basename</em>. See also <a href="http://php.net/manual/en/function.pathinfo.php"><code>pathinfo()</code></a>'s <code>PATHINFO_FILENAME</code>.</dd>
    
    <dt><em>Relative URL</em></dt>
    <dd>A relative URL is described in <a href="https://tools.ietf.org/html/rfc3986#section-4.2">RFC 3986</a>. I recommend to use only root-relative URLs, not directory-relative or protocol-relative ones.</dd>
    
    <dt><em>Absolute URL</em></dt>
    <dd>An absolute URL is described in <a href="https://tools.ietf.org/html/rfc3986#section-4.3">RFC 3986</a>.</dd>
    
    
    
    
    
    <dt><em>Option</em></dt>
    <dd><em>Option</em> is context-dependant. For example, in context of a GvBlock, it means a GvBlock option, while in context of <code>conf.json</code> it means a configuration option.</dd>
    
    <dt><em>API</em></dt>
    <dd>Gustav's PHP API. See <em>API</em>.</dd>
    
    <dt><em>SRC</em></dt>
    <dd>Used interchangeably with <em>source</em>.</dd>
    
    <dt><em>DEST</em></dt>
    <dd>Used interchangeably with <em>destination</em>.</dd>
    
    
    
    
    
    <dt><em>GvDir</em></dt>
    <dd>The directory the Gustav-related files are located in.</dd>
    
    <dt><em>Extension directory</em></dt>
    <dd>See <em>Extending Gustav</em>.</dd>
    
    <dt><em>Destination directory</em></dt>
    <dd>See <em>string dest_dir</em> under <em>Location options</em> in <em>Gustav configuration</em>.</dd>
    
    <dt><em>Source directory</em></dt>
    <dd>See <em>string src_dir</em> under <em>Location options</em> in <em>Gustav configuration</em>.</dd>
    
    <dt><em>Templates directory</em></dt>
    <dd>See <em>string templs_dir</em> under <em>Location options</em> in <em>Gustav configuration</em>.</dd>
    
    
    
    
        
    <dt><em>Source file</em></dt>
    <dd>See <em>Source files</em>.</dd>
    
    <dt><em>PHP source file</em></dt>
    <dd>See <em>PHP source files</em>.</dd>
    
    <dt><em>Source content</em></dt>
    <dd>See <em>Source content</em>.</dd>
    
    <dt><em>Transparent extension</em></dt>
    <dd>See <em>Extending source content</em>.</dd>
    
    <dt><em>Isolated extension</em></dt>
    <dd>See <em>Extending source content</em>.</dd>
    
    <dt><em>GvBlock</em></dt>
    <dd>See <em>GvBlock</em>.</dd>
    
    <dt><em>Template variables</em>, <em>Templating variables</em></dt>
    <dd>See <em>GvBlock option templating</em> and <code>GustavBase::templ()</code>.</dd>
    
    
    
    
    
    <dt><em>Destination file</em></dt>
    <dd>See <em>Destination files</em>.</dd>
    
    <dt><em>PHP destination file</em></dt>
    <dd>See <em>Destination files</em>.</dd>
    
    <dt><em>Destination content</em></dt>
    <dd>See <em>Generating the destination content</em> in <em>Generating destination files</em>.</dd>
    
    <dt><em>Destination path</em></dt>
    <dd>See <em>Generating the destination path</em> in <em>Generating destination files</em>.</dd>
    
    <dt><em>Auto-generating</em>, <em>Auto-generation</em></dt>
    <dd>See <em>Automatic generation of destination files</em>.</dd>
    
    
    
    
    
    <dt><em>Converter file</em></dt>
    <dd>The file used to describe a user-defined converter. For more information see <em>User-defined converters</em>.</dd>
    
    <dt><em>User-defined converter</em></dt>
    <dd>See <em>User-defined converters</em>.</dd>
    
    <dt><em>Hardcoded converter</em></dt>
    <dd>See <em>Hardcoded converters</em> in <em>Converting source content</em>.</dd>





    <dt><em>Template file</em></dt>
    <dd>See <em>Template files</em>.</dd>
</dl>