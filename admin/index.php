<?php
/**
* ==================================================================================//
*   This script hanldes the Admin page loadings and takes care of 
*   Every Administration tasks. This is the main Application script
*   That loads every other sections/codes to make the program run                                
*   script : index.php
*   
*   Author: Ogundaere Obaloluwa
*   Copyright (c) 2014  All rights reserved 
* 
*   No part of this code can be used/modified without prior permission
*   from the Author
* ==================================================================================//
*/
//INITIAL CHECK AND LOAD DEFAULTS

include("config.php");
require_once("../myassets/functions/da_function.php");
require_once("../myassets/functions/da_sql.inc.php");
$ezzzy = new SamMysql($con);
// which section
$section = "God is Involved";
$pageclass = "fa fa-bank fa-lg";
@$typ = $_GET['rdr'];
//@$typ1 = $_GET['typ1'];
//=====================================================



if(isset($typ)){
        $mytitle = "WELCOME |&nbsp;";
        if(!file_exists($typ.'.php')){
        	display_error('Page Not exist!!');
            //@include('404.php');
            exit;            
        }
        else{
        include($typ.'.php');
        exit;
        }
}
//=====================================================
//## DEFAULT PAGE
@include_once('home.php');

?>