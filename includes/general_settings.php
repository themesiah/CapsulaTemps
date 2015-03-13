<?php

//------------------------------------------------ Server config --------------------------------------------------------------
if (isset($_SERVER['SERVER_NAME'])) {
    //ini_set("session.cache_expire","7200");
    //ini_set("session.cookie_lifetime","7200");
    //ini_set("session.gc_maxlifetime","7200");
    session_start();
    switch ($_SERVER['SERVER_NAME']) {
        case 'localhost' :
            ini_set('display_errors', 1);
            //error_reporting(E_ALL & ~E_NOTICE);
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
            //error_reporting(0);
            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '-1');
            date_default_timezone_set('Europe/Madrid');

            define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Proj_CapsulaTemporal/');
            define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/Proj_CapsulaTemporal/');

            //MySQL Connection
            define('DBSERVER', 'localhost');
            define('DBUSERNAME', 'root');
            define('DBPASSWORD', '');
            define('DBSCHEMA', '');


            define('FUNCTION_DEBUG', false);
            define('SQL_DEBUG', false);

            break;

        default :
            ini_set('display_errors', 1);
            //error_reporting(E_ALL & ~E_NOTICE);
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
            //error_reporting(0);
            ini_set('max_execution_time', 300);
            // ini_set('memory_limit', '-1');
            date_default_timezone_set('Europe/Madrid');

            define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Proj_CapsulaTemporal/');
            define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/Proj_CapsulaTemporal/');

            //MySQL Connection
            define('DBSERVER', 'localhost');
            define('DBUSERNAME', 'root');
            define('DBPASSWORD', '');
            define('DBSCHEMA', '');
            define('FUNCTION_DEBUG', false);
            define('SQL_DEBUG', false);
            break;
    }
} else {
    ini_set('display_errors', 1);
    //error_reporting(E_ALL & ~E_NOTICE);
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    //error_reporting(0);
    ini_set('max_execution_time', 300);
    // ini_set('memory_limit', '-1');
    date_default_timezone_set('Europe/Madrid');

    define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Proj_CapsulaTemporal/');
    define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/Proj_CapsulaTemporal/');

    //MySQL Connection
    define('DBSERVER', 'localhost');
    define('DBUSERNAME', 'root');
    define('DBPASSWORD', '');
    define('DBSCHEMA', '');

    define('FUNCTION_DEBUG', false);
    define('SQL_DEBUG', false);
}

//------------------------------------------------ control definitions --------------------------------------------------------------

define('DEBUG_ENTER', "<br/>\n");


//------------------------------------------------ instanciar controlador bbdd ----------------------------------------------------------------------
require_once BASE_PATH . 'includes/functions.php';
//cleanDebugLog();
//insertDebugLog('inicio');
require_once BASE_PATH . 'dal/database.php';

//------------------------------------------------- autoloader dal define------------------------------------------------------------------------------

function mi_autocargador($clase) {
    include BASE_PATH . 'dal/' . strtolower($clase) . '_db.php';
}

spl_autoload_register('mi_autocargador');


//------------------------------------------------ basic definitions ----------------------------------------------------------------
