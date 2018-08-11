<?php

//INITIAL CHECK AND LOAD DEFAULTS
include("admin/config.php");
//include_once('../pages/headerlinks.php');
require_once("myassets/functions/da_function.php");
require_once("myassets/functions/da_sql.inc.php");
require_once("myassets/functions/timzzy_function.php");

@$typ = $_GET['rdr'];
@$typ2 = $_GET['s'];//for the search page

//=====================================================
if ($typ =="index") {
    $typ="home";
}

//## Getting the pages
if(isset($typ)){
        //$mytitle = "WELCOME |&nbsp;";
        if(!file_exists($typ.'.php')){
            //include('404.php');
            display_error('ERROR 404, PAGE NOT FOUND');
            exit;            
        }
        else{
        include($typ.'.php');
        exit;
        }
}
elseif (isset($typ2)) {
    include('mysearch.php');
    exit;
}
else{
//=====================================================
//## DEFAULT PAGE
@include_once('home.php');
}//default to show
?>