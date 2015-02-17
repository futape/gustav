<?php
namespace futape\gustav;

//use BadMethodCallException; //used by __callStatic()

abstract class GustavBase {
    
    
    
    #misc-constants#
    
    #GustavBase::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavBase class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavBaseHooks";
    
    #GustavBase::ENC#
    /**
     * The character encoding used by Gustav for generating the DEST files,
     * declaring the charset in the "Content-Type" HTTP header,
     * reading the SRC files and working with mb_* functions.
     * Must be one of the IANA charset names compatible with htmlspecialchars(),
     * html_entity_decode(), htmlentities() and get_html_translation_table()
     * as well as the mb_* functions.
     *
     * @see http://www.iana.org/assignments/character-sets/character-sets.xhtml IANA charset names
     * @see http://php.net/manual/en/mbstring.supported-encodings.php PHP multibyte string: Supported Character Encodings
     *
     * @type string
     */
    const ENC="UTF-8";
    
    
    
    #key-constants#
    
    #GustavBase::KEY_TITLE#
    /**
     * An often used key of associative arrays for a source file's title.
     *
     * @type string
     */
    const KEY_TITLE="title";
    
    #GustavBase::KEY_TAGS#
    /**
     * An often used key of associative arrays for a source file's tags.
     *
     * @type string
     */
    const KEY_TAGS="tags";
    
    #GustavBase::KEY_FILE#
    /**
     * An often used key of associative arrays for a source or destination file's filename.
     *
     * @type string
     */
    const KEY_FILE="file";
    
    
    
    #string-functions#
    
    #GustavBase::lstrip()#
    /**
     * This function strips off the specified string from the beginning of
     * a string.
     *
     * @param string $string The string to remove $remove from.
     * @param string $remove The string to remove from the beginning of $string.
     *
     * @return string The stripped string.
     */
    protected static function lstrip($str_a, $str_b){
        return preg_replace('/^'.self::escapeRegex($str_b).'/', "", $str_a);
    }
    
    #GustavBase::realStr()#
    /**
     * Converts a literal string to a real string.
     *
     * Treats a string as it would be a double-quoted string.
     * The only exception is that variables aren't resolved.
     * But everything else like meta-characters such as \n or \t is treated as expected.
     *
     * @param string $string A literal string.
     *
     * @return string The real string.
     */
    protected static function realStr($str_a){
        return eval('return "'.self::addslashes($str_a, '$"').'";');
    }
    
    #GustavBase::addslashes()#
    /**
     * Prepends a backslash to specific characters.
     *
     * Prepends a "\" to the specified characters and the NUL byte.
     * The result is optimized for using it as a literal string. For example:
     * `eval('return "'.GustavBase::addslashes('foo"bar').";')`
     * which results in `eval('return "foo\"bar";')`.
     * If you wish to use the returned value in a single quoted string, you should ensure that you set $chars to `"'\\"` because no other characters need to be escaped.
     * If they would, the resulting string would be `'foo\"bar'` which is a literal string and therfore the backslash (\) would remain.
     * In case that the backslash character itself isn't contained in $chars, a "\" following a sequence of backslashes, which contains an odd number of backslashes or which is the string's first character, and which is followed by a character that is contained in $chars or which acts as the last character of the string, is removed from the string because if it wouldn't, the backslash that will be prepended would get consumed by the preceding backslash and therefore the character which should be escaped wouldn't be escaped.
     *
     * @param string      $string A string whose "special characters" should be escaped by a backslash.
     * @param string|null $chars  OPTIONAL | Default: null
     *                            A literal string defining the characters that should be escaped by a backslash. If set to null, `'\\"$\''` is used.
     *
     * @return string The passed string containing no more non-escaped "special characters".
     */
    protected static function addslashes($str_a, $str_chars=null){
        if(is_null($str_chars)){
            $str_chars='\\"$\'';
        }
        
        $re_a='['.self::escapeRegex($str_chars).'\0]';
        
        if(self::mb_strpos($str_chars, "\\")===false){
            $str_a=preg_replace('/((?:^|[^\\\\])(?:\\\\{2})*)\\\\($|'.$re_a.')/', '$1$2', $str_a);
        }
        
        return preg_replace_callback('/'.$re_a.'/', function($arr_a){
            return "\\".$arr_a[0];
        }, $str_a);
    }
    
    #GustavBase::unl()#
    /**
     * Normalizes newlines. 
     *
     * Normalizes various types of linebreaks.
     * Windows's \r\n and Mac OS's (up to version 9) \r are changed to match Unix's \n.
     *
     * @param string $string A string whose linebreaks should be normalized.
     *
     * @return string Returns the passed string containing only Unix-like linebreaks.
     */
    protected static function unl($str_a){
        return preg_replace('/\r/', "\n", preg_replace('/\r(?=\n)/', "", $str_a));
    }
    
    #GustavBase::strStartsWith()#
    /**
     * Checks whether a string starts with a specific string.
     *
     * @param string $string      A string whose beginning should be checked against the other string.
     * @param string $starts_with The string the beginning of $string should be checked against.
     * @param bool   $ignore_case OPTIONAL | Default: false
     *                            If set to true, the comparision is done case-insensitively.
     *
     * @return bool Whether the beginning matches the specified string.
     */
    protected static function strStartsWith($str_a, $str_b, $q_ignoreCase=false){
        if($q_ignoreCase){
            $str_a=self::mb_strtolower($str_a);
            $str_b=self::mb_strtolower($str_b);
        }
        
        return self::mb_substr($str_a, 0, self::mb_strlen($str_b))===$str_b;
    }
    
    #GustavBase::strEndsWith()#
    /**
     * Checks whether a string ends with a specific string.
     *
     * @param string $string      A string whose end should be checked against the other string.
     * @param string $ends_with   The string the end of $string should be checked against.
     * @param bool   $ignore_case OPTIONAL | Default: false
     *                            If set to true, the comparision is done case-insensitively.
     *
     * @return bool Whether the end matches the specified string.
     */
    protected static function strEndsWith($str_a, $str_b, $q_ignoreCase=false){
        if($q_ignoreCase){
            $str_a=self::mb_strtolower($str_a);
            $str_b=self::mb_strtolower($str_b);
        }
        
        return self::mb_substr($str_a, -self::mb_strlen($str_b))===$str_b;
    }
    
    #GustavBase::unescapeHtml()#
    /**
     * Decodes HTML entities.
     *
     * Translates all HTML entities to characters. For example: "&amp;" gets converted to "&" and "&quot;" gets converted to '"'.
     * For the conversion the value of GustavBase::ENC is used as charset and HTML5 is used as document type.
     *
     * @param string $html A string containing the HTML code whose HTML entities should be decoded.
     *
     * @return string Returns the passed HTML code whose HTML entities have been decoded.
     */
    protected static function unescapeHtml($str_a){
        return html_entity_decode($str_a, ENT_QUOTES|(int)@constant("ENT_HTML5"), self::ENC);
    }
    
    #GustavBase::escapeHtml()#
    /**
     * Encodes special HTML characters.
     *
     * Translates special HTML characters (", ', &, <, >) to HTML entities. For example "&" gets converted to "&amp;" and '"' gets converted to "&quot;". Already existing HTML entities such as "&quot;" are encoded, too. For example "&quot;" gets converted to "&amp;&quot;".
     * For the conversion the value of GustavBase::ENC is used as charset and HTML5 is used as document type. Invalid code unit sequences, also those invalid in the used document type, are replaced with a unicode replacement character.
     *
     * @param string $string A string whose special HTML characters should be encoded.
     *
     * @return string Returns the passed string whose special HTML characters have been encoded.
     */
    protected static function escapeHtml($str_a){
        return htmlspecialchars($str_a, ENT_QUOTES|(int)@constant("ENT_HTML5")|(int)@constant("ENT_SUBSITUTE")|(int)@constant("ENT_DISALLOWED"), self::ENC);
    }
    
    #GustavBase::escapeRegex()#
    /**
     * Escapes special regular expression characters.
     *
     * Prepends a backslash to special regular expression characters (., \, +, *, ?, [, ^, ], $, (, ), {, }, =, !, <, >, |, :, -) and the delimiter used by Gustav (/).
     *
     * @param string $string A string whose special regular expression characters should be escaped.
     *
     * @return string Returns the passed string whose special regular expression characters have been escaped.
     */
    protected static function escapeRegex($str_a){
        return preg_quote($str_a, "/");
    }
    
