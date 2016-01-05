<?php 
    include_once('Hierarchy.php');
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    include_once('active_vehicle_func.php');
    //if($account_id!=2)
    {
    include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
    include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/
    
    $o_cassandra = new Cassandra();	
    $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
    }
   $vehicle_color1=getColorFromAP($account_id,$DbConnection); /// A->Account P->Preference

    $vcolor = explode(':',$vehicle_color1); //account_name:active:inactive
    $vcolor1 = "#".$vcolor[0];
    $vcolor2 = "#".$vcolor[1];
    $vcolor3 = "#".$vcolor[2];
    

    $root=$_SESSION['root'];
    $DEBUG = 0;    
    $display_type = @$_POST['display_type1']; 
    $common_div_option = @$_POST['common_div_option1'];
    $user_type_option = @$_POST['user_type_option1'];
    $category1 = $_POST['category'];
    if($user_type_option=="group")
    {
        $tmp_type="By Group";
    }
    else if($user_type_option=="user")
    {
        $tmp_type="By User";
    }
    else if($user_type_option=="vehicle_tag")
    {
            $tmp_type="By Vehicle Tag";
    }
    else if($user_type_option=="vehicle_type")
    {
            $tmp_type="By Vehicle Type";
    }
    else if($user_type_option=="vehicle")
    {
            $tmp_type="By Vehicle";
    }
    else if($user_type_option=="all" || $user_type_option=="")
    {
            $tmp_type="All";
    }
	
    $function_name="get_".$common_div_option;
    //echo "functon_name=".$function_name;
    $div_option_values = @$_POST['div_option_values1'];
    $group=array(array());
    $group_cnt;
    $cnt=0;

    $vehicleid=array();
    $vehicle_cnt; 
    $user_name1;
    $user_cnt;

    //date_default_timezone_set('Asia/Calcutta');
    $current_time = date('Y/m/d H:i:s');
    $today_date=explode(" ",$current_time);
    $today_date1=$today_date[0];
    $today_date2 = str_replace("/","-",$today_date1);	

    echo "map_show_vehicle##";	
    $logDate=date('Y-m-d');
    //echo "<br>CURRENT_TIME=".$current_time;
    //echo "category1=".$category1."<br>";
    //echo "user_type_option=".$user_type_option."temp_type=".$tmp_type."<br>";
    echo "<table width=100% border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>";		
    echo"<tr>
            <td class='text' colspan='2'>
                <span id='all'>
                    &nbsp;<input type='checkbox' name='all' value='1' onClick='javascript:SelectAll(\"vehicle\");'>&nbsp;&nbsp;&nbsp;Select All	( ".$tmp_type." )			         
                </span>
            </td>			
        </tr>";	
    
    if($display_type=="default" || $display_type=="single")
    { 
        //$category="1";
        //echo "category=".$category1;
        show_all_vehicle($root,$account_id,$category1);				
    } 
    else
    {		
        $function_name($root,$div_option_values,$category1);
    }
