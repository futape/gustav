<?php
namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));

use Exception, BadMethodCallException, RuntimeException;

class GustavDest extends Gustav {
    
    
    
    #misc-constants#
    
    #GustavDest::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavDest class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavDestHooks";
    
    
    
    #dest-functions#
    
    #Gustav::finalizePath()#
    /**
     * Finalizes a dest path by appending "index" and the right file extension if only the dest file's dirname has been specified or just the correct file extension if also a (extension-less) filename has been specified (and $reverse is set to true).
     * If the passed destination path ends with a directory separator, this function assumes that the passed path describes a dirname.
     *
     * @param string $dest_path The unfinalzed path of the destination file. Gets passed to GustavBase::path().
     * @param array  $gvblock   The SRC file's GvBlock.
     * @param bool   $reverse   OPTIONAL | Default: false
     *                          By default "index.<ext>" is appended to the passed DEST path if a trailing directory separator has been found.
     *                          If this parameter is set to true, only <ext> is appended if NO trailing directory separator was found.
     *
     * @return string The finalized DEST path.
     */
    protected static function finalizePath($str_path, $arr_block, $q_reverse=false){
        /**/
        $str_path=self::path($str_path);
        /**/
        
        $str_ext=".".(array_key_exists("_dyn", $arr_block) ? "php" : "html");
        
        if(self::strEndsWith($str_path, DIRECTORY_SEPARATOR)){
            if(!$q_reverse){
                $str_path=self::path($str_path, "index".$str_ext);
            }
        }else{
            if($q_reverse){
                $str_path.=$str_ext;
            }
        }
        
        return $str_path;
    }
    
    
    
    #properties#
    
    #GustavDest::$src#
    /**
     * A GustavSrc object representing the source of this DEST file.
     *
     * @type GustavSrc
     */
    private $src;
    
    #GustavDest::$path#
    /**
     * The path of the DEST file.
     *
     * @type string
     */
    private $path;
    
    #GustavDest::$content#
    /**
     * The DEST file's final content.
     *
     * @type string
     */
    private $content;
    
    
    
    #init-functions#
    
    #GustavDest::__construct()#
    /**
     * A "magic" function that gets called when a new instance of this class is created.
     * If no GustavSrc object can be created for the passed path, a RuntimeException is thrown.
     * If everything worked properly, the newly created object is initialized.
     *
     * @param string|string[] $src_path The path of the SRC file creating the DEST file for.
     *                                  Gets passed to GustavSrc::__construct() which in turn calls GustavBase::path() on the path.
     *
     * @return void
     */
    public function __construct($str_srcPath){
        try {
            $this->src=new GustavSrc($str_srcPath);
        } catch(Exception $e){
            throw new RuntimeException("Couldn't process source file.");
        }
        
        $this->initPath(); //depends on $this->src
        $this->initContent(); //depends on $this->path
    }
    
    #GustavDest::initPath()#
    /**
     * Builds the real DEST path for a SRC file and initializes the object's $path property.
     *
     * "Real DEST path" means that, unlike a GvBlock's "_dest" option (in some cases), the returned path is the DEST file's full path and not only the most necessary part of the path, for example the dirname if the DEST file's filename would be "index.*".
     * The returned path isn't relative to the document root but t'is an absolute path, relative to the server's root.
     *
     * @return void
     */
    private function initPath(){
        $arr_block=$this->src->getBlock()->get();
        $str_dest=$arr_block["_dest"];
        $str_dest=self::finalizePath(self::path($_SERVER["DOCUMENT_ROOT"], $str_dest), $arr_block);
        
        $this->path=$str_dest;
    }
    