    #GustavBase::templ()#
    /**
     * Resolves placeholders within a template string.
     *
     * Placeholders have the form `"{{" constant "}}"` or `"{{$" variable "}}"`.
     * `constant` and `variable` are names of constants or additional variables respectively.
     * Placeholders are replaced with the string-representation of the  appropriate value.
     * Note that the part between the pairs of double braces isn't trimmed. Therefore "{{PHP_EOL}}" isn't the same as "{{ PHP_EOL}}". If a constant or variable isn't defined, the sequence is kept as it has been defined.
     * A placeholder can be escaped using a backslash following the first curly brace. The backslash gets removed and the placeholder is kept as it has been defined. If multiple backslashes are contained, only the first one gets removed.
     * You may even use class constants in placeholders.
     * When using a namespaced class or conastant you should always use the fully qualified name (`\My_NS\MY_CONST` for example) or a qualified name (`My_NS\MY_CONST` for example) which is treated relatively to the global namespace.
     *
     * @param string  $template              A template string.
     * @param array   $vars                  OPTIONAL | Default: array()
     *                                       An associative array containing additional variables, using their names as keys.
     * @param bool    $resolve_constants     OPTIONAL | Default: true
     *                                       If set to false, constants are not taken into account when resolving templates.
     * @param bool    $unescape_placeholders OPTIONAL | Default: true
     *                                       If set to false, backslashes are not removed from escaped placeholders.
     *
     * @return string The passed string with resolved placeholders.
     */
    protected static function templ($str_a, $arr_vars=array(), $q_const=true, $q_unesc=true){
        /** /
        if(count($arr_vars)>0){
            $arr_vars=array_combine(array_map(array($str_hooks, "mb_strtolower"), array_keys($arr_vars)), array_values($arr_vars));
        }
        /**/
        
        return preg_replace_callback('/(\{)(\\\\*)\{(\$?)((?=\}{2})|(?:.(?!\}{2}))*.)\}{2}/', function($arr_a) use ($arr_vars, $q_const, $q_unesc){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            if($arr_a[2]!=""){ //if(call_user_func(array($str_hooks, "preg_match"), '/^\\\\+$/', $arr_a[2])==1)
                return $q_unesc ? $arr_a[1].call_user_func(array($str_hooks, "mb_substr"), $arr_a[0], call_user_func(array($str_hooks, "mb_strlen"), $arr_a[1])+1) : $arr_a[0];
            }
            
            if($arr_a[3]==""){ //constant
                if($q_const){
                    if(defined($arr_a[4])){
                        return (string)constant($arr_a[4]);
                    }
                }
            }else /*if($arr_a[3]=='$')*/{ //variable
                /** /
                if(array_key_exists(self::mb_strtolower($arr_a[4]), $arr_vars)){
                    return (string)($arr_vars[self::mb_strtolower($arr_a[4])]);
                }
                /*/
                if(array_key_exists($arr_a[4], $arr_vars)){
                    return (string)($arr_vars[$arr_a[4]]);
                }
                /**/
            }
            
            return $arr_a[0];
        }, $str_a);
    }
    
    #GustavBase::plain2html()#
    /**
     * Converts plain text to HTML.
     *
     * Replaces 2 subsequent spaces with a combination of a non-breaking and a simple space, tabs with 4 subsequent non-breaking spaces, spaces that follow on a newline with a non-breaking space and newline characters with `<br />` so that all whitespaces are visible when using HTML without wrapping the text into `<pre>` or using CSS.
     * Furthermore this function replaces special HTML characters with HTML entities.
     *
     * @param string $plain The plain text to convert to HTML.
     *
     * @return string Returns the passed text ready for usage in HTML.
     */
    protected static function plain2html($str_a){
        return nl2br(preg_replace('/\t/', str_repeat("&nbsp;", 4), preg_replace('/ {2}/', " &nbsp;", preg_replace('/^ | $/m', "&nbsp;", self::escapeHtml($str_a)))));
    }
    
