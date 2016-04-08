<?php
	$link_list = new Hierarchy();	
	$root = $link_list->GetHierarchy($group_id,$account_id,$admin_id,$user_type,$DbConnection); 
	$_SESSION['root']=$root;
?>