<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');	
	//$js_function_name = "manage_show_file";    // FUNCTION NAME  
	$DEBUG =0;
	echo "get_features##";
	//echo "SizeFeatureCount=".sizeof($feature_count);

  
  $usertype1 = $_POST['usertype'];
  $account_str1 = $_POST['account_str'];  
  //echo "<br>usertype1=".$usertype1;
  //echo "<br>account_str=".$account_str1;
  
  // GET USER TYPE FEATURES
  $query1="SELECT features FROM usertype_mapping WHERE usertype='$usertype1'";
  //echo $query1;
  $result1=@mysql_query($query1,$DbConnection);
	$row1=@mysql_fetch_object($result1);
	$feature_id_str = $row1->features;
	$feature_id_arr = explode(',',$feature_id_str);	
	$len = sizeof($feature_id_arr);
	
  $feautre_name ="";
  for($i=0;$i<$len;$i++)
	{
    $query1="SELECT feature_name FROM feature_mapping WHERE feature_id='$feature_id_arr[$i]'";
    $result1=@mysql_query($query1,$DbConnection);
  	$row1=@mysql_fetch_object($result1);	
  	$feature_name_tmp = $row1->feature_name;
      
    if($i==$len-1)
      $feautre_name .= "'".$feature_name_tmp."'";
	  else
      $feautre_name .= "'".$feature_name_tmp."'".',';
  }
    
  //echo "<br>ftype=".$feature_name;
  
  $tmp = explode(',',$account_str1);
	$account_id1 = $tmp[0];
	$group_id1 = $tmp[1];	
	
  $query1="SELECT ".$list_fname." FROM account_feature WHERE account_id='$account_id1'";
	if($DEBUG){echo "<br>fields=". print_query($query1);}
	
  $result1=@mysql_query($query1,$DbConnection);
	$row1=@mysql_fetch_object($result1);
	for ($fi=1; $fi<=$feature_count; $fi++) 
	{
		$perm_features[$fi]=$row1->$fname[$fi];
		if($DEBUG) print_message($fname[$fi],$perm_features[$fi]); 
	}		
	
  echo'
      <table class="add_account" border="0" align="center">
        <tr valign="top">
          <td colspan="6" align="center">
            <!--<input type="checkbox" name="all" onclick="javascript:check_uncheck_features(this.form)" checked/> Select All-->
            <input type="button" name="all" Onclick="javascript:return check_uncheck_features_button(this.form)" value="Select None" />
          </td>
        </tr>';
      
        $td_count=0;
        echo '<tr valign="top">';
        echo '<td><input type="hidden" value="'.$feature_count.'" name="fcount" id="fcount">';
  			//echo "feature_count=".$feature_count."<br>";
  			//echo "perm_features=".$perm_features."<br>";
	
        for ($fi=1; $fi<=$feature_count; $fi++)
        {
          //echo "<br>fname1=".$feautre_name;
          $query="SELECT feature_name FROM feature_mapping WHERE field_name = '$fname[$fi]' AND feature_name IN($feautre_name)";
          //echo $query;
          //if($DEBUG){print $query;}
          $result2=mysql_query($query,$DbConnection); 
          $row2=mysql_fetch_object($result2);
          $fname_mapping=$row2->feature_name; 			        
                  
          //echo "perm_features=".$perm_features[$fi]."<br>";
          if($perm_features[$fi]==1 && $fname_mapping)
          {
		        //echo "in if<br>";
            $td_count++;				
    				if($fname_mapping=="device_permission")
    				{
    					echo '<td><input type="checkbox" value="'.$fname[$fi].'" name="account_feature[]"></td>';
    				}
    				else
    				{
    					echo '<td><input type="checkbox" value="'.$fname[$fi].'" name="account_feature[]" checked></td>';
    				}
            echo '<td>'.ucfirst(strtolower($fname_mapping)).'</td>';
          }
          else
          {
            echo '<input type="hidden" value="'.$fname[$fi].'" name="account_feature[]">';
          }
          if ($td_count%3==0) echo '</tr><tr><td>';
        }
        echo '</td></tr>';
     echo'</table>';
?>  