    #GustavBase::inline()#
    /**
     * Inlines a text or a HTML code.
     *
     * Removes HTML tags of a HTML code on basis of their display and semantic meaning (if enabled), decodes HTML entities and inlines the resulting plain text.
     * Inlining means that whitespaces that aren't simple spaces (such as tabs or newlines) are replaced with one.
     * If the content is treated as HTML code, sequences of whitespaces are stripped to 1 space.
     * For more information read the comments in the source code of this function.
     *
     * @param string $text                 The plain text or HTML code to inline.
     * @param bool   $is_html              OPTIONAL | Default: true
     *                                     Defines whether the passed text is a HTML code. If set to true, HTML tags within the passed HTML code are removed and HTML entities are decoded to real characters. Moreover sequences of whitespaces are stripped to 1 space. The described behavior affects only tags and entities that are not placed within a `<plaintext>` element.
     *                                     Otherwise HTML tags and HTML entities are kept as they are.
     * @param bool   $use_semantic_meaning OPTIONAL | Default: true
     *                                     If set to true, HTML tags within the passed HTML code are, unlike removing them only because of their display, treated on basis of their semantic meaning.
     *                                     This parameter takes effect only when $is_html is set to true.
     *
     * @return string Returns the passed text or HTML code converted to a inline plain text.
     */
    protected static function inline($str_content, $q_html=true, $q_semantic=true){
        if($q_html){
            /**
             * An array of HTML tags.
             *
             * In the markup, a space is prepended to every tag (opening and closing) in this array.
             * Tags having a value of true are removed entirely, together with their descendants, from the makup.
             * Which elements belong to this array depends on their default styling, display, function and behavior.
             *
             * A few examples:
             * + <aside>, <html>, <body>, <div>, <article>, <section>, <p> and a lot more are all block-level elements
             *   and are displayed each on a single line by default, thus wrap them into spaces.
             *   Moreover most likely they contain text content, thus don't remove them entirely.
             * + <input> is displayed inline by default and has a margin. Surrounding text may depend on that margin
             *   and doesn't pre-/append spaces to the input element itself, thus add spaces.
             *   Furthermore it doesn't contain any (text-)content, thus a value of false should be used.
             *   However, <input> is a void-tag and doesn't has any descendants, therefore strip_tags() is enough
             *   to remove it entirely. Due to that use a value of false.
             * + <meter> and <progress> are inline-elements, too, but they don't have a margin by default.
             *   Moreover they may or should contain text content describing their current state. Due to that
             *   I assume that the author of the markup already added spaces by himself, thus no addition of spaces
             *   is needed.
             * + Similar to <input>, <button>, <table>, <video> and a few other elements are displayed inline
             *   and have either a margin or are displayed that large that subsequent text wraps to the next line and
             *   it is reasonable to wrap them into spaces. However, unlike <input> they aren't void-tags and therefore
             *   require a different method than strip_tags() to remove them entirely.
             * + <img> is similar to the point above, but it's a void-tag, thus use a value of false and let strip_tags()
             *   do the job.
             * + <hr> and <br> are block-level elements (or are displayed block-like in the case of <br>), thus add spaces.
             *   Also they are void-tags, therefore use a value of false.
             * + <legend> may not be a block-level element and may not have any margins, but it usually appear inside of a
             *   fieldset element and is styled very special in a (very) different location than the one it was defined in the
             *   markup, therefore the author may not have added any spaces himself, thus wrap that element into spaces.
             * + <label>, <em>, <i>, <u>, <b>, <strong>, <a> and other elements I can't remember in this moment are all
             *   inline-styled elements and are more or less phrasing-elements which means that the author of the markup is
             *   responsible for the spaces.
             * + <tr>, <td>, <tbody>, <thead>, <caption>, <colgroup> and <col> are descendants of a table element.
             *   <option> and <optgroup> are descendants of the select element (or the datalist element in case of <option>).
             *   <param> is a descendant of the object element.
             *   <source> and <track> are descendants of the video or audio elements.
             *   All these elements' parent elements will be removed entirely by using a value of true, therefore these
             *   elements don't need to be wrapped into spaces or being removed explicitly.
             * + <noframes>, <noscript>, <style>, <script>, <head>, <map>, <datalist>, <title>, <option>, <colgroup>,
             *   <optgroup>, <area>, <col>, <param>, <font>, <base>, <basefont>, <link>, <meta>, <source> and <track>
             *   are all invisible elements that don't need to be wrapped into spaces since they are removed entirely below ($arr_remove).
             */
            $arr_tags=array(
                //block (non-void) (keep contents)
                "aside"=>false,
                "article"=>false,
                "section"=>false,
                "figure"=>false,
                "figcaption"=>false,
                "footer"=>false,
                "header"=>false,
                "hgroup"=>false,
                "h1"=>false,
                "h2"=>false,
                "h3"=>false,
                "h4"=>false,
                "h5"=>false,
                "h6"=>false,
                "address"=>false,
                "p"=>false,
                "blockquote"=>false,
                "div"=>false,
                "dialog"=>false,
                "dir"=>false,
                "ul"=>false,
                "ol"=>false,
                "dl"=>false,
                "li"=>false,
                "dd"=>false,
                "dt"=>false,
                "body"=>false,
                "center"=>false,
                "details"=>false,
                "summary"=>false,
                "form"=>false,
                "fieldset"=>false,
                "html"=>false,
                "pre"=>false,
                "plaintext"=>false,
                "main"=>false,
                "legend"=>false,
                //"meter"=>false, //? - it's an inline element and it must not be empty. thus the author should have ensured that it is separated (by spaces) from its surrounding content (regardless of whether it's rendered as a kind of progressbar or whether its content is printed literally) (moreover it's not an input elem) -> remove from this array
                //"progress"=>false, //same as <meter>,
                //"label"=>false,
                "nav"=>false,
                "menu"=>false,
                
                //void (remove contents | remove entire tag by using strip_tags() because it hasn't any contents (void-tag). semantically these tags should have a value of true)
                "embed"=>false,
                "frame"=>false,
                "input"=>false,
                "img"=>false,
                "keygen"=>false,
                "hr"=>false,
                "br"=>false,
                
                //block (non-void) (remove contents)
                "table"=>true,
                "applet"=>true,
                "audio"=>true,
                "video"=>true,
                "canvas"=>true,
                "button"=>true,
                "command"=>true,
                "textarea"=>true,
                "select"=>true,
                "object"=>true,
                "iframe"=>true,
                "frameset"=>true
                //"meter"=>true, //don't do this because <meter> requires a content and its appearance depends on the render engine (some browsers simply display the text content). moreover it doesn't allow userinput.
                //"progress"=>true //same as <meter>, but it doesn't require (but allows) a content. <progress> doesn't allow userinput, too.
            );
            
            if($q_semantic){
                /**
                 * If the semantic meaning of elements is taken into account by setting $q_semantic to true,
                 * some elements that are not removed entirely yet may now be removed so, and vice-versa.
                 *
                 * For example:
                 * + <h1> - <h6>, <hgroup>, <legend> and often <header> only summarize or label their subsequent contents.
                 *   Moreover it's not very applicaple to express their meaning and display in
                 *   inline text.
                 * + <figure> doesn't have a special display, like table for example, and it may contain text content.
                 *   Usually figure elements are referenced by the surrounding text in an absolute manner such as "See figure #1"
                 *   or in a relative manner such as "See the picture above". The latter one is discouraged by the W3C.
                 *   Assuming that the author referenced the figure in an absolute manner, the figure can be moved to any place
                 *   without affecting the surrounding text's understanding. For a (short) inline summary of the content it is applicable
                 *   to remove the figure entirely.
                 * + <aside> is similar to <figure>. It provides (useful) information regarding the surrounding content but isn't
                 *   required for understanding the surrounding text. Therfore it can be removed entirely.
                 * + <nav>, <menu>, and <footer> and <header> usually contain meta data or navigation links, related to, but not necessarily
                 *   part of the surrounding content.
                 */
                $arr_tags=array_merge($arr_tags, array(
                    "h1"=>true,
                    "h2"=>true,
                    "h3"=>true,
                    "h4"=>true,
                    "h5"=>true,
                    "h6"=>true,
                    "header"=>true,
                    "footer"=>true,
                    "hgroup"=>true,
                    "aside"=>true,
                    "figure"=>true,
                    "nav"=>true,
                    "menu"=>true,
                    "legend"=>true
                ));
            }
            
            /**
             * An array containing HTML tags that get removed entirely, together with their
             * descendants, from the markup.
             *
             * The array consists of tags from the $arr_tags array that have a truely value
             * and a few other tags that are described below.
             *
             * + <noframes>, <noscript>, <style>, <script>, <head>, <map>, <datalist>, <title>, <option>, <colgroup> and <optgroup>
             *   are all invisible elements.
             *   <area>, <col>, <param>, <font>, <base>, <basefont>, <link>, <meta>, <source> and <track> are invisible, too, but
             *   they are void-tags that get removed entirely by strip_tags() and don't require a different method.
             * + <tr>, <td>, <tbody>, <thead>, <caption>, <colgroup> and <col> are descendants of the table element.
             *   <option> and <optgroup> are descendants of the select element (or the datalist element in case of <option>).
             *   <param> is a descendant of the object element.
             *   <source> and <track> are descendants of the video or audio elements.
             *   <title>, <base>, <basefont> and usually <meta> are descendants of the head element.
             *   <area> is a descendant of the map element.
             *   All these elements' parent elements will be removed entirely, therefore these elements don't need to be
             *   removed explicitly. However, these element may appear outside of their usual parent elements, thus remove them
             *   explicitly. <area>, <base>, <basefont>, <meta> and <col> are void-tags that get removed entirely by strip_tags()
             *   and don't require a different method.
             *
             * This array must never be empty.
             */
            $arr_remove=array_merge(array_keys($arr_tags, true), array(
                "noframes", "noscript", "style", "script", "head", "map", "datalist", "title", "option", "colgroup", "optgroup", //invisible
                //"area", "col", "param", "font", "base", "basefont", "link", "meta", "source", "track", //invisible, but void-tags (content-less)
                "td", "th", "tr", "thead", "tbody", "tfoot", "caption" //descendants of <table>
            ));
            
            if($q_semantic){
                /**
                 * If the semantic meaning of elements is taken into account by setting $q_semantic to true,
                 * some elements that are not removed entirely yet may now be removed so, and vice-versa.
                 *
                 * For example:
                 * + <figcaption> is a descendant of the figure element which is removed entirely,
                 *   together with its descendants (e.g. <figcaption>), due to its semantic meaning.
                 *   Therefore the figcaption element doesn't need to be wrapped into spaces (remove
                 *   from $arr_tags) or beeing removed explicitly. However, the element may appear 
                 *   outside of the figure element, thus remove it explicitly (add to $arr_remove).
                 */
                unset($arr_tags["figcaption"]);
                array_push($arr_remove, "figcaption"); //descendant(s) of <figure>
            }
            
            /**
             * The plaintext element breaks the HTML parser.
             * Text following a plaintext element isn't considered to be HTML markup,
             * rather it is treated as plain text.
             *
             * Add the subsequent text to $str_plain and remove it from $str_content.
             * $str_content now ends with "<plaintext ...>".
             */
            $str_plain="";
            
            if(self::preg_match('/^(?:<(?:[^"\'>]*(["\'])(?:\1|(?:.(?!\1))*.\1))*[^"\'>]*>|[^<])*<plaintext(?:>|\s(?:[^"\'>]*(["\'])(?:\2|(?:.(?!\2))*.\2))*[^"\'>]*>)/is', $str_content, $arr_a)==1){
                $str_plain=self::mb_substr($str_content, self::mb_strlen($arr_a[0]));
                $str_content=self::mb_substr($str_content, 0, self::mb_strlen($arr_a[0]));
            }
            
            /**
             * Remove all HTML elements' attributes.
             * A few examples:
             *
             * +   `<input type="text" name="name" />` -> `<input>`
             * +   `<input type="text" name=" /> <strong>A strong text.</strong>` -> `<input>`
             * +   `<input type="text" id="name-input" name=" /> <label for="name-input">A strong text.</label>` -> `<input>`
             * +   `<input type="text" id="name-input" name=" /> <label for="name-input>A strong text.</label>` -> `<input>A strong text.</label>`
             * +   `</label data-name="Carl">` -> `</label>`
             * +   `<value="foo">` -> `<value=>`
             * +   `< >` -> `<>`
             * +   `<span <div style="color:red;">A red <strong>bold</strong> text.</div>` -> `<span>A red <strong>bold</strong> text.</div>`
             * +   `<em class="emphasized"` -> `<em>`
             *
             * Do this after handling a <plaintext> element since the descendants of that element shouldn't be modified.
             */
            $str_content=preg_replace('/(<[^"\'>\s]*)(?:[^"\'>]*(["\'])(?:\2|(?:.(?!\2))*.\2))*[^"\'>]*(?:["\'].*|>)?/s', '$1>', $str_content);
            
            /**
             * Emulate <li>'s and <dd>'s function and styling by prepending a
             * comma (,) to the closing tags.
             */
            $str_content=preg_replace('/\s*<\/(?:li|dd)>/i', ',$0', $str_content);
            
            /**
             * Emulate <dt>'s function and styling by prepending a
             * colon (:) to the closing tags.
             */
            $str_content=preg_replace('/\s*<\/dt>/i', ':$0', $str_content);
            
            /**
             * Emulate <blockquotes>'s and <q>'s function and styling by wrapping
             * their contents into double quotes (").
             */
            $str_content=preg_replace('/<(?:blockquote|q)>\s*/i', '$0"', $str_content); //use &quot;, not ", because of the possibility that the tag exists inside of an attribute-value an could end the attribute-value by " - HTML elements' attrbutes are removed above, this is no longer an argument. thus simply use `"` instead of `&quot;`
            $str_content=preg_replace('/\s*<\/(?:blockquote|q)>/i', '"$0', $str_content); //again &quot;, not ". see line above - no longer relevant
            
            if(/** /!$q_semantic/*/!array_key_exists("figcaption", array_flip($arr_remove))/**/){
                /**
                 * If the semantic meaning of elements is taken into account, this block would be useless
                 * since <figcaption> (and <figure>) would be removed entirely from the markup.
                 *
                 * Simulate <figcaptions>'s function and styling by wrapping its content
                 * into brackets (`(...)`)
                 */
                $str_content=preg_replace('/<figcaption>\s*/i', '$0(', $str_content);
                $str_content=preg_replace('/\s*<\/figcaption>/i', ')$0', $str_content);
            }
            
            /**
             * Prepend a space to all appropiate (i.e. the tags in $arr_tags)
             * tags (opening and closing).
             */
            $str_content=preg_replace('/<\/?(?:'.implode("|", array_map(array(__CLASS__, "escapeRegex"), array_keys($arr_tags))).')>/i', ' $0', $str_content);
            
            /**
             * Remove all tags (without their contents) except the ones
             * contained in $arr_remove, since these should be removed
             * entirely together with their content.
             */
            $str_content=strip_tags($str_content, "<".implode("><", $arr_remove).">");
            
            /**
             * Remove the tags in $arr_remove, together with
             * their content.
             */
            self::preg_match_all('/<(\/?)('.implode("|", array_map(array(__CLASS__, "escapeRegex"), $arr_remove)).')>/i', $str_content, $arr_a, PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
            
            $arr_b=array();
            $str_content_b="";
            $int_pos=0;
            
            /**
             * Loop through all occurrences (opening and closing) of the tags in $arr_remove.
             */
            foreach($arr_a as $val){
                /**
                 * Lowercase the tag name.
                 */
                $str_a=self::mb_strtolower($val[2][0]);
                
                /**
                 * If the current tag name doesn't exist in $arr_b, add it and set it to 0.
                 */
                if(!array_key_exists($str_a, $arr_b)){
                    $arr_b[$str_a]=0;
                }
                
                $int_depth=array_sum($arr_b);
                
                /**
                 * If currently no tag of another tag name is enterered.
                 * This means that the current tag is either of the same type
                 * (tag name) as the one already entered, or no tag has been entered yet
                 * (i.e. currently in root level).
                 */
                if($arr_b[$str_a]==$int_depth){
                    /**
                     * If no tag has been entered yet,
                     * append the part of the content starting after the
                     * last discovered closing tag that entered the root level
                     * again ($int_pos) and ending before the current tag.
                     */
                    if($int_depth==0){
                        $str_content_b.=self::mb_substr($str_content, $int_pos, $val[0][1]-$int_pos);
                    }
                    
                    /**
                     * If the current tag is a closing one,
                     * increase the (deepness) level by 1,
                     * otherwise decrease it by 1.
                     * Manage the level never to be less than 0.
                     */
                    $arr_b[$str_a]=max($arr_b[$str_a]+($val[1][0]!="" ? -1 : 1), 0);
                    
                    /**
                     * Save the position of the current tag's ending to $int_pos.
                     * Actually only necessary for the closing tag that is entering the
                     * root level again.
                     */
                    $int_pos=$val[0][1]+self::mb_strlen($val[0][0]);
                }
            }
            
            /**
             * If the last entered level is the root level,
             * append the text following the last discovered
             * closing tag to the produced content.
             */
            if(array_sum($arr_b)==0){
                $str_content_b.=self::mb_substr($str_content, $int_pos);
            }
            
            $str_content=$str_content_b;
            
            /**
             * Decode HTML entities to real characters.
             */
            $str_content=self::unescapeHtml($str_content);
            
            /**
             * Append the text following the first plaintext element
             * in the markup (if any).
             */
            $str_content.=$str_plain;
        }
        
        /**
         * Replace any kind of whitespaces with a simple space.
         */
        $str_content=preg_replace('/\s/', " ", $str_content);
        
        /**
         * If HTML code, trim the produced content and strip subsequent
         * spaces to 1 space.
         */
        if($q_html){
            $str_content=trim(preg_replace('/ +/', " ", $str_content));
        }
        
        return $str_content;
    }
    
    #GustavBase::__callStatic()#
    /**
     * This "magic" function handles calls of non-defined static methods.
     * More precisely it "defines" the following methods:
     *
     * +   mb_strtoupper()
     * +   mb_strtolower()
     * +   mb_strlen()
     * +   mb_strpos()
     * +   mb_substr()
     * +   mb_substr_count()
     *
     * More or less, the methods are just aliases for the corresponsing multibyte
     * string functions. All of these functions are called with their last parameter,
     * the character encoding, set to the value of GustavBase::ENC.
     * This allows us to use these functions always with the same character encoding,
     * without setting mb_internal_encoding() globally.
     * Required arguments of the supported mb_* functions that are not specified
     * are replaced with null, non-defined optional arguments are set to their default values.
     *
     * Throws a BadMethodCallException if the called method isn't "defined".
     *
     * @param string $function_name The name of the called method.
     * @param array  $arguments     The arguments passed to the called method.
     *
     * @return void
     */
    /*public static function __callStatic($str_fn, $arr_args){
        $arr_mbArgs=array();
        
        foreach(array(
            "strtoupper"=>array(1),
            "strtolower"=>array(1),
            "strlen"=>array(1),
            "strpos"=>array(2, 0), //item 1: number of required parameters;
                                    //item 2+: default values for optional parameters, except for the charset parameter
            "substr"=>array(2, function($arr_args){
                /**
                 * When calculating the default value dynamically using a callback function, as done here,
                 * it's important not to rely on other optional values' default value since these may be
                 * calculated dynamically, too, and may therefore not be calculated when executing this
                 * callback function. 
                 * /
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                return call_user_func(array($str_hooks, "mb_strlen"), $arr_args[0])-$arr_args[1];
            }),
            "substr_count"=>array(2)
        ) as $key=>$val){
            $arr_mbArgs["mb_".$key]=array_merge(array_fill(0, array_shift($val), null), $val, array(self::ENC));
        }
        
        if(array_key_exists($str_fn, $arr_mbArgs)){
            $arr_args=array_merge($arr_args, array_map(function($val) use ($arr_args){
                /**
                 * If you want to pass a callback function as a parameter's default value
                 * without executing it, you have to use a callback function returning the
                 * default-value-callback-function.
                 * /
                return is_callable($val) ? $val($arr_args) : $val;
            }, array_slice($arr_mbArgs[$str_fn], count($arr_args))));
            
            return call_user_func_array($str_fn, $arr_args);
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }*/
    
    #GustavBase::callMbFunc()#
    /**
     * This function calls one of its supported mb_* functions.
     * When calling one of that functions, the items of the array passed to
     * this function are passed to the called function.
     * Missing required parameters are set to null, wich will most likely cause an
     * error, and missing optional parameters are set to their default values.
     * The only exception is the last parameter of each mb_* function - the character encoding
     * definition. If not passed, it is set to the character encoding used by Gustav
     * (`GustavBase::ENC`).
     * This has the same effect as setting the character encoding used by mb_* functions globally
     * using mb_internal_encoding() without doing so and let the user define his own global
     * multibyte string character encoding.
     *
     * Supported mb_* function are:
     *
     * +   mb_strtoupper()
     * +   mb_strtolower()
     * +   mb_strlen()
     * +   mb_strpos()
     * +   mb_substr()
     * +   mb_substr_count()
     *
     * @param string $function_name The name of the mb_* function to call.
     * @param array  $arguments     The arguments to pass to the function.
     *
     * @return mixed The return value of the mb_* function.
     */
    private static function callMbFunc($str_fn, $arr_args){
        $arr_mbArgs=array(
            "mb_strtoupper"=>array(1),
            "mb_strtolower"=>array(1),
            "mb_strlen"=>array(1),
            "mb_strpos"=>array(2, 0), //item 1: number of required parameters;
                                    //item 2+: default values for optional parameters, except for the charset parameter
            "mb_substr"=>array(2, function($arr_args){
                /**
                 * When calculating the default value dynamically using a callback function, as done here,
                 * it's important not to rely on other optional values' default value since these may be
                 * calculated dynamically, too, and may therefore not be calculated when executing this
                 * callback function. 
                 */
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                return call_user_func(array($str_hooks, "mb_strlen"), $arr_args[0])-$arr_args[1];
            }),
            "mb_substr_count"=>array(2)
        );
        $arr_mbArgs=$arr_mbArgs[$str_fn];
        $arr_mbArgs=array_merge(array_fill(0, array_shift($arr_mbArgs), null), $arr_mbArgs, array(self::ENC));
        $arr_mbArgs=array_slice($arr_mbArgs, count($arr_args));
        $arr_mbArgs=array_map(function($val) use ($arr_args){
            /**
             * If you want to pass a callback function as a parameter's default value
             * without executing it, you have to use a callback function returning the
             * default-value-callback-function.
             */
            return is_callable($val) ? $val($arr_args) : $val;
        }, $arr_mbArgs);
        
        $arr_args=array_merge($arr_args, $arr_mbArgs);
        
        return call_user_func_array($str_fn, $arr_args);
    }
    
    #GustavBase::mb_strtoupper()#
    /**
     * This function is just an alias for the corresponsing multibyte
     * string function.
     * That function is called using GustavBase::callMbFunc(). See that function for
     * more information.
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing mb_* function.
     *
     * @see http://php.net/manual/en/function.mb-strtoupper.php mb_strtoupper() in the PHP documentation.
     */
    protected static function mb_strtoupper(){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        return self::callMbFunc($str_fn, $arr_args);
    }
    
    #GustavBase::mb_strtolower()#
    /**
     * This function is just an alias for the corresponsing multibyte
     * string function.
     * That function is called using GustavBase::callMbFunc(). See that function for
     * more information.
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing mb_* function.
     *
     * @see http://php.net/manual/en/function.mb-strtolower.php mb_strtolower() in the PHP documentation.
     */
    protected static function mb_strtolower(){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        return self::callMbFunc($str_fn, $arr_args);
    }
    
    #GustavBase::mb_strlen()#
    /**
     * This function is just an alias for the corresponsing multibyte
     * string function.
     * That function is called using GustavBase::callMbFunc(). See that function for
     * more information.
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing mb_* function.
     *
     * @see http://php.net/manual/en/function.mb-strlen.php mb_strlen() in the PHP documentation.
     */
    protected static function mb_strlen(){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        return self::callMbFunc($str_fn, $arr_args);
    }
    
    #GustavBase::mb_strpos()#
    /**
     * This function is just an alias for the corresponsing multibyte
     * string function.
     * That function is called using GustavBase::callMbFunc(). See that function for
     * more information.
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing mb_* function.
     *
     * @see http://php.net/manual/en/function.mb-strpos.php mb_strpos() in the PHP documentation.
     */
    protected static function mb_strpos(){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        return self::callMbFunc($str_fn, $arr_args);
    }
    
    #GustavBase::mb_substr()#
    /**
     * This function is just an alias for the corresponsing multibyte
     * string function.
     * That function is called using GustavBase::callMbFunc(). See that function for
     * more information.
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing mb_* function.
     *
     * @see http://php.net/manual/en/function.mb-substr.php mb_substr() in the PHP documentation.
     */
    protected static function mb_substr(){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        return self::callMbFunc($str_fn, $arr_args);
    }
    
    #GustavBase::mb_substr_count()#
    /**
     * This function is just an alias for the corresponsing multibyte
     * string function.
     * That function is called using GustavBase::callMbFunc(). See that function for
     * more information.
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing mb_* function.
     *
     * @see http://php.net/manual/en/function.mb-substr-count.php mb_substr_count() in the PHP documentation.
     */
    protected static function mb_substr_count(){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        return self::callMbFunc($str_fn, $arr_args);
    }
    
    #GustavBase::preg_match_all()#
    /**
     * This function is just an alias for the corresponsing PCRE function.
     * The differences between that function and this function are the following:
     *
     * +   The parameter defining the flags defaults to PREG_SET_ORDER, not PREG_PATTERN_ORDER as it does natively.
     * +   The native PCRE function doesn't support capturing of offsets in multibyte strings properly.
     *     It captures the number of bytes preceding the match, rather than the number of characters.
     *     This function fixes that problem.
     * +   The parameter accepting a variable to contain the matches is not required.
     *
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing preg_* function.
     *
     * @see http://php.net/manual/en/function.preg-match-all.php preg_match_all() in the PHP documentation.
     */
    protected static function preg_match_all($re_pattern, $str_a, &$arr_matches=null, $int_flags=PREG_SET_ORDER /*, $int_offset=0*/){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        $arr_args[2]=&$arr_matches;
        $arr_args[3]=$int_flags;
        
        $int_matches=call_user_func_array($str_fn, $arr_args);
        
        if($int_flags&PREG_OFFSET_CAPTURE){
            array_walk($arr_matches, function(&$val) use ($str_a){ //works fine, gegardless of whether `PREG_SET_ORDER` or `PREG_PATTERN_ORDER` is used
                array_walk($val, function(&$val) use ($str_a){
                    $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                    
                    $val[1]=call_user_func(array($str_hooks, "mb_strlen"), substr($str_a, 0, $val[1]));
                });
            });
        }
        
        return $int_matches;
    }
    
    #GustavBase::preg_match()#
    /**
     * This function is just an alias for the corresponsing PCRE function.
     * The differences between that function and this function are the following:
     *
     * +   The native PCRE function doesn't support capturing of offsets in multibyte strings properly.
     *     It captures the number of bytes preceding the match, rather than the number of characters.
     *     This function fixes that problem.
     *
     * For information on this function's parameters and its return value
     * see the PHP documentation on the corresponsing preg_* function.
     *
     * @see http://php.net/manual/en/function.preg-match.php preg_match() in the PHP documentation.
     */
    protected static function preg_match($re_pattern, $str_a, &$arr_match=null, $int_flags=0 /*, $int_offset=0*/){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        $arr_args[2]=&$arr_match;
        /**/
        $arr_args[3]=$int_flags;
        /**/
        
        $int_match=call_user_func_array($str_fn, $arr_args);
        
        if($int_flags&PREG_OFFSET_CAPTURE){
            array_walk($arr_match, function(&$val) use ($str_a){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                $val[1]=call_user_func(array($str_hooks, "mb_strlen"), substr($str_a, 0, $val[1]));
            });
        }
        
        return $int_match;
    }
    
    
    
    #array-functions#
    
    #GustavBase::arrayUnique()#
    /**
     * Makes an array to contain only one occurrence of a string.
     *
     * The strings are compared case-insensitively and only the first occurrence of a string is kept (in its original case).
     *
     * @param string[] $strings           An array containig strings that should be unique.
     * @param bool     $lowercase_strings OPTIONAL | Default: false
     *                                    If set to true, all items are lowercased.
     *
     * @return string[] The passed array containing unique strings only.
     */
    protected static function arrayUnique($arr_a, $q_lowercase=false){
        if($q_lowercase){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            $arr_b=array_unique(array_map(array($str_hooks, "mb_strtolower"), $arr_a));
        }else{
            $arr_b=array();
            
            foreach($arr_a as $val){
                if(!array_key_exists(self::mb_strtolower($val), $arr_b)){
                    $arr_b[self::mb_strtolower($val)]=$val;
                }
            }
        }
        
        return array_values($arr_b);
    }
    
    
    
    #path-functions#
    #url-functions#
    
    #GustavBase::stripPath()#
    /**
     * Strips away a path from the beginning of another path.
     * First a trailing directory separator is removed from the end of $remove_path.
     * After that the two paths are compared. If they match, a directory separator is returned.
     * Otherwise, a directory separator is appended to $remove_path again and the resulting path
     * is stripped away from the beginning of $path.
     * The returned path always has a leading directory separator. The passed paths should have
     * one, too. If they haven't, one is prepended.
     *
     * @param string|string[]      $path        The path to remove the other one from. Gets passed to GustavBase::path().
     * @param string|string[]|null $remove_path OPTIONAL | Default: null
     *                                          The path to remove from the beginning of $path. Gets passed to GustavBase::path().
     *                                          Is set to null, the value of `$_SERVER["DOCUMENT_ROOT"]` is used.
     *
     * @return string The stripped path.
     */
    protected static function stripPath($str_path, $str_path_b=null){
        $str_path=self::path($str_path);
        $str_path_b=is_null($str_path_b) ? $_SERVER["DOCUMENT_ROOT"] : $str_path_b;
        $str_path_b=rtrim(self::path($str_path_b), DIRECTORY_SEPARATOR);
        
        return self::path($str_path==$str_path_b ? "" : self::lstrip($str_path, self::path($str_path_b, "")));
    }
    
    #GustavBase::path()#
    /**
     * Fixes or builds a path.
     *
     * This function is similar to PHP's realpath() funtion. But unlike that function, this function doesn't take the real filesystem into account.
     * Takes a variable number of arguments, being either arrays or strings containing path segments to be joined using the DIRECTORY_SEPARATOR constant.
     * Various actions to fix a broken path are performed:
     *
     * +   Multiple subsequent directory separators are stripped down to one.
     * +   Occurrences of "." path segments are removed.
     * +   Occurrences of ".." path segments are removed together with their preceding path segment.
     *     If no preceding path segment exist, since the ".." is the first path segment, nothing is removed.
     *
     * The returned path always starts with a directory separator.
     * Calling this function with empty arrays as the only parameters will return a directory separator (string).
     *
     * @see http://php.net/manual/en/function.realpath.php PHP documentation on realpath().
     *
     * @param string[]|string $path_segment An array of strings containing path segments or a string representing a single path segment.
     * @param string[]|string $path_segment OPTIONAL
     *                                      Variable number of parameters, each containing an array of strings containing path segments or a string representing a single path segment.
     *
     * @return string Returns a path built from the passed path segments.
     */
    protected static function path($str_part){
        $arr_args=func_get_args(); //Stores the result of func_get_args() because the result can't be passed to a function directly. however, since php 5.3 this is possible
        
        /*uncomment the first comment (this one) and the subsequent code block to disable usage of further parameters when passing an array to the first param. arrays, passed to the other parameters are merged with the others. when switching, remember to adjust the docComment! obsolete* /
        return preg_replace('/'.self::escapeRegex(DIRECTORY_SEPARATOR).'{2,}/', DIRECTORY_SEPARATOR, implode(DIRECTORY_SEPARATOR, is_array($str_part) ? $str_part : $arr_args));
        /*/
        $re_dirSep=self::escapeRegex(DIRECTORY_SEPARATOR);
        
        $str_path=implode(DIRECTORY_SEPARATOR, call_user_func_array("array_merge", array_map(function($val){
            return is_array($val) ? $val : array($val);
        }, array_merge(/**/array(DIRECTORY_SEPARATOR),/**/ $arr_args))));
        $str_path=preg_replace('/'.$re_dirSep.'{2,}/', DIRECTORY_SEPARATOR, $str_path);
        $str_path=preg_replace('/(?<=^|'.$re_dirSep.')\.(?:'.$re_dirSep.'|$)/', "", $str_path);
        
        $re_dirUp='[^'.$re_dirSep.']+'.$re_dirSep.'\.{2}(?:'.$re_dirSep.'|$)';
        
        while(self::preg_match('/'.$re_dirUp.'/', $str_path)==1){
            $str_path=preg_replace('/'.$re_dirUp.'/', "", $str_path);
        }
        
        /** /
        $str_path=preg_replace('/^(?<='.$re_dirSep.')(?:\.{2}(?:'.$re_dirSep.'|$))+/', "", $str_path);
        
        if(!self::strStartsWith($str_path, DIRECTORY_SEPARATOR)){
            $str_path=DIRECTORY_SEPARATOR.$str_path;
        }
        /**/
        
        return $str_path;
        /**/
    }
    
    #GustavBase::path2url()#
    /**
     * Converts an OS-specific path into an URL path.
     *
     * Replaces all occurences of the DIRECTORY_SEPARATOR constant in the passed path with "/" (essential for windows users)
     * and calls rawurlencode() on the path segments.
     * Moreover this function removes the document root from the beginning of the path
     * and appends a "/" to the URL path if the path points on a directory.
     *
     * @param string|string[] $path                   The path to convert to an URL path. Gets passed to GustavBase::path().
     * @param bool            $path_includes_doc_root OPTIONAL | Default: true
     *                                                If set to false, the document root is prepended to the path.
     *                                                It's removed again before converting it.
     *
     * @return string Returns an URL path built from the passed path.
     */
    protected static function path2url($str_path, $q_includesDocRoot=true){
        $str_path=self::path($str_path);
        
        if(!$q_includesDocRoot){
            $str_path=self::path($_SERVER["DOCUMENT_ROOT"], $str_path);
        }
        
        if(@is_dir($str_path)){
            $str_path=self::path($str_path, "");
        }
        
        $str_path=self::stripPath($str_path);
        
        return implode("/", array_map("rawurlencode", explode(DIRECTORY_SEPARATOR, $str_path)));
    }
    
    #GustavBase::url2path()#
    /**
     * Converts an URL path into an OS-specific path.
     *
     * Replaces all occurences of "/" within the passed path with the DIRECTORY_SEPARATOR constant (essential for windows users) and calls rawurldecode() on the path.
     * The returned path always has a leading directory separator.
     *
     * @param string $url_path         The URL path to convert to an OS-specific one.
     * @param bool   $prepend_doc_root OPTIONAL | Default: true
     *                                 If set to `true`, the path of the document root is prepended.
     *
     * @return string Returns an OS-specific path built from the passed URL path.
     */
    protected static function url2path($str_url, $q_prependDocRoot=true){
        $str_path=self::path(explode("/", rawurldecode($str_url)));
        
        /**/
        if($q_prependDocRoot){
            $str_path=self::path($_SERVER['DOCUMENT_ROOT'], $str_path);
        }
        /*/
        $str_path=self::path($_SERVER['DOCUMENT_ROOT'], $str_path);
        /**/
        
        return $str_path;
    }
    
    
    
    #misc-functions#
    
    #GustavBase::short2byte()#
    /**
     * Converts a shorthand byte value into a real byte value.
     *
     * You can get the most information on this topic in the FAQ by PHP.
     * Additionally this function accepts spaces between the number and the symbol as well as spaces wrapping the whole shorthand value.
     * Moreover it supports invalid symbols by ignoring them and treating the specified number as bytes.
     * If no number is specified, 0 is used instead unless a valid symbol is included, in that case 1 (multiplied by the symbol) is used.
     * The case of the symbol doesn't matter, it is handles case-insensitively.
     * Negative numbers aren't supported. Instead 0 is used and the whole term is treated as symbol.
     * Since no symbol starting with "-" exists, the function will return 0.
     *
     * @see http://php.net/manual/en/faq.using.php#faq.using.shorthandbytes Shothand byte values in PHP's FAQ on using PHP.
     *
     * @param string $shorthand The shorthand byte value.
     *
     * @return int The calculated number of bytes described by the shorthand value.
     */
    protected static function short2byte($str_a){
        $str_a=trim($str_a);
        
        self::preg_match('/^\d*/', $str_a, $arr_a); //matches in any case
        
        $str_num=$arr_a[0];
        $arr_symbols=array(
            "g"=>3,
            "m"=>2,
            "k"=>1
        );
        $str_symbol=self::mb_strtolower(trim(self::mb_substr($str_a, self::mb_strlen($str_num))));
        
        if($str_num=="" && array_key_exists($str_symbol, $arr_symbols)){
            $str_num="1";
        }
        
        return (int)$str_num*pow(1024, (int)(@$arr_symbols[$str_symbol])); // if $str_symbol doesn't exist in $arr_symbols: `(int)$str_num * pow(1024, 0)`which equals `(int)$str_num * 1`
    }
    
    #GustavBase::checkIni()#
    /**
     * Checks an INI option's value.
     *
     * Compares the value of an INI option with a specified value.
     * If the option doesn't exist, NULL is returned.
     * If a boolean value was passed to $value, TRUE is returned if the option's value matches the passed one. The values "on" and "1" match TRUE and "off" and "0" (sometimes ini_get() also returns an empty string) match FALSE.
     * When comparing the option's value with NULL, TRUE is returned if the option contains a NULL value (ini_get() returns an empty string).
     * Any other value passed to $value is casted as a string and gets compared to the option's value.
     * In any other case this function returns FALSE.
     *
     * @see http://php.net/manual/de/function.ini-get.php
     *
     * @param string          $option The INI option's name.
     * @param bool|null|mixed $value  The value to check the option's value against.
     *
     * @return bool|null Whether the option's values matches the specified value or null if the option doesn't exist.
     */
    protected static function checkIni($str_ini, $mix_val){
        $mix_a=ini_get($str_ini); //false or <string>
        
        if($mix_a===false){ //configuration option doesn't exist
            return null;
        }
        
        if(is_bool($mix_val)){
            if(self::mb_strtolower($mix_a)=="off"){ //off, 0, <empty string>: false
                $mix_a="0";
            }else if(self::mb_strtolower($mix_a)=="on"){ //on, 1: true
                $mix_a="1";
            }
            
            return (bool)$mix_a==$mix_val; //the strings "0" and "" are convertied to FALSE, any other string is converted to TRUE
        }else if(is_null($mix_val)){
            return $mix_a==""; //<empty string>: NULL - "A boolean ini value of off will be returned as an empty string or "0" [...]" (http://php.net/manual/de/function.ini-get.php) But a null value is returned as an empty string, too!? :o - Not problem at all since the value is relative to the value the option's value should be checked against.
        }else{
            return $mix_a==(string)$mix_val; //other (non-special) string representations
        }
    }
    
    #GustavBase::header()#
    /**
     * Checks whether the header has already been sent back to the client and, if not,
     * adds the specified HTTP header field to the header.
     * Already defined header fields are overwritten.
     *
     * @param string   $header      The header field and value to be appended to the HTTP response header.
     * @param int|null $status_code OPTIONAL | Default: null
     *                              If not null, the status code of the response is set to this parameter's value.
     *
     * @return void
     */
    protected static function header($str_header, $int_status=null){
        $arr_args=array($str_header, true);
        
        if(!is_null($int_status)){
            array_push($arr_args, $int_status);
        }
        
        if(!headers_sent()){
            call_user_func_array("header", $arr_args);
        }
    }
    
    #GustavBase::getSuperglobals()#
    /**
     * Returns all supergloabls.
     *
     * The returned value is an associative array
     * containing the superglobals' names as keys and their values as values.
     * Usually these should be the following variables:
     *
     * +   $GLOBALS
     * +   $_SERVER
     * +   $_GET
     * +   $_POST
     * +   $_FILES
     * +   $_COOKIE
     * +   $_REQUEST
     * +   $_ENV
     *
     * If currently a session is running, also $_SESSION is available as a superglobal.
     *
     * @see http://php.net/manual/en/language.variables.superglobals.php PHP documentation on superglobals.
     *
     * @return array An associative array containing the superglobals.
     */
    protected static function getSuperglobals(){
        /** /
        return array_intersect_key($GLOBALS, array_flip(array_filter(array_keys($GLOBALS), function($val){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            return eval('return isset(${"'.call_user_func(array($str_hooks, "addslashes"), $val).'"});');
        })));
        /*/
        return array_intersect_key($GLOBALS, array_flip(array_filter(array_keys($GLOBALS), function($val){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            return call_user_func(create_function("", 'return eval(\'return isset(${"'.call_user_func(array($str_hooks, "addslashes"), $val).'"});\');'));
        })));
        /**/
    }
    
    #GustavBase::validateVars()#
    /**
     * Validates variable names.
     *
     * Takes an associative array of variables and checks whether their names
     * (the array's keys) are valid variable names.
     * If they aren't, they are filtered out of the array.
     *
     * @see http://php.net/manual/en/language.variables.basics.php PHP documentation on variables and how valid variable names look like.
     *
     * @param array $variables An associative array containing the variables' names as keys and their values as values.
     *
     * @return array The passed array with the invalid variables filtered out.
     */
    protected static function validateVars($arr_vars){
        $arr_superglobals=self::getSuperglobals();
        $arr_validVars=call_user_func(create_function("", 'extract(array_flip(array("'.implode('", "', array_map(array(__CLASS__, "addslashes"), array_keys($arr_vars))).'")), EXTR_SKIP); return get_defined_vars();')); //though supergloabls are not overwritten by extract() due to the EXTR_SKIP flag, get_defined_vars() will list those variable (but only those "extracted" by extract())
        
        $arr_vars=array_intersect_key($arr_vars, array_flip(array_filter(array_keys($arr_validVars), function($val) use ($arr_superglobals){
            return !array_key_exists($val, $arr_superglobals);
        })));
        
        return $arr_vars;
    }
    
    
    
    #scandir-constants#
    
    #GustavBase::SCANDIR_TYPE_FILE#
    /**
     * Include files.
     *
     * If included in the bitmask of GustavBase::scandir()'s second parameter that function's returned array will contain files (if there are some).
     *
     * @type int
     */
    const SCANDIR_TYPE_FILE=1; //1 = `1<<0`
    
    #GustavBase::SCANDIR_TYPE_DIR#
    /**
     * Include directories.
     *
     * If included in the bitmask of GustavBase::scandir()'s second parameter that function's returned array will contain directories (if there are some).
     *
     * @type int
     */
    const SCANDIR_TYPE_DIR=2; //2 = `1<<1`
    
    #GustavBase::SCANDIR_TYPE_LINK#
    /**
     * Include symbolic links.
     *
     * If included in the bitmask of GustavBase::scandir()'s second parameter that function's returned array will contain symbolic links (if there are some).
     *
     * @type int
     */
    const SCANDIR_TYPE_LINK=4; //4 = `1<<2`
    
    #GustavBase::SCANDIR_TYPES#
    /**
     * Include all types of items.
     *
     * Use this constant as value for GustavBase::scandir()'s second parameter, to get an array containing items of all supported types (if there are some).
     *
     * @type int
     */
    const SCANDIR_TYPES=7; //7 = `self::SCANDIR_TYPE_FILE|self::SCANDIR_TYPE_DIR|self::SCANDIR_TYPE_LINK`
    
    
    
    #fs-functions#
    
    #GustavBase::$readFileCache#
    /**
     * The internal cache used by GustavBase::readFile().
     *
     * @type string[]
     */
    private static $readFileCache=array();
    
    #GustavBase::readFile()#
    /**
     * Reads a file.
     *
     * @param string|string[]  $path                The path of the file to read the content from. If $is_url is set to false, this value is passed to GustavBase::path(). If it's set to true, this value should be an URL. If not a URL and if the specified file doesn't exist, false is returned.
     * @param bool             $is_url              OPTIONAL | Default: false
     *                                              When setting this parameter to true, $path sould be a URL. If set to true, the file is read using file_get_contents(), regardless of the value of $execution_arguments. In fact, taht parameter is igroned when this parameter is set to true. If "allow_url_fopen" isn't enabled, FALSE is returned.
     * @param array|mixed|null $execution_arguments OPTIONAL | Default: NULL
     *                                              Setting this parameter to a value other than null means that the file is executed before getting it's content. This is done by using `include` (instead of file_get_contents()) and does therfore work only with PHP files, otherwise no execution will happen.
     *                                              If an array is passed to this parameter, each of the array's values whose key is a valid variablename is made available as a variable named like the key and can be used inside of the included file.
     *                                              A file that gets `include`d should not return any value.
     *                                              If $is_url is set to true, this parameter is ignored completely.
     * @param bool             $use_cache           OPTIONAL | Default: true
     *                                              If set to true, the content is written into and taken from (if present) a Gustav-internal cache (not PHP's cache).
     *                                              Using the cache is only available when $is_url is set to false and $execution_arguments is set to null.
     *
     * @return string|false Returns the file's content on success or false on failure.
     */
    protected static function readFile($str_path, $q_url=false, /*$q_exec=false,*/ $arr_execArgs=null, $q_cache=true){
        /**
         * @param bool $execute_file OPTIONAL. Default: FALSE - Setting this to TRUE means that the file should be executed before getting its content.
         *                           This is done by using file_get_contents() and treating the file as a remote file by converting the path into an URL path using GustavBase::path2url() and prepending the value of the constant GustavBase::HTTP_URL if $execution_arguments is NULL and "allow_url_fopen" is enabled or by using include() in any other case.
         *                           If $is_url is set to TRUE, this parameter is ignored.
         */
        
        if($q_url){ //$str_path is an urlencoded URL
            if(self::checkIni("allow_url_fopen", false)){
                return false;
            }
            
            return @file_get_contents($str_path); //string|false
        }else{
            $str_path=self::path($str_path);
            
            if(!@is_file($str_path)){
                return false;
            }
            
            /*obsolete* /
            if($q_exec){ //$str_path isn't an URL but it's a path to a local resource
                if(self::checkIni("allow_url_fopen", true) && is_null($arr_execArgs)){
                    return @file_get_contents(self::getHttpUrl($str_path)); //string|false
                }else{
                    $arr_args=is_array($arr_execArgs) ? array_intersect_key($arr_execArgs, array_flip(array_filter(array_keys($arr_execArgs), function($val){
                        $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                        
                        return call_user_func(array($str_hooks, "preg_match"), '/^(?!\d)\w+$/u', $val)==1; //use `(?!\d)\w` instead of `[a-zA-Z]` because the latter option doesn't include special characters like "ü".
                    }))) : array();
                    
                    ob_start();
                    
                    if(call_user_func_array(create_function(count($arr_args)>0 ? '$'.implode(', $', array_keys($arr_args)) : "", 'return (include "'.self::addslashes($str_path).'");'), array_values($arr_args))!==false){ //`include` returns either the value returned by the included script (if any) or 1 or false, depending on whether the inclusion was successful.
                        $str_a=ob_get_contents();
                    }else{
                        $str_a=false;
                    }
                    
                    ob_end_clean();
                    
                    return $str_a;
                }
            /*/
            if(!is_null($arr_execArgs)){ //$str_path isn't an URL but it's a path to a local resource
                /** /
                $arr_args=is_array($arr_execArgs) ? array_intersect_key($arr_execArgs, array_flip(array_filter(array_keys($arr_execArgs), function($val){
                    $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                    
                    return call_user_func(array($str_hooks, "preg_match"), '/^(?!\d)\w+$/u', $val)==1; //use `(?!\d)\w` instead of `[a-zA-Z_]` because the latter option doesn't include special characters like "ü".
                }))) : array();
                /*/
                $arr_args=is_array($arr_execArgs) ? self::validateVars($arr_execArgs) : array();
                /**/
                
                ob_start(/** /function(){
                    return "";
                }/**/);
                
                if(call_user_func_array(create_function(count($arr_args)>0 ? '$'.implode(', $', array_keys($arr_args)) : "", 'return (/*@*/include "'.self::addslashes($str_path).'");'), array_values($arr_args))!==false){ //`include` returns either the value returned by the included script (if any) or 1 or false, depending on whether the inclusion was successful.
                    $str_a=ob_get_contents();
                }else{
                    $str_a=false;
                }
                
                ob_end_clean();
                
                return $str_a;
            /**/
            }else{
                if($q_cache){
                    $str_path_b=@realpath($str_path);
                    
                    if($str_path_b!==false && array_key_exists($str_path_b, self::$readFileCache)){
                        return self::$readFileCache[$str_path_b];
                    }
                    if(array_key_exists($str_path, self::$readFileCache)){
                        return self::$readFileCache[$str_path];
                    }
                }
                
                $str_a=@file_get_contents($str_path);
                
                if($q_cache){
                    if($str_a!==false){
                        if($str_path_b!==false){
                            self::$readFileCache[$str_path_b]=$str_a;
                        }
                        self::$readFileCache[$str_path]=$str_a;
                    }
                }
                
                return $str_a;
            }
        }
    }
    
    #GustavBase::file_put_contents()#
    /**
     * This function is just an alias for PHP's file_put_contents() function.
     * The differences between that function and this function are the following:
     *
     * +   Unexisting directories in the dirname of the path are created.
     * +   No warnings are raised.
     *
     * For information on this function's parameters and its return value
     * see the PHP documentation on file_put_contents().
     *
     * @see http://php.net/manual/en/function.file-put-contents.php file_put_contents() in the PHP documentation.
     */
    protected static function file_put_contents($str_path){
        $str_fn=__FUNCTION__;
        $arr_args=func_get_args();
        
        self::mkdir(dirname($str_path));
        
        return @call_user_func_array($str_fn, $arr_args);
    }
    
    #GustavBase::rm()
    /**
     * Deletes a file, directory or a symbolic link.
     * If the item, passed to this function, doesn't exist, the operation is considered to be successful.
     *
     * @param string|string[] $path The path of the item to remove. Gets passed to GustavBase::path().
     *
     * @return bool Whether the item has been removed successfully.
     */
    protected static function rm($str_path){
        $str_path=self::path($str_path);
    
        if(@is_link($str_path)){
            @unlink($str_path);
            @rmdir($str_path); //if PHP runs on a windows server, unlink() doesn't work for links that are pointing on a directory. for such links rmdir() is necessary (see https://bugs.php.net/bug.php?id=52176)
        }else if(@is_file($str_path)){
            @unlink($str_path);
        }else if(@is_dir($str_path)){
            self::cleandir($str_path);
            @rmdir($str_path);
        }
    
        return !(@file_exists($str_path) || @is_link($str_path));
    }
    
    #GustavBase::scandir()#
    /**
     * Scans a directory.
     *
     * Returns an array containing absolute paths of all items found in the specified directory (direct children only, not general descendants) matching one of the specified types.
     *
     * @param string|string[] $path  The path of the directory to scan. Gets passed to GustavBase::path().
     * @param int             $types OPTIONAL. Default: GustavBase::SCANDIR_TYPES
     *                               A bitmask consisting of several GustavBase::SCANDIR_TYPE_* constants specifying which kinds of items should be included in the returned array.
     *
     * @return string[] Returns an array containing paths of all items found in the specified directory matching one of the specified types.
     */
    protected static function scandir($str_path, $int_type=self::SCANDIR_TYPES){
        $str_path=self::path($str_path);
        
        $arr_a=@is_dir($str_path) ? @scandir($str_path) : false; //scandir() -> false|string[]
        
        if($arr_a===false){
            return array();
        }
        
        $arr_a=array_filter($arr_a, function($val){
            return ($val!=".." && $val!=".");
        });
        $arr_a=array_map(function($val) use ($str_path){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            return call_user_func(array($str_hooks, "path"), $str_path, $val);
        }, $arr_a);
        $arr_a=array_filter($arr_a, function($val) use ($int_type){
            return ((!@is_link($val) || $int_type&constant(__CLASS__."::SCANDIR_TYPE_LINK")) && (!@is_file($val) || $int_type&constant(__CLASS__."::SCANDIR_TYPE_FILE")) && (!@is_dir($val) || $int_type&constant(__CLASS__."::SCANDIR_TYPE_DIR")));
        });
        
        return $arr_a;
    }
    
    #GustavBase::cleandir()#
    /**
     * Empties a directory.
     *
     * Deletes all symbolic links, files, as well as subdirectories of a directory recursively.
     * Deletes as much items as it can. If the deletion of one item fails it won't stop its task. However, it will mark the operation as failed.
     *
     * @param string|string[] $path The path of the directory to be emptied. Gets passed to GustavBase::path().
     *
     * @return bool Whether emptying the directory was successful.
     */
    protected static function cleandir($str_path){
        $str_path=self::path($str_path);
        
        $q_a=true;
        
        foreach(self::scandir($str_path) as $val){
            if(!self::rm($val)){
                $q_a=false;
            }
        }
        
        return $q_a;
    }
    
    #GustavBase::mkdir()#
    /**
     * Creates a directory.
     *
     * Creates a directory recursively which means that every non-existing directory within the dirname of the specified directory is created, too.
     * Every directory is created using the mode 777. However, already existing directories may have another mode.
     *
     * @param string|string[] $path The path of the directory to create. Gets passed to GustavBase::path().
     *
     * @return bool Whether creating the directories was successful.
     */
    protected static function mkdir($str_path){
        $str_path=self::path($str_path);
        
        @mkdir($str_path, 0777, true);
        
        return @is_dir($str_path);
    }
    
    
    
    #date-functions#
    
    #GustavBase::getDateBegin()#
    /**
     * Get the first second of a day.
     *
     * @param int|string|null $timestamp OPTIONAL | Default: NULL
     *                                   A unix timestamp or a datetime string whose day's first second should be calculated. If set to NULL, the current time is used.
     *
     * @return int|false Returns a unix timestamp representing the first second of the day specified by the passed timestamp or false on failure.
     */
    public static function getDateBegin($int_ts=null){
        if(is_null($int_ts)){
            $int_ts=time();
        }else if(is_string($int_ts)){
            $int_ts=strtotime($int_ts);
            
            if($int_ts===false){
                return false;
            }
        }
        
        return mktime(0, 0, 0, date("n", $int_ts), date("j", $int_ts), date("Y", $int_ts));
    }
    
    #GustavBase::getDateEnd()#
    /**
     * Get the last second of a day.
     *
     * @param int|string|null $timestamp OPTIONAL | Default: NULL
     *                                   A unix timestamp or a datetime string whose day's last second should be get. If set to NULL, the current time is used.
     *
     * @return int|false Returns a unix timestamp representing the last second of the day specified by the passed timestamp or false on failure.
     */
    public static function getDateEnd($int_ts=null){
        /**/
        $int_begin=self::getDateBegin($int_ts);
        
        return $int_begin===false ? false : $int_begin+(60*60*24-1);
        /*/
        if(is_null($int_ts)){
            $int_ts=time();
        }else if(is_string($int_ts)){
            $int_ts=strtotime($int_ts);
            
            if($int_ts===false){
                return false;
            }
        }
        
        return mktime(23, 59, 59, date("n", $int_ts), date("j", $int_ts), date("Y", $int_ts));
        /**/
    }
    
    
    
    #deprecated-functions#
    
    #GustavBase::getConstants()
    /**
     * Get related constants.
     *
     * Get all constants whose names match a prefix (case-insensitively).
     *
     * @param string $prefix            The prefix for filtering the constants.
     * @param bool   $user_defined_only OPTIONAL | Default: true
     *                                  If set to true, only user-defined constants are used.
     *
     * @return array An associative array containing the matching constants' values and their names as keys.
     */
    /*protected static function getConstants($str_prefix, $q_user=true){
        if($q_user){
            $arr_constants=get_defined_constants(true);
            $arr_constants=array_key_exists("user", $arr_constants) ? $arr_constants["user"] : array();
        }else{
            $arr_constants=get_defined_constants();
        }
        
        $arr_constants=array_intersect_key($arr_constants, array_flip(array_filter(array_keys($arr_constants), function($val) use ($str_prefix){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            return call_user_func(array($str_hooks, "strStartsWith"), $val, $str_prefix, true);
        })));
        
        return $arr_constants;
    }*/
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
