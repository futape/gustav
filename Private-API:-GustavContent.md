##Static functions

###`string prepareContent( string $content, string $path )`

Checks whether the source file is a PHP file (i.e. its extension equals "php" case-insensitively). If it is, it is executed and the resulting content is used as content. Otherwise the passed, unmodified content is used instead.

This is the first function called in the process of building the content.
     
<dl>
    <dt><code>$content</code></dt>
    <dd>The (non-executed) source file's content.</dd>
    
    <dt><code>$path</code></dt>
    <dd>The path of the source file.</dd>
</dl>

Returns the content. Either the one that has been passed to this function or the resulting content of the executed source file.

###`string finalizeContent( string $content, array $gvblock [, bool $convert_content = true ] )`

Finalizes a source file's content.

Extends other source files' contents (if specified) and converts the source content.

Steps:

<pre><code>1.  If <code>_ext</code> is defined:
        If content is empty or constists of whitespaces only (not 3.):
            Use content (not converted) of extended source file instead.
    2.  Convert content using the source file's converter(s).
    3.  If <code>_ext</code> is defined:
            If the source file's original content is not empty and doesn't consist of whitespaces only (not 1.):
                If <code>_ext_content</code> is defined:
                    If the final content of the extended source file isn't empty:
                        Concatenate final content of extended source file and the content (converted, see 2.)
                        of the source file separated by the value of <code>_ext_content</code>.
                        Extended source file's content first, then the content the extending SRC file.</code></pre>

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

For more information see *Extending source content*.

This is the second and last function called in the process of building the content, following `GustavContent::prepareContent()`.

<dl>
    <dt><code>$content</code></dt>
    <dd>The prepared content. May very likely be a string returned by <code>GustavContent::prepareContent()</code>.</dd>
    
    <dt><code>$gvblock</code></dt>
    <dd>The source file's GvBlock.</dd>
    
    <dt><code>$convert_content</code></dt>
    <dd>If set to <code>false</code>, the content isn't converted using the converter(s) of the source file. The content of an extended source file that gets concatenated with the extending source file's content will still be converted using its own converter(s).</dd>
</dl>

Returns the finalized content.

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
        For the hardcoded plain text converter the value will be <code>html</code> while being <code>null</code> for the hardcoded HTML converter. Although returning <code>html</code> or <code>null</code> has a very similar effect, user-defined converters should always prefer <code>html</code> over <code>null</code>.<br />
        If the converter doesn't exist, the variable will contain <code>false</code>.<br />
        The converter name passed to the variable may not exist.
    </dd>
</dl>
     
Returns the converted content.