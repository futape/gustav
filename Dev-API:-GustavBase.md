##Static functions

###`private mixed callMbFunc( string $function_name, array $arguments )`

This function calls one of its supported MB functions.  
When calling one of that functions, the items of the array passed to this function are passed to the called function. Missing required parameters are set to `null`, wich will most likely cause an error, and missing optional parameters are set to their default values.  
The only exception is the last parameter of each MB function - the character encoding definition. If not passed, it is set to the character encoding used by Gustav (`GustavBase::ENC`). This has the same effect as setting the character encoding used by MB functions globally using `mb_internal_encoding()` without doing so and let the user define his own global multibyte string character encoding.  
The following MB functions are supported.

+   `mb_strtoupper()`
+   `mb_strtolower()`
+   `mb_strlen()`
+   `mb_strpos()`
+   `mb_substr()`
+   `mb_substr_count()`

See PHP's documentation on these function for more information.

<dl>
    <dt><code>$function_name</code></dt>
    <dd>The name of the MB function to call.</dd>
    
    <dt><code>$arguments</code></dt>
    <dd>The arguments to pass to the function.</dd>
</dl>
     
Returns the return value of the called MB function.



##Static properties

###`private string[] $readFileCache`

The internal cache used by `GustavBase::readFile()`.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `GustavBase`'s corresponding Hooks class.