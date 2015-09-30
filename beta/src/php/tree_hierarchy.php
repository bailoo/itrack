<?php
	$link_list = new Hierarchy();
        if($user_type=='proc_admin')
         {
            $row_account_admin_id =getAccountAdminId($account_id,$DbConnection);
            $parent_admin_id=getAccountIdByAdminId($row_account_admin_id,$DbConnection);
            //echo "PID=".$parent_admin_id;
            $root = $link_list->GetHierarchy($group_id,$parent_admin_id,$userTypeLogin,$admin_id,$user_type,$DbConnection); 
         }
         else
         {
             $root = $link_list->GetHierarchy($group_id,$account_id,$userTypeLogin,$admin_id,$user_type,$DbConnection); 
         }
	//$root = $link_list->GetHierarchy($group_id,$account_id,$userTypeLogin,$admin_id,$user_type,$DbConnection); 
	$_SESSION['root']=$root;
?>