    #GustavDest::initContent()#
    /**
     * Builds the final content of the DEST file and initializes the object's $content property.
     * The SRC file's content gets wrapped into the SRC file's templates.
     * Within the template files a variable $gv is available containing an array looking like the one below:
     *
     *     array(
     *         "dest"=>[GustavDest object],
     *         "src"=>[GustavSrc object], //Also available via `$gv["dest"]->getSrc()`.
     *         "templ"=>array(
     *             "id"=>"blog_template",
     *             GustavBase::KEY_FILE=>"blog_template.php",
     *             "path"=>"/usr/www/users/example/templates/blog_template.php",
     *             "total"=>3,
     *             "index"=>0
     *         ),
     *         "content"=>"Hello world." //The result of the last template. Before the first template this is the content of the SRC file.
     *     )
     *
     * The SRC file's GvBlock, category and description are available via the corresponding methods of the GustavSrc object.
     *
     * @return void
     */
    private function initContent(){
        $src_a=$this->src;
        $arr_templ=$src_a->getBlock("_templ");
        $str_srcContent=$src_a->getContent()->get();
        
        $str_destContent=$str_srcContent;
        
        if(count($arr_templ)>0){
            $arr_gv=array(
                "dest"=>$this,
                "src"=>$this->src,
                "templ"=>array(
                    "total"=>count($arr_templ)
                )
            );
            
            foreach(array_reverse($arr_templ) as $i=>$val){
                $str_templPath=self::path($_SERVER['DOCUMENT_ROOT'], self::getConf(self::CONF_TEMPLS_DIR), $val.".php");
                
                $str_destContent=self::readFile($str_templPath, false, array(
                    "gv"=>array_merge_recursive($arr_gv, array(
                        "templ"=>array(
                            "id"=>$val,
                            self::KEY_FILE=>basename($str_templPath),
                            "path"=>$str_templPath, //os-specific path, relative to server root
                            "index"=>$i
                        ),
                        "content"=>$str_destContent
                    ))
                ));
            }
        }
        
        $this->content=$str_destContent;
    }
    
    
    
    #getter-functions#
    
    #GustavDest::__call()#
    /**
     * A "magic" overloading function that gets called when an object's non-reachable function is called.
     * This function is used to emulate global getter functions for some of the object's properties. The following getters are available:
     *
     * +   getPath(): The path of the destination file ($path property).
     * +   getSrc(): The GustavSrc object for the used source file ($src property).
     * +   getContent(): The final content of the destination file ($content property).
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
            "src",
            "path",
            "content"
        )));
        
        if(array_key_exists($str_fn, $arr_getters)){
            return $this->{lcfirst(self::lstrip($str_fn, $str_getterPrefix))};
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }
    
    #GustavDest::getPhp()#
    /**
     * Get a dynamic DEST file's content.
     *
     * Returns the PHP code for including GustavGenerator.php, generating the final DEST content dynamically, printing it and setting the Content-Type header.
     * The returned value may be written to a dynamic DEST file.
     *
     * @return string Returns the dynamic DEST file's PHP code.
     */
    public function getPhp(){
        $str_path=$this->src->getPath();
        
        return '<?php require_once "'.self::addslashes(self::path(self::GV_DIR, "GustavGenerator.php")).'"; use futape\gustav\GustavGenerator; GustavGenerator::gen("'.self::addslashes(self::stripPath($str_path, array($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_SRC_DIR)))).'", true); ?>';
    }
    
    
    
    #dest-functions#
    
    #GustavDest::createFile()#
    /**
     * Creates a DEST file.
     *
     * Creates the DEST file.
     * Moreover this function creates the directories to place the built file in if they don't exist.
     * If the SRC file is disabled, no DEST file is created and false is returned.
     *
     * The content of a dynamic DEST file, created from a SRC file whose GvBlock's _dyn option is set, contains the hardcoded absolute path of the GustavGenerator.php file that creates the DEST file.
     * When that file has been moved to another directory and the DEST file is requested, an error will occur.
     * All other paths within the DEST file's content are only partly hardcoded. For example the final path of the used SRC file is calculated when the DEST file is requested.
     * This gives you the opportunity to change the "src_dir" configuration option after the DEST file has been created without getting an error due to an unexisting SRC file.
     * This works fine unless you move the used SRC file into another directory since the path of the SRC file, relative to the source directory is hardcoded into the DEST file.
     *
     * @return bool Whether the DEST file has been created successfully.
     */
    public function createFile(){
        $src_a=$this->src;
        
        /**/
        if($src_a->isDis()){
            return false;
        }
        /**/
        
        if(!is_null($src_a->getBlock("_dyn"))){
            $str_destContent=$this->getPhp();
        }else{
            $str_destContent=$this->content;
        }
        
        $str_destPath=$this->path;
        
        if(self::preg_match('/^index\.(?:html|php)$/', basename($str_destPath))==1){
            self::rm(array(dirname($str_destPath), "index.html"));
            self::rm(array(dirname($str_destPath), "index.php"));
        }
        
        return self::file_put_contents($str_destPath, $str_destContent, LOCK_EX)!==false;
    }
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavSrc.php"));
