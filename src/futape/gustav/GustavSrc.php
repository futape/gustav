<?php
/*! Gustav 1.0.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));

use Exception, RuntimeException, BadMethodCallException;

class GustavSrc extends Gustav {
    
    
    
    #misc-constants#
    
    #GustavSrc::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavSrc class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavSrcHooks";
    
    
    
    #properties#
    
    #GustavSrc::$path#
    /**
     * The path of the SRC file represented by this object.
     *
     * @type string
     */
    private $path;
    
    #GustavSrc::$content#
    /**
     * The SRC file's content with the GvBlock definition stripped away.
     *
     * @type GustavContent
     */
    private $content;
    
    #GustavSrc::$block#
    /**
     * A GustavBlock object representing the SRC file's GvBlock.
     *
     * @type GustavBlock
     */
    private $block;
    
    #GustavSrc::$isDis#
    /**
     * Whether the SRC file is disabled.
     * For disabled SRC files no DEST file can be created.
     * Moreover they don't appear in the result of Gustav::query().
     * SRC files can be disabled by prepending a "_" to their filenames.
     *
     * @type bool
     */
    private $isDis;
    
    #GustavSrc::$desc#
    /**
     * The description of the SRC file.
     * This may be either the value of the "_desc" GvBlock option, if specified,
     * or the description is generated from the SRC file's content.
     *
     * @type string
     */
    private $desc;
    
    #GustavSrc::$category#
    /**
     * The category of the SRC file.
     * An array that contains the single segments of the SRC file's category path as its item,
     * the uppermost one as its first item, that category's sub-category as its second item and so on.
     * If the SRC file is located in the root of the source directory, this property will be an empty array.
     *
     * @type string[]
     */
    private $category;
    
    
    
    #init-functions#
    
    #GustavSrc::__construct()#
    /**
     * A "magic" function that gets called when a new instance of this class is created.
     * If the passed path doesn't point on a file, a RuntimeException is thrown.
     * If no GustavContent object can be created for the passed path, a RuntimeException is thrown, too.
     * The same happens if no GustavBlock object can be created.
     * If everything worked properly, the newly created object is initialized.
     *
     * @param string $path The path of the SRC file represented by this object. Gets passed to GustavBase::path().
     *
     * @return void
     */
    public function __construct($str_path){
        $this->path=self::path($str_path);
        
        if(!@is_file($this->path)){
            throw new RuntimeException("Source file doesn't exist.");
        }
        
        try {
            $this->content=new GustavContent($this->path);
        } catch(Exception $e){
            throw new RuntimeException("Couldn't process source content.");
        }
        
        try {
            $this->block=new GustavBlock($this->path);
        } catch(Exception $e){
            throw new RuntimeException("Couldn't process GvBlock.");
        }
        
        $this->initIsDis();
        $this->initDesc();
        $this->initCategory();
    }
    
    #GustavSrc::initIsDis()#
    /**
     * Checks whether the SRC file is disabled and initializes the object's $isDis property.
     * For disabled SRC files no DEST file can be created.
     * Moreover they don't appear in the result of Gustav::query().
     * SRC files can be disabled by prepending a "_" to their filenames.
     *
     * @return void
     */
    private function initIsDis(){
        $this->isDis=self::preg_match('/^_./', pathinfo($this->path, PATHINFO_FILENAME))==1;
    }
    
    #GustavSrc::initDesc()#
    /**
     * Creates an inline, plaintext description for the SRC file and and initializes the object's $desc property.
     * If the "_desc" GvBlock option exist, that description is used. Otherwise the description is built from the SRC file's content.
     *
     * @return void
     */
    private function initDesc(){
        $arr_block=$this->block->get();
        
        if(array_key_exists("_desc", $arr_block)){
            $this->desc=$arr_block["_desc"];
        }else{
            $this->desc=self::inline($this->content->get(), /**/self::convExists($arr_block["_conv"][count($arr_block["_conv"])-1], self::CONV_HTML)/**/);
        }
    }
    
    #GustavSrc::initCategory()#
    /**
     * Gets the category of the SRC file and initializes the object's $category property.
     * The category is taken from the folder structure starting at the root of the source directory and ending at the directory the SRC file is locating in.
     * The resulting array will contain the uppermost category as its first item, that category's sub-category as its second item and so on.
     * If the SRC file is located in the root of the source directory, the category will be an empty array.
     * Before getting the category the SRC file's path is resolved using realpath() to remove symbolic links, occurences of "./" or "../" and sequences of "/".
     * If realpath() fails, the category is set to an empty array.
     * The created array may be used for filling a breadcrumb navigation for example.
     *
     * @return void
     */
    private function initCategory(){
        $str_path=@realpath($this->path);
        $str_a=$str_path!==false ? trim(self::stripPath(dirname($str_path), array($_SERVER['DOCUMENT_ROOT'], self::getConf(self::CONF_SRC_DIR))), DIRECTORY_SEPARATOR) : "";
        
        $this->category=$str_a!="" ? explode(DIRECTORY_SEPARATOR, $str_a) : array();
    }
    
    
    
    #getter-functions#
    
    #GustavSrc::__call()#
    /**
     * A "magic" overloading function that gets called when an object's non-reachable function is called.
     * This function is used to emulate global getter functions for some of the object's properties. The following getters are available:
     *
     * +   getPath(): The path of the SRC file represented by this object ($path property).
     * +   getDesc(): The SRC file's description ($desc property).
     * +   getCategory(): The category of the SRC file ($category property).
     * +   isDis(): Whether the SRC file is disabled ($isDis property).
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
            "desc",
            "category",
            "content"
        )));
        
        if(array_key_exists($str_fn, $arr_getters)){
            return $this->{lcfirst(self::lstrip($str_fn, $str_getterPrefix))};
        }
        
        $arr_directGetters=array_flip(array(
            "isDis"
        ));
        
        if(array_key_exists($str_fn, $arr_directGetters)){
            return $this->{$str_fn};
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }
    
    #GustavSrc::getBlock()#
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
    
    #GustavSrc::getMeta()#
    /**
     * Get meta information of the SRC file.
     * The returned array is perfectly suitable as value for the first parameter of GustavBase::highlightMatches().
     *
     * @return array Returns an associative array containing the SRC file's meta information.
     *               The returned array contains the following items:
     *
     *                   array(
     *                       GustavBase::KEY_TAGS=>array("one tag", "of the SRC file", "another tag"),
     *                       "category"=>array("Technology", "Apple", "iOS"), //Result of GustavSrc::getCategory().
     *                       "desc"=>"This is an inline description of the SRC file.", //Result of GustavSrc::getDesc().
     *                       GustavBase::KEY_FILE=>"dest-file", //Last path segment retrieved from the GvBlock's "_dest" option.
     *                       "path"=>"/dest/dest-file/", //"_dest" option of GvBlock, converted to a URL path. Not has to be a full path
     *                                                   //but can be the dirname only if the DEST file's filename is "index.*"
     *                                                   //(as you can see in the example).
     *                       "url"=>"http://example.com/dest/dest-file/", //A URL referencing the DEST file.
     *                       "src"=>"/usr/www/users/example/src/dest-file.md", //The OS-specific path of the SRC file, relative to the server's root.
     *
     *                       //The following items are optional and exist only if the corresponding options of the SRC file's GvBlock exist.
     *                       GustavBase::KEY_TITLE=>"The SRC file's title", //"_title" option of GvBlock.
     *                       "pub"=>1383130658, //"_pub" option of GvBlock.
     *                       "pub_rss"=>"Wed, 30 Oct 2013 11:57:38 +0100" //GvBlock's "_pub" option, formatted using `DATE_RSS`.
     *                   )
     * /
    public function getMeta(){
        $arr_block=$this->block->get();
        $arr_meta=array(
            self::KEY_TAGS=>$arr_block["_tags"],
            "category"=>$this->category,
            "desc"=>$this->desc,
            self::KEY_FILE=>basename($arr_block["_dest"]), //dest file (basename)
            "path"=>self::path2url($arr_block["_dest"], false), //dest file (url path, relative to doc root)
            "url"=>self::getHttpUrl($arr_block["_dest"], false), //dest file (url)
            //"dest"=>,//GustavDest::getPath()
            "src"=>$this->path //src file (os-specific path, relative to server root)
        );
        
        if(array_key_exists("_title", $arr_block)){
            $arr_meta[self::KEY_TITLE]=$arr_block["_title"];
        }
        if(array_key_exists("_pub", $arr_block)){
            $arr_meta["pub"]=$arr_block["_pub"];
            $arr_meta["pub_rss"]=date(DATE_RSS, $arr_block["_pub"]);
        }
        
        return $arr_meta;
    }
    /**/
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavBlock.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavContent.php"));
