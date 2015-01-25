<dl>
    <dt><em>Linebreak</em>, <em>Newline</em>, <em>Newline character</em></dt>
    <dd>
        Gustav always accepts any kind of linebreaks, being either <code>\n</code>, <code>\r\n</code> or <code>\r</code>. <em>Linebreak</em> or <em>Newline</em> mean a linebreak in any of these forms.<br />
        However, <em>linefeed character</em> or <code>\n</code>, for example, doesn't mean any of the linebreaks above, rather it means the linefeed character (<code>0x000a</code>) specifically.
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
    <dd>Gustav's PHP API. See <a href="API"><em>API</em></a>.</dd>
    
    <dt><em>SRC</em></dt>
    <dd>Used interchangeably with <em>source</em>.</dd>
    
    <dt><em>DEST</em></dt>
    <dd>Used interchangeably with <em>destination</em>.</dd>
    
    
    
    
    
    <dt><em>GvDir</em></dt>
    <dd>The directory the Gustav-related files are located in.</dd>
    
    <dt><em>Extension directory</em></dt>
    <dd>See <a href="Extending-Gustav"><em>Extending Gustav</em></a>.</dd>
    
    <dt><em>Logs directory</em></dt>
    <dd>See <a href="Log-files"><em>Log files</em></a>.</dd>
    
    <dt><em>Destination directory</em></dt>
    <dd>See <a href="Gustav-configuration#string-dest_dir"><em>Gustav configuration</em></a>.</dd>
    
    <dt><em>Source directory</em></dt>
    <dd>See <a href="Gustav-configuration#string-src_dir"><em>Gustav configuration</em></a>.</dd>
    
    <dt><em>Templates directory</em></dt>
    <dd>See <a href="Gustav-configuration#templs-dest_dir"><em>Gustav configuration</em></a>.</dd>
    
    
    
    
        
    <dt><em>Source file</em></dt>
    <dd>See <a href="Source-files"><em>Source files</em></a>.</dd>
    
    <dt><em>PHP source file</em></dt>
    <dd>See <a href="PHP-source-files"><em>PHP source files</em></a>.</dd>
    
    <dt><em>Source content</em></dt>
    <dd>See <a href="Source-content"><em>Source content</em></a>.</dd>
    
    <dt><em>Transparent extension</em></dt>
    <dd>See <a href="Extending-source-content"><em>Extending source content</em></a>.</dd>
    
    <dt><em>Isolated extension</em></dt>
    <dd>See <a href="Extending-source-content"><em>Extending source content</em></a>.</dd>
    
    <dt><em>GvBlock</em></dt>
    <dd>See <a href="GvBlock"><em>GvBlock</em></a>.</dd>
    
    <dt><em>Template variables</em>, <em>Templating variables</em></dt>
    <dd>See <a href="GvBlock-option-templating"><em>GvBlock option templating</em></a> and <a href="Private-API%3A-GustavBase#string-templ-string-template--array-vars--array--bool-resolve_constants--true--bool-unescape_placeholders--true--"><code>GustavBase::templ()</code></a>.</dd>
    
    
    
    
    
    <dt><em>Destination file</em></dt>
    <dd>See <a href="Destination-files"><em>Destination files</em></a>.</dd>
    
    <dt><em>PHP destination file</em></dt>
    <dd>See <a href="Destination-files"><em>Destination files</em></a>.</dd>
    
    <dt><em>Destination content</em></dt>
    <dd>See <a href="Generating-destination-files#generating-the-destination-content"><em>Generating destination files</em></a>.</dd>
    
    <dt><em>Destination path</em></dt>
    <dd>See <a href="Generating-destination-files#generating-the-destination-path"><em>Generating destination files</em></a>.</dd>
    
    <dt><em>Auto-generating</em>, <em>Auto-generation</em></dt>
    <dd>See <a href="Automatic-generation-of-destination-files"><em>Automatic generation of destination files</em></a>.</dd>
    
    
    
    
    
    <dt><em>Converter file</em></dt>
    <dd>The file used to describe a user-defined converter. For more information see <a href="User-defined-converters"><em>User-defined converters</em></a>.</dd>
    
    <dt><em>User-defined converter</em></dt>
    <dd>See <a href="User-defined-converters"><em>User-defined converters</em></a>.</dd>
    
    <dt><em>Hardcoded converter</em></dt>
    <dd>See <a href="Converting-source-content#hardcoded-converters"><em>Converting source content</em></a>.</dd>





    <dt><em>Template file</em></dt>
    <dd>See <a href="Template-files"><em>Template files</em></a>.</dd>
</dl>