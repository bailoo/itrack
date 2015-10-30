<?php
$dataCustomerArr=getCustomerPlantChillingRecord($account_id,0,$DbConnection);
$size=0;
$customerArr=array();
foreach($dataCustomerArr as $dCValue)
{
    $station[$size]= preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dCValue['station_name']);
    $customer[$size]=$dCValue['customer_no'];
    $coord = $dCValue['station_coord'];
    $type[$size] = $dCValue['type'];  	
    $coord1 = explode(',',$coord);
    $lat[$size]= substr(trim($coord1[0]),0,-1);
    $lng[$size]= substr(trim($coord1[1]),0,-1);
    $customerArrNew[trim($customer[$size])]=trim($lat[$size])."^".trim($lng[$size])."^".$station[$size]."^".$type[$size];
    $size++;
}

//print_r($customerArrNew);
$_SESSION['uniqueCustomerArrNew'] = json_encode($customerArrNew);
$query = "";
$result="";
$row="";

$dataPlantArr=getCustomerPlantChillingRecord($account_id,1,$DbConnection);
$size=0;
//$plantArr=array();
foreach($dataPlantArr as $dPValue)
{
    $station[$size]= preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dPValue['station_name']);
    $customer[$size]=$dPValue['customer_no'];
    $coord = $dPValue['station_coord'];
    $type[$size] = $dPValue['type'];  	
    $coord1 = explode(',',$coord);
    $lat[$size]= substr(trim($coord1[0]),0,-1);
    $lng[$size]= substr(trim($coord1[1]),0,-1);
    $plantArrNew[trim($customer[$size])]=trim($lat[$size])."^".trim($lng[$size])."^".$station[$size]."^".$type[$size];
    $size++;
}
$_SESSION['uniquePlantArrNew'] = json_encode($plantArrNew);
$query = "";
$result="";
$row="";	

//echo $query."<br>";

$dataChillingPlantArr=getCustomerPlantChillingRecord($account_id,2,$DbConnection);
$size=0;
//$plantArr=array();
foreach($dataChillingPlantArr as $dCPValue)
{
    $station[$size]= preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dCPValue['station_name']);
    $customer[$size]=$dCPValue['customer_no'];
    $coord = $dCPValue['station_coord'];
    $type[$size] = $dCPValue['type'];  	
    $coord1 = explode(',',$coord);
    $lat[$size]= substr(trim($coord1[0]),0,-1);
    $lng[$size]= substr(trim($coord1[1]),0,-1);  
    $chillingArrNew[trim($row->customer_no)]=trim($lat[$size])."^".trim($lng[$size])."^".$station[$size]."^".$type[$size];
    $size++;
}
$_SESSION['uniqueChillingArrNew'] = json_encode($plantArrNew);	
for($k=0;$k<@$size_feature;$k++)
{
    //$feature_id_session[$k];
    if($feature_name_session_login[$k] == "station")
    {
        $flag_station = 1;
        break;
    }
    //echo "<br>feature_name=".$feature_name_session[$k];
}	
//if($account_id==231)
//echo "flag_station=".$flag_substation."<br>";
if(@$flag_station==1)
{
    $fileEMPath="src/php/gps_report/".$account_id."/master";
    $filePath="src/php/gps_report/".$account_id."/master";
    foreach(glob($fileEMPath.'/*.*') as $fileEM) 
    {
        $fileNameEM=explode("/",$fileEM);
        $strPosition = strpos($fileNameEM[5], '#7');
        //echo "efm=".$eveningFileName."<br>";
        if($strPosition!="")
        {
            $eveningFileName=$fileEM;
        }			
        $strPosition = strpos($fileNameEM[5], '#8');
        //echo "mfm=".$morningFileName."<br>";
        if($strPosition!="")
        {
            $morningFileName=$fileEM;
        }
        $strPosition = strpos($fileNameEM[5], '#6');
        //echo "mfm=".$morningFileName."<br>";
        if($strPosition!="")
        {
            $transporterName=$fileEM;
        }
    }
    if(file_exists($eveningFileName))
    {		
        $file = fopen($eveningFileName,"r");
        $routeArrEvening=array();
        while(! feof($file))
        {
            $csvEveningArr=fgetcsv($file);
            //echo "customer=".$csvEveningArr[0]."routeNo".$csvEveningArr[3]."<br>";
            //echo "customerNo=".$customerArr[74521]."<br>";
            if(trim($csvEveningArr[0])=="12")
            {
                //echo "<br>present in file";
            }				
            $trimCustomerNo=trim($csvEveningArr[0]);
            if($customerArrNew[$trimCustomerNo]=="12")
            {
                //echo "<br>present in file customer";
            }
            //echo "val=".$dsfdasfds."<br>";
            if($customerArrNew[$trimCustomerNo]!="")
            {
                //echo "customerNo=".$customerArr[$trimCustomerNo]."<br>";
                $routeArr=explode("/",$csvEveningArr[3]);
                $routeArrSize=sizeof($routeArr);
                for($i=0;$i<$routeArrSize;$i++)
                {                    
                    if($customerArrNew[$trimCustomerNo]!="")
                    {
                        $customerDetail=explode("^",$customerArrNew[$trimCustomerNo]);
                        $routeArrEveningNew[]=array(
                                            'routeNo'=>$routeArr[$i],
                                            'lat'=>$customerDetail[0],
                                            'lng'=>$customerDetail[1],
                                            'stationName'=>$customerDetail[2],
                                            'customerNo'=>$trimCustomerNo,
                                            'type'=>'2'
                                        );
                    }
                }
            }		
        }			
        $_SESSION['uniqueRouteArrEveningNew'] = json_encode($routeArrEveningNew);      
    }
    if(file_exists($morningFileName))
    {
        //echo "in file";		
        $file = fopen($morningFileName,"r");
        $routeArrMorning=array();
        while(!feof($file))
        {
            $csvMorningArr=fgetcsv($file);                       
            $trimCustomerNo=trim($csvMorningArr[0]);
            //echo "cc=".$trimCustomerNo."<br>";
            /*if(trim($csvMorningArr[0])=="12")  // for debug
            {
                echo "<br>present in file Morning";
            }*/
            if($customerArrNew[$trimCustomerNo]!="")
            {
                $routeArr=explode("/",$csvMorningArr[3]);
                $routeArrSize=sizeof($routeArr);
                for($i=0;$i<$routeArrSize;$i++)
                {
                    if($customerArrNew[$trimCustomerNo]!="")
                    {
                        $customerDetail=explode("^",$customerArrNew[$trimCustomerNo]);
                        $routeArrMorningNew[]=array(
                                                'routeNo'=>$routeArr[$i],
                                                'lat'=>$customerDetail[0],
                                                'lng'=>$customerDetail[1],
                                                'stationName'=>$customerDetail[2],
                                                'customerNo'=>$trimCustomerNo,
                                                'type'=>'2'
                                            );
                    }               
                }
            }		
        }
        //print_r($routeArrMorningNew);
        $_SESSION['uniqueRouteArrMorningNew'] = json_encode($routeArrMorningNew);        
    }
    if(file_exists($transporterName))
    {			
        $transporterRouteArr=array();
        $file = fopen($transporterName,"r");
        while(! feof($file))
        {
            $csvTransporterArr=fgetcsv($file);
            $transporterRouteArr[$csvTransporterArr[0]]=$csvTransporterArr[1];
        }
        //print_r($transporterRouteArr);
        $_SESSION['uniqueRouteTransporters'] = json_encode($transporterRouteArr);
    }
}

