##Static functions

###`(string|true)[] parseBlock( string &$content )`

Parses a [GvBlock](GvBlock) out of a [source file](Source-files)'s content.

[Options](GvBlock-options)' names are trimmed. After trimming them, options' names must not be empty, nor can they be `!`, nor can they consist of consecutive occurrences of `_default` only. Otherwise they are removed from the GvBlock.  
If an option doesn't have a value (without a `:` delimiter) (i.e it's a boolean option), `true` is used as the option's value.  
If not described differently for a specific option, its value get trimmed and is taken literally which means that an `\n` doesn't mean a newline character, rather it means a literal `\n`, 2 characters, a backslash and the lowercased letter `N` (`0x006e`).  
All options support [templating](GvBlock-option-templating). Using this feature, it's possible to insert a newline character into the value (for example using <code>{{<a href="https://php.net/manual/en/reserved.constants.php#constant.php-eol">PHP_EOL</a>}}</code>), even if the option doesn't support this explicitly.  
For more information see [*GvBlock definition*](GvBlock-definition).

This is the first function called in the process of building a GvBlock.

<dl>
    <dt><code>&amp;$content</code></dt>
    <dd>The source file's content from which to get the GvBlock. If a variable has been passed, it will contain the source file's content with the GvBlock definition stripped away.</dd>
</dl>

Returns the parsed GvBlock.

###`(string|true)[] extendBlock( (string|true)[] $gvblock, string|string[] $path )`

[Extends a GvBlock](Extending-a-GvBlock).

