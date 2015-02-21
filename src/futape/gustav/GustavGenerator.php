<?php
/*! Gustav 1.0.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));

use Exception;

abstract class GustavGenerator extends Gustav {
    
    
    
    #misc-constants#
    
    #GustavGenerator::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the GustavGenerator class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavGeneratorHooks";
    
    
    
    #generator-functions#
    
    #GustavGenerator::gen()#
    /**
     * Generates a DEST file containing the final DEST content or simply prints out that content.
     * If the specified path doesn't point on a file, a Gustav-404-error is raised.
     * Also a Gustav-error is raised when the GustavDest object for getting the destination information can't be created for that SRC file.
     * If the content gets printed, also a proper value for the "Content-Type" HTTP header field is added to the header.
     * If the content gets not printed, but rather a DEST file is created, a redirection is done.
     * If the DEST file couldn't be created, a Gustav-error is raised, otherwise a success log-entry is made.
     *
     * @param string|string[] $path          The path of the SRC file for which to "output" the destination content, relative to the source directory.
     *                                       The source directory's path is prepended to this parameter's value. Gets passed to GustavBase::path().
     * @param bool            $print_content OPTIONAL | Default: false
     *                                       If set to true, no DEST file is created. Instead the DEST content gets printed out directly.
     * @param string|null     $redirect_url  OPTIONAL | Default: null
     *                                       This parameter takes effect only when $print_content is set to false.
     *                                       After creating the DEST file, a redirection is done.
     *                                       If set to null, the client is redirected to the location of the created DEST file.
     *                                       If this parameter's value is a string starting with "?", the client is redirected to
     *                                       the same location, but this parameter's value is appended to the URL as a query string.
     *                                       If the value is a string not starting with "?", the value is considered to be a properly
     *                                       encoded URL, either a relative (https://tools.ietf.org/html/rfc3986#section-4.2) or an
     *                                       absolute (https://tools.ietf.org/html/rfc3986#section-4.3) one, to redirect the client to.
     *
     * @return void
     */
    public static function gen($str_path, $q_echo=false, $str_redirectUrl=null){
        $str_path=self::path($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_SRC_DIR), $str_path);
        
        if(!@is_file($str_path)){
            self::error('Source file "'.$str_path.'" doesn\'t exist.', self::ERROR_404);
        }
        
        try {
            $dest_a=new GustavDest($str_path);
        } catch(Exception $e){
            self::error('Couldn\'t process destination information for source file "'.$str_path.'".');
        }
        
        if($q_echo){
            self::header("Content-Type: text/html; charset=".self::ENC, true, 200);
            
            echo $dest_a->getContent();
            
            exit;
        }else{
            $str_destPath=$dest_a->getPath();
            $str_dest=$dest_a->getSrc()->getBlock("_dest");
            
            if(is_null($str_redirectUrl) || self::strStartsWith($str_redirectUrl, "?") || self::strStartsWith($str_redirectUrl, "#")){
                $str_redirectUrl=self::path2url($str_dest, false).(string)$str_redirectUrl;
            }
            
            if(!$dest_a->createFile()){
                self::error("Couldn't create destination file. Source file used: ".$str_path);
            }
            
            self::success($str_redirectUrl, 'The destination file for the source file "'.$str_path.'" has been built successfully. Built file: '.$str_destPath);
        }
    }
    
    #GustavGenerator::genByUrl()#
    /**
     * Takes a not-yet-existing DEST file's URL and creates it or prints out its final content.
     * Searches either in the directory specified by the URL path (the destination directory's path is stripped away and the server-root-relative path of the source directory is prepended) only, or in all subdirectories of the source directory for a matching SRC file.
     * "Matching SRC files" match the "dest" filter of Gustav::query(), set to the path of the URL.
     * If a SRC file, whose corresponding GustavDest object's getPath() method's returned value matches the path of the requested DEST file, is found, that file is used.
     * Otherwise, the disabled files are filtered out of the matching SRC files and the most similar SRC file in the remaining matching SRC files is chosen.
     * The most similar SRC file is discovered as described below (important to less important):
     *
     * 1.  The position of the first converter in the array that has been specified using the "preferred_convs" configuration option
     *     that is also used in the SRC file. If any of the prefered converters is used in the SRC file or if the GustavSrc object
     *     can't be created for the SRC file, the SRC file is moved to the end of the list.
     *     The lower the position, the higher the ranking.
     * 2.  The timestamp of the last modification of the SRC file's content. The timestamp is retrieved by calling filemtime().
     *     If that function fails, the SRC file is moved to the end of the list.
     *     The newer the timestamp, the higher the ranking.
     *
     * If multiple SRC files whose corresponding GustavDest object's getPath() method returns the same path as the requested path were found, the same steps are done to determine the SRC file to use.
     * If no similar SRC files can be found or if the SRC file, chosen due to its corresponding GustavDest object's `path` property's value, is disabled, a Gustav-404-error is raised.
     * If a DEST file should be created, the client is redirected to the new file after creating it.
     * The query string of the original request (if any) is passed through to that request.
     *
     * @param string $dest_url         The URL of a not-yet-existing DEST file to create or to print out its final content.
     * @param bool   $search_recursive OPTIONAL | Default: false
     *                                 If set to true, all subdirectories of the source directory are searched for a matching SRC file.
     *                                 Otherwise, only the directory retrieved from the URL path is searched.
     *                                 Searching in all directories may be a bit slow and memory-intensive but is more flexible
     *                                 since the DEST file doesn't have to be located in the destination directory's subdirectory
     *                                 corresponsing to the source directory's subdirectory the SRC file is located in.
     * @param bool   $print_content    OPTIONAL | Default: false
     *                                 If set to true, no DEST file is created. Instead the DEST content gets printed out directly.
     *                                 Gets passed to GustavGenerator::gen().
     *
     * @return void
     */
    public static function genByUrl($str_url, $q_searchRecursive=false, $q_echo=false){
        $arr_req=parse_url(self::getHttpUrl($str_url, true));
        $str_destPath=self::url2path($arr_req["path"]);
        $str_destPath_b=self::stripPath($str_destPath); //relative to doc root
        
        if($q_searchRecursive){
            $str_srcDir="";
        }else{
            $str_srcDir=self::stripPath(dirname($str_destPath_b), self::getConf(self::CONF_DEST_DIR)); //directories within/relative to the src directory
            
            /**
             * path must not end with a directory separator. due to that match against the whole path instead of the basename only.
             * actually using `/\/index\.(?:html|php)$/` would be enough since a path returned by GustavBase::path() (like $str_destPath_b) always starts with a directory separator, but we use `(?:\/|^)` instead, just for the case.
             */
            if(self::preg_match('/(?:'.self::escapeRegex(DIRECTORY_SEPARATOR).'|^)index\.(?:html|php)$/', $str_destPath_b)==1){
                $str_srcDir=dirname($str_srcDir);
            }
        }
        
        $arr_srcFiles=self::query($str_srcDir, $q_searchRecursive, array(
            "dest"=>$str_destPath_b
        ), self::FILTER_AND, self::ORDER_NONE, 0, true, true);
        
        $arr_srcFiles_b=array();
        $arr_srcFiles=array_filter($arr_srcFiles, function($val) use ($str_destPath, &$arr_srcFiles_b){
            try {
                $dest_a=new GustavDest($val);
            } catch(Exception $e){
                $dest_a=null;
            }
            
            if(!is_null($dest_a)){
                $src_a=$dest_a->getSrc();
            }else{
                try {
                    $src_a=new GustavSrc($val);
                } catch(Exception $e){
                    return false;
                }
            }
            
            $q_dis=$src_a->isDis();
            
            if(!is_null($dest_a)){
                if($dest_a->getPath()==$str_destPath){
                    array_push($arr_srcFiles_b, $val);
                }
            }
            
            return !$q_dis;
        });
        
        if(count($arr_srcFiles_b)>0){
            $arr_srcFiles=array_intersect($arr_srcFiles_b, $arr_srcFiles); //remove disabled source files from `$arr_srcFiles_b`
        }
        
        if(count($arr_srcFiles)==0){
            self::error("No matching source files could be found. Requested destination file: ".$str_destPath, self::ERROR_404);
        }
        
        usort($arr_srcFiles, function($a, $b){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            $arr_prefConvs=call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_PREFERRED_CONVS"));
            
            try {
                $src_a=new GustavSrc($a);
            } catch(Exception $e){
                $src_a=null;
            }
            
            if(!is_null($src_a)){
                $arr_conv=$src_a->getBlock("_conv");
                $int_prefConv=min(array_map(function($val) use ($str_hooks, $arr_prefConvs){
                    $int_a=count($arr_prefConvs);
        
                    foreach($arr_prefConvs as $i=>$val_b){
                        if(is_array($val_b) ? call_user_func(array($str_hooks, "convExists"), $val, implode(".", $val_b)) : $val==$val_b){
                            $int_a=$i;
                            
                            break;
                        }
                    }
        
                    return $int_a;
                }, $arr_conv));
            }else{
                $int_prefConv=count($arr_prefConvs)+1;
            }
            
            try {
                $src_b=new GustavSrc($b);
            } catch(Exception $e){
                $src_b=null;
            }
            
            if(!is_null($src_b)){
                $arr_conv_b=$src_b->getBlock("_conv");
                $int_prefConv_b=min(array_map(function($val) use ($str_hooks, $arr_prefConvs){
                    $int_a=count($arr_prefConvs);
        
                    foreach($arr_prefConvs as $i=>$val_b){
                        if(is_array($val_b) ? call_user_func(array($str_hooks, "convExists"), $val, implode(".", $val_b)) : $val==$val_b){
                            $int_a=$i;
                            
                            break;
                        }
                    }
        
                    return $int_a;
                }, $arr_conv_b));
            }else{
                $int_prefConv_b=count($arr_prefConvs)+1;
            }
            
            $int_mtime=@filemtime($a);
            $int_mtime_b=@filemtime($b);
            
            if($int_prefConv==$int_prefConv_b){
                if($int_mtime===$int_mtime_b){ //2nd level: order by last-modified-datetime (DESC)
                    return 0;
                }else if($int_mtime!==false && $int_mtime_b!==false){
                    return $int_mtime>$int_mtime_b ? -1 : 1;
                }else{
                    return $int_mtime!==false /*&& $int_mtime_b===false*/ ? -1 : /*$int_mtime===false && $int_mtime_b!==false*/ 1;
                }
            }else{
                return $int_prefConv<$int_prefConv_b ? -1 : 1; //1st level: order by position in prefered converters (ASC)
            }
        });
        
        $str_path=$arr_srcFiles[0];
        $str_path_b=self::stripPath($str_path, array($_SERVER['DOCUMENT_ROOT'], self::getConf(self::CONF_SRC_DIR))); //path of source file, relative to src dir
        $str_redirectUrl="";
        
        if(array_key_exists("query", $arr_req)){
            $str_redirectUrl.="?".$arr_req["query"];
        }
        if(array_key_exists("fragment", $arr_req)){
            $str_redirectUrl.="#".$arr_req["fragment"];
        }
        
        self::gen($str_path_b, $q_echo, $str_redirectUrl!="" ? $str_redirectUrl : null);
    }
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavSrc.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavDest.php"));
