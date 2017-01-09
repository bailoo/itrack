<?php
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php'); 	
$root=$_SESSION['root'];
include_once("util_account_detail.php");
//echo $account_id;
//echo "u=".$user_type;


$action=$_POST['action'];
if($action=='show_invoice')
{
    $type=$_POST['type'];
    $customer_infos=getDetailAllPGAD($account_id,$DbConnection);
    //print_r($customer_infos);
    if(count($customer_infos)>0)
    {
        foreach($customer_infos as $cif)
        {
           // echo $cif['plant_customer_no'];
           $plant_in.=" invoice_mdrm.plant=".$cif['plant_customer_no']." OR ";
        }
        $plant_in = substr($plant_in, 0, -3);
        //echo $plant_in;
        if($type=='tanker')
        {
            $vehicle_no=$_POST['vehicle_no'];
            //echo $vehicle_no;
            $condition="invoicestatus_alldataNoDate_vehicle";
            $orderA="1";
            $user_type_for="plant_raw_milk";
            $result=getInvoiceMDRMGate($condition,$startdate,$enddate,$plant_in,$orderA,$user_type_for,$vehicle_no,$DbConnection);
            if(count($result)>0)
            {
                //print_r($result);
                echo"
                    <div class='panel-group'>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>Please Select Lorry No</div>
                            <div class='panel-body'>
                               <table>
                                    <tr>
                                        ";
                                foreach($result as $rs)
                                {
                                    $transporter_name="";
                                    if($rs['transporter_account_id']!='')
                                    {
                                        $transporter_name=getAccountName($rs['transporter_account_id'],$DbConnection);
                                    }
                                   echo" <td><label class='radio-inline'><input type='radio' name='lorry_radio' onclick='javascript:getInvoiceGate(\"".$rs['sno']."\",\"".$rs['lorry_no']."\",\"".$rs['station_name']."\",\"".$rs['plant']."\",\"".$rs['tanker_type']."\",\"".$rs['dispatch_time']."\",\"".$rs['target_time']."\",\"".$transporter_name[0]."\",\"".$rs['driver_name']."\",\"".$rs['driver_mobile']."\",\"".$rs['vehicle_no']."\");' >".$rs['lorry_no']."</label></td>";
                                }

                                       echo" </tr>
                                 </table>
                            </div>
                        </div>

                        <div class='panel panel-primary' id='invoice_detail' style='display:none;'>
                            <div class='panel-heading'>Invoice Detail for Station/Plant : <span id='station_plant'></span> For :<span id='production_type'></span> </span></div>
                            <div class='panel-body'>
                                <table class='form-group table table-bordered' align=center>
                                  <tr>
                                    <th class='control-label'>TankerNo</th>
                                    <th class='control-label'>Dispatch Date</th>
                                    <th class='control-label'>Target Date</th>
                                    <th class='control-label'>Transporter</th>
                                    <th class='control-label'>Driver</th>
                                    <th class='control-label'>DriverMobile</th>
                                    <th class='control-label'>Gate Entry DateTime</th>
                                  </tr>
                                  <tr>
                                    <td><span id='tanker_no' class='control-label'></span></td>
                                    <td><span id='dispatch_date' class='control-label'></span></td>
                                    <td><span id='target_date' class='control-label'></span></td>
                                    <td><span id='transporter' class='control-label'></span></td>                                                            
                                    <td><span id='driver_name' class='control-label'></span></td>
                                    <td><span id='driver_mobile' class='control-label'></span></td>
                                    <td><input type=text id='gate_time' readonly class='form-control' onclick=javascript:NewCal(this.id,'yyyymmdd',true,24);></td>
                                  </tr>

                                  <tr>
                                   <td colspan=7>
                                     <input type=hidden id='invoice_sno'>
                                     <input type=hidden id='invoice_lorry'>
                                     <center><input type='button' value='Submit' class='btn btn-primary' onclick='javascript:close_plant_gate_entry();'></center>
                                   </td>
                                  </tr>
                                </table>
                            </div>
                        </div>
                    </div>  
                         ";

            }
            else
            {
                echo"No Information found";
            }
        }
        if($type=='by_pending_tanker')
        {
            $vehicle_no="";
            //echo $vehicle_no;
            $condition="invoicestatus_alldataNoDate";
            $orderA="1";
            $user_type_for="plant_raw_milk";
            $result=getInvoiceMDRMGate($condition,$startdate,$enddate,$plant_in,$orderA,$user_type_for,$vehicle_no,$DbConnection);
            if(count($result)>0)
            {
                //print_r($result);
                echo"
                    <div class='panel-group'>
                        <div class='panel panel-default'>
                            <div class='panel-heading'>Please Select Lorry No</div>
                            <div class='panel-body' style='overflow:auto;height:200px'>
                               <table class='table-hover table-condensed' align=center>
                                    <tr>
                                        ";
                                        $cnt=0;
                                        $total_size=count($result);
                                        //echo $total_size;
                                        foreach($result as $rs)
                                        {
                                            $transporter_name="";
                                            if($rs['transporter_account_id']!='')
                                            {
                                                $transporter_name=getAccountName($rs['transporter_account_id'],$DbConnection);
                                            }
                                           echo" <td><label class='radio-inline'><input type='radio' name='lorry_radio' onclick='javascript:getInvoiceGate(\"".$rs['sno']."\",\"".$rs['lorry_no']."\",\"".$rs['station_name']."\",\"".$rs['plant']."\",\"".$rs['tanker_type']."\",\"".$rs['dispatch_time']."\",\"".$rs['target_time']."\",\"".$transporter_name[0]."\",\"".$rs['driver_name']."\",\"".$rs['driver_mobile']."\",\"".$rs['vehicle_no']."\");' >".$rs['lorry_no']."(".$rs['vehicle_no'].")</label></td>";
                                           $cnt++;
                                           if((($cnt%5)==0) && ($cnt!=$total_size-2))
                                           {
                                              echo"</tr><tr>" ;
                                              $cnt=0;
                                           }
                                           else if(($cnt==$total_size-1))
                                           {
                                               echo"</tr>" ; 
                                           }
                                           
                                        }

                                       echo" 
                                 </table>
                            </div>
                        </div>

                        <div class='panel panel-primary' id='invoice_detail' style='display:none;'>
                            <div class='panel-heading'>Invoice Detail for Station/Plant : <span id='station_plant'></span> For :<span id='production_type'></span> </span></div>
                            <div class='panel-body'>
                                <table class='form-group table table-bordered' align=center>
                                  <tr>
                                    <th class='control-label'>TankerNo</th>
                                    <th class='control-label'>Dispatch Date</th>
                                    <th class='control-label'>Target Date</th>
                                    <th class='control-label'>Transporter</th>
                                    <th class='control-label'>Driver</th>
                                    <th class='control-label'>DriverMobile</th>
                                    <!--<th class='control-label'>Gate Entry DateTime</th>-->
                                  </tr>
                                  <tr>
                                    <td><span id='tanker_no' class='control-label'></span></td>
                                    <td><span id='dispatch_date' class='control-label'></span></td>
                                    <td><span id='target_date' class='control-label'></span></td>
                                    <td><span id='transporter' class='control-label'></span></td>                                                            
                                    <td><span id='driver_name' class='control-label'></span></td>
                                    <td><span id='driver_mobile' class='control-label'></span></td>
                                    <!--<td><input type=text id='gate_time' readonly class='form-control' onclick=javascript:NewCal(this.id,'yyyymmdd',true,24);></td>-->
                                  </tr>

                                  <tr>
                                   <td colspan=7>
                                     <input type=hidden id='invoice_sno'>
                                     <input type=hidden id='invoice_lorry'>
                                     <!--<center><input type='button' value='Submit' class='btn btn-primary' onclick='javascript:close_plant_gate_entry();'></center>-->
                                   </td>
                                  </tr>
                                </table>
                            </div>
                        </div>
                    </div>  
                         ";

            }
            else
            {
                echo"No Information found";
            }
        }
    }
    else
    {
        echo"Sorry No Information";
    }
}

if($action=='submit_invoice_close')
{
    $gate_time=$_POST['gate_time'];
    $sno=$_POST['sno'];   
    //echo $date;
    $result_update = updateInvoiceMdrmGateEntry($gate_time,$sno,$date,$account_id,$DbConnection);
    if($result_update)
    {
        echo "Gate Entry Date Enter Successfully";  
    }
    else
    {
        echo "Not Enter Successfully";
    }
}

?>