echo"</table>";
//$o_cassandra->close();
    
    function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name)
    {	
        //echo "in function";
        global $today_date2;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;		
		//echo "today_date=".$today_date2;		
        echo'<tr> 
                <td align="left">
                    &nbsp;<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$vehicle_imei.'">
                </td>
                <td>
                    <A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">';
                    $xml_current = "../../../xml_vts/xml_data/".$today_date2."/".$vehicle_imei.".xml";
                    //echo "<br>xml_current=".$xml_current;					
                    if (file_exists($xml_current))
                    {
                       //echo "<font color='green'>".$vehicle_name."</font>";
                       echo '<font color="'.$vcolor2.'">'.$vehicle_name.'</font>';
                    }
                    else
                    {
                        //echo "<font color='grey'>".$vehicle_name."</font>";
                        echo '<font color="'.$vcolor3.'">'.$vehicle_name.'</font>';
                    }
                echo'</A>
                </td>
            </tr>';
    }  	  	
  
   function active_inactive_count($grn_cnt,$gry_cnt)
   {        
      global $vcolor1;
      global $vcolor2;
      global $vcolor3;
           
      echo '
      <tr>
        <td colspan="2">            
          (
            <font color="'.$vcolor2.'">Active : '.$grn_cnt.'</font>
            /
            <font color="'.$vcolor3.'">InActive : '.$gry_cnt.'</font>
          )
        </td>
      </tr>
      ';    
   }
  
  $s=0; 
  function common_display_vehicle($vehicle_name_arr,$imei_arr,$color)
  {      
      global $s;
      
      if(sizeof($vehicle_name_arr)>0)
      { 
         
        natcasesort($vehicle_name_arr);
        
        foreach($vehicle_name_arr as $vehicle)
        {
          if($s==0)
          {
            //echo "<br>ss=".$s;
            $vchk = "vcheckbox";
            $vrad = "vradio";
          }
          else
          {
            $vchk = "vcheckbox".$s;
            $vrad = "vradio".$s;          
          }
          
          echo '
          <tr>
            <td align="left">             
              <span id="'.$vchk.'"><INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$imei_arr[$vehicle].'"></span>
              <span id="'.$vrad.'" style="display:none;"><INPUT TYPE="radio"  name="vehicleserial_radio" VALUE="'.$imei_arr[$vehicle].'"></span>              
            </td>
            <td>
              <font color="'.$color.'">'.$vehicle.'</font>
            </td>
          </tr>
          ';
          $s++;
        }
      }
  }
  
    function show_all_vehicle($AccountNode,$account_id_local,$category1)
    {
        global $today_date2;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $DbConnection;
        global $account_id;
        global $o_cassandra;
        //var_dump($o_cassandra);
        //global $logDate;
    
        //echo "cat:".$category1;
        //echo $vcolor1.":".$vcolor2.":".$vcolor3;
     
        $user_type_local=$AccountNode ->data-> AccountType;
        $account_name=$AccountNode->data->AccountName;
        $vehicle_name_arr=array();
        $imei_arr=array();
        $vehicle_color=array();
        $veh_flag=1; 
		  
        for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)   ///////this is for show root vehicle of any account /////////
        {				
            if($AccountNode->data->VehicleCategory[$j]==$category1)
            {
                $veh_flag=0;					
                $vehicle_id = $AccountNode->data->VehicleID[$j];
                $vehicle_name = $AccountNode->data->VehicleName[$j];
                $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                ///array_search('green', $array);
                $iovalueandtypearr = @$AccountNode->data->DeviceIOTypeValue[$j];

                $tmp_iotype_str="";
                if(count($iovalueandtypearr)>0)
                {					
                    $tmp_iotype_str="*".$iovalueandtypearr[$vehicle_imei];
                }
                else
                {
                    $tmp_iotype_str="*tmp_str";
                }
                if(@$vehicle_id!=null)
                {
                    for($i=0;$i<@$vehicle_cnt;$i++)
                    {
                        if(@$vehicleid[$i]==@$vehicle_id)
                        {
                            break;
                        }
                    }			
                    if($i>=@$vehicle_cnt)
                    {
                        $vehicleid[@$vehicle_cnt]=$vehicle_id;
                        @$vehicle_cnt++;  
                        if($AccountNode->data->DeviceRunningStatus[$j]=="1")
                        {							
                            $color= $vcolor2;
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                        }
                        else
                        {
                            $currentFilePath="/mnt/itrack/beta/src/php/vehicleStatus";
                            $iterator = new FilesystemIterator($currentFilePath);                       
                            //echo "imeiNo11=".count($iterator)."<br>";
                            $fileFoundFlag=0;
                            $dateObject=new DateTime();
                            $todayDateOnly=$dateObject->format('Y-m-d');
                            //echo"todayDate=".$todayDateOnly."<br>";
                            $exactFilePath=$currentFilePath."/".$vehicle_imei.".txt";
                            //echo "exactFilePath=".$exactFilePath."<br>";
                            
                            $todayDataLog=hasImeiLogged($o_cassandra, $vehicle_imei, $todayDateOnly);
                            if($todayDataLog!='')
                            {
                                //echo "in if";
                                touch($currentFilePath."/".$vehicle_imei.".txt");
                                $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="1"; 
                            }
                            else
                            {
                                //echo "in else 1<br>";
                                $AccountInfo -> DeviceRunningStatus[$AccountInfo -> VehicleCnt]="0"; 
                            }  
                            
                            //$color="gray";
                            $color= $vcolor3;      					  
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                        }
                    }
                }
            }
        }
        if($veh_flag==0)
        {
        echo'<tr>
                <td colspan="2">
                    <font color="'.$vcolor1.'">
                        '.$account_name.'
                    </font> 
                </td>
            </tr>';
            $grn_cnt=sizeof(@$vehicle_name_arr[$vcolor2]);
            //echo "sixe_of_green_vehicle=".$grn_cnt."<br>";
            $gry_cnt=sizeof(@$vehicle_name_arr[$vcolor3]);
            // echo "size_of_gray_vehicle=".$gry_cnt."<br>";	  
            active_inactive_count($grn_cnt,$gry_cnt);
            //echo "<br>color:".$color;  
            $color=@$vcolor2;
            common_display_vehicle(@$vehicle_name_arr[$color],@$imei_arr[$color],@$color); 
            $color=@$vcolor3; 
            common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
        }
        $ChildCount=$AccountNode->ChildCnt;
        for($i=0;$i<$ChildCount;$i++)   /////////////this is for show child vehicle only ///////////
        {    
            show_all_vehicle($AccountNode->child[$i],$account_id_local,$category1);
        } 
    }
	
    function get_group($AccountNode,$div_option_values,$category1)
    {
        global $vehicleid;
        global $vehicle_cnt;  

        $groupid =explode(",",$div_option_values);
        $sizeofgroup=sizeof($groupid);
        $type="group";
        for($j=0;$j<$sizeofgroup;$j++)
        {
            $group_name =get_group_name($AccountNode,$groupid[$j]);
            $vehicle_cnt=0;
            echo'<tr> 
                    <td colspan="2"><font color="'.$vcolor1.'">
                        <b>&nbsp;&nbsp;'.$group_name.'('.$groupid[$j].')'.'</b></font>
                    </td>
                </tr>';
            $green_cnt1=0;  
            $gray_cnt1=0;
            print_count($AccountNode,$groupid[$j],$category1,$green_cnt1,$gray_cnt1,$type);
            $grn_cnt_grp=$green_cnt1;
            $gry_cnt_grp =$gray_cnt1;
            //echo "green=".$grn_cnt_grp."gray=".$gry_cnt_grp."<br>"; 
            active_inactive_count($grn_cnt_grp,$gry_cnt_grp);
            $vehicle_cnt=0;
            print_group_vehicle($AccountNode,$groupid[$j],$category1);
        }
    }
    function print_count($AccountNode,$cmd,$category1,&$green_cnt1,&$gray_cnt1,$type)
    {
        //echo "in print group<br>";
        global $vehicleid;
        global $vehicle_cnt;
        global $today_date2;
       // global $o_cassandra;
        //var_dump($o_cassandra);
        //global $logDate;
    
    
        if($type=="group")
        {
            $cmd_local=$AccountNode->data->AccountGroupID;
        }
        else if($type=="user")
        {
            $cmd_local=$AccountNode->data->AccountID;
        } 

   	
        if($cmd_local==$cmd)
        { 		  
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {
                if($AccountNode->data->VehicleCategory[$j]==$category1)
                { 			  
                    $vehicle_id = $AccountNode->data->VehicleID[$j]; 			
                    $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                    if($vehicle_id!=null)
                    {
                        for($i=0;$i<$vehicle_cnt;$i++)
                        {
                            if($vehicleid[$i]==$vehicle_id)
                            {
                                break;
                            }
                        }			
                        if($i>=$vehicle_cnt)
                        {
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            //$logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$xml_current = "../../../xml_vts/xml_data/".$today_date2."/".$vehicle_imei.".xml";
                            //if (file_exists($xml_current))
                            if($AccountNode->data->DeviceRunningStatus[$j]=="1")
                            {
                                $green_cnt1++; 
                                //$green_cnt1=$green_cnt;     		
                            }
                            else
                            {
                                $gray_cnt1++; 
                               // $gray_cnt1=$gray_cnt;    				
                            } 										
                        }
                    }
                }
            }      
        }
        if($type=="group")   ///////only for group vehicle cnt 
        { 
            if($cmd_local!="")
            {
            }
            else
            {    
    		$ChildCount=$AccountNode->ChildCnt;
    		for($i=0;$i<$ChildCount;$i++)
    		{ 
                    print_count($AccountNode->child[$i],$cmd,$category1,$green_cnt1,$gray_cnt1,$type);
    		}
            }
        }
        else
        {
            $ChildCount=$AccountNode->ChildCnt;
            for($i=0;$i<$ChildCount;$i++)
            { 
                print_count($AccountNode->child[$i],$cmd,$category1,$green_cnt1,$gray_cnt1,$type);
            }
        }
    }
    function get_group_name($AccountNode,$groupid)
    {
        if(($AccountNode->data->AccountGroupID!=null) && ($AccountNode->data->AccountGroupID==$groupid))
        {
            return $AccountNode->data->AccountGroupName;
        }
        else
        {
            $ChildCount=$AccountNode->ChildCnt;			
            for($i=0;$i<$ChildCount;$i++)
            { 
                $tmpGroupName = get_group_name($AccountNode->child[$i],$groupid);
                if($tmpGroupName!=null)
                {
                    return $tmpGroupName;
                }
            }
            return null;	
        }
    }
	
    function print_group_vehicle($AccountNode,$groupid,$category1)
    {
        //echo "in group vehicle<br>";
        global $vehicleid;
        global $vehicle_cnt;
        global $today_date2;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3; 
        global $DbConnection;
        //global $o_cassandra;
        //global $logDate;
         
        $vehicle_name_arr=array();
        $imei_arr=array();
        $vehicle_color=array(); 
        //echo "groupidlocal=".$AccountNode->data->AccountGroupID."group_id=".$groupid."<br>";
  	
        if($AccountNode->data->AccountGroupID==$groupid)
        { 		  
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {
                //echo "category=".$category1."local=".$AccountNode->data->VehicleCategory[$j]."<br>";
                if($AccountNode->data->VehicleCategory[$j]==$category1)
                { 			  
                    $vehicle_id = $AccountNode->data->VehicleID[$j];
                    $vehicle_name = $AccountNode->data->VehicleName[$j];
                    $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                    $iovalueandtypearr = $AccountNode->data->DeviceIOTypeValue[$j];
                    $tmp_iotype_str="";
                    if(count($iovalueandtypearr)>0)
                    {
                        $tmp_iotype_str="*".$iovalueandtypearr[$vehicle_imei];
                    }
                    else
                    {
                        $tmp_iotype_str="*tmp_str";
                    }
				
                    if($vehicle_id!=null)
                    {
                        for($i=0;$i<$vehicle_cnt;$i++)
                        {
                            if($vehicleid[$i]==$vehicle_id)
                            {
                                break;
                            }
                        }			
                        if($i>=$vehicle_cnt)
                        {
                            //echo "in if<br>";
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            if($AccountNode->data->DeviceRunningStatus[$j]=="1")
                            {
                                // echo "in if";
                                $color = $vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            else
                            {
                                // echo "in else";
                                $color = $vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);					
                        }
                    }
                }
            }      
        }  
            // echo "sizeofgreen=".$vehicle_name_arr[$color]."sizeofgray=".$vehicle_name_arr[$color]."<br>";
         if($AccountNode->data->AccountGroupID!="")
         {   
            $color=$vcolor2;
            common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
            $color=$vcolor3; 
            common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);  		 	
         }
  	else
  	{
            $ChildCount=$AccountNode->ChildCnt; 
            for($i=0;$i<$ChildCount;$i++)
            { 
                print_group_vehicle($AccountNode->child[$i],$groupid,$category1);
            }
        }
    }	
		
    function get_user($AccountNode,$div_option_values,$category1)
    {
        global $vehicleid;
        global $vehicle_cnt;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;      

        $userid =explode(",",$div_option_values);		
        $sizeofuserid=sizeof($userid);
        $type="user";
        for($j=0;$j<$sizeofuserid;$j++)
        {
            $vehicle_cnt=0;
            $user_name =get_user_name($AccountNode,$userid[$j]);
            echo'<tr> 
                    <td colspan="2">
                        <font color="'.$vcolor1.'">
                            <b>&nbsp;&nbsp;'.$user_name.'</b>
                        </font>
                    </td>
                </tr>';
                $green_cnt1=0; 
                $gray_cnt1=0;
                print_count($AccountNode,$userid[$j],$category1,$green_cnt1,$gray_cnt1,$type);
                $grn_cnt_grp=$green_cnt1;
                $gry_cnt_grp =$gray_cnt1;
            //echo "green=".$grn_cnt_grp."gray=".$gry_cnt_grp."<br>"; 
                active_inactive_count($grn_cnt_grp,$gry_cnt_grp);
                $vehicle_cnt=0;
                print_user_vehicle($AccountNode,$userid[$j],$category1);
            /*echo'<tr> 
                        <td colspan="2"><b>------------------------------</b></td>
                  </tr>';*/
        }
    }
	
    function get_user_name($AccountNode,$userid)
    {
        if(($AccountNode->data->AccountID!=null) && ($AccountNode->data->AccountID==$userid))
        {
            return $AccountNode->data->AccountName;
        }
        else
        {
            $ChildCount=$AccountNode->ChildCnt;
            for($i=0;$i<$ChildCount;$i++)
            {
                $tmpUserName = get_user_name($AccountNode->child[$i],$userid);
                if($tmpUserName!=null)
                {
                    return $tmpUserName;
                }
            }
                return null;	
        }
    }
	
    function print_user_vehicle($AccountNode,$userid,$category1)
    {
        global $vehicleid; 
        global $vehicle_cnt;
        global $today_date2;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $DbConnection;
       
		
        $vehicle_name_arr=array();
        $imei_arr=array();
        $vehicle_color=array();
        $veh_flag=0;
        if($AccountNode->data->AccountID==$userid)
        {
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {
                if($AccountNode->data->VehicleCategory[$j]==$category1)
                {
                    $veh_flag=1;
                    $vehicle_id = $AccountNode->data->VehicleID[$j];
                    $vehicle_name = $AccountNode->data->VehicleName[$j];
                    $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                    $iovalueandtypearr = $AccountNode->data->DeviceIOTypeValue[$j];
                    $tmp_iotype_str="";
                    if(count($iovalueandtypearr)>0)
                    {
                        $tmp_iotype_str="*".$iovalueandtypearr[$vehicle_imei];
                    }
                    else
                    {
                        $tmp_iotype_str="*tmp_str";
                    }
                    if($vehicle_id!=null)
                    {
                        for($i=0;$i<$vehicle_cnt;$i++)
                        {
                            if($vehicleid[$i]==$vehicle_id)
                            {
                                break;
                            }
                        }			
                        if($i>=$vehicle_cnt)
                        {
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            if($AccountNode->data->DeviceRunningStatus[$j]=="1")
                            {
                                $color=$vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            else
                            {
                                $color=$vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
							//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);
                        }
                    }
                }
            }
        }
        $color=$vcolor2;
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
        $color=$vcolor3; 
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);  
    
        $ChildCount=$AccountNode->ChildCnt;
        for($i=0;$i<$ChildCount;$i++)
        { 
            print_user_vehicle($AccountNode->child[$i],$userid,$category1);
        }
    }

    /*function get_user_type($AccountNode,$div_option_values)
    {
        global $vehicleid;
        global $vehicle_cnt; 
        $user_type =explode(",",$div_option_values);
        $sizeofusertype=sizeof($user_type);
        for($j=0;$j<$sizeofusertype;$j++)
        {
            $vehicle_cnt=0;
            echo'<tr> 
                    <td colspan="2"><font color="red"><b>&nbsp;&nbsp;'.$user_type[$j].'</b></font></td>
                </tr>';
            print_user_type_vehicle($AccountNode,$user_type[$j]);
            echo'<tr> 
                    <td colspan="2"><b>------------------------------</b></td>
            </tr>';
        }
    }	
		
    function print_user_type_vehicle($AccountNode,$usertype)
    {
        global $vehicleid;
        global $vehicle_cnt;
        if($AccountNode->data->AccountType==$usertype)
        {
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {			    
                $vehicle_id = $AccountNode->data->VehicleID[$j];
                $vehicle_name = $AccountNode->data->VehicleName[$j];
                $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                if($vehicle_id!=null)
                {
                    for($i=0;$i<$vehicle_cnt;$i++)
                    {
                        if($vehicleid[$i]==$vehicle_id)
                        {
                            break;
                        }
                    }			
                    if($i>=$vehicle_cnt)
                    {
                        $vehicleid[$vehicle_cnt]=$vehicle_id;
                        $vehicle_cnt++;
                        common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);
                    }
                }
            }
        }
        $ChildCount=$AccountNode->ChildCnt;
        for($i=0;$i<$ChildCount;$i++)
        { 
            print_user_type_vehicle($AccountNode->child[$i],$usertype);
        }
    }*/

    function get_vehicle_tag($AccountNode,$div_option_values,$category1)
    {
        global $vehicleid;
        global $vehicle_cnt; 
        $vehicle_tag =explode(",",$div_option_values);
        $sizeofvehicletag=sizeof($vehicle_tag);

        for($j=0;$j<$sizeofvehicletag;$j++)
        {
            $vehicle_cnt=0;
            echo'<tr> 
                    <td colspan="2">
                        <font color="red">
                            <b>&nbsp;&nbsp;'.$vehicle_tag[$j].'</b>
                        </font>
                    </td>
                </tr>';
            print_vehicle_tag($AccountNode,$vehicle_tag[$j],$category1);
        }
    }		
	
    function print_vehicle_tag($AccountNode,$vehicletag,$category1)
    {
        global $vehicleid;		
        global $vehicle_cnt;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;   
        global $DbConnection;
    
     
        $vehicle_name_arr=array();
        $imei_arr=array();
        $vehicle_color=array();
        global $today_date2;	
        $veh_flag=0;
        for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
        {
            $vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
            if($vehicle_tag_local==$vehicletag)
            {
                if($AccountNode->data->VehicleCategory[$j]==$category1)
                {
                    $veh_flag=1;
                    $vehicle_id = $AccountNode->data->VehicleID[$j];
                    $vehicle_name = $AccountNode->data->VehicleName[$j];
                    $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                    $iovalueandtypearr = $AccountNode->data->DeviceIOTypeValue[$j];
                    $tmp_iotype_str="";
                    if(count($iovalueandtypearr)>0)
                    {
                        $tmp_iotype_str="*".$iovalueandtypearr[$vehicle_imei];
                    }
                    else
                    {
                        $tmp_iotype_str="*tmp_str";
                    }

                    if($vehicle_id!=null)
                    {
                        for($i=0;$i<$vehicle_cnt;$i++)
                        {
                            if($vehicleid[$i]==$vehicle_id)
                            {
                                break;
                            }
                        }			
                        if($i>=$vehicle_cnt)
                        {
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            if($AccountNode->data->DeviceRunningStatus[$j]=="1")
                            {
                                $color=$vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            else
                            {
                                $color=$vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);
                        }
                    }
                }
            }
        }
        if($vehicle_tag_local!="")  ///////cause root include all child vehicle show for loop running once here for every vehicle tag
        {
            if($veh_flag==1)
            {
                $grn_cnt=sizeof($vehicle_name_arr[$vcolor2]);
                $gry_cnt=sizeof($vehicle_name_arr[$vcolor3]);	  
                active_inactive_count($grn_cnt,$gry_cnt);
                $color=$vcolor2;
                common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
                $color=$vcolor3; 
                common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
            } 
        }
        else
        {
            $ChildCount=$AccountNode->ChildCnt;
            for($i=0;$i<$ChildCount;$i++)
            { 
                print_vehicle_tag($AccountNode->child[$i],$vehicletag,$category1);
            }
  	}
    }
	
    function get_vehicle_type($AccountNode,$div_option_values,$category1)
    {
        global $vehicleid;
        global $vehicle_cnt;
        $vehicle_type =explode(",",$div_option_values);
        $sizeofvehicletype=sizeof($vehicle_type);

        for($j=0;$j<$sizeofvehicletype;$j++)
        {
            $vehicle_cnt=0;
            echo'<tr> 
                    <td colspan="2">
                        <font color="red">
                            <b>&nbsp;&nbsp;'.$vehicle_type[$j].'</b>
                        </font>
                    </td>
                </tr>';
            print_vehicle_type($AccountNode,$vehicle_type[$j],$category1);
        }
    }
	
    function print_vehicle_type($AccountNode,$vehicletype,$category1)
    {
        global $vehicleid;
        global $vehicle_cnt; 
        global $today_date2;
        global $vcolor2;
        global $vcolor3;
        global $DbConnection;      
		
        $vehicle_name_arr=array();
        $imei_arr=array();
        $vehicle_color=array();
        $veh_flag=0;
        for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
        {
            $vehicle_type_local=$AccountNode->data->VehicleType[$j];
            if($vehicle_type_local==$vehicletype)
            {
                if($AccountNode->data->VehicleCategory[$j]==$category1)
                {
                    $veh_flag=1;
                    $vehicle_id = $AccountNode->data->VehicleID[$j];
                    $vehicle_name = $AccountNode->data->VehicleName[$j];
                    $vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
                    $iovalueandtypearr = $AccountNode->data->DeviceIOTypeValue[$j];
                    $tmp_iotype_str="";
                    if(count($iovalueandtypearr)>0)
                    {
                        $tmp_iotype_str="*".$iovalueandtypearr[$vehicle_imei];
                    }
                    else
                    {
                        $tmp_iotype_str="*tmp_str";
                    }
                    if($vehicle_id!=null)
                    {
                        for($i=0;$i<$vehicle_cnt;$i++)
                        {
                            if($vehicleid[$i]==$vehicle_id)
                            {
                                break;
                            }
                        }			
                        if($i>=$vehicle_cnt)
                        {
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                           if($AccountNode->data->DeviceRunningStatus[$j]=="1")
                            {
                                $color=$vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            else
                            {
                                $color=$vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei.$tmp_iotype_str."*".$vehicle_name;
                            }
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);
                        }
                    }
                }
            }
        }
        if($vehicle_type_local!="")
        {
            if($veh_flag==1)
            {
                $grn_cnt=sizeof($vehicle_name_arr[$vcolor2]);
                $gry_cnt=sizeof($vehicle_name_arr[$vcolor3]);	  
                active_inactive_count($grn_cnt,$gry_cnt);       
                $color=$vcolor2;
                common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
                $color=$vcolor3; 
                common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
            } 
        }
        else
        {
            $ChildCount=$AccountNode->ChildCnt;
            for($i=0;$i<$ChildCount;$i++)
            { 
                print_vehicle_type($AccountNode->child[$i],$vehicletype,$category1);
            }
  	}
    }
	
    function get_vehicle($AccountNode,$div_option_values,$category1)
    {
        global $vehicle_cnt;
        $vehicle =explode(",",$div_option_values);		
        $sizeofvehicle=sizeof($vehicle);

        for($j=0;$j<$sizeofvehicle;$j++)
        {
            $vehicle_cnt=0;
            //echo"1:".$vehicle[$j]."<br>";
            print_vehicle($AccountNode,$vehicle[$j],$category1);			
        }
    }		

    function print_vehicle($AccountNode,$vehicle,$category1)
    {
        //global $user_name1;
        //global $cnt;
        global $vehicleid;
        global $vehicle_cnt; 

        for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
        {	
            //echo"1:".$AccountNode->data->VehicleID[$j]."<br>"."2:".$vehicle."<br>";
            if($AccountNode->data->VehicleID[$j]==$vehicle)
            {
                if($AccountNode->data->VehicleCategory[$j]==$category1)
                {
                    $vehicle_id = $AccountNode->data->VehicleID[$j];
                    $vehicle_name = $AccountNode->data->VehicleName[$j];
                    $vehicle_imei_no = $AccountNode->data->DeviceIMEINo[$j];
					
                    if($vehicle_id!=null)
                    {					
                        for($i=0;$i<$vehicle_cnt;$i++)
                        {
                            if($vehicleid[$i]==$vehicle_id)
                            {
                                break;
                            }
                        }			
                        if($i>=$vehicle_cnt)
                        {
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);
                        }
                    }
                }
            }
        }
        $ChildCount=$AccountNode->ChildCnt;
        for($i=0;$i<$ChildCount;$i++)
        { 
            print_vehicle($AccountNode->child[$i],$vehicle,$category1);
        }
    }
?>