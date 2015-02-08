##Static functions

###`public void initGustav()`

Initializes some of the Gustav class's static properties.  
For example, this function reads the configuration file and decodes it as JSON and saves the decoded array to [`Gustav::$conf`](Dev-API%3A-Gustav#private-array-conf). Moreover it validates the configurations and resets invalid options to their default values. If no default value is available for an invalid option, a fatal Gustav-error may be raised. Otherwise a warning log entry is done for every invalid option.  
Furthermore this function checks whether all required files and directories exist and logs on inexistence.  
If Gustav discovers a critical value for an important PHP configuration option, it creates a log entry, too.  
For more information see [*Gustav configuration*](Gustav-configuration#bool-check_status--true).  
If the [`check_status`](Gustav-configuration#bool-check_status--true) configuration option is set to `false`, nothing is logged.
This function gets executed everytime `Gustav.php` is included and can be executed for just one time.



##Static properties

###`private array $conf`

An array containing the configuration options and their values.

###`private bool $isInit`

Whether [`Gustav::initGustav()`](#public-void-initgustav) has already been executed.



##Constants

###`string HOOKS_CLASS`

The name, including the namespace, of `Gustav`'s corresponding Hooks class.

###`string GV_DIR`

The path of the directory containing Gustav-related files.
    
###`string EXT_DIR`

The name of the directory containing Gustav extensions.

###`string GEN_FILE`

The basename of the file, handling the auto-generation of missing destination files.

###`string CONF_FILE`

The basename of the configuration file.

###`string LOG_FILE`

The name of the directory containing the log-files created by Gustav.

###`string LOG_FILE`

The basename of the main log file, relative to the logs directory.

###`string CONV_DIR`

The name of the directory containing user-defined converters, relative to the Gustav extensions directory.

###`string CONV_CONST_NAMES`

The names of the `Gustav::CONV_*` constants (except for this one, `Gustav::CONV_CONST_NAMES`) separated by spaces.