$routeMorningArr=getRouteMorning($account_id,1,$DbConnection);
//print_r($routeMorningArr);
if(count($routeMorningArr)>0)
{
    
    foreach($routeMorningArr as $rMorValue)
    {
        $explodedMRouteNo=explode("/",$rMorValue['route_name_mor']);  
        for($i=0;$i<sizeof($explodedMRouteNo);$i++)
        {            
            if($explodedMRouteNo[$i]!='')
            {
                //echo "routeNo=".$explodeeRouteNo[$i]."<br>"; 
                if (strpos($explodedMRouteNo[$i],'@') !== false) 
                {
                    $routeNo=  substr($explodedMRouteNo[$i], 1);
                    //echo "RouteNoa=".$routeNo."<br>";
                }
                else
                {
                    $routeNo=$explodedMRouteNo[$i];
                     //echo "RouteNob1=".$routeNo."<br>";
                }
                
                $routeArrMorning[$routeNo]=$routeNo; 
            }
        }
    }
    $_SESSION['uniqueRouteArrMorning'] = $routeArrMorning;
    //echo "morningRoute<br>";
    //print_r($routeArrMorning);
}


$routeEveningArr=getRouteEvening($account_id,1,$DbConnection);
if(count($routeEveningArr)>0)
{
    foreach($routeEveningArr as $rEvValue)
    {
        $explodedERouteNo=explode("/",$rEvValue['route_name_ev']);  
        for($i=0;$i<sizeof($explodedERouteNo);$i++)
        {            
            if($explodedERouteNo[$i]!='')
            {
                //echo "routeNo=".$explodeeRouteNo[$i]."<br>"; 
                if (strpos($explodedERouteNo[$i],'@') !== false) 
                {
                    $routeENo=  substr($explodedERouteNo[$i], 1);
                    //echo "RouteNoea=".$routeENo."<br>";
                }
                else
                {
                    $routeENo=$explodedERouteNo[$i];
                     //echo "RouteNobe1=".$routeENo."<br>";
                }
                
                $routeArrEvening[$routeENo]=$routeENo; 
            }
        }
       
    }
    $_SESSION['uniqueRouteArrEvening'] = $routeArrEvening;
    //echo "morningRoute<br>";
    //print_r($routeArrEvening);
}

?>
