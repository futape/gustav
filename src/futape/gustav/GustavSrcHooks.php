<?php
/*! Gustav 1.1.0 | Copyright (c) 2015 Lucas Krause | New BSD License | http://gustav.futape.de */

namespace futape\gustav;

require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavSrc.php"));

use BadMethodCallException;

abstract class GustavSrcHooks extends GustavSrc {
    
    
    
    #misc-functions#
    
    #GustavSrcHooks::__callStatic()#
    /**
     * A "magic" overloading function that gets called when an class's non-reachable function is called.
     * This function is used to make all non-reachable static function of the GustavSrc class publically
     * available.
     * If a non-existing function is called, a BadMethodCallException exception is thrown.
     *
     * @param string $function_name The name of the called function.
     * @param array  $arguments     The arguments passed to the called function.
     *
     * @return mixed
     */
    public static function __callStatic($str_fn, $arr_args){
        if(method_exists(__CLASS__, $str_fn)){
            return call_user_func_array(array(__CLASS__, $str_fn), $arr_args);
        }
        
        if(method_exists(get_parent_class(), "__callStatic")){
            $arr_args_b=func_get_args();
            
            return call_user_func_array(array(get_parent_class(), "__callStatic"), $arr_args_b);
        }
        
        throw new BadMethodCallException("Method doesn't exist.");
    }
    
    
    
}
