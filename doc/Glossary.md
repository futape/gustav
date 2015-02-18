##Contents

+   [General terminology](#general-terminology)
+   [Abbreviations and ambiguous terms](#abbreviations-and-ambiguous-terms)
+   [Gustav-related directories and files](#gustav-related-directories-and-files)
+   [Source files](#source-files)
+   [Destination files](#destination-files)
+   [GvBlock](#gvblock)
+   [Converters](#converters)
+   [Miscellaneous Gustav-related terms](#miscellaneous-gustav-related-terms)



##General terminology

<dl>
    <dt><em>Absolute URL</em></dt>
    <dd>An absolute URL is described in <a href="https://tools.ietf.org/html/rfc3986#section-4.3">RFC 3986</a>.</dd>
    
    <dt><em>Relative URL</em></dt>
    <dd>A relative URL aka. <em>relative reference</em> is described in <a href="https://tools.ietf.org/html/rfc3986#section-4.2">RFC 3986</a>. I recommend to use only root-relative URLs (<em>absolute-path reference</em>), not directory-relative (<em>relative-path reference</em>) or protocol-relative (<em>network-path reference</em>) ones.</dd>
    
    <dt><em>Protocol-relative URL</em></dt>
    <dd>A relative URL described in <a href="https://tools.ietf.org/html/rfc3986#section-4.2">RFC 3986</a> as <em>network-path reference</em>.</dd>
    
    <dt><em>Root-relative URL</em></dt>
    <dd>A relative URL described in <a href="https://tools.ietf.org/html/rfc3986#section-4.2">RFC 3986</a> as <em>absolute-path reference</em>.</dd>
    
    <dt><em>Directory-relative URL</em></dt>
    <dd>A relative URL described in <a href="https://tools.ietf.org/html/rfc3986#section-4.2">RFC 3986</a> as <em>relative-path reference</em>.</dd>
    
    <dt><em>Dirname</em></dt>
    <dd>The part of a path describing the directory. See <a href="http://php.net/manual/en/function.dirname.php"><code>dirname()</code></a> for more information.</dd>
    
    <dt><em>Basename</em></dt>
    <dd>Usually the full filename of a path, including the file-extension. See <a href="http://php.net/manual/en/function.basename.php"><code>basename()</code></a> for more information.</dd>
    
    <dt><em>Filename</em></dt>
    <dd>The filename of a path. If how the file-extension is handled is not explicitly defined, this is just a synonym for <em>Basename</em>. See also <a href="http://php.net/manual/en/function.pathinfo.php"><code>pathinfo()</code></a>'s <code>PATHINFO_FILENAME</code>.</dd>
    
    <dt><em>Directory separator</em></dt>
    <dd>The character used in an OS-specific path to separate a directory name from another. For example this would be <code>\</code> for Windows and <code>/</code> for Unix-based systems.</dd>
    
    <dt><em>Path segments</em></dt>
    <dd>The single parts of a path separated by directory separators. Empty path segments are not possible.</dd>
    
    <dt><em>Document root</em></dt>
    <dd>The root directory of the host. See <a href="http://httpd.apache.org/docs/2.4/mod/core.html#documentroot"><code>DocumentRoot</code></a> in the Apache documentation.</dd>
    
    <dt><em>Server root</em></dt>
    <dd>The root directory of the server. For example, on Unix-based systems the path `/` points on that directory. See <a href="http://httpd.apache.org/docs/2.4/mod/core.html#serverroot"><code>ServerRoot</code></a> for a analogously named configuration options in Apache.</dd>
    
    <dt><em>Linebreak</em>, <em>Newline</em>, <em>Newline character</em></dt>
    <dd>
        Gustav always accepts any kind of linebreaks, being either <code>\n</code>, <code>\r\n</code> or <code>\r</code>. <em>Linebreak</em> or <em>Newline</em> mean a linebreak in any of these forms.<br />
        However, <em>linefeed character</em> or <code>\n</code>, for example, doesn't mean any of the linebreaks above, rather it means the linefeed character (<code>0x000a</code>) specifically.
    </dd>
    
    <dt><em>Whitespaces</em>, <em>Whitespace characters</em></dt>
    <dd>
        The following characters are considered to be whitespaces.
        
        <ul>
            <li>Space character (<code>0x20</code>)</li>
            <li>Horizontal tab character (<code>\t</code>, <code>0x09</code>)</li>
            <li>Linefeed character (<code>\n</code>, <code>0x0a</code>)</li>
            <li>Carriage return character (<code>\r</code>, <code>0x0d</code>)</li>
            <li>Formfeed character (<code>\f</code>, <code>0x0c</code>)</li>
        </ul>
    </dd>
    
    <dt><em>Trim</em></dt>
    <dd>Remove whitespace characters from the beginning and the end of a string. See <a href="http://php.net/manual/en/function.trim.php"><code>trim()</code></a>.</dd>
    
    <dt><em>Return value</em></dt>
    <dd>The value returned by a function. Used interchangeably with <em>return<strong>ed</strong> value</em>.</dd>
</dl>



##Abbreviations and ambiguous terms

<dl>
    <dt><em>Option</em></dt>
    <dd><em>Option</em> is context-dependant. For example, in context of a GvBlock, it means a <em>GvBlock option</em>, while in context of the <code>conf.json</code> file it means a <em>configuration option</em>. The meaning in other contexts is not defined.</dd>
    
    <dt><em>Variable</em></dt>
    <dd><em>Variable</em> is context-dependant. For example, in context of GvBlock templating, it means a <em>templating variable</em> available in a templating placeholder, while in context of PHP it means a <a href="http://php.net/manual/en/language.variables.php">PHP variable</a>. The meaning in other contexts is not defined.</dd>
    
    <dt><em>Score</em></dt>
    <dd><em>Score</em> is context-dependant. For example, in context of <a href="API#gustavmatch"><code>GustavMatch</code></a>, it means the <em>match score</em> calculated for a source file. The meaning in other contexts is not defined.</dd>
    
    <dt><em>Template</em></dt>
    <dd><em>Template</em> is context-dependant. For example, in context of a GvBlock, it means a template string containing <em>templating placeholders</em>, while otherwise (mostly in context of destination files) meaning a <em>template file</em>. Any other meaning is specified explicitly.</dd>
    
    <dt><em>API</em></dt>
    <dd>Gustav's PHP Application Programming Interface. See <a href="API"><em>API</em></a>.</dd>
    
    <dt><em>SRC</em></dt>
    <dd>Used interchangeably with <em>source</em>.</dd>
    
    <dt><em>DEST</em></dt>
    <dd>Used interchangeably with <em>destination</em>.</dd>
    
    <dt><em>OS</em></dt>
    <dd>An abbreviation for <em>Operating System</em>.</dd>
</dl>



##Gustav-related directories and files

<dl>
    <dt><em>GvDir</em></dt>
    <dd>The directory the Gustav-related files are located in.</dd>
    
    <dt><em>conf.json</em></dt>
    <dd>See <a href="Gustav-configuration#confjson"><em>Gustav configuration</em></a>.</dd>
    
    <dt><em>generate.php</em></dt>
    <dd>The file used to create non-existing destination files. See <a href="Automatic-generation-of-destination-files"><em>Automatic generation of destination files</em></a>. For related terms see <a href="#destination-files"><em>Destination files</em></a>.</dd>
    
    <dt><em>__base file</em></dt>
    <dd>A special source file used for the system default value for <a href="Gustav-core-options#_ext"><code>_ext</code></a>. See <a href="GvBlock-option-default-values#_ext_default"><em>GvBlock option default values</em></a>. For related terms see <a href="#source-files"><em>Source files</em></a> and <a href="#gvblock"><em>GvBlock</em></a>.</dd>
    
    <dt><em>__hidden directory</em></dt>
    <dd>A special directory located in the source directory containing source files that shouldn't be included in the results of <a href="Public-API%3a-Gustav#string-query--stringstring-src_directory----bool-recursive--true--arraynull-filters--null--int-filters_operator--gustavfilter_and--int-order_by--gustavorder_pub--int-min_match_score--0--bool-include_disabled--false--include_hidden_directory--false--"><code>Gustav::query()<code></a>. See that function for more information. For related terms see <a href="source-files"><em>Source files</em></a>.</dd>
    
    <dt><em>Converter file</em></dt>
    <dd>This and related terms are described under <a href="#converters"><em>Converters</em></a>.</dd>
    
    <dt><em>Extension directory</em></dt>
    <dd>See <a href="Extending-Gustav"><em>Extending Gustav</em></a>.</dd>
    
    <dt><em>Logs directory</em></dt>
    <dd>See <a href="Log-files"><em>Log files</em></a>.</dd>
    
    <dt><em>Templates directory</em></dt>
    <dd>See <a href="Gustav-configuration#templs-dest_dir"><em>Gustav configuration</em></a>.</dd>
    
    <dt><em>Template file</em></dt>
    <dd>See <a href="Template-files"><em>Template files</em></a>.</dd>
    
    <dt><em>Destination directory</em></dt>
    <dd>See <a href="Gustav-configuration#string-dest_dir"><em>Gustav configuration</em></a>. For related terms see <a href="#destination-files"><em>Destination files</em></a>.</dd>
    
    <dt><em>Destination file</em></dt>
    <dd>This and related terms are described under <a href="#destination-files"><em>Destination files</em></a>.</dd>
    
    <dt><em>Source directory</em></dt>
    <dd>See <a href="Gustav-configuration#string-src_dir"><em>Gustav configuration</em></a>. For related terms see <a href="#source-files"><em>Source files</em></a>.</dd>
    
    <dt><em>Source file</em></dt>
    <dd>This and related terms are described under <a href="#source-files"><em>Source files</em></a>.</dd>
</dl>



##Source files

<dl>
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
    
    
</dl>



##GvBlock

<dl>
    <dt><em>GvBlock</em></dt>
    <dd>See <a href="GvBlock"><em>GvBlock</em></a>.</dd>
    
    <dt><em>GvBlock option</em></dt>
    <dd>See <a href="GvBlock-options"><em>GvBlock options</em></a>.</dd>
    
    <dt><em>! options</em></dt>
    <dd>These are options used to prevent a GvBlock from inheriting an option from another GvBlock. See <a href="Extending-a-GvBlock#-options"><em>Extending a GvBlock</em></a>.</dd>
    
    <dt><em>System default values</em></dt>
    <dd>See <a href="GvBlock-option-default-values#system-default-values"><em>GvBlock option default values</em></a>.</dd>
    
    <dt><em>Templating variables</em>, <em>Template variables</em></dt>
    <dd>See <a href="GvBlock-option-templating"><em>GvBlock option templating</em></a> and <a href="Private-API%3A-GustavBase#string-templ-string-template--array-vars--array--bool-resolve_constants--true--bool-unescape_placeholders--true--"><code>GustavBase::templ()</code></a>.</dd>
    
    <dt><em>Templating placeholder</em>, <em>Template placeholder</em></dt>
    <dd>A term in a templating string that will be replaced with the placeholder's corresponding value. See <a href="GvBlock-option-templating"><em>GvBlock option templating</em></a> and <a href="Private-API%3A-GustavBase#string-templ-string-template--array-vars--array--bool-resolve_constants--true--bool-unescape_placeholders--true--"><code>GustavBase::templ()</code></a>.</dd>
</dl>



##Destination files

<dl>
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
</dl>



##Converters

<dl>
    <dt><em>Converter file</em></dt>
    <dd>The file used to describe a user-defined converter. For more information see <a href="User-defined-converters"><em>User-defined converters</em></a>.</dd>
    
    <dt><em>User-defined converter</em></dt>
    <dd>See <a href="User-defined-converters"><em>User-defined converters</em></a>.</dd>
    
    <dt><em>Hardcoded converter</em></dt>
    <dd>See <a href="Converting-source-content#hardcoded-converters"><em>Converting source content</em></a>.</dd>
</dl>



##Miscellaneous Gustav-related terms

<dl>
    <dt><em>Gustav-error</em>, <em>Gustav-specific error</em></dt>
    <dd>An error thrown by Gustav, not a PHP error. The error message is written to the log file. See <a href="Private-API%3a-Gustav#void-error--stringnull-log_message--null--stringint-error_type--gustaverror_500--"><code>Gustav::error()</code></a>.</dd>
    
    <dt><em>Configuration option</em></dt>
    <dd>See <a href="Gustav-configuration#configuration-options"><em>Gustav configuration</em></a>.</dd>
    
    <dt><em>Match score</em></dt>
    <dd>The score specifying how well a source file matches the specified search items, calculated from the matching items. See <a href="API#gustavmatch"><code>GustavMatch</code></a>.</dd>
</dl>