[Options](Gustav-core-options) whose names start with `_ext_` and the [`_ext` option](Gustav-core-options#_ext) itself as well as options starting with `!` aren't passed down to the extending GvBlock. Extending GvBlocks over multiple levels is supported.  
An option of the extending GvBlock starting with `!` removes the equally named (without the leading `!`) option from the GvBlock to extend. An extending GvBlock's option overwrites the appropiate option of the extended GvBlock, regardless of whether its value is valid or not.  
The value of an option of the extended GvBlock can be included in the corresponding option's value of the extending GvBlock by using [templating](GvBlock-option-templating). The extended GvBlock's value is available via the [`$ext` variable](GvBlock-option-templating#ext).  
Even if `_ext_*` options aren't inherited automatically if not defined in the extending GvBlock, their values can be inherited by defining them as `_ext_content:{{$ext}}`, for example, in the extending GvBlock. The only option that can't be extended is the [`_ext` option](Gustav-core-options#_ext).  
If an option of the extended GvBlock isn't inherited automatically because it has been disabled in the extending GvBlock using `!<option name>`, it can still be defined by the extending GvBlock. The value of the option of the extended GvBlock even can still be inherited by using a value of [`{{$ext}}`](GvBlock-option-templating#ext). When doing so and the value of the extended GvBlock's option isn't a string, the template is replaced by an empty string.

This is the second function called in the process of building a GvBlock, following [`GustavBlock::parseBock()`](#stringtrue-parseblock-string-content-).
     
<dl>
    <dt><code>$gvblock</code></dt>
    <dd>The extending GvBlock. May very likely be an array returned by <a href="#stringtrue-parseblock-string-content-"><code>Gustav::parseBlock()</code></a>.</dd>
    
    <dt><code>$path</code></dt>
    <dd>The path of the [source file](Source-files) the GvBlock has been extracted from. Gets passed to <a href="#array-finalizeblock-stringtrue-gvblock-stringstring-path--check_required_options--true--"><code>GustavBlock::finalizeBlock()</code></a> which in turn calls <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a> on the path.</dd>
</dl>

Returns the extending GvBlock with all of the extended GvBlock's options inherited, if not prevented, and [`{{$ext}}` templating placeholders](GvBlock-option-templating#ext) resolved.

###`(string|true)[] templBlock( (string|true)[] $gvblock )`

Resolves all [templating placeholders](GvBlock-option-templating) in the block's options' string values.  
Constants as well as the following *special* variables will be resolved.

<dl>
    <dt><code>$src_dir</code></dt>
    <dd>The value of the <a href="Gustav-configuration#string-src_dir">configuration option</a> specifying the path of the source directory (<a href="Public-API%3a-Gustav#string-conf_src_dir"><code>Gustav::CONF_SRC_DIR</code></a>). Ends with a directory separator.</dd>
    
    <dt><code>$dest_dir</code></dt>
    <dd>The value of the <a href="Gustav-configuration#string-dest_dir">configuration option</a> specifying the path of the destination directory (<a href="Public-API%3a-Gustav#string-conf_dest_dir"><code>Gustav::CONF_DEST_DIR</code></a>). Ends with a directory separator.</dd>
</dl>

This is the third function called in the process of building a GvBlock, following [`GustavBlock::parseBock()`](#stringtrue-parseblock-string-content-) and [`GustavBlock::extendBock()`](#stringtrue-extendblock-stringtrue-gvblock-stringstring-path-).

<dl>
    <dt><code>$gvblock</code></dt>
    <dd>The GvBlock whose options' values should be resolved as templates. May very likely be an array returned by <a href="#stringtrue-extendblock-stringtrue-gvblock-stringstring-path-"><code>Gustav::extendBock()</code></a>.</dd>
</dl>

Returns the passed GvBlock, with all (available) templating placeholders resolved.

###`array finalizeBlock( (string|true)[] $gvblock, string|string[] $path [, $check_required_options = true ] )`

[Finalizes a GvBlock](Finalizing-a-GvBlock).

[Validates](GvBlock-option-processing) a GvBlock and resets invalid options to their [defaults](GvBlock-option-default-values) or removes them completely from the GvBlock.  
Options' values may be converted (into another datatype) and [processed](GvBlock-option-processing) using a option-specific processor-function.  
Options starting with `!` are removed from the GvBlock. `_default` options starting with `_`, which means that they are [Gustav core options](Gustav-core-options), are removed from the GvBlock, too.  
More precisely the following actions are applied to the GvBlock.

1.  GvBlock entries starting with `!` are removed from the GvBlock.
2.  [System default values](GvBlock-option-default-values#system-default-values) are added to the GvBlock as `_default` options.
3.  Options and the corresponding `_default` options are merged together.
4.  For every option the merged values are validated and the first valid value is used. If no valid value could be found, the option is removed from the GvBlock.
5.  If, after the first valid value has been found, there are still merged values, not taken into account yet since a valid value has already been found, the options' merged values are unmerged again and added to the GvBlock as `_default` entries. These entries' values may be valid or not.
    For [Gustav core options](Gustav-core-options) starting with `_` all of the above doesn't apply. For those options only the first valid value is kept and all other merged values are removed from the GvBlock.
6.  The valid values are [converted and processed](GvBlock-option-processing) to get the options' final values.
7.  If no valid values could be found for [required options](Required-GvBlock-options) (i.e. `_conv`, `_templ`, `_dest` and `_tags`), a Gustav-error is raised.

This is the fourth and last function called in the process of building a GvBlock, following [`GustavBlock::parseBock()`](#stringtrue-parseblock-string-content-), [`GustavBlock::extendBock()`](#stringtrue-extendblock-stringtrue-gvblock-stringstring-path-) and [`GustavBlock::templBock()`](#stringtrue-templblock-stringtrue-gvblock-).
     
<dl>
    <dt><code>$gvblock</code></dt>
    <dd>The GvBlock that should be finalized. May very likely be an array returned by <a href="#stringtrue-templblock-stringtrue-gvblock-"><code>Gustav::templBlock()</code></a>.</dd>
    
    <dt><code>$path</code></dt>
    <dd>The path of the [source file](Source-files) the GvBlock was extracted from. Gets passed to <a href="Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---"><code>GustavBase::path()</code></a>.</dd>
    
    <dt><code>$check_required_options</code></dt>
    <dd>If set to <code>true</code>, a Gustav-error is raised if a required option isn't set or if its value isn't valid. Otherwise, no Gustav-errors are thrown.</dd>
</dl>

Returns the finalized GvBlock.