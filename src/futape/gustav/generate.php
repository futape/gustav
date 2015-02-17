<?php
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "Gustav.php"));
require_once implode(DIRECTORY_SEPARATOR, array(rtrim(__DIR__, DIRECTORY_SEPARATOR), "GustavGenerator.php"));

use futape\gustav\Gustav;
use futape\gustav\GustavGenerator;

GustavGenerator::genByUrl($_SERVER['REQUEST_URI'], Gustav::getConf(Gustav::CONF_GEN_SEARCH_RECURSIVE));
?>