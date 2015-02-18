##Static functions

###`string prepareContent( string $content, string $path )`

Checks whether the source file is a [PHP source file](PHP-source-files) (i.e. its extension equals "php" case-insensitively). If it is, [it is executed](Reading-source-content) and the resulting content is used as content. Otherwise the passed, unmodified content is used instead.

This is the first function called in the process of building the [source content](Source-content).
     
<dl>
    <dt><code>$content</code></dt>
    <dd>The (non-executed) source file's content.</dd>
    
    <dt><code>$path</code></dt>
    <dd>The path of the source file.</dd>
</dl>

Returns the content. Either the one that has been passed to this function or the resulting content of the executed source file.

###`string finalizeContent( string $content, array $gvblock [, bool $convert_content = true ] )`

Finalizes a source file's content.

[Extends](Extending-source-content) another source file's content (if specified) and [converts](Converting-source-content) the source content.

Steps:

<pre><code>1.  If <a href="Gustav-core-options#_ext"><code>_ext</code></a> is defined:
        If content is empty or constists of whitespaces only (not 3.):
            Use content (not converted) of extended source file instead.
2.  <a href="Converting-source-content">Convert</a> content using the source file's converter(s).
3.  If <a href="Gustav-core-options#_ext"><code>_ext</code></a> is defined:
        If the source file's original content is not empty and doesn't consist of whitespaces only (not 1.):
            If <a href="Gustav-core-options#_ext_content"><code>_ext_content</code></a> is defined:
                If the final content of the extended source file isn't empty:
                    Concatenate final content of extended source file and the content (converted, see 2.)
                    of the source file separated by the value of <a href="Gustav-core-options#_ext_content"><code>_ext_content</code></a>.
                    Extended source file's content first, then the content the extending source file.</code></pre>

Notes on the steps:

<dl>
    <dt>1. (<em><strong>transparent extension</strong></em>)</dt>
    <dd>
        <p>If the extended source file (<em>src2</em>) matches the conditions in 1., too, the content of the source file (<em>src3</em>) extended by <em>src2</em> is not converted, too. The same applies to a source file (<em>src4</em>) that gets extended by <em>src3</em> if <em>src3</em> matches the conditions in 1., too. And so on for <em>src5</em>, <em>src6</em> ...</p>
    
        <p>
            If <em>src2</em> would match the conditions in 3., the content of <em>src3</em> would be converted using <em>src3</em>'s converter(s) and is concatenated with the content (not converted) of <em>src2</em>. The content of <em>src2</em> in turn wouldn't be converted using the converter(s) of <em>src2</em>.<br />
            The concatenated content (<em>src3</em>'s one converted, <em>src2</em>'s one not converted) would then be converted using the converter(s) of <em>src1</em>. Therefore the content of <em>src3</em> would be converted twice: first with its own converter(s) and then with the one(s) of <em>src1</em>.
        </p>
    </dd>
    
    <dt>3. (<em><strong>isolated extension</strong></em>)</dt>
    <dd>The content of the extended source file (<em>src2</em>) is converted using the converter(s) of <em>src2</em>. It's built completely independent of the extending source file (<em>src1</em>).</dd>
</dl>

This is the second and last function called in the process of building the [source content](Source-content), following [`GustavContent::prepareContent()`](#string-preparecontent-string-content-string-path-).

<dl>
    <dt><code>$content</code></dt>
    <dd>The prepared content. May very likely be a string returned by <a href="#string-preparecontent-string-content-string-path-"><code>GustavContent::prepareContent()</code></a>.</dd>
    
    <dt><code>$gvblock</code></dt>
    <dd>The source file's <a href="GvBlock">GvBlock</a>.</dd>
    
    <dt><code>$convert_content</code></dt>
    <dd>If set to <code>false</code>, the content isn't [converted](Converting-source-content) using the converter(s) of the source file. The content of an extended source file that gets concatenated with the extending source file's content will still be converted using its own converter(s).</dd>
</dl>

Returns the finalized [source content](Source-content).

###`string convContent( string $content, string $converter [, mixed &$next_converter ] )`

Converts text using the specified converter. If the converter doesn't exist, the original text is returned.

<dl>
    <dt><code>$content</code></dt>
    <dd>The content to convert.</dd>
    
    <dt><code>$converter</code></dt>
    <dd>The name of the converter that should be used.</dd>
    
    <dt><code>&amp;$next_converter</code></dt>
    <dd>
        A variable passed to this parameter will contain the converter name returned by the used converter.<br />
        For the <a href="Converting-source-content#the-plain-text-converter-txttextplain">hardcoded plain text converter</a> the value will be <code>html</code> while being <code>null</code> for the <a href="Converting-source-content#the-html-converter-htmlhtm">hardcoded HTML converter</a>. Although returning <code>html</code> or <code>null</code> has a very similar effect, <a href="User-defined-converters">user-defined converters</a> should always prefer <code>html</code> over <code>null</code>.<br />
        If the converter doesn't exist, the variable will contain <code>false</code>.<br />
        The converter name passed to the variable may not exist.
    </dd>
</dl>
     
Returns the converted content.