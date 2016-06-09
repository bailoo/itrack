<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('util_session_variable.php');
if($_POST['drop_down_menu_module']=='manage.htm')
{
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
}
else if($_POST['drop_down_menu_module']=='report.htm')
{
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
    
    if($_POST['title_report']!="")
    {
         $_SESSION['drop_down_menu_title_report'] = $_POST['title_report'];
    }
    else
    {
        $_SESSION['drop_down_menu_title_report'] = "";
    }
    
    if($_POST['type_upload']!="")
    {
         $_SESSION['drop_down_menu_report_type_upload'] = $_POST['type_upload'];
    }
    else
    {
        $_SESSION['drop_down_menu_report_type_upload'] = "";
    }
}

//echo $getUrlModule;
?>
