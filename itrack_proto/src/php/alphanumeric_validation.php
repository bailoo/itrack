<?php
$input = "A1SFS-sdsd";
if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $input)){
    echo "<br>Valid";
    //Valid
}
else{
    echo "<br>InValid";
    //invalid
}
/*
$element = "123123";
 if (is_numeric($element)) {
        echo "Is numeric";
    } else {
        echo "is NOT numeric";
   }
 

 $a="12345/2001/4000";  
if (strpos($a,'200') !== false) {
    echo 'true';
}
else
{
    echo "false";
}*/
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>