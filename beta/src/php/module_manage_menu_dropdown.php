<style>
.marginBottom-0 {margin-bottom:0;}
.dropdown-submenu{position:relative;}
.dropdown-submenu>.dropdown-menu{top:0;left:100%;margin-top:-6px;margin-left:-1px;-webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;}
.dropdown-submenu>a:after{display:block;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 0 5px 5px;border-left-color:#cccccc;margin-top:5px;margin-right:-10px;}
.dropdown-submenu:hover>a:after{border-left-color:#555;}
.dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px;}
.dropdown-submenu:hover .dropdown-menu {
		display: block;
	}
 .dropdown-submenu .dropdown-menu {
		display: none;
	}
</style>
<script>
    (function($){
	$(document).ready(function(){
		$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
			event.preventDefault(); 
			event.stopPropagation(); 
			$(this).parent().siblings().removeClass('open');
			$(this).parent().toggleClass('open');
		});
	});
    })(jQuery);
</script>
<?php

$flag_sector = 0;
for($k=0;$k<$size_feature_session;$k++)
{
	
	if($feature_name_session[$k] == "sector")
	{
		$flag_sector = 1;
	}

  if($feature_name_session[$k] == "station")
	{
		$flag_station = 1;
	}
	if($feature_name_session[$k] == "substation")
	{
		$flag_substation = 1;
	}
	if($feature_name_session[$k] == "invoice")
	{
		$flag_invoice = 1;
	}	
	if($feature_name_session[$k] == "raw_milk")
	{
		$flag_raw_milk = 1;
	}
        if($user_type == "proc_admin")
	{
		$flag_proc_admin = 1;
	}
	if($feature_name_session[$k] == "hindalco_invoice")
	{
		$flag_hindalco_invoice = 1;
	}
	if($feature_name_session[$k] == "visit_track")
	{
		$flag_visit = 1;
	}
	
	if($feature_name_session[$k] == "vehicle_trip")
	{
		$flag_vtrip = 1;
	}	
  
	if($feature_name_session[$k] == "load_cell")
	{
		$flag_load_cell = 1;
	}
	if($feature_name_session[$k] == "consignment")
	{
		$consignment_info = 1;
	}
	if($feature_name_session[$k] =="upl_flag")
	{
		$flag_upl = 1;
	}
}
$nbsp="&nbsp;&nbsp;";
$contetnbsp="&nbsp;";
        echo'  
            <li><a href="manage.htm" >Main-Manage</a></li>
            <li class="divider"></li>
            <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Accounts</a>
                    <ul class="dropdown-menu" style="width:200px">
                        ';
                        if($flag_proc_admin!=1)
                        {
                            echo'
                            <li><a href="javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_group.htm\');"><i class=\'fa fa-users fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Group</a></li>
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_account.htm\');><i class=\'fa fa-user fa-1x\' aria-hidden=\'true\'></i>'.$nbsp.'Account</a></li>
                            ';
                        }
                        if($account_id=='1')
			{
                            echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_account_thirdparty.htm\');><i class=\'fa fa-user-plus fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'ThirdParty Account</a></li>
                           ';
                        }
                        echo'   
                    </ul>
            </li>
        ';
        echo'<li class="divider"></li>';
        echo'                       
            <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Device & Vehicles</a>
                    <ul class="dropdown-menu" style="width:200px">
                    ';
                    if($flag_proc_admin!=1)
                    {
                        echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_vehicle_thirdparty.htm\');><i class=\'fa fa-user-plus fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'ThirdParty Vehicle</a></li>                        
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_vehicle.htm\');><i class=\'fa fa-truck fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.''.$report_type.'</a></li>
                        ';
                    }
                    if($report_type=="Person")
                    {
                    echo'
                        <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_device.htm\');><i class=\'fa fa-tablet fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Mobile</a></li>
                     ';
                    }
                    else
                    {
                       if($flag_proc_admin!=1)
                       {                    
                        echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_device.htm\'); ><i class=\'fa fa-anchor fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Device</a></li>                    
                        ';
                       }
                    }
                    echo'
                    
                    

                    </ul>
            </li>
        ';
        echo'<li class="divider"></li>';
        echo'                       
            <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Services</a>
                    <ul class="dropdown-menu" style="width:200px">  
                        ';
                        if($consignment_info==1)
                        {
                        echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_consignment_info.htm\');><i class=\'fa fa-shopping-cart fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Consignment Info</a></li>
                        ';
                        }
                        if($report_type!="Person" && $flag_proc_admin!=1)
                        {
                        echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_calibration.htm\');><i class=\'fa fa-tachometer  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Calibration</a></li>
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_escalation.htm\');><i class=\'fa fa-hand-o-right  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Escalation</a></li> 
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_io_assignment.htm\');><i class=\'fa fa-check-square-o  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'IO Assignment</a></li>
                            ';
                        }
                        if($account_id==715)
			{
                        echo'                                               
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_klp_input.htm\');><i class=\'fa fa-globe fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'KLP INPUT</a></li>
                        ';
                        }
                        echo'
                    </ul>
            </li>
        ';
        echo'<li class="divider"></li>';
        echo'                       
           <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Geographical</a>
                   <ul class="dropdown-menu" style="width:200px">
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_landmark.htm\');><i class=\'fa fa-map-pin fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Landmark</a></li>
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_trip.htm\');><i class=\'fa fa-globe fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Vehicle Trip</a></li>
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_geofence.htm\');><i class=\'fa fa-connectdevelop fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Geofence</a></li>
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_polyline.htm\');><i class=\'fa fa-code-fork fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Polyline</a></li>                       
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_polyline_history.htm\');><i class=\'fa fa-random  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Route from History</a></li>
                       
                   </ul>
           </li>
       ';
        echo'<li class="divider"></li>';
        echo'                       
           <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Invoice & Trip</a>
                   <ul class="dropdown-menu" style="width:250px">
                   ';
                   if($flag_invoice)
                   {
                       echo'                   
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_invoice.htm\');><i class=\'fa fa-file-text-o fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Invoice</a></li>
                       ';
                   }
                   if($flag_raw_milk)
                   {
                       echo'                   
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_invoice_raw_milk_material_prev.htm\',\'manage_show_file_jquery\');><i class=\'fa fa-flask fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Raw Milk Material</a></li>
                       <li><a href=\'src/php/manage_invoice_milk_add_upload.htm\' target=\'_blank\'><i class=\'fa fa-plus-square fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Create & Upload Invoice</a></li>
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_edit_invoice_raw_milk_admin_prev.htm\',\'manage_show_file_jquery\');><i class=\'fa fa-file-text fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Raw Milk Invoice</a></li>                       
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_account_plant.htm\');><i class=\'fa fa-industry fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Plant Account Assignment</a></li>                       
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_default_chilling_plant.htm\');><i class=\'fa fa-link fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Transporter Default Chilling Plant</a></li>
                       ';
                   }
                   if($flag_hindalco_invoice)
                   {
                   echo'
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_edit_hindalco_invoice_admin_prev.htm\');><i class=\'fa fa-flask fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Hindalco Invoice</a></li>
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_substation_vehicle.htm\');><i class=\'fa fa-stop fa-1x\' aria-hidden=\'true\'></i>Transporter'.$contetnbsp.''.$report_type.' Assignment</a></li>
                        ';
                   }
                   if($flag_vtrip)
                   {
                   echo'
                       <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_vtrip.htm\');><i class=\'fa fa-globe fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Vehicle Trip</a></li>                       
                       ';
                   }
                   if($flag_station)
                   {
                   echo'
                   
                        <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_station.htm\');><i class=\'fa fa-stop fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Station</a></li>
                        ';
                        if($flag_substation)
                        {
                            echo'
                               <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_route.htm\');><i class=\'fa fa-random fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Route Assignment</a></li>
                            ';
                            if($account_id=="231" || $account_id=="1115" || $account_id=="1100")
                            {
                                echo'
                                <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_secondary_vehicle.htm\');><i class=\'fa fa-paperclip fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Secondary Vehicle</a></li>
                                ';
                            }
                        }
                        if($flag_raw_milk || $flag_substation)
                        {
                            echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_substation_vehicle.htm\');><i class=\'fa fa-stop-circle fa-1x\' aria-hidden=\'true\'></i>Substation'.$contetnbsp.''.$report_type.' Assignment</a></li>
                            ';
                        }
                        if($account_id=="1")
                        {
                            echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_upload_file_format.htm\');><i class=\'fa fa-upload fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Upload File</a></li>                       
                            ';
                        }
                        if($account_id=="696")
                        {
                            echo'
                            <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_consignment.htm\');><i class=\'fa fa-shopping-basket  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Consignment</a></li>
                        ';
                        }
                   }
                   echo'
                   </ul>
           </li>
       ';
       echo'<li class="divider"></li>';
       echo' 
           ';
           if($flag_upl)
           {
               echo'
                <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_schedule_upl.htm\');><i class=\'fa fa-calendar-plus-o  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'ScheduleUPL</a></li>
           ';
           }
           if($flag_visit)
           {
               echo'
                <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_schedule.htm\');><i class=\'fa fa-calendar-check-o  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Schedule</a></li>
               ';
           }
           if($flag_sector)
           {
               echo'
               <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_route.htm\');><i class=\'fa fa-road  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Route</a></li>
               ';
           }
           if($flag_load_cell)
           {
               echo'
               <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_load_cell.htm\');><i class=\'fa fa-battery-full  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Load Cell</a></li> 
               ';
           }
           if($report_type=="Person")
           {
               echo'
                <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_visit.htm\');><i class=\'fa fa-font-awesome  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Schedule Visit</a></li>
               ';
           }
           if($mining_user_type==1)
           {
               echo'
               <li><a href=javascript:manage_show_file_switcher(\'manage.htm\',\'src/php/manage_milestone.htm\');><i class=\'fa fa-fighter-jet  fa-1x\' aria-hidden=\'true\'></i>'.$contetnbsp.'Miles Stone</a></li>
              ';
           }
       
?>
