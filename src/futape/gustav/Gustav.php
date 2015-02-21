<?php
/*! Gustav 1.0.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavBase.php"));

use Exception;

abstract class Gustav extends GustavBase {
    
    
    
    #misc-constants#
    
    #Gustav::HOOKS_CLASS#
    /**
     * The name of the class providing hooks for the Gustav class.
     * All static functions, public or not, are available via this function.
     *
     * @type string
     */
    const HOOKS_CLASS="futape\gustav\GustavHooks";
    
    
    
    #gustav-constants#
    
    #Gustav::GV_DIR#
    /**
     * The path of the directory containing Gustav-related files.
     *
     * @type string
     */
    const GV_DIR=__DIR__;
    
    #Gustav::EXT_DIR#
    /**
     * The name of the directory containing Gustav extensions.
     *
     * @type string
     */
    const EXT_DIR="ext";
    
    
    
    #gen-constants#
    
    #Gustav::GEN_FILE#
    /**
     * The basename of the file, handling the auto-generation of missing DEST files.
     *
     * @type string
     */
    const GEN_FILE="generate.php";
    
    
    
    #conf-constants#
    
    #Gustav::CONF_FILE#
    /**
     * The basename of the configuration file.
     *
     * @type string
     */
    const CONF_FILE="conf.json";
    
    #Gustav::CONF_DEST_DIR#
    /**
     * The name of the configuration option, specifying the path to the root of the destination directory.
     *
     * @type string
     */
    const CONF_DEST_DIR="dest_dir";
    
    #Gustav::CONF_SRC_DIR#
    /**
     * The name of the configuration option, specifying the path to the root of the source directory.
     *
     * @type string
     */
    const CONF_SRC_DIR="src_dir";
    
    #Gustav::CONF_TEMPLS_DIR#
    /**
     * The name of the configuration option, specifying the path of the directory containing the template files.
     *
     * @type string
     */
    const CONF_TEMPLS_DIR="templs_dir";
    
    #Gustav::CONF_404_DOC#
    /**
     * The name of the configuration option, specifying the URL (relative or absolute) of the error document for 404 (not found) errors.
     *
     * @type string
     */
    const CONF_404_DOC="404_error_doc";
    
    #Gustav::CONF_PREFERRED_CONVS#
    /**
     * The name of the configuration option, specifying a list of prefered converters, used for deciding which SRC file to use when auto-generating a DEST file and no unambiguous SRC file could be figured out.
     *
     * @type string
     */
    const CONF_PREFERRED_CONVS="preferred_convs";
    
    #Gustav::CONF_LOG_MAX_SIZE#
    /**
     * The name of the configuration option, specifying the maximum file size of a log file.
     *
     * @type string
     */
    const CONF_LOG_MAX_SIZE="log_file_max_size";
    
    #Gustav::CONF_EXIT_ON_ERROR#
    /**
     * The name of the configuration option, specifying whether to stop the execution of a script when a Gustav-specific error happen.
     *
     * @type string
     */
    const CONF_EXIT_ON_ERROR="exit_on_error";
    
    #Gustav::CONF_CHECK_STATUS#
    /**
     * The name of the configuration option, specifying whether to check Gustav for a proper setup when including Gustav.php.
     *
     * @type string
     */
    const CONF_CHECK_STATUS="check_status";
    
    #Gustav::CONF_SITE_URL#
    /**
     * The name of the configuration option, specifying an absolute URL of the site Gustav is running on.
     *
     * @type string
     */
    const CONF_SITE_URL="site_url";
    
    #Gustav::CONF_ENABLE_LOG#
    /**
     * The name of the configuration option, specifying whether to write errors, warnings and success messages to the log file.
     *
     * @type string
     */
    const CONF_ENABLE_LOG="enable_log";
    
    #Gustav::CONF_GEN_SEARCH_RECURSIVE#
    /**
     * The name of the configuration option, specifying whether to search in all subdirectories of the SRC directory
     * when generating a DEST file using GustavGenerator::genByUrl().
     *
     * @type string
     */
    const CONF_GEN_SEARCH_RECURSIVE="generator_search_recursive";
    
    #Gustav::CONF_REPLACE_DIR_SEP#
    /**
     * The name of the configuration option, specifying a character to replace with
     * the directory separator (i.e. the value of `DIRECTORY_SEPARATOR`).
     * If an empty string, no characters are replaced.
     *
     * @type string
     */
    const CONF_REPLACE_DIR_SEP="replace_directory_separator";
    
    #Gustav::CONF_FALLBACK_RESOURCE#
    /**
     * The name of the configuration option, specifying whether to use
     * Apache's `FallbackResource` configuration option.
     * If disabled, a combination `mod_rewrite`,
     * and `ErrorDocument` is used instead.
     *
     * @type string
     */
    const CONF_FALLBACK_RESOURCE="use_fallback_resource";
    
    
    
    #conf-functions#
    
    #Gustav::$conf#
    /**
     * An array containing the configuration options and their settings.
     *
     * @type array
     */
    private static $conf;
    
    #Gustav::getConf()#
    /**
     * Get a configuration option's value.
     *
     * @param string $setting The configuration option's name.
     *
     * @return mixed|null Returns the configuration option's value or null if it doesn't exist.
     */
    public static function getConf($str_conf){
        return @self::$conf[$str_conf];
    }
    
    
    
    #log-constants#
    
    #Gustav::LOG_FILE#
    /**
     * The name of the directory containing the log-files created by Gustav.
     *
     * @type string
     */
    const LOG_DIR="logs";
    
    #Gustav::LOG_FILE#
    /**
     * The basename of the main log file, relative to the logs directory.
     *
     * @type string
     */
    const LOG_FILE="gustav.log"; //relative to LOG_DIR
    
    #Gustav::LOG_TYPE_WARNING#
    /**
     * The "warning" log type.
     *
     * @type string
     */
    const LOG_TYPE_WARNING="warning";
    
    #Gustav::LOG_TYPE_ERROR#
    /**
     * The "error" log type.
     *
     * @type string
     */
    const LOG_TYPE_ERROR="error";
    
    #Gustav::LOG_TYPE_SUCCESS#
    /**
     * The "success" log type.
     *
     * @type string
     */
    const LOG_TYPE_SUCCESS="success";
    
    
    
    #error-constants#
    
    #Gustav::ERROR_404#
    /**
     * The HTTP 404 error status code.
     *
     * @type string
     */
    const ERROR_404="404 Not Found";
    
    #Gustav::ERROR_500#
    /**
     * The HTTP 500 error status code.
     *
     * @type string
     */
    const ERROR_500="500 Internal Server Error";
    
    #Gustav::ERROR_FATAL#
    /**
     * A fatal error, stopping the script execution, even if the configuration deactivates such behavior.
     *
     * @type int
     */
    const ERROR_FATAL=1;
    
    
    
    #log-functions#
    
    #Gustav::log()#
    /**
     * Writes to the Gustav log-file.
     *
     * If a filesize limit for gustav.log is set using the "log_file_max_size" configuration option and the log-file's filesize has already exceeded that limit, the unix timestamp of the last modification of the log-file's content and a unique ID are appended to its filename, resulting in a filename like "gustav.1419426764921.4b3403665fea6.log", and the log entry is written to a new log-file named "gustav.log". If the timestamp of the last modification can't be retrieved, the current time is used instead.
     * If the "log_file_max_size" configuration option is set to -1, no limit is specified.
     * If the "enable_log" configuration option is set to false, nothing is written to the log-file.
     *
     * @param string $log_message The text that should be written to the end of the log-file.
     * @param string $log_type    OPTIONAL | Default: Gustav::LOG_TYPE_WARNING
     *                            A Gustav::LOG_TYPE_* constant defining the uppercased first word of the log entry.
     * @param bool   $force       OPTIONAL | Default: false
     *                            By default this function doesn't write anything to the log-file if the "enable_log" configuration option is set to false. However, if this parameter is set to true, it does.
     *
     * @return void
     */
    protected static function log($str_message, $str_type=self::LOG_TYPE_WARNING, $q_force=false){
        $str_path=self::path(self::GV_DIR, self::LOG_DIR, self::LOG_FILE);
        
        if(self::getConf(self::CONF_ENABLE_LOG) || $q_force){
            if(@is_file($str_path)){
                $int_size=@filesize($str_path);
            
                if($int_size!==false && self::getConf(self::CONF_LOG_MAX_SIZE)>-1 && $int_size>=self::getConf(self::CONF_LOG_MAX_SIZE)){
                    $int_mtime=@filemtime($str_path);
                    $str_ext=pathinfo($str_path, PATHINFO_EXTENSION);
                    
                    @rename($str_path, self::path(dirname($str_path), implode(".", array(pathinfo($str_path, PATHINFO_FILENAME), $int_mtime!==false ? $int_mtime : time(), uniqid())).(!is_null($str_ext) ? ".".$str_ext : "")));
                }
            }
        
            self::file_put_contents($str_path, PHP_EOL.self::mb_strtoupper($str_type).str_repeat(" ", 4).date(DATE_RFC2822).PHP_EOL.$str_message.PHP_EOL, FILE_APPEND);
        }
    }
    
    #Gustav::error()#
    /**
     * Exits the script as a consequence of an error.
     *
     * Exits the script with a HTTP status code and a possible error log entry in the Gustav log-file.
     * If the "exit_on_error" configuration option is set to false, the execution of the script isn't stopped
     * unless $error_type is set to Gustav::ERROR_FATAL. In that case, also a log entry is forced (if a log message is specified),
     * regardless of the setting of the "enable_log" configuration option.
     * If Gustav::ERROR_404 is used as value for $error_type, a redirect to the page specified by the "404_error_doc" configuration option is done using a 303 HTTP status code.
     *
     * @param string|null $log_message OPTIONAL. | Default: null
     *                                 If not null, an error log entry containing this text is written to the log-file if logging isn't disabled or if a fatal error is raised.
     * @param string|int  $error_type  OPTIONAL | Default: Gustav::ERROR_500
     *                                 A Gustav::ERROR_* constant defining the error type. This may be a error, representing a HTTP status or a Gustav-internal error.
     *
     * @return void
     */
    protected static function error($str_log=null, $str_status=self::ERROR_500){
        $q_fatal=$str_status===self::ERROR_FATAL;
        
        if(!is_null($str_log)){
            self::log($str_log, self::LOG_TYPE_ERROR, $q_fatal);
        }
        
        /** /
        if($str_status===self::ERROR_404){
            self::header("Location: ".self::getConf(self::CONF_404_DOC), 303);
        }else if(!$q_fatal){
            self::header("Status: ".$str_status);
        }
        /*/
        self::header("Status: ".$str_status);
        
        if($str_status===self::ERROR_404){
            self::header("Content-Type: text/html; charset=".self::ENC);
            
            $str_errDocUrl=self::getConf(self::CONF_404_DOC);
            $str_errDocPath=self::url2path($str_errDocUrl);
            
            if(@is_file($str_errDocPath) && self::mb_strtolower((string)pathinfo($str_errDocPath, PATHINFO_EXTENSION))=="php"){
                echo self::readFile($str_errDocPath, false, true);
            }else{
                echo self::readFile(self::getHttpUrl($str_errDocUrl, true), true);
            }
            
            exit;
        }
        /**/
        
        if(self::getConf(self::CONF_EXIT_ON_ERROR) || $q_fatal){
            exit;
        }
    }
    
    #Gustav::success()#
    /**
     * Exits the script as a consequence of success and redirects to another location.
     *
     * Exits the script with the HTTP status code 303, a possible log entry in the Gustav log-file and redirection to the specified URL.
     *
     * @param string      $url         A relative or a absolute URL defining the target of the redirection.
     * @param string|null $log_message OPTIONAL | Default: null
     *                                 If not null, a log entry containing this text is written to the log-file if logging is enabled.
     *
     * @return void
     */
    protected static function success($str_url, $str_log=null){
        if(!is_null($str_log)){
            self::log($str_log, self::LOG_TYPE_SUCCESS);
        }
        
        self::header("Location: ".$str_url, 303);
        exit;
    }
    
    
    
    #conv-constants#
    
    #Gustav::CONV_DIR#
    /**
     * The name of the directory containing user-defined converters, relative to the Gustav extensions directory.
     *
     * @type string
     */
    const CONV_DIR="conv";
    
    #Gustav::CONV_CONST_NAMES#
    /**
     * The names of the Gustav::CONV_* constants (except for this one, Gustav::CONV_CONST_NAMES) separated by spaces.
     *
     * @type string
     */
    const CONV_CONST_NAMES="CONV_HTML CONV_TEXT";
    
    #Gustav::CONV_HTML#
    /**
     * Possible names for the hardcoded HTML converter.
     *
     * Lowercased names, separated by `.`s.
     *
     * @type string
     */
    const CONV_HTML="html.htm";
    
    #Gustav::CONV_TEXT#
    /**
     * Possible names for the hardcoded plain text converter.
     *
     * Lowercased names, separated by `.`s.
     *
     * @type string
     */
    const CONV_TEXT="txt.plain.text";
    
    #Gustav::CONVS#
    /**
     * Possible converter names.
     *
     * Lowercased names, separated by `.`s.
     * Concatenates the values of all Gustav::CONV_* constants (except for Gustav::CONV_CONST_NAMES).
     *
     * @type string
     */
    const CONVS="html.htm.txt.plain.text"; //"html.htm.txt.plain.text" = `implode(".", array_map(function($val){return constant(__CLASS__."::".$val);}, explode(" ", self::CONV_CONST_NAMES)))`
    
    
    
    #conv-functions#
    
    #Gustav::convExists()#
    /**
     * Checks whether a converter exists.
     *
     * Checks whether a converter exists within a specified set of converters or within all available converters.
     * When searching in user-defined converters, this function compares the converters' names with the specified converter name case-sensitively.
     * Hardcoded converters' names are compared with the specified converter name case-insensitively.
     * When specifying a set of converter names using the $converters parameter, the comparison is done case-insensitively.
     *
     * @param string|string[] $converter  The name of the converter to check. Or an array of strings containing names of converters. At least one of them must exist.
     * @param string|null     $converters OPTIONAL | Default: null
     *                                    A set of available converters (separated by `.`s). The value of a GustavBase::CONV_* constant or GustavBase::CONVS is perfectly suitable for this parameter's value.
     *                                    If not null, this function will search case-insensitively for the specified converter name only within the set that was specified by this parameter.
     *                                    Otherwise the converter is searched within all, user-defined (case-sensitively) and hardcoded (case-insensitively), converters.
     *
     * @return bool Whether the converter has been found.
     */
    protected static function convExists($arr_conv, $str_convs=null){
        $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
        
        if(!is_array($arr_conv)){
            $arr_conv=array($arr_conv);
        }
        
        if(is_null($str_convs)){
            $str_convs=self::CONVS;
            
            if(count(array_filter($arr_conv, function($val) use ($str_hooks){
                return @is_file(call_user_func(array($str_hooks, "path"), constant(__CLASS__."::GV_DIR"), constant(__CLASS__."::EXT_DIR"), constant(__CLASS__."::CONV_DIR"), $val.".php"));
            }))>0){
                return true;
            }
        }
        
        $arr_convs=explode(".", self::mb_strtolower($str_convs));
        
        return count(array_intersect(array_map(array($str_hooks, "mb_strtolower"), $arr_conv), $arr_convs))>0;
    }
    
    #Gustav::getHardConv()#
    /**
     * Get available hardcoded converters.
     *
     * Get all hardcoded converters or only those whose names match a specified converter name (and the converter's aliases).
     *
     * @param string|null $converter OPTIONAL | Default: null
     *                               If not null, the function returns only converters that have names matching (case-insensitive) this parameter's value. Otherwise all hardcoded converters are returned.
     *
     * @return string[] All names of all converters matching the specified converter name, if any, or all available names for all available hardcoded converters if no converter name has been specified.
     */
    protected static function getHardConv($str_conv=null){
        return call_user_func_array("array_merge", array_map(function($val) use ($str_conv){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            $str_a=call_user_func(array($str_hooks, "mb_strtolower"), constant(__CLASS__."::".$val));
            $arr_a=explode(".", $str_a);
            
            if(!is_null($str_conv) && !call_user_func(array($str_hooks, "convExists"), $str_conv, $str_a)){
                return array();
            }
            
            return $arr_a;
        }, explode(" ", self::CONV_CONST_NAMES)));
    }
    
    
    
    #path-functions#
    #url-functions#
    
    #Gustav::getHttpUrl()#
    /**
     * Get an URL for a given path.
     *
     * @param string $path                   The path to use as the URL path. May also be an empty string which would result in an URL path of "/". Gets passed to GustavBase::path() and gets converted to a properly urlencoded path, directory separators replaced by "/".
     * @param bool   $path_includes_doc_root OPTIONAL | Default: true
     *                                       If set to false, the document root is prepended to the passed path.
     *                                       It's removed again before converting it to an URL path.
     *
     * @return string Returns the built URL.
     */
    protected static function getHttpUrl($str_path, $q_url=false, $q_includesDocRoot=true){
        if($q_url){
            $str_url=$str_path;
        }else{
            $str_path=self::path($str_path);
            
            $str_url=self::path2url($str_path, $q_includesDocRoot);
        }
        
        $str_url=self::getConf(self::CONF_SITE_URL).$str_url;
        
        return $str_url;
    }
    
    
    
    #init-functions#
    
    #Gustav::$isInit#
    /**
     * Whether Gustav::initGustav() already got executed.
     *
     * @type bool
     */
    private static $isInit=false;
    
    #Gustav::initGustav()#
    /**
     * Initializes some of the Gustav class's static properties.
     * For example this function reads the configuration file and
     * decodes it as JSON and saves the decoded array to a class-
     * variable. Moreover it validates the configuration and resets
     * invalid options to their default values. If no default value is
     * available for an invalid option, a fatal Gustav-error may be raised.
     * Otherwise a warning log entry is done for every invalid option.
     * Furthermore this function checks whether all required files and directories
     * exist and logs on inexistence.
     * If the "check_status" configuration option is set to false,
     * nothing is logged.
     * This function gets executed everytime Gustav.php is included
     * and can be executed for just one time.
     *
     * @return void
     */
    public static function initGustav(){
        if(self::$isInit){
            return;
        }
        
        $str_confPath=self::path(self::GV_DIR, self::CONF_FILE);
        
        if(!@is_file($str_confPath)){
            self::error('The configuration file "'.self::CONF_FILE.'" is missing.', self::ERROR_FATAL);
        }
        
        $arr_conf=json_decode(self::readFile($str_confPath), true);
        
        if(!is_array($arr_conf)){
            self::error('The configuration file "'.self::CONF_FILE.'" doesn\'t define an object.', self::ERROR_FATAL);
        }
        
        $q_enableLogIsSet=array_key_exists(self::CONF_ENABLE_LOG, $arr_conf);
        
        if(!$q_enableLogIsSet){
            $arr_conf[self::CONF_ENABLE_LOG]=true;
        }else{
            $q_enableLogIsBool=is_bool($arr_conf[self::CONF_ENABLE_LOG]);
            
            $arr_conf[self::CONF_ENABLE_LOG]=(bool)($arr_conf[self::CONF_ENABLE_LOG]);
        }
        
        $q_checkStatusIsSet=array_key_exists(self::CONF_CHECK_STATUS, $arr_conf);
        
        if(!$q_checkStatusIsSet){
            $arr_conf[self::CONF_CHECK_STATUS]=true;
        }else{
            $q_checkStatusIsBool=is_bool($arr_conf[self::CONF_CHECK_STATUS]);
            
            $arr_conf[self::CONF_CHECK_STATUS]=(bool)($arr_conf[self::CONF_CHECK_STATUS]);
        }
        
        self::$conf=$arr_conf; //as soon as a few options are fixed (here: check_status and enable_log), the $conf array should be updated
        
        if($arr_conf[self::CONF_CHECK_STATUS]){
            if(!$q_enableLogIsSet){
                self::log('The "'.self::CONF_ENABLE_LOG.'" configuration option in isn\'t set.');
            }else if(!$q_enableLogIsBool){
                self::log('The value of the "'.self::CONF_ENABLE_LOG.'" configuration option isn\'t a boolean.');
            }
            
            if(!$q_checkStatusIsSet){
                self::log('The "'.self::CONF_CHECK_STATUS.'" configuration option in isn\'t set.');
            }else if(!$q_checkStatusIsBool){
                self::log('The value of the "'.self::CONF_CHECK_STATUS.'" configuration option isn\'t a boolean.');
            }
            
            if(!array_key_exists(self::CONF_TEMPLS_DIR, $arr_conf)){
                self::error('The "'.self::CONF_TEMPLS_DIR.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_TEMPLS_DIR])){
                self::log('The value of the "'.self::CONF_TEMPLS_DIR.'" configuration option isn\'t a string.');
            }
            
            if(!array_key_exists(self::CONF_SRC_DIR, $arr_conf)){
                self::error('The "'.self::CONF_SRC_DIR.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_SRC_DIR])){
                self::log('The value of the "'.self::CONF_SRC_DIR.'" configuration option isn\'t a string.');
            }
            
            if(!array_key_exists(self::CONF_DEST_DIR, $arr_conf)){
                self::error('The "'.self::CONF_DEST_DIR.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_DEST_DIR])){
                self::log('The value of the "'.self::CONF_DEST_DIR.'" configuration option isn\'t a string.');
            }
        
            if(!array_key_exists(self::CONF_PREFERRED_CONVS, $arr_conf)){
                self::log('The "'.self::CONF_PREFERRED_CONVS.'" configuration option isn\'t set.');
            }else if(!is_array($arr_conf[self::CONF_PREFERRED_CONVS])){
                self::log('The value of the "'.self::CONF_PREFERRED_CONVS.'" configuration option isn\'t an array.');
            }
            
            //the value of the "404_error_doc" configuration option is used as value for the "location" http header field. as of HTTP/1.1 (RFC 7231, <https://tools.ietf.org/html/rfc7231#section-7.1.2>) that value can be either a absolute URL (i.e. a fully qualified URL including a scheme, <https://tools.ietf.org/html/rfc3986#section-4.3>) or a relative URL inheriting missing URL parts from the requested URL (<https://tools.ietf.org/html/rfc3986#section-4.2>).
            if(!array_key_exists(self::CONF_404_DOC, $arr_conf)){
                self::log('The "'.self::CONF_404_DOC.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_404_DOC])){
                self::log('The value of the "'.self::CONF_404_DOC.'" configuration option isn\'t a string.');
            }
            
            if(!array_key_exists(self::CONF_LOG_MAX_SIZE, $arr_conf)){
                self::log('The "'.self::CONF_LOG_MAX_SIZE.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_LOG_MAX_SIZE]) && !is_int($arr_conf[self::CONF_LOG_MAX_SIZE])){
                self::log('The value of the "'.self::CONF_LOG_MAX_SIZE.'" configuration option is neither a string nor an integer.');
            }
            
            if(!array_key_exists(self::CONF_EXIT_ON_ERROR, $arr_conf)){
                self::log('The "'.self::CONF_EXIT_ON_ERROR.'" configuration option isn\'t set.');
            }else if(!is_bool($arr_conf[self::CONF_EXIT_ON_ERROR])){
                self::log('The value of the "'.self::CONF_EXIT_ON_ERROR.'" configuration option isn\'t a boolean.');
            }
            
            if(!array_key_exists(self::CONF_SITE_URL, $arr_conf)){
                self::log('The "'.self::CONF_SITE_URL.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_SITE_URL])){
                self::log('The value of the "'.self::CONF_SITE_URL.'" configuration option isn\'t a string.');
            }
            
            if(!array_key_exists(self::CONF_GEN_SEARCH_RECURSIVE, $arr_conf)){
                self::log('The "'.self::CONF_GEN_SEARCH_RECURSIVE.'" configuration option isn\'t set.');
            }else if(!is_bool($arr_conf[self::CONF_GEN_SEARCH_RECURSIVE])){
                self::log('The value of the "'.self::CONF_GEN_SEARCH_RECURSIVE.'" configuration option isn\'t a boolean.');
            }
            
            if(!array_key_exists(self::CONF_REPLACE_DIR_SEP, $arr_conf)){
                self::log('The "'.self::CONF_REPLACE_DIR_SEP.'" configuration option isn\'t set.');
            }else if(!is_string($arr_conf[self::CONF_REPLACE_DIR_SEP])){
                self::log('The value of the "'.self::CONF_REPLACE_DIR_SEP.'" configuration option isn\'t a string.');
            }
            
            if(!array_key_exists(self::CONF_FALLBACK_RESOURCE, $arr_conf)){
                self::log('The "'.self::CONF_FALLBACK_RESOURCE.'" configuration option isn\'t set.');
            }else if(!is_bool($arr_conf[self::CONF_FALLBACK_RESOURCE])){
                self::log('The value of the "'.self::CONF_FALLBACK_RESOURCE.'" configuration option isn\'t a boolean.');
            }
        }
        
        $arr_conf[self::CONF_TEMPLS_DIR]=self::path((string)(@$arr_conf[self::CONF_TEMPLS_DIR]));
        $arr_conf[self::CONF_SRC_DIR]=self::path((string)(@$arr_conf[self::CONF_SRC_DIR]));
        $arr_conf[self::CONF_DEST_DIR]=self::path((string)(@$arr_conf[self::CONF_DEST_DIR]));
        
        if(!array_key_exists(self::CONF_PREFERRED_CONVS, $arr_conf)){
            $arr_conf[self::CONF_PREFERRED_CONVS]=array();
        }else{
            $arr_preferredConvs=$arr_conf[self::CONF_PREFERRED_CONVS];
            
            if(!is_array($arr_preferredConvs)){
                $arr_preferredConvs=array($arr_preferredConvs);
            }
            
            $arr_conf[self::CONF_PREFERRED_CONVS]=array();
            
            foreach($arr_preferredConvs as $val){
                /** /
                if(is_array($val)){
                    array_push($arr_conf[self::CONF_PREFERRED_CONVS], $val);
                }else{
                    $val=trim((string)$val);
                    $arr_a=self::getHardConv($val);
                    
                    array_push($arr_conf[self::CONF_PREFERRED_CONVS], count($arr_a)>0 ? $arr_a : $val);
                }
                /*/
                $val=trim((string)$val);
                $arr_a=self::getHardConv($val);
                
                array_push($arr_conf[self::CONF_PREFERRED_CONVS], count($arr_a)>0 ? $arr_a : $val);
                /**/
            }
        }
        
        if(!array_key_exists(self::CONF_404_DOC, $arr_conf)){
            $arr_conf[self::CONF_404_DOC]="/";
        }else{
            $arr_conf[self::CONF_404_DOC]=(string)($arr_conf[self::CONF_404_DOC]);
        }
        
        if(!array_key_exists(self::CONF_LOG_MAX_SIZE, $arr_conf)){
            $arr_conf[self::CONF_LOG_MAX_SIZE]=-1; //no limit
        }else if(!is_int($arr_conf[self::CONF_LOG_MAX_SIZE])){
            $arr_conf[self::CONF_LOG_MAX_SIZE]=self::short2byte((string)($arr_conf[self::CONF_LOG_MAX_SIZE]));
        }
        
        if(!array_key_exists(self::CONF_EXIT_ON_ERROR, $arr_conf)){
            $arr_conf[self::CONF_EXIT_ON_ERROR]=true;
        }else{
            $arr_conf[self::CONF_EXIT_ON_ERROR]=(bool)($arr_conf[self::CONF_EXIT_ON_ERROR]);
        }
        
        if(!array_key_exists(self::CONF_SITE_URL, $arr_conf)){
            //$q_https=/**/(array_key_exists("HTTPS", $_SERVER) && (bool)($_SERVER["HTTPS"]) && !(is_string($_SERVER["HTTPS"]) && self::mb_strtolower($_SERVER["HTTPS"])=="off"))/*/self::mb_strtolower(@array_shift(explode("/", $_SERVER["SERVER_PROTOCOL"])))=="https"/**/;
            $q_forwarded=(array_key_exists("HTTP_X_FORWARDED_PROTO", $_SERVER) && array_key_exists("HTTP_X_FORWARDED_HOST", $_SERVER));
            $q_https=$q_forwarded ? self::mb_strtolower(trim(@array_shift(explode(",", $_SERVER["HTTP_X_FORWARDED_PROTO"]))))=="https" : !empty($_SERVER["HTTPS"]);
            $str_url="http".($q_https ? "s" : "")."://";
            
            if($q_forwarded){
                $str_url.=trim(@array_shift(explode(",", $_SERVER["HTTP_X_FORWARDED_HOST"]))); //includes port if not the default port for http(s)
            }else if(array_key_exists("HTTP_HOST", $_SERVER)){
                $str_url.=trim($_SERVER["HTTP_HOST"]); //includes port if not the default port for http(s)
            }else{
                $str_url.=$_SERVER["SERVER_NAME"]; //don't trim since this is a admin-defined value, not a user-defined one
                
                if(array_key_exists("SERVER_PORT", $_SERVER) && (int)($_SERVER["SERVER_PORT"])!=$q_https ? 443 : 80){
                    $str_url.=":".$_SERVER["SERVER_PORT"];
                }
            }
            
            $arr_conf[self::CONF_SITE_URL]=$str_url;
        }else{
            $arr_conf[self::CONF_SITE_URL]=rtrim((string)($arr_conf[self::CONF_SITE_URL]), "/");
        }
        
        if(!array_key_exists(self::CONF_GEN_SEARCH_RECURSIVE, $arr_conf)){
            $arr_conf[self::CONF_GEN_SEARCH_RECURSIVE]=false;
        }else{
            $arr_conf[self::CONF_GEN_SEARCH_RECURSIVE]=(bool)($arr_conf[self::CONF_GEN_SEARCH_RECURSIVE]);
        }
        
        if(!array_key_exists(self::CONF_REPLACE_DIR_SEP, $arr_conf)){
            $arr_conf[self::CONF_REPLACE_DIR_SEP]="";
        }else{
            $arr_conf[self::CONF_REPLACE_DIR_SEP]=self::mb_substr((string)($arr_conf[self::CONF_REPLACE_DIR_SEP]), 0, 1);
        }
        
        if(!array_key_exists(self::CONF_FALLBACK_RESOURCE, $arr_conf)){
            $arr_conf[self::CONF_FALLBACK_RESOURCE]=false;
        }else{
            $arr_conf[self::CONF_FALLBACK_RESOURCE]=(bool)($arr_conf[self::CONF_FALLBACK_RESOURCE]);
        }
        
        self::$conf=$arr_conf;
        
        if($arr_conf[self::CONF_CHECK_STATUS]){
            if(!@is_dir(self::path($_SERVER['DOCUMENT_ROOT'], $arr_conf[self::CONF_TEMPLS_DIR]))){
                self::error('The directory specified by the "'.self::CONF_TEMPLS_DIR.'" configuration option doesn\'t exist.');
            }
            if(!@is_dir(self::path($_SERVER['DOCUMENT_ROOT'], $arr_conf[self::CONF_SRC_DIR]))){
                self::error('The directory specified by the "'.self::CONF_SRC_DIR.'" configuration option doesn\'t exist.');
            }
            if(!@is_dir(self::path($_SERVER['DOCUMENT_ROOT'], $arr_conf[self::CONF_DEST_DIR]))){
                self::error('The directory specified by the "'.self::CONF_DEST_DIR.'" configuration option doesn\'t exist.');
            }
            
            if(!@is_file(self::path($_SERVER["DOCUMENT_ROOT"], $arr_conf[self::CONF_DEST_DIR], ".htaccess"))){
                self::log('The destination directory ("'.$arr_conf[self::CONF_DEST_DIR].'") doesn\'t contain a ".htaccess" file.');
            }
            
            if(!@is_file(self::path(self::GV_DIR, self::GEN_FILE))){
                self::log('The file "'.self::GEN_FILE.'" which handles the creation of non-existing destination files is missing.');
            }
            
            if(!$arr_conf[self::CONF_EXIT_ON_ERROR]){
                self::log('The "'.self::CONF_EXIT_ON_ERROR.'" configuration option is set to false. The script execution isn\'t canceled when a Gustav-error occurs. You should expect many (non-Gustav-)errors to appear.');
            }
            
            /**/
            if(self::checkIni("allow_url_fopen", false)){
                self::log('You may need to enable PHP\'s "allow_url_fopen" configuration option to use the specified 404 error document ("'.self::CONF_404_DOC.'" option).');
            }
            /**/
            
            if(!self::checkIni("open_basedir", null)){
                self::log('Be sure that PHP\'s "open_basedir" configuration option doesn\'t exclude important files like the source files located in the directory specified by the "'.self::CONF_SRC_DIR.'" configuration option.');
            }
        }
        
        self::$isInit=true;
    }
    
    
    
    #query-constants#
    
    #Gustav::ORDER_NONE#
    /**
     * Don't order matching SRC files.
     *
     * @type int
     */
    const ORDER_NONE=1;
    
    #Gustav::ORDER_MATCH#
    /**
     * Orders matching SRC files by their match scores (descending).
     *
     * @type int
     */
    const ORDER_MATCH=2;
    
    #Gustav::ORDER_MATCH_ASC#
    /**
     * Orders matching SRC files by their match scores (ascending).
     *
     * @type int
     */
    const ORDER_MATCH_ASC=3;
    
    #Gustav::ORDER_PUB#
    /**
     * Orders matching SRC files by their dates of publication (descending).
     *
     * @type int
     */
    const ORDER_PUB=4;
    
    #Gustav::ORDER_PUB_ASC#
    /**
     * Orders matching SRC files by their dates of publication (ascending).
     *
     * @type int
     */
    const ORDER_PUB_ASC=5;
    
    #Gustav::ORDER_RAND#
    /**
     * Orders matching source files randomly.
     *
     * @type int
     */
    const ORDER_RAND=6;
    
    #Gustav::FILTER_OR#
    /**
     * Filters SRC files using the OR operator.
     *
     * @type int
     */
    const FILTER_OR=1;
    
    #Gustav::FILTER_AND#
    /**
     * Filters SRC files using the AND operator.
     *
     * @type int
     */
    const FILTER_AND=2;
    
    
    
    #search-constants#
    
    #Gustav::SEARCH_TAGS#
    /**
     * Search for search term items in source files' tags.
     *
     * @type int
     */
    const SEARCH_TAGS=1;
    
    #Gustav::SEARCH_TAGS#
    /**
     * Search for search term items in source files' titles.
     *
     * @type int
     */
    const SEARCH_TITLE=2;
    
    #Gustav::SEARCH_TAGS#
    /**
     * Search for search term items in destination files' filenames.
     *
     * @type int
     */
    const SEARCH_FILE=4;
    
    
    
    #gustav-functions#
    
    #Gustav::setup()#
    /**
     * Sets up Gustav.
     *
     * Creates the directories specified by the configuration options "src_dir", "dest_dir" and "templs_dir" if they don't exist and prepares or creates the destination directory's .htaccess file to handle the creation of non-existing DEST files on request (set `ErrorDocument 404` to generate.php) and to show the right files when a directory is requested (`DirectoryIndex`). The directives are appended to the end of the .htaccess file.
     *
     * @return bool Whether all operations were successful.
     */
    public static function setup(){
        $fn_tab=function($val){
            return str_repeat(" ", 4).$val;
        };
        $str_htaccess=implode("\n", array_merge(array(
            "", "",
            
            '### BEGIN Gustav ###',
            
            'DirectoryIndex index.html index.php',
            'DirectorySlash On'),
            
            self::getConf(self::CONF_FALLBACK_RESOURCE) ? array(
                'FallbackResource '.self::path2url(array(self::GV_DIR, self::GEN_FILE))
            ) : array(
                'ErrorDocument 404 '.self::path2url(array(self::GV_DIR, self::GEN_FILE)),
                
                '<IfModule mod_dir.c>', array_map($fn_tab, array(
                    'RewriteEngine On',
                    'Options +FollowSymLinks',
                    
                    'RewriteCond %{REQUEST_FILENAME} -d',
                    'RewriteCond %{REQUEST_FILENAME} ^((?:[^/]|/(?!$))*)/?$',
                    'RewriteCond %1/index.html !-f',
                    'RewriteCond %1/index.php !-f',
                    'RewriteRule $ - [R=404,L]')),
                '</IfModule>'
            ), array(
            
            '### END Gustav ###',
            
            "", ""
        )));
        
        $q_a=true;
        
        $q_a=(self::mkdir(array($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_SRC_DIR))) && $q_a);
        $q_a=(self::mkdir(array($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_DEST_DIR))) && $q_a);
        $q_a=(self::mkdir(array($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_TEMPLS_DIR))) && $q_a);
        $q_a=(self::file_put_contents(self::path($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_DEST_DIR), ".htaccess"), $str_htaccess, LOCK_EX|FILE_APPEND)!==false && $q_a);
        
        return $q_a;
    }
    
    #Gustav::reset()#
    /**
     * Resets the destination directory.
     *
     * Removes all directories, files and symbolic links within the destination directory leaving an empty directory.
     *
     * @return bool Whether emptying the destination directory was successful.
     */
    public static function reset(){
        $q_a=true;
        
        $q_a=(self::cleandir(array($_SERVER['DOCUMENT_ROOT'], self::getConf(self::CONF_DEST_DIR))) && $q_a);
        $q_a=(self::setup() && $q_a);
        
        return $q_a;
    }
    
    #Gustav::query()#
    /**
     * Get matching SRC files.
     *
     * Creates and returns an array containing the matching SRC files' paths as keys and the matching items of $filter's "match" item as values.
     * By default disabled SRC files are ignored and are not included in the returned array.
     *
     * @param string|string[] $src_directory            OPTIONAL | Default: ""
     *                                                  The path (relative to the source directory) of the directory to start searching for SRC files in.
     * @param bool            $recursive                OPTIONAL | Default: true
     *                                                  If set to true, SRC files placed in the subdirectories of the specified directory are included, too (if matching the filters).
     * @param array|null      $filters                  OPTIONAL | Default: null
     *                                                  An associative array containing filters a SRC file must match to be included in the resulting array. The following filters are available:
     *
     *                                                      array(
     *                                                          //Calls GustavSrc::getMatchScore() for each of this array's items passing the item's key to getMatchScore()'s first parameter and its value to its second parameter.
     *                                                          //Each match-filter is considered to be a single, standalone filter.
     *                                                          //If an empty array is passed to one of this array's items, that item is ignored.
     *                                                          //If the calculated match score for that item is greater than 0, the SRC file is considered to match that filt
     *                                                          //Using an unsupported match-filter (i.e. an unsupported key) won't match any source files.
     *                                                          "match"=>array(
     *                                                              GustavBase::KEY_FILE=>array(),
     *                                                              GustavBase::KEY_TITLE=>array(),
     *                                                              GustavBase::KEY_TAGS=>array()
     *                                                          ),
     *
     *                                                          //Accepts any GvBlock option. Use the option's name as key and the value to match the option's value against as value.
     *                                                          //Each property-filter is considered to be a single, standalone filter.
     *                                                          "prop"=>array(
     *                                                              //If a boolean is set as value, this function checks whether a SRC file's GvBlock contains the option, if true, or not, if false.
     *                                                              "_hidden"=>true,
     *
     *                                                              //Any other type of value is converted into an array or is left as it is if it's already an array. The same applies to the appropiate property's value.
     *                                                              //If (originally) an empty array has been specified, the filter matches only if the property's (original) value is an empty array, too.
     *                                                              //Otherwise this function looks for intersections (case-sensitive for strings) of the property's array (original value or converted) and the filter's array (original value or converted).
     *                                                              //If the array isn't empty, only one intersection needs to be found, to consider the SRC file to match this filter. If the option isn't contained in the GvBlock at all, the SRC file doesn't match this filter's property.
     *                                                              //This corresponds to the OR operator. This behavior cannot be changed using this function's $filters_operator parameter.
     *                                                              "_templ"=>array("file_uses_this_templates", "or_this_one", "or_both")
     *                                                          ),
     *
     *                                                          //This filter is an extended version of `"prop"=>array("_conv"=>array())`.
     *                                                          //Unlike the "prop"-version, this filter doesn't only match SRC files that use one of the specified converter names but also matches such that use a different name (an alias) for the same converter. Moreover the comparision is done case-insensitively.
     *                                                          //This works only for hardcoded converters. User-defined converters are compared case-sensitively and without any consideration of aliases.
     *                                                          //If an empty array is passed to this filter, the filter is ignored.
     *                                                          "conv"=>array("text", "md"), //Matches all SRC files using any name of the hardcoded text converter (Gustav::CONV_TEXT, case-insensitive)
     *                                                                                       //or a user-defined markdown converter named "md" (case-sensitive)
     *                                                                                       //but not a converter named "markdown" since "md" is a user-defined converter and can therefore not have any aliases.
     *
     *                                                          //This filter is an extended version of `"prop"=>array("_dest"=>array())`.
     *                                                          //In addition to the "prop"-version, this filter also takes the result of GustavDest::getPath()
     *                                                          //for the SRC file into account.
     *                                                          //A SRC file matches this filter if its GvBlock's "_dest" option's value matches the filter value
     *                                                          //or if that value ends with a directory separator and the filter value matches that value
     *                                                          //with the trailing directory separator stripped away.
     *                                                          //SRC files, whose corresponding GustavDest object's getPath() method's returned value matches
     *                                                          //the filter value is considered to be successfully matched, too.
     *                                                          //This filter expects a string value containing a full path, including the filename,
     *                                                          //or just the dirname of a DEST file's path (assuming that its filename is "index.*").
     *                                                          //The path has to be relative to the document root.
     *                                                          "dest"=>"/dest/category/hello-world/",
     *
     *                                                          //If the option "_pub" of a SRC file's GvBlock is set and is greater than the value passed to this filter, the file matches the filter.
     *                                                          "newer_than"=>time(),
     *
     *                                                          //If the option "_pub" of a SRC file's GvBlock isn't set or if it's lower than or equal to the value passed to this filter, the file matches the filter.
     *                                                          "older_than"=>time()
     *                                                      )
     *
     *                                                  If set to null, a default filter of `array("prop"=>array("_hidden"=>false), "older_than"=>time())` is used.
     *                                                  To get all SRC files without filtering them, use an empty array or one containing unsupported items only.
     * @param int             $filters_operator         OPTIONAL | Default: Gustav::FILTER_AND
     *                                                  Use one of the Gustav::FILTER_* constants as this parameter's value.
     *                                                  If set to Gustav::FILTER_AND, a SRC file must match all specified (and supported) filters to be included in the resulting array.
     *                                                  Setting this parameter to Gustav::FILTER_OR means that a SRC file must match at least 1 (supported) filter to be included in the resulting array.
     * @param int             $order_by                 OPTIONAL | Default: Gustav::ORDER_PUB
     *                                                  Defines how to sort the matching SRC files. Use one of the Gustav::ORDER_* constants as this parameter's value.
     *                                                  If set to Gustav::ORDER_PUB or Gustav::ORDER_PUB_ASC, SRC files whose GvBlock doesn't contain the "_pub" option are moved to the end of the returned array, regardless of whether the sort is ascending or descending.
     * @param int             $min_match_score          OPTIONAL | Default: 0
     *                                                  Defines a percentage value relative to the highest match score of all matching SRC files. SRC files whose match score is lower than the defined minimum percentage of the maximum match score are ignored and removed from the resulting array.
     *                                                  Setting this parameter to 0, disables this filter and keeps all matching SRC files in the array.
     *                                                  Actually this parameter's value can be any number, also a float.
     * @param bool            $include_disabled         OPTIONAL | Default: false
     *                                                  If set to true, disabled source files are no longer ignored.
     * @param bool            $include_hidden_directory OPTIONAL | Default: false
     *                                                  If set to true, source files located in a `__hidden` directory or in one of its subdirectories are no longer ignored.
     *
     * @return string[][] Returns an associative array whose keys are the matching SRC files' paths and whose values are associative arrays whose keys are the supported keys of the $filters's "match" item and whose values are arrays containing the appropriate matching items. If "match" doesn't exist within $filters, an empty array is used as value instead.
     */
    public static function query($str_dir="", $q_recursive=true, $arr_filter=null, $int_filterOp=self::FILTER_AND, $int_order=self::ORDER_PUB, $int_minScore=0, $q_incDis=false, $q_incHiddenDir=false){
        $str_dir=self::path($str_dir);
        
        if(is_null($arr_filter)){
            $arr_filter=array(
                "prop"=>array(
                    "_hidden"=>false
                ),
                "older_than"=>time()
            );
        }
        
        $arr_files=array();
        $arr_scores=array();
        $arr_pub=array();
        $fn_a=function($str_dir) use (&$fn_a, $q_recursive, $arr_filter, $int_filterOp, $q_incDis, $q_incHiddenDir, &$arr_files, &$arr_scores, &$arr_pub){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            foreach(call_user_func(array($str_hooks, "scandir"), $str_dir, constant(__CLASS__."::SCANDIR_TYPE_FILE")|constant(__CLASS__."::SCANDIR_TYPE_DIR")) as $val){
                if(@is_dir($val)){
                    if(basename($val)=="__hidden" && !$q_incHiddenDir){
                        continue;
                    }
                    
                    if($q_recursive){
                        $fn_a($val);
                    }
                }else /**/if(@is_file($val))/**/{
                    try {
                        $src_a=new GustavSrc($val);
                    } catch(Exception $e){
                        continue;
                    }
                    
                    if($src_a->isDis() && !$q_incDis){
                        continue;
                    }
                    
                    $arr_block=$src_a->getBlock()->get();
                    $int_filters=0;
                    $int_matchingFilters=0;
                    $int_score=0;
                    
                    if(array_key_exists("match", $arr_filter)){ //array
                        $arr_match=$arr_filter["match"];
                        $int_matchFlags=array_key_exists("flags", $arr_match) ? $arr_match["flags"] : 0;
                        
                        unset($arr_match["flags"]);
                        
                        try {
                            $match_a=new GustavMatch($src_a->getPath(), $arr_match, $int_matchFlags);
                        } catch(Exception $e){
                            $match_a=null;
                        }
                        
                        if(!is_null($match_a)){ //if the GustavMatch object cannot be created, the "match" filter is ignored
                            $int_score=$match_a->getScore();
                            $arr_matches=$match_a->getMatches();
                            
                            foreach($arr_matches as $key=>$val_b){
                                if(count($arr_match[$key])>0){
                                    /* each item is a single standalone filter * /
                                    if(count($val_b)>0){
                                        $int_matchingFilters++;
                                    }
                                    $int_filters++;
                                    /*/
                                    if($int_score>0){
                                        $int_matchingFilters++;
                                    }
                                    $int_filters++;
                                    
                                    break;
                                    /* at least one item must match */
                                }
                            }
                        }
                    }
                    if(array_key_exists("conv", $arr_filter) && count($arr_filter["conv"])>0){ //array
                        //_conv will never be an empty array, thus only take this filter into account when it has at least 1 item and don't consider the match to be successful when both, the filter's conv array as well as the _conv array, are empty.
                        
                        $str_filterHardConvs=implode(".", call_user_func_array("array_merge", array_map(array($str_hooks, "getHardConv"), $arr_filter["conv"])));
                        
                        if(count(array_intersect($arr_block["_conv"], $arr_filter["conv"]))>0 || count(array_filter($arr_block["_conv"], function($val) use ($str_filterHardConvs, $str_hooks){
                            return call_user_func(array($str_hooks, "convExists"), $val, $str_filterHardConvs);
                        }))>0){
                            $int_matchingFilters++;
                        }
                        $int_filters++;
                    }
                    if(array_key_exists("dest", $arr_filter)){ //string - is passed to GustavBase::path()
                        $str_filter=call_user_func(array($str_hooks, "path"), $arr_filter["dest"]);
                        $str_dest=$arr_block["_dest"];
                        
                        if($str_dest==$str_filter || /*using rtrim() is save since $str_dest should never end with multiple directory separators since it has been passed to GustavBase::path()*/rtrim($str_dest, DIRECTORY_SEPARATOR)/*/preg_replace('/'.call_user_func(array($str_hooks, "escapeRegex"), DIRECTORY_SEPARATOR).'$/', "", $str_dest)/**/==$str_filter){
                            /**
                             * +   if the "_dest" option's value ends with a directory separator,
                             *     the filter's value may or may not end with a directory separator
                             * +   if the "_dest" option's value does not end with a directory separator,
                             *     the filter's value must not do, too
                             */
                            $int_matchingFilters++;
                        }else{
                            try {
                                $dest_a=new GustavDest($src_a->getPath());
                            } catch(Exception $e){
                                $dest_a=null;
                            }
                            
                            if(!is_null($dest_a)){
                                $str_destPath=call_user_func(array($str_hooks, "stripPath"), $dest_a->getPath());
                                
                                if($str_destPath==$str_filter){
                                    $int_matchingFilters++;
                                }
                            }
                        }
                        $int_filters++;
                    }
                    if(array_key_exists("newer_than", $arr_filter)){ //int
                        if(array_key_exists("_pub", $arr_block) && $arr_block["_pub"]>$arr_filter["newer_than"]){
                            $int_matchingFilters++;
                        }
                        $int_filters++;
                    }
                    if(array_key_exists("older_than", $arr_filter)){ //int
                        if(!array_key_exists("_pub", $arr_block) || $arr_block["_pub"]<=$arr_filter["older_than"]){
                            $int_matchingFilters++;
                        }
                        $int_filters++;
                    }
                    if(array_key_exists("prop", $arr_filter)){ //array
                        foreach($arr_filter["prop"] as $key=>$val_b){
                            if(is_bool($val_b)){
                                if(array_key_exists($key, $arr_block)==$val_b){
                                    $int_matchingFilters++;
                                }
                            }else{
                                if(array_key_exists($key, $arr_block)){
                                    $val_b=is_array($val_b) ? $val_b : array($val_b);
                                    $arr_a=is_array($arr_block[$key]) ? $arr_block[$key] : array($arr_block[$key]);
                                    
                                    if(count($val_b)>0 ? count(array_intersect($arr_a, $val_b))>0 : /*count($val_b)==0*/ count($arr_a)==0){
                                        /**
                                         * if an empty array has been specified as an item's value,
                                         * it matches that prop if that prop's value is an empty array, too.
                                         *
                                         * if a value of another type than array has been specified, it gets casted as an array
                                         * (i.e. a new array with the specified value as its first item is created).
                                         * the same applies to props' values.
                                         * Therefore an empty array as a prop-filter's item's value is possible only when
                                         * it has been specfied as such. if the value gets converted to an array, it will always
                                         * has exactly 1 item (the original value).
                                         *
                                         * if the passed array (original value or converted) isn't empty, the prop's array
                                         * (property's original or casted value) must contain at least 1 item
                                         * that is also present in the passed array.
                                         */
                                        
                                        $int_matchingFilters++;
                                    }
                                }
                            }
                            $int_filters++;
                        }
                    }
                    
                    if($int_filters==0 || $int_filterOp==constant(__CLASS__."::FILTER_AND") && $int_matchingFilters==$int_filters || $int_filterOp==constant(__CLASS__."::FILTER_OR") && $int_matchingFilters>0){
                        array_push($arr_files, $val);
                        array_push($arr_scores, $int_score);
                        array_push($arr_pub, array_key_exists("_pub", $arr_block) ? $arr_block["_pub"] : false);
                    }
                }
            }
        };
        
        $str_dir=self::path($_SERVER["DOCUMENT_ROOT"], self::getConf(self::CONF_SRC_DIR), $str_dir);
        
        $fn_a($str_dir);
        
        if(count($arr_files)==0 /*|| count($arr_scores)==0 || count($arr_pub)==0*/){ //`max()` requires an array containing at least one item
            return array();
        }
        
        $int_maxScore=max($arr_scores);
        
        foreach($arr_scores as $i=>$val){
            if($val<$int_minScore/100*$int_maxScore){
                //use array_splice() instead of unset() to ensure that the keys match the real offset/index of the items (sothat those keys can be used for array_splice()'s second parameter at #Gustav::query-order-pub-false since that function doesn't use the key but the offset of the arrays beginning)
                array_splice($arr_scores, $i, 1);
                array_splice($arr_pub, $i, 1);
                array_splice($arr_files, $i, 1);
            }
        }
        
        if($int_order==self::ORDER_MATCH || $int_order==self::ORDER_MATCH_ASC || $int_order==self::ORDER_PUB || $int_order==self::ORDER_PUB_ASC){
            //if the list is ordered by _pub (Gustav::ORDER_PUB or Gustav::ORDER_PUB_ASC), files that have no _pub option in their GvBlock will be at the end of the list, no matter whether the order is asc or desc
            array_multisort(${($int_order==self::ORDER_MATCH || $int_order==self::ORDER_MATCH_ASC) ? "arr_scores" : "arr_pub"}, SORT_NUMERIC, ($int_order==self::ORDER_MATCH_ASC || $int_order==self::ORDER_PUB_ASC) ? SORT_ASC : SORT_DESC, $arr_files);
            
            #Gustav::query-order-pub-false#
            if($int_order==self::ORDER_PUB || $int_order==self::ORDER_PUB_ASC){
                foreach(array_reverse(array_keys($arr_pub, false, true)) as $val){ //from high indexes to low indexes to unsure that the indexes are still correct after removing an item from the array (and pushing it to end of the array)
                    array_push($arr_files, @array_pop(array_splice($arr_files, $val, 1)));
                }
                
                //at this point $arr_scores and $arr_pub may not correspond to $arr_files any more (if a `false` value was found in $arr_pub and $arr_files has been reordered)
            }
        }else if($int_order==self::ORDER_RAND){
            shuffle($arr_files);
            
            //at this point $arr_scores and $arr_pub don't correspond to $arr_files any more since (only) $arr_files has been shuffled
        }
        
        return $arr_files;
    }
    
    #Gustav::search()#
    /**
     * Search for source files matching a search term.
     *
     * The matching source files are ordered by their match scores. Disabled source files and such located in a `__hidden` directory are ignored.
     * Besides the `match` filter, the default filter for `Gustav::query()` is used.
     *
     * @param string   $search_term      The search term to search for in the source files' properties.
     * @param string   $directory        OPTIONAL | Default: ""
     *                                   The path of the directory to search in for matching source files. The path is treated relatively to the source directory
     *                                   and is passed to `Gustav::query()` which in turn calls `GustavBase::path()` on the path.
     * @param bool     $search_recursive OPTIONAL | Default: true
     *                                   Specifies whether to include all subdirectories of the specified directory when searching for source files.
     * @param int|null $search_members   OPTIONAL | Default: null
     *                                   Defines the source-file-properties to match the search term items against.
     *                                   The value for this parameter should be a bitmask of `Gustav::SEARCH_*` constants.
     *                                   If set to `null`, a value of `Gustav::SEARCH_TAGS|Gustav::SEARCH_TITLE|Gustav::SEARCH_FILE` is used instead.
     * @param int      $match_flags      OPTIONAL | Default: 0
     *                                   The flags passed to the `GustavMatch` constructor.
     * @param int      $min_score        OPTIONAL | Default: 0
     *                                   Defines a percentage value, relative to the highest match score of all matching source files.
     *                                   Source files whose match score is lower than the specified minimum percentage are removed from the resulting array.
     *
     * @return GustavMatch[] Returns an array of `GustavMatch` object for the matching source files.
     */
    public static function search($str_term, $str_dir="", $q_recursive=true, $int_search=null, $int_matchFlags=0, $int_minScore=0){
        $int_search=is_null($int_search) ? self::SEARCH_TAGS|self::SEARCH_TITLE|self::SEARCH_FILE : $int_search;
        
        $arr_term=GustavMatch::processSearchTerm($str_term);
        $arr_match=array();
        
        if($int_search&self::SEARCH_TITLE){
            $arr_match[self::KEY_TITLE]=$arr_term;
        }
        if($int_search&self::SEARCH_TAGS){
            $arr_match[self::KEY_TAGS]=$arr_term;
        }
        if($int_search&self::SEARCH_FILE){
            $arr_match[self::KEY_FILE]=$arr_term;
        }
        
        $arr_matches=array();
        
        foreach(self::query($str_dir, $q_recursive, array(
            "match"=>array_merge(
                array(
                    "flags"=>$int_matchFlags
                ),
                $arr_match
            ),
            "prop"=>array(
                "_hidden"=>false
            ),
            "older_than"=>time()
        ), self::FILTER_AND, self::ORDER_MATCH, $int_minScore) as $val){
            try {
                $match_a=new GustavMatch($val, $arr_match, $int_matchFlags);
            } catch(Exception $e){
                continue;
            }
            
            array_push($arr_matches, $match_a);
        }
        
        return $arr_matches;
    }
    
    #Gustav::getTags()#
    /**
     * Get all available tags.
     *
     * Tags that differ only in their letters' cases are merged and the version with the most occurrences in all SRC files' tags is used.
     * The returned array contains only tags used by at least one SRC file matching the default filter of Gustav::query().
     *
     * @return int[] Returns an associative array containing the tags' names as keys and their numbers of occurrences as values. The tags are ordered by their numbers of occurrences.
     */
    public static function getTags(){
        $arr_files=self::query("", true, null, self::FILTER_AND, self::ORDER_NONE);
        $arr_a=array();
        
        foreach($arr_files as $val){
            try {
                $src_a=new GustavSrc($val);
            } catch(Exception $e){
                continue;
            }
            
            foreach($src_a->getBlock("_tags") as $val_b){
                $arr_a[$val_b]=(int)(@$arr_a[$val_b])+1;
            }
        }
        
        arsort($arr_a); //ensures that tags that differ only in their letters' cases, the various versions get summed up and merged together to the one with the most occurrences in all SRC files' tags.
        
        $arr_b=array();
        $arr_tags=array();
        
        foreach($arr_a as $key=>$val){
            $str_a=self::mb_strtolower($key);
            
            if(!array_key_exists($str_a, $arr_b)){
                $arr_b[$str_a]=$key;
            }
            
            $str_b=$arr_b[$str_a];
            
            $arr_tags[$str_b]=(int)(@$arr_tags[$str_b])+$val;
        }
        
        arsort($arr_tags); //sort the resulting array by the number of the tags' occurrences (case-insensitively; descending)
        
        return $arr_tags;
    }
    
    #Gustav::getCategories()#
    /**
     * Get all available categories.
     *
     * The returned array contains only the categories containing (directly or within their subcategories/-directories) at least one SRC file matching the default filter of Gustav::query().
     *
     * @return array[] Returns an array containing the categories' urlencoded absolute paths as keys and arrays containing information on a category as well as its subcategories as values. The categories are ordered by their names.
     *                 An example array as returned by this function:
     *
     *                     array(
     *                         "/blog/"=>array(
     *                             "root"=>true, //True, if this category is the of the dest dir, otherwise false.
     *                             "count"=>3, //Number of src/dest files that are direct children of this directory
     *                             "name"=>"blog" //Name of category (i.e. `basename("/blog")`).
     *                                            //You may want to use a custom name for the root "category" instead.
     *                             "sub"=>array( //Subcategories of this category
     *                                 "/blog/Development/"=>array(
     *                                     "root"=>false,
     *                                     "count"=>12,
     *                                     "name"=>"Development",
     *                                     "sub"=>array(
     *                                         "/blog/Development/Web%20Dev/"=>array(
     *                                             "root"=>false,
     *                                             "count"=>7,
     *                                             "name"=>"Web Dev",
     *                                             "sub"=>array(
     *                                                 //...
     *                                             )
     *                                         )
     *                                         //...
     *                                     )
     *                                 ),
     *                                 "/blog/Music/"=>array(
     *                                     "root"=>false,
     *                                     "count"=>18,
     *                                     "name"=>"Music",
     *                                     "sub"=>array(
     *                                         //...
     *                                     )
     *                                 )
     *                                 //...
     *                             )
     *                         )
     *                     )
     */
    /*public static function getCategories(){
        $arr_files=self::query("", true, null, self::FILTER_AND, self::ORDER_NONE);
        
        if(count($arr_files)==0){
            return array();
        }
        
        $arr_categories=call_user_func_array("array_merge_recursive", array_map(function($val){
            $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
            
            $arr_a=array();
            $arr_b=&$arr_a;
            
            try {
                $src_a=new GustavSrc($val);
            } catch(Exception $e){
                return $arr_a;
            }
            
            $arr_category=$src_a->getCategory();
            
            foreach($arr_category as $i=>$val_b){
                $str_path=call_user_func(array($str_hooks, "path"), call_user_func(array(__CLASS__, "getConf"), constant(__CLASS__."::CONF_DEST_DIR")), array_slice($arr_category, 0, $i+1));

                $arr_b[$str_path]=array();
                $arr_b=&$arr_b[$str_path];
            }
            
            return $arr_a;
        }, $arr_files));
        $fn_a=function(&$arr_a) use (&$fn_a){ //can't use array_walk_recursive since the callback function passed to that function only gets called for non-array values
            ksort($arr_a);
            array_walk($arr_a, $fn_a);
        };
        
        $fn_a($arr_categories);
        
        return $arr_categories;
    }*/
    
    public static function getCategories(){
        $arr_categories=array();
        $arr_categoryProto=array(
            "count"=>0,
            "sub"=>array(),
            "root"=>false
            //"name"=>""
        );
        $str_root=self::getConf(self::CONF_DEST_DIR);
        
        $arr_categories[$str_root]=array_merge($arr_categoryProto, array(
            "name"=>basename($str_root),
            "root"=>true
        ));
        
        $arr_files=self::query("", true, null, self::FILTER_AND, self::ORDER_NONE);
        
        foreach($arr_files as $val){
            $str_categoryPath=$str_root;
            $arr_category=&$arr_categories[$str_categoryPath];
            
            try {
                $src_a=new GustavSrc($val);
            } catch(Exception $e){
                continue;
            }
            
            $arr_srcCategory=$src_a->getCategory();
            
            foreach($arr_srcCategory as $val_b){
                $arr_category=&$arr_category["sub"];
                $str_categoryPath=self::path($str_categoryPath, $val_b);
                
                if(!array_key_exists($str_categoryPath, $arr_category)){
                    $arr_category[$str_categoryPath]=array_merge($arr_categoryProto, array(
                        "name"=>basename($str_categoryPath)
                    ));
                }
                
                $arr_category=&$arr_category[$str_categoryPath];
            }
            
            $arr_category["count"]++;
        }
        
        if(count($arr_categories[$str_root]["sub"])==0 && $arr_categories[$str_root]["count"]==0){
            unset($arr_categories[$str_root]);
        }
        
        $fn_a=function(&$arr_a) use (&$fn_a){ //can't use array_walk_recursive() since the callback function passed to that function only gets called for non-array values
            if(count($arr_a)==0){
                return;
            }
            
            ksort($arr_a);
            
            $arr_a=array_combine(array_map(function($val){
                $str_hooks=constant(__CLASS__."::HOOKS_CLASS");
                
                return call_user_func(array($str_hooks, "path2url"), $val, false);
            }, array_keys($arr_a)), array_values($arr_a));
            
            array_walk($arr_a, function(&$val) use ($fn_a){
                $fn_a($val["sub"]);
            });
        };
        
        $fn_a($arr_categories);
        
        return $arr_categories;
    }
    
    
    
}

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), @array_pop(explode("\\", constant(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME)."::HOOKS_CLASS", "\\")))).".php")); //include before calling Gustav::initGustav()
/**/
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavSrc.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavDest.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavMatch.php"));

call_user_func(array(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME), "\\"), "initGustav"));
/*/

call_user_func(array(ltrim(__NAMESPACE__."\\".pathinfo(__FILE__, PATHINFO_FILENAME), "\\"), "initGustav"));

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavSrc.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavDest.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavMatch.php"));
/**/
