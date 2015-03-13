<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Proj_CapsulaTemporal/includes/general_settings.php';

//authorization();
$langs = array('es');

if ((!isset($_GET['lang'])) && !isset($_GET['url'])) {
    set404();
} else {
    $url = cleanUrl($_GET['url']);
    if (in_array($_GET['lang'], $langs)) {
        $lang_set = $_GET['lang'];
        require_once BASE_PATH . 'langs/' . $_GET['lang'] . '.lang.php';
    } else {
        set404();
    }
    /* INICIALIZAMOS LAS VARIABLES QUE FORMAN EL CUERPO */
    $output_css = '';
    $output_js = '';
    $output_body = '';
    $output_footer = '';
    $output_nav = '';
    
    /* INCLUIMOS LOS FICHEROS DE FUNCIONES DE LAS ZONAS ESTÁNDAR DE LA APP */
    require_once BASE_PATH . 'public-functions/css.php';
    require_once BASE_PATH . 'public-functions/js.php';

    /* CARGAMOS EL CONTENIDO COMUN DE LAS DIFERENTES VARIABLES QUE FORMAN EL CUERPO QUE TIENEN CONTENIDO COMÚN */
    $output_css .= getCss();
    $output_js .= getJs();
    
    /* EN FUNCIÓN DE LA SECCIÓN QUE CARGAMOS $url[0] ACTUAMOS */
    switch ($url[0]) {
        default :
            if (isset($url[0])) {
                
            } else {
                //HOME
                $output_body .= 'echo "CAPSULA CORP".';
            }
            break;
    }
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <title>
            <?php
            echo 'Capsulas Temporal';
            ?>
        </title>
        <?php
            echo $output_css;
        ?>
    </head>
    <body>
        <div id="wrap">
            <div id="nav">
                <?php
                    echo $output_nav;
                ?>
            </div>
            <div id="content" class="">
                <?php
                echo $output_body;
                ?>
            </div>

            <div id="footer" class="">
                <?php
                    echo $output_footer;
                ?>
            </div>
        </div>
        <?php
            echo $output_js;
        ?>
    </body>
</html>
