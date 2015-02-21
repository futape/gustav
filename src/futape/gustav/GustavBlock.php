<?php
/*! Gustav 1.0.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));

use RuntimeException, BadMethodCallException;

class GustavBlock extends Gustav {
    
    
    
    #misc-constants#
    
    #GustavBlock::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavBlock class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavBlockHooks";
    
    
    
    #block-functions#
    
    #GustavBlock::parseBlock()#
    /**
     * Parses a GvBlock out of a SRC file's content.
     *
     * Options' names are trimmed. After trimming them, options' names must not be empty, nor can they be "!", nor can they consist of consecutive occurrences of "_default" only. Otherwise they are removed from the GvBlock.
     * If an option doesn't have a value (without a ":" delimiter) (i.e it's a boolean option), true is used as the option's value.
     * If not described differently for a specific option, its value get trimmed and is taken literally which means that an "\n" doesn't mean a newline character, but rather it means a literal `'\n'`, 2 characters, a backslash and the lowercased letter "N" (0x006e).
     * All options support templating. Using this feature, it's possible to insert a newline character into the value (for example using "{{PHP_EOL}}"), even if the option doesn't support this explicitly.
     *
     * This is the first function called in the process of building a GvBlock.
     *
     * @param string &$content The SRC file's content from which to get the GvBlock. If a variable was passed, it will contain the SRC file's content without the GvBlock.
     *
     * @return array Returns the parsed GvBlock.
     */
    protected static function parseBlock(&$str_content){
        $arr_realStr=array_flip(array("_ext_content"));
        $arr_vars=array();
        
        if(self::preg_match('/^(?:<\?(?:php)?(?:\r\n?|\n)\/\*)?(-{5})BEGIN GV BLOCK\1[\r\n]+/', $str_content, $arr_a)==1 && self::preg_match('/[\r\n]+(-{5})END GV BLOCK\1(?:\*\/)?(?:\r\n?|\n|$)/', $str_content, $arr_b, PREG_OFFSET_CAPTURE)==1){
            $arr_lines=explode("\n", self::unl(self::mb_substr($str_content, self::mb_strlen($arr_a[0]), $arr_b[0][1]-self::mb_strlen($arr_a[0]))));
            
            $str_content=self::mb_substr($str_content, $arr_b[0][1]+self::mb_strlen($arr_b[0][0]));
            
            foreach($arr_lines as $val){
                $arr_a=explode(":", $val);
                $str_a=trim(array_shift($arr_a));
                
                /**
                 * Remove invalid/broken options.
                 * Depending on their names - not on their contents.
                 * Their contents are checked using GustavBlock::finalizeBlock().
                 *
                 * Check option on special meaning and remove it if the option's name isn't valid.
                 * An option isn't valid if its name indicates a special meaning
                 * but doesn't contain all necessary information.
                 *
                 * Invalid optionnames are those consisting of occurrences of "_default" only.
                 * Moreover empty names ("") and names equal to "!" are invalid.
                 * The names are checked after trimming them.
                 */
                if(preg_replace('/(?:_default)+$/', "", $str_a)=="" || $str_a=="!"){ //exclude useless and invalid options due to their special (but incorrectly used) meaning
                    continue;
                }
                
                if(count($arr_a)>0){
                    $arr_vars[$str_a]=implode(":", $arr_a);
                    
                    if(array_key_exists($str_a, $arr_realStr)){
                        $arr_vars[$str_a]=self::realStr($arr_vars[$str_a]);
                    }else{
                        $arr_vars[$str_a]=trim($arr_vars[$str_a]);
                    }
                }else{
                    $arr_vars[$str_a]=true;
                }
            }
        }
        
        return $arr_vars;
    }
    
    #GustavBlock::extendBlock()#
    /**
     * Extends a GvBlock.
     * Options whose names start with "_ext_" and the option "_ext" itself as well as options starting with "!" aren't passed down to the extending GvBlock.
     * Extending GvBlocks over multiple levels is supported. 
     * An option of the extending GvBlock starting with "!" removes the equally named (without the leading "!") option from the GvBlock to extend.
     * An extending GvBlock's option overwrites the appropiate option of the extended GvBlock, regardless of whether its value is valid or not.
     * The value of an option of the extended GvBlock can be included in the appropiated option's value of the extending GvBlock by using templating. The extended GvBlock's value is available by the variable $ext.
     * Even if _ext_* options aren't inherited automatically if not defined in the extending GvBlock, their values can be inherited by defining them as `_ext_content:{{$ext}}`, for example, in the extending GvBlock. The only option that can't be extended is the _ext option.
     * If an option of the extended GvBlock isn't inherited automatically because it has been disabled in the extending GvBlock using `!<option name>`, it can still be defined by the extending GvBlock. The value of the option of the extended GvBlock even can still be inherited by using a value of `{{$ext}}`.
     * When doing so and the value of the extended GvBlock's option isn't a string, the template is replaced by an empty string.
     *
     * This is the second function called in the process of building a GvBlock, following GustavBlock::parseBock().
     *
     * @param array $gvblock  The extending GvBlock. May very likely be an array returned by Gustav::parseBlock().
     * @param string|string[] The path of the SRC file the GvBlock was extracted from. Gets passed to GustavBlock::finalizeBlock() which in turn calls GustavBase::path() on the path.
     *
     * @return array Returns the extending GvBlock with all of the extended GvBlock's options inherited, if not prevented, and `{{$ext}}` templating placeholders resolved.
     */
    protected static function extendBlock($arr_block, $str_path){ //block must be parsed but not finalized
        //$str_path=self::path($str_path); //not necessary since $str_path is just passed through to finalizeBlock() which calls path() on the path. $str_path isn't used for another purpose in this function
        
        $arr_block_b=self::templBlock($arr_block); //just for _ext
        $arr_block_b=self::finalizeBlock($arr_block_b, $str_path, false); //just for _ext; dont check required options because some required options may not be set since the block may rely on inherited options but does not have extended another block yet (i.e. extendBlock() hasn't been completed yet).

        if(array_key_exists("_ext", $arr_block_b)){
            $str_extPath=self::path($_SERVER['DOCUMENT_ROOT'], $arr_block_b["_ext"]);
            $str_extContent=self::readFile($str_extPath);
            
            if($str_extContent!==false){
                //do not templ and finalize extBlock! just parse and inherit
                $arr_extBlock=self::parseBlock($str_extContent);
                $arr_extBlock=self::extendBlock($arr_extBlock, $str_extPath);
                
                foreach($arr_extBlock as $key=>$val){
                    if(!self::strStartsWith($key, "_ext_") && $key!="_ext" && !self::strStartsWith($key, "!") && !array_key_exists($key, $arr_block) && !array_key_exists("!".$key, $arr_block)){
                        $arr_block[$key]=is_string($val) ? '{{$ext}}' : true;
                    }
                }
            
                array_walk($arr_block, function(&$val, $key) use ($arr_extBlock){
                    $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                    
                    if($key!="_ext"){
                        if(is_string($val)){
                            $val=call_user_func(array($str_hooks, "templ"), $val, array(
                                "ext"=>is_string(@$arr_extBlock[$key]) ? $arr_extBlock[$key] : ""
                            ), false, false);
                        }
                    }
                });
            }
        }
        
        return $arr_block;
    }
    
    #GustavBlock::templBlock()#
    /**
     * Resolves all templating placeholders in the block's options' string values.
     * Constants as well as the following "special" variables will be resolved.
     *
     * +   src_dir: The value of the configuration option specifying the path source directory (Gustav::CONF_SRC_DIR). Ends with a directory separator.
     * +   dest_dir: The value of the configuration option specifying the path destination directory (Gustav::CONF_DEST_DIR). Ends with a directory separator.
     *
     * This is the third function called in the process of building a GvBlock, following GustavBlock::parseBock() and GustavBlock::extendBock().
     *
     * @param array $gvblock The GvBlock whose options' values should be resolved as templates. May very likely be an array returned by Gustav::extendBock().
     *
     * @return array Returns the passed GvBlock, with all (available) templating placeholders resolved.
     */
    protected static function templBlock($arr_block){
        /**
         * An associative array of template variables.
         *
         * @type string[]
         */
        $arr_vars=array(
            "src_dir"=>self::path(self::getConf(self::CONF_SRC_DIR), ""),
            "dest_dir"=>self::path(self::getConf(self::CONF_DEST_DIR), "")
        );
        
        //unset($arr_vars["ext"]); //resolution of `{{$ext}}` already has been done above in extendBlock(), therfore no occurrences of `{{$ext}}` should be found any more
        
        array_walk($arr_block, function(&$val, $key) use ($arr_vars){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            if(is_string($val)){
                $val=call_user_func(array($str_hooks, "templ"), $val, $arr_vars);
            }
        });
        
        return $arr_block;
    }
    
    #GustavBlock::finalizeBlock()#
    /**
     * Finalizes a GvBlock.
     *
     * Validates a GvBlock and resets invalid options to their defaults or removes them completely from the GvBlock.
     * Options' values may be converted (into another datatype) and processed using a option-specific processor-function.
     * Options starting with "!" are removed from the GvBlock.
     * *_default options starting with "_", which means that they are Gustav core properties, are also removed from the GvBlock.
     * More precisely the following actions are applied to the GvBlock:
     *
     * 1.  GvBlock entries starting with "!" are removed from the GvBlock.
     * 2.  Options' default values are added to the GvBlock as *_default options.
     * 3.  Options and the appropiate *_default options are merged together.
     * 4.  For every option the merged values are validated and the first valid value is used.
     *     If no valid value could be found, the option is removed from the GvBlock.
     * 5.  If, after the first valid value has been found, there are still merged values,
     *     not taken into account yet since a valid value has already been found,
     *     the option's merged values are unmerged again and added to the GvBlock as *_default entries.
     *     These entries' values may be valid or not.
     *     For Gustav core properties starting with "_" all of the above doesn't apply.
     *     For those options only the first valid value is kept and all other merged values are removed
     *     from the GvBlock.
     * 6.  The valid values are converted and processed to get the options' final values.
     * 7.  If no valid values could be found for required options (i.e. _conv, _templ and _tags), a Gustav-error is raised.
     *
     * This is the fourth and last function called in the process of building a GvBlock, following GustavBlock::parseBock(), GustavBlock::extendBock() and GustavBlock::templBock().
     *
     * @param array           $gvblock                The GvBlock that should be finalized. May very likely be an array returned by Gustav::templBlock()
     * @param string|string[] $path                   The path of the SRC file the GvBlock was extracted from. Gets passed to GustavBase::path().
     * @param bool            $check_required_options OPTIONAL | Default: true
     *                                                If set to true, a Gustav-error is raised if a required option isn't set or if its value isn't valid. Otherwise, no Gustav-errors are thrown.
     *
     * @return array Returns the finalized GvBlock.
     */
    protected static function finalizeBlock($arr_block, $str_path, $q_checkRequiredOptions=true){
        $str_path=self::path($str_path);
        
        /**
         * Options' default values must be a string or the boolen `true`. `NULL` is considered to be invalid.
         * Using callbacks (lambda-functions) as one of an option's default values indicates that that value depends on the final value of another option and can therefore be not gotten until the value it depends on is defined.
         * This requirement is not considered when dealing with options where "prefer" is set to true. In such options using callbacks as values is supported but they don't indicate that the value depends on another option's final value.
         * Callbacks are called and their returned values are taken as values.
         * If the option, a callback default value depends on contains callbacks as default values, too, make sure that the option whose callback relies on the other option's callback's returned value is defined AFTER the other option.
         * Also make sure that an option's callback-default-value doesn't rely on another option's callback-default-value which in turn depends on the first option's final value (returned by its callback function). This would cause a dependency circle and the options can't be put into the right order (described above).
         * In fact the information required by the first defined option are not given when calling that callback (the value of the option, the callback depends on would be an array containing the callback-default-value (lambda-function) for that option as its first item).
         * The computed result would be incorrect and therefore the result of the second option, that depends on the first option's result, would be incorrect, too.
         * Moreover you have to check within the callbacks whether the options, the callback depends on are defined.
         * Also use references when using $arr_block inside of callbacks (i.e. `function() use (&$arr_block)`) because the array changes after the callback has been defined.
         */
        $arr_defaults=array(
            "_conv"=>array(
                "prefer"=>true,
                "values"=>array((string)pathinfo($str_path, PATHINFO_EXTENSION)) //empty strings (`pathinfo("file", PATHINFO_EXTENSION)`-> `null`, `(string)null` -> `""`) are removed from the _conv array below
            ),
            "_templ"=>array(
                "prefer"=>false,
                "values"=>array("")
            ),
            "_dest"=>array(
                "prefer"=>false,
                
                //if not starting with a directory separator, the dest_dir and the dirname of the source file, relative to the src_dir, is prepended (see below).
                //if not ending with a directory separator, an appropiate extension is appended (see below), otherwise "index" followed by that extension is appended (in GustavDest::initPath()).
                //only source files whose filename starts with `_` followed by at least one other character are considered to be disaled. therefore, use `/^_(?=.)/` to remove a leading `_`.
                "values"=>array(ltrim(self::path(preg_replace('/^_(?=.)/', "", pathinfo($str_path, PATHINFO_FILENAME)), ""), DIRECTORY_SEPARATOR))
            ),
            "_tags"=>array(
                "prefer"=>false,
                "values"=>array("")
            )/* this isn't possibel because _fu_getContent() calls _fu_getBlock() on the same file, which in turn calls _fu_Block() which will call _fu_getContent() for that file again and so on -> max stack count - created GustavSrc::initDesc() * /,
            "_desc"=>array(
                "prefer"=>false,
                "values"=>array(
                    function() use ($str_path, &$arr_block){
                        $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                        
                        if(!array_key_exists("_conv", $arr_block)){
                            return null;
                        }
                        
                        return call_user_func(array($str_hooks, "inline"), _fu_getContent($str_path), !_fu_convExists($arr_block["_conv"], _FU_CONV_TEXT));
                    }
                )
            )/**/
        );
        $arr_a=array(self::path(self::stripPath(dirname($str_path), array($_SERVER['DOCUMENT_ROOT'], self::getConf(self::CONF_SRC_DIR))), "__base"));
        
        while(true){
            $str_a=dirname($arr_a[count($arr_a)-1]);
            $str_b=dirname($str_a); //`dirname("/")` -> `"/"`
            
            if($str_b==$str_a){ //root of src_dir for 2nd time
                break;
            }
            
            array_push($arr_a, self::path($str_b, "__base"));
        }
        if(basename($str_path)=="__base"){ //if the source file creating the GvBlcok for is a `__base` file, that file doesn't need to be in the `_ext` list (array)
            array_shift($arr_a);
        }
        
        /**
         * At this point, $arr_a looks something like this:
         *
         *     array(
         *         "/category/sub-category/__base",
         *         "/category/__base",
         *         "/__base"
         *     )
         *
         * (for a source file located under "<src_dir>/category/sub-category/article.txt".)
         * The paths are relative to the src dir.
         */
        $arr_defaults["_ext"]=array(
            "prefer"=>false,
            "values"=>$arr_a
        );
        
        ksort($arr_block);
        
        /**
         * At this point, $arr_block looks something like this:
         *
         *     array(
         *         "!_dyn"=>true,
         *         "!_pub"=>true,
         *         "_conv"=>"html",
         *         "_conv_default"=>"plain",
         *         "_title"=>"Hello world",
         *         "_title_default"=>"Lorem ipsum",
         *         "_title_default_default_default=>"3rd default title",
         *         "bam_default"=>"42",
         *         "cat"=>true,
         *         "foo"=>"bar",
         *         "foo_default_default=>"baz"
         *     )
         */
        foreach($arr_block as $key=>$val){
            unset($arr_block[$key]);
            
            if(self::strStartsWith($key, "!")){ //remove `!` optiosn from the GvBlock
                continue;
            }
            
            $str_a=preg_replace('/(?:_default)+$/', "", $key);
            
            if(!array_key_exists($str_a, $arr_block)){
                $arr_block[$str_a]=array();
                
                if($key!=$str_a){
                    array_push($arr_block[$str_a], null); //use `null` as the array's first item if no non-`_default` option has been specified for that option (see `bam` option below for example)
                }
            }
            
            array_push($arr_block[$str_a], $val);
        }
        
        //$arr_a=array();
        
        /**
         * At this point, $arr_block looks something like this:
         *
         *     array(
         *         "_conv"=>array(
         *             "html",
         *             "plain"
         *
         *             //if `"_conv_default"=>"html", "_conv_default_default"=>"plain"`
         *             //would have been defined instead:
         *             //null,
         *             //"html",
         *             //"plain"
         *         )
         *         "_title"=>array(
         *             "Hello world",
         *             "Lorem ipsum",
         *             "3rd default title"
         *         ),
         *         "bam"=>array(
         *             null,
         *             "42"
         *         ),
         *         "cat"=>array(
         *             true
         *         ),
         *         "foo"=>array(
         *             "bar",
         *             "baz"
         *         )
         *     )
         */
        foreach($arr_defaults as $key=>$val){
            if(!array_key_exists($key, $arr_block)){
                $arr_block[$key]=array();
            }
            
            $mix_a=array_shift($arr_block[$key]); //returns `null` if the array is empty. also `null` is returned if no non-`_default` option has been specified fot that option since `null` is used as the array's first item in that case (see above)
            
            if($val["prefer"]){
                $arr_block[$key]=array_merge(array_map(function($val){
                    return is_callable($val) ? $val() : $val;
                }, $val["values"]), $arr_block[$key]);
            }else{
                $arr_block[$key]=array_merge($arr_block[$key], $val["values"]); //callback "system default values" are executed below
            }
            if(!is_null($mix_a)){ //if the option doesn't had exist before or if no non-`_default` option has been specified for that option, no value need to be prepended to the array
                array_unshift($arr_block[$key], $mix_a);
            }
            
            //$arr_a[$key]=true; //just to bring the right order into $arr_a/$arr_block
        }
        $arr_block=array_merge(/**/$arr_defaults/*/$arr_a/**/, $arr_block); //use array_merge() just to bring the right order (of $arr_defaults) into $arr_block. $arr_block will always contain all elements of $arr_defaults and will therefore overwrite $arr_defaults's values.
        
        /**
         * An associative array containing the options' names as keys and their processor functions as values.
         * A processor function takes 1 argument containing the option's value.
         * It validates that value, processes a usable value out of the original value and returns the processed value.
         * Returning NULL indicates, that the original value is invalid.
         * Moreover a processor function must not rely on another option's value since it's unpredictable whether the option the processor depends on is defined at the point the processor function is called.
         * The best way to calculate values depending on another option's value, is to do that later at #finalizeBlock-processor-dependency.
         * [But if you do so, the way the value is treated (depending on another option) must be suitable for every value the option that gets modified may contain at that point. (?)]
         * If no processor exists for an option, the orignal value is used as it is, no matter of its contents (unless it's NULL. In that case the value is considered to be invalid).
         */
        $arr_processors=array(
            "_templ"=>function($mix_a){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                $q_a=false;
                
                if(is_string($mix_a)){
                    $q_a=true;
                    
                    if($mix_a==""){ //an empty string (after getting trimmed (see GustavBlock::parse()) turns into an empty array (array()) and will result in dest: *.txt - obsolete - now, only html- and php-dest-files are supported. however, an empty array results in no templates to be used.
                        $mix_a=array();
                    }else{
                        //empty templs ('templA.templB..templC.  .templD.') are removed
                        //                            |        |        |
                        //                            +--------+--------+-- these ones
                        $mix_a=array_filter(array_map("trim", explode(".", $mix_a)), function($val){
                            return $val!="";
                        });
                        
                        if(count($mix_a)==0){ //at least one templ must not be an empty string ('', see above)
                            $q_a=false;
                        }else{
                            foreach($mix_a as $val){ //all not-empty ('', see above) templs must exist
                                if(!@is_file(call_user_func(array($str_hooks, "path"), $_SERVER['DOCUMENT_ROOT'], call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_TEMPLS_DIR")), $val.".php"))){
                                    $q_a=false;
                                    
                                    break;
                                }
                            }
                        }
                    }
                }
                if(!$q_a){
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_conv"=>function($mix_a){
                $q_a=false;
                
                if(is_string($mix_a)){
                    /** /
                    $mix_a=array_filter(array_map("trim", explode(".", $mix_a)), function($val){
                        $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                        
                        return ($val!="" && call_user_func(array($str_hooks, "convExists"), $val));
                    });
                    /*/
                    $arr_conv=array_map("trim", explode(".", $mix_a));
                    $mix_a=array();
                    $str_nextConv=null;
                    
                    while(count($arr_conv)>0){
                        $val=array_shift($arr_conv);
                        
                        if($val!=""){
                            GustavContentHooks::convContent("", $val, &$str_nextConv_b); //false: converter not found; null: hardcoded html converter; <string>: other hardcoded or user-defined converter
                            
                            if($str_nextConv_b!==false){
                                array_push($mix_a, $val);
                                
                                $str_nextConv=$str_nextConv_b;
                            }
                        }
                        
                        if(count($arr_conv)==0 && !is_null($str_nextConv)){
                            array_push($arr_conv, $str_nextConv);
                            
                            $str_nextConv=null;
                        }
                    }
                    /**/
                    
                    if(count($mix_a)>0){ //at least one converter must not be an empty string ('') and must exist
                        $q_a=true;
                    }
                }
                if(!$q_a){
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_tags"=>function($mix_a){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                if(is_string($mix_a)){
                    $mix_a=call_user_func(array($str_hooks, "arrayUnique"), array_map(function($val) use ($str_hooks){
                        return call_user_func(array($str_hooks, "inline"), $val, false);
                    }, array_filter(array_map("trim", explode(",", $mix_a)), function($val){
                        return $val!="";
                    })));
                }else{
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_dest"=>function($mix_a) use ($str_path){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                if(is_string($mix_a)){
                    $mix_a=str_replace(call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_REPLACE_DIR_SEP")), DIRECTORY_SEPARATOR, $mix_a);
                    
                    if(!call_user_func(array($str_hooks, "strStartsWith"), $mix_a, DIRECTORY_SEPARATOR)){
                        $str_srcDir=call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_SRC_DIR"));
                        $str_destDir=call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_DEST_DIR"));
                        
                        $str_a=call_user_func(array($str_hooks, "path"), $str_destDir, call_user_func(array($str_hooks, "stripPath"), dirname($str_path), array($_SERVER["DOCUMENT_ROOT"], $str_srcDir)));
                    }else{
                        $str_a="";
                    }
                    
                    if(call_user_func(array($str_hooks, "strEndsWith"), $mix_a, DIRECTORY_SEPARATOR)){
                        $mix_a=call_user_func(array($str_hooks, "path"), $str_a, $mix_a);
                    }else{
                        $mix_a=rtrim(call_user_func(array($str_hooks, "path"), $str_a, dirname($mix_a), pathinfo($mix_a, PATHINFO_FILENAME)), DIRECTORY_SEPARATOR); //rtrim() actually unnecessary
                    }
                }else{
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_ext"=>function($mix_a){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                $q_a=false;
                
                if(is_string($mix_a) && $mix_a!=""){ //pfadangabe relativ zum src_dir. src_dir wird vorangestellt - actually checking for `$mix_a!=""` is unnecessary since `<src_dir>/` would point on a directory instead of a file and the value for _ext would be considered to be invalid
                    $mix_a=str_replace(call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_REPLACE_DIR_SEP")), DIRECTORY_SEPARATOR, $mix_a);
                    
                    $mix_a=call_user_func(array($str_hooks, "path"), call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_SRC_DIR")), $mix_a);
                    
                    if(@is_file(call_user_func(array($str_hooks, "path"), $_SERVER['DOCUMENT_ROOT'], $mix_a))){
                        $q_a=true;
                    }
                }
                if(!$q_a){
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_ext_content"=>function($mix_a){
                if(!is_string($mix_a)){
                    $mix_a="";
                }
                
                return $mix_a;
            },
            "_pub"=>function($mix_a){
                $q_a=false;
                
                if(is_string($mix_a)){
                    $mix_a=strtotime($mix_a);
                    
                    /**
                     * "Vor PHP 5.1.0 gab die Funktion -1 im Fehlerfall zurueck." That's crap!
                     * How could you distinguish betweet a failure and the unix timestamp for the date
                     * 1969-12-31 23:59:59 UTC ?! :o
                     * /
                    if($mix_a!==false && $int_a!=-1){
                    /*/
                    if($mix_a!==false){
                    /**/
                        $q_a=true;
                    }
                }
                if(!$q_a){
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_title"=>function($mix_a){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                if(is_string($mix_a)){
                    $mix_a=call_user_func(array($str_hooks, "inline"), $mix_a, false);
                }else{
                    $mix_a=null;
                }
                
                return $mix_a;
            },
            "_desc"=>function($mix_a){
                /** /
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                if(is_string($mix_a)){
                    $mix_a=call_user_func(array($str_hooks, "inline"), $mix_a, false); //GvBlock's _desc option contains plain text (without line-breaks because these would end/break the option, except for linebreaks by writing something like '{{PHP_EOL}}'. but such linebreaks are removed by GustavBase::inline()). an empty string ('') is possible/valid/allowed
                }else{
                    $mix_a=null;
                }
                /*/
                if(!is_string($mix_a)){
                    $mix_a=null;
                }
                /**/
                
                return $mix_a;
            }
            
            /**
             * Other GvBlock core properties (mostly boolean options):
             * +   _hidden
             * +   _dyn
             */
        );
        
        /**
         * At this point, $arr_block looks something like this:
         *
         *     array(
         *         "_conv"=>array(
         *             "html",
         *             "txt",
         *             "plain"
         *
         *             //if `"_conv_default"=>"html", "_conv_default_default"=>"plain"`
         *             //would have been defined instead:
         *             //"txt",
         *             //"html",
         *             //"plain"
         *             //because the "_conv" default is marked as `"prefer"=>true`.
         *             //if it wouldn't:
         *             //"html",
         *             //"plain"
         *             //"txt"
         *             //regardless of whether `"_conv"=>"html", "_conv_default"=>"plain"`
         *             //or `"_conv_default"=>"html", "_conv_default_default"=>"plain"` were defined
         *         ),
         *         "_templ"=>array(
         *             ""
         *         ),
         *         "_dest"=>array(
         *             "article/"
         *         ),
         *         "_tags"=>array(
         *             ""
         *         ),
         *         //the "_desc" default is actually not defined (i.e. commented out).
         *         //"_desc"=>array(
         *         //    function(){ ... } //if the "_desc" default would have
         *         //                      //been marked as `"prefer"=>true`,
         *         //                      //the function would have been executed
         *         //                      //and the returned value would have been
         *         //                      //used instead of the function itself
         *         //),
         *         "_ext"=>array(
         *             "/category/sub-category/__base",
         *             "/category/__base",
         *             "/__base"
         *         ),
         *         "_title"=>array(
         *             "Hello world",
         *             "Lorem ipsum",
         *             "3rd default title"
         *         ),
         *         "bam"=>array(
         *             "42"
         *         ),
         *         "cat"=>array(
         *             true
         *         ),
         *         "foo"=>array(
         *             "bar",
         *             "baz"
         *         )
         *     )
         */
        /** /
        $arr_options=null;
        
        while(is_null($arr_options) || count($arr_options)>0){
            $arr_options_b=array();
            
            foreach($arr_block as $key=>$val){
                if(is_null($arr_options) || array_key_exists($key, $arr_options)){
                    while(count($val)>0){
                        if(is_callable($val[0])){
                            if(is_null($arr_options)){ //if is_null() returns true, it's the first loop
                                $arr_options_b[$key]=true;
                                
                                break;
                            }else{
                                $val[0]=$val[0]();
                            }
                        }
                        
                        if(!is_null($val[0]) && array_key_exists($key, $arr_processors)){
                            $val[0]=$arr_processors[$key]($val[0]);
                        }
                        if(!is_null($val[0])){
                            break;
                        }
                        
                        array_shift($val);
                    }
                    if(count($val)>0){
                        $arr_block[$key]=$val;
                    }else{
                        unset($arr_block[$key]);
                    }
                }
            }
            $arr_options=$arr_options_b;
        }
        /*/
        $arr_block_b=$arr_block; //remaining properties
        $arr_block=array();
        
        for($i=0; /** /$i<2/*/count($arr_block_b)>0/**/; $i++){
            foreach($arr_block_b as $key=>$val){
                $q_a=true;
                
                while(count($val)>0){
                    if(is_callable($val[0])){
                        if($i==0){
                            $q_a=false;
                            
                            break;
                        }else{
                            $val[0]=$val[0]();
                        }
                    }
                    
                    if(!is_null($val[0]) && array_key_exists($key, $arr_processors)){
                        $val[0]=$arr_processors[$key]($val[0]);
                    }
                    
                    if(!is_null($val[0])){
                        break;
                    }
                    
                    array_shift($val);
                }
                
                if($q_a){
                    if(count($val)>0){
                        $arr_block[$key]=/** /$val/*/array_filter($val, function($val){
                            return (!is_null($val) && !is_callable($val));
                        })/**/;
                    }
                
                    unset($arr_block_b[$key]);
                }
            }
        }
        /**/
        
        /**
         * At this point, $arr_block looks something like this:
         *
         *     array(
         *         "_conv"=>array(
         *             "html"
         *         ),
         *         "_templ"=>array(
         *             array()
         *         ),
         *         "_dest"=>array(
         *             "/dest/category/sub-category/article/"
         *         ),
         *         "_tags"=>array(
         *             array()
         *         ),
         *         "_title"=>array(
         *             "Hello world",
         *             "Lorem ipsum",
         *             "3rd default title"
         *         ),
         *         "bam"=>array(
         *             "42"
         *         ),
         *         "cat"=>array(
         *             true
         *         ),
         *         "foo"=>array(
         *             "bar",
         *             "baz"
         *         )
         *     )
         *
         * The array is not sorted by a specific order.
         * It does no longer contain any (anonymous/callback/lambda) functions or `null` values.
         */
        foreach($arr_block as $key=>$val){
            /**/unset($arr_block[$key]);/**/ //actually unnecessary
            
            foreach($val as $i=>$val_b){
                if($i>0 && self::strStartsWith($key, "_")){ //no *_default entries for Gustav core properties in GvBlock
                    break;
                }
                
                $arr_block[$key.str_repeat("_default", $i)]=$val_b;
            }
        }
        
        #finalizeBlock-processor-dependency#
        if(array_key_exists("_dest", $arr_block)){
            /** /
            if(!array_key_exists("_templ", $arr_block)){
                unset($arr_block["_dest"]); //do this before the `if($q_checkRequiredOptions)`-block, sothat an Gustav-error is thrown if the _dest option is removed (since it's a required option)
            }else{
                $arr_block["_dest"]=GustavDestHooks::finalizePath($arr_block["_dest"], $arr_block, true); //depends on the values of _templ and _dyn, therefore do it at this point, not in the _dest processor function
            }
            /*/
            $arr_block["_dest"]=GustavDestHooks::finalizePath($arr_block["_dest"], $arr_block, true); //depends on the value of _dyn (i.e. whether that option is set since it's a boolean option), therefore do it at this point, not in the _dest processor function
            /**/
        }
        
        if($q_checkRequiredOptions){
            if(!array_key_exists("_conv", $arr_block)){
                self::error("No valid converter could be discovered. Requested source file: ".$str_path);
            }
            if(!array_key_exists("_templ", $arr_block)){
                self::error("No valid and existing template(s) could be found. Requested source file: ".$str_path);
            }
            if(!array_key_exists("_tags", $arr_block)){
                self::error('No tags were specified for source file "'.$str_path.'"');
            }
            if(!array_key_exists("_dest", $arr_block)){
                self::error('No destination location has been specified for source file "'.$str_path.'"');
            }
        }
        
        return $arr_block;
    }
    
    
    
    #properties#
    
    #GustavBlock::$path#
    /**
     * The path of the SRC file the GvBlock has been extracted from.
     *
     * @type string
     */
    private $path;
    
    #GustavBlock::$content#
    /**
     * The SRC file's content with the GvBlock definition stripped away.
     *
     * @type string
     */
    private $content;
    
    #GustavBlock::$block#
    /**
     * The GvBlock.
     *
     * @type array
     */
    private $block;
    
    
    
    #init-functions#
    
    #GustavBlock::__construct()#
    /**
     * A "magic" function that gets called when a new instance of this class is created.
     * If the passed path doesn't point on a file, a RuntimeException is thrown.
     * If the file's content can't be read, a RuntimeException is thrown, too.
     * If everything worked properly, the newly created object is initialized.
     *
     * @param string $path The path of the SRC file to build the GvBlock from. Gets passed to GustavBase::path().
     *
     * @return void
     */
    public function __construct($str_path){
        $this->path=self::path($str_path);
        
        if(!@is_file($this->path)){
            throw new RuntimeException("Source file doesn't exist.");
        }
        
        $this->content=self::readFile($this->path);
        
        if($this->content===false){
            throw new RuntimeException("Couldn't read contents of source file.");
        }
        
        $this->initBlock();
    }
    
    #GustavBlock::initBlock()#
    /**
     * Creates the GvBlock array and initializes the object's $block property.
     *
     * @return void
     */
    private function initBlock(){
        //parse
        $this->block=self::parseBlock($this->content);
        
        //inherit
        $this->block=self::extendBlock($this->block, $this->path);
        
        //templating
        $this->block=self::templBlock($this->block);
        
        //finalize
        $this->block=self::finalizeBlock($this->block, $this->path);
    }
    
    
    
    #getter-functions#
    
    #GustavBlock::__call()#
    /**
     * A "magic" overloading function that gets called when an object's non-reachable function is called.
     * This function is used to emulate global getter functions for some of the object's properties. The following getters are available:
     *
     * +   getPath(): The path of the SRC file the GvBlock has been extracted from ($path property).
     * +   getContent(): The content of the SRC file with the GvBlock definition stripped away ($content property).
     *
     * If any other non-reachable function is called, a BadMethodCallException exception is thrown.
     *
     * @param string $function_name The name of the called function.
     * @param array  $arguments     The arguments passed to the called function.
     *
     * @return mixed
     */
    public function __call($str_fn, $arr_args){
        $str_getterPrefix="get";
        $arr_getters=array_flip(array_map(function($val) use ($str_getterPrefix){
            return $str_getterPrefix.ucfirst($val);
        }, array(
            "path",
            "content"
        )));
        
        if(array_key_exists($str_fn, $arr_getters)){
            return $this->{lcfirst(self::lstrip($str_fn, $str_getterPrefix))};
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }
    
    #GustavBlock::get()#
    /**
     * Returns either a single option of the GvBlock or the whole GvBlock as an array.
     *
     * @param string|null $option OPTIONAL | Default: null
     *                            The name of the GvBlock's option whose value should be returned. If it doesn't exist, null is returned.
     *                            If set to null, the whole GvBlock is returned as an array.
     *
     * @return array|mixed|null The whole GvBlock or just a single option's value.
     */
    public function get($str_prop=null){
        if(!is_null($str_prop)){
            return @$this->block[$str_prop];
        }else{
            return $this->block;
        }
    }
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavDest.php"));
/**/
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavContent.php"));
/**/
