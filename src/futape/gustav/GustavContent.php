<?php
/*! Gustav 1.0.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));

use Exception, RuntimeException, BadMethodCallException;

class GustavContent extends Gustav {
    
    
    
    #misc-constants#
    
    #GustavContent::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavContent class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavContentHooks";
    
    
    
    #content-functions#
    
    #Gustav::prepareContent()#
    /**
     * Checks whether the SRC file is a PHP file (i.e. its extension equals "php" case-insensitively). If it is, it gets executed and the resulting content
     * is used as content. Otherwise the passed, unmodified content is used instead.
     *
     * This is the first function called in the process of building the content.
     *
     * @param string $content The (non-executed) SRC file's content.
     * @param string $path    The path of the SRC file.
     *
     * @return string The content. Either the one that has been passed to this function or the resulting content of the executed SRC file.
     */
    protected static function prepareContent($str_content, $str_path){
        $str_path=self::path($str_path);
        
        if(self::mb_strtolower((string)pathinfo($str_path, PATHINFO_EXTENSION))=="php"){
            $str_content=self::readFile($str_path, false, true);
        }
        
        return $str_content;
    }
    
    #Gustav::finalizeContent()#
    /**
     * Finalizes a SRC file's content.
     *
     * Extends other SRC files (if specified) and extends the content.
     *
     * Steps:
     *
     * 1.  If _ext is defined:
     *         If content is empty or constists of whitespaces only (not 3.):
     *             Use content (not converted) of extended SRC file instead.
     * 2.  Convert content using the SRC file's converter(s).
     * 3.  If _ext is defined:
     *         If the SRC file's original content is not empty and doesn't consist of whitespaces only (not 1.):
     *             If _ext_content is defined:
     *                 If the final content of the extended SRC file isn't empty:
     *                     Concatenate final content of extended SRC file and the content (converted, see 2.)
     *                     of the SRC file separated by the value of _ext_content.
     *                     Extended SRC file's content first, then the content the extending SRC file.
     *
     * Notes on the steps:
     *
     * 1.: If the extended SRC file (src2) matches the conditions in 1., too, the content of the SRC file (src3) extended by src2 is not converted, too.
     *     The same applies to a SRC file (src4) that gets extended by src3 if src3 matches the conditions in 1., too. And so on for src5, src6 ...
     *     If src2 would match the conditions in 3., the content of src3 would be converted using src3's converter(s) and is concatenated with the content (not converted) of src2.
     *     The content of src2 in turn wouldn't be converted using the converter(s) of src2.
     *     The concatenated content (src3's one converted, src2's one not converted) would then be converted using the converter(s) of src1.
     *     Therefore the content of src3 would be converted twice: first with its own converter(s) and then with the one(s) of src1.
     *     -> transparent extension
     * 3.: The content of the extended SRC file (src2) is converted using the converter(s) of src2.
     *     It's built completely independent of the extending SRC file (src1).
     *     -> isolated extension
     *
     * This is the second and last function called in the process of building the content, following GustavContent::prepareContent().
     *
     * @param string $content             The prepared content. May very likely be a string returned by GustavContent::prepareContent().
     * @param array  $gvblock             The SRC file's GvBlock.
     * @param bool   $convert_content     OPTIONAL | Default: true
     *                                    If set to false, the content isn't converted using the converter(s) of the SRC file.
     *                                    The content of an extended SRC file that gets concatenated with the extending SRC file will still be converted using its own converter(s). 
     *
     * @return string Returns the finalized content.
     */
    protected static function finalizeContent($str_content, $arr_block, $q_conv=true){
        $q_empty=trim($str_content)=="";
        $str_extPath=array_key_exists("_ext", $arr_block) ? self::path($_SERVER['DOCUMENT_ROOT'], $arr_block["_ext"]) : null;
        
        if($q_empty){
            $str_content="";
            
            if(!is_null($str_extPath)){
                /*obsolete* /
                try {
                    $content_ext=new self($str_extPath); //calls `self::finalizeContent(..., ..., true)` (converted) which isn't intended - use alternative codeblock in second comment instead to prevent this
                } catch(Exception $e){
                    $content_ext=null;
                }
                
                if(!is_null($content_ext)){
                    $str_extContent=$content_ext->content; //works just fine since you can access private properties from within the object's class
                    $str_extContent=self::finalizeContent($str_extContent, $content_ext->getBlock()->get(), false);
                    
                    $str_content=$str_extContent;
                }
                /*/
                try {
                    $block_ext=new GustavBlock($str_extPath);
                } catch(Exception $e){
                    $block_ext=null;
                }
                
                if(!is_null($block_ext)){
                    $str_extContent=$block_ext->getContent();
                    
                    //prepare (execute if php-source-file) and finalize (without converting)
                    $str_extContent=self::prepareContent($str_extContent, $str_extPath);
                    $str_extContent=self::finalizeContent($str_extContent, $block_ext->get(), false);
                    
                    //at this point, $str_extContent does never consist of whitespaces only. instead it would be empty ("")
                    $str_content=$str_extContent;
                }
                /**/
            }
        }
        
        if($str_content!=""){ //at this position $str_content won't contain a string consisting of whitespaces only. instead the string would be empty (""). therfore we don't need to call trim() any more
            if($q_conv){
                foreach($arr_block["_conv"] as $val){
                    $str_content=self::convContent($str_content, $val);
                }
            }
        }
        
        if(!$q_empty){
            if(!is_null($str_extPath)){
                if(array_key_exists("_ext_content", $arr_block)){
                    try {
                        $content_ext=new self($str_extPath);
                    } catch(Exception $e){
                        $content_ext=null;
                    }
                    
                    if(!is_null($content_ext)){
                        $str_extContent=$content_ext->get();
                        
                        if($str_extContent!=""){ //here again: empty string instead of whitespaces only -> no trim()
                            $str_content=$str_extContent.$arr_block["_ext_content"].$str_content;
                        }
                    }
                }
            }
        }
        
        return $str_content;
    }
    
    #Gustav::convContent()#
    /**
     * Converts text.
     *
     * Converts text using the specified converter. If the converter doesn't exist, the original text is returned.
     *
     * @param string            $content         The content to convert.
     * @param string            $converter       The name of the converter that should be used.
     * @param string|false|null &$next_converter OPTIONAL
     *                                           A variable passed to this parameter will contain the converter name returned by the used converter.
     *                                           For the hardcoded plain text converter the value will be "html" while being null for the hardcoded HTML converter.
     *                                           Although returning "html" or null has a very similar effect, user-defined converters should always prefer "html" over null.
     *                                           If the converter doesn't exist, the variable will contain false.
     *                                           The converter name passed to the variable may not exist.
     *
     * @return string The converted content.
     */
    protected static function convContent($str_content, $str_conv, &$str_nextConv=null){
        $str_nextConv=false;
        
        if(self::convExists($str_conv, self::CONV_HTML)){
            //do nothing
            
            $str_nextConv=null;
        }else if(self::convExists($str_conv, self::CONV_TEXT)){
            $str_content=self::plain2html($str_content);
            
            $str_nextConv=@array_shift(explode(".", self::CONV_HTML));
        }else{ //before this line, all hardcoded converters, such as text/plain/txt and html/htm, must be handled
            if(self::convExists($str_conv)){
                /**/
                ob_start(/** /function(){
                    return "";
                }/**/);
                
                $str_nextConv=(string)call_user_func(create_function('$gv', 'return (/*@*/include "'.self::addslashes(self::path(self::GV_DIR, self::EXT_DIR, self::CONV_DIR, $str_conv.".php")).'");'), $str_content);
                
                $str_content=ob_get_contents();
                
                ob_end_clean();
                /*/ //obsolete
                $str_content=call_user_func(function($gv) use ($str_conv){
                    $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                    
                    return (@include call_user_func(array($str_hooks, "path"), constant(__CLASS__."::GV_DIR"), constant("__CLASS__."::EXT_DIR"), constant(__CLASS__."::CONV_DIR"), $str_conv.".php"));
                    
                }, $str_content);
                /**/
            }
        }
        
        return $str_content;
    }
    
    
     
    #properties#
    
    #GustavContent::$path#
    /**
     * The path of the SRC file containing the content.
     *
     * @type string
     */
    private $path;
    
    #GustavContent::$content#
     /**
     * The content.
     *
     * @type string
     */
    private $content;
    
    #GustavContent::$block#
     /**
     * The SRC file's GvBlock.
     *
     * @type GustavBlock
     */
    private $block;
    
    
    
    #init-functions#
    
    #GustavContent::__construct()#
    /**
     * A "magic" function that gets called when a new instance of this class is created.
     * If the passed path doesn't point on a file, a RuntimeException is thrown.
     * If the file's GvBlock can't be processed, a RuntimeException is thrown, too.
     * If everything worked properly, the newly created object is initialized.
     *
     * @param string $path The path of the SRC file containing the content. Gets passed to GustavBase::path().
     *
     * @return void
     */
    public function __construct($str_path){
        $this->path=self::path($str_path);
        
        if(!@is_file($this->path)){
            throw new RuntimeException("Source file doesn't exist.");
        }
        
        try {
            $this->block=new GustavBlock($this->path);
        } catch(Exception $e){
            throw new RuntimeException("Couldn't process GvBlock.");
        }
        
        $this->initContent();
    }
    
    #GustavContent::initContent()#
    /**
     * Prepares and finalizes the content and initializes the object's $content property.
     *
     * @return void
     */
    private function initContent(){
        $this->content=$this->block->getContent();
        
        //prepare
        $this->content=self::prepareContent($this->content, $this->path);
        
        //finalize
        $this->content=self::finalizeContent($this->content, $this->block->get());
    }
    
    
    
    #getter-functions#
    
    #GustavContent::__call()#
    /**
     * A "magic" overloading function that gets called when an object's non-reachable function is called.
     * This function is used to emulate global getter functions for some of the object's properties. The following getters are available:
     *
     * +   getPath(): The path of the SRC file containing the content ($path property).
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
            "path"
        )));
        
        if(array_key_exists($str_fn, $arr_getters)){
            return $this->{lcfirst(self::lstrip($str_fn, $str_getterPrefix))};
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }
    
    #GustavContent::getBlock()#
    /**
     * A global getter function returning the SRC file's GvBlock ($block property)
     * or just one of its options' values.
     * See GustavBlock::get() for more information.
     *
     * @param string|null $option OPTIONAL | Default: null
     *                            The name of the GvBlock's option whose value should be returned. If it doesn't exist, null is returned.
     *                            If set to null, the GustavBlock object is returned.
     *
     * @return GustavBlock|mixed|null The whole GvBlock or just a single option's value.
     */
    public function getBlock($str_prop=null){
        if(!is_null($str_prop)){
            return $this->block->get($str_prop);
        }
        
        return $this->block;
    }
    
    #GustavContent::get()#
    /**
     * Returns the finalized content.
     *
     * @return string The finalized content.
     */
    public function get(){
        return $this->content;
    }
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavBlock.php"));
