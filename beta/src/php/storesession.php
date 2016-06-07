<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('util_session_variable.php');
$_SESSION['drop_down_menu_module'] = $_POST['drop_down_menu_module'];
$_SESSION['drop_down_menu_file'] = $_POST['drop_down_menu_file'];
if($_POST['js_type']!="")
{
    $_SESSION['drop_down_menu_js_type'] = $_POST['js_type'];
}
else
{
    $_SESSION['drop_down_menu_js_type'] ="";
}
//echo $getUrlModule;
?>
