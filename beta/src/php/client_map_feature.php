<?php
$dataCustomerArr=getCustomerPlantChillingRecord($account_id,0,$DbConnection);
$customerArr=array();
$customerArrNew=array();
if($dataCustomerArr>0)
{
    foreach($dataCustomerArr as $dCValue)
    {
        $station= isset($dCValue['station_name'])?preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dCValue['station_name']):'';
        $customer=isset($dCValue['customer_no'])?$dCValue['customer_no']:'';
        $coord = isset($dCValue['station_coord'])?$dCValue['station_coord']:'';
        $type = isset($dCValue['type'])?$dCValue['type']:'';  	
        $coord1 = explode(',',$coord);
        $lat= isset($coord1[0])?substr(trim($coord1[0]),0,-1):'';
        $lng= isset($coord1[1])?substr(trim($coord1[1]),0,-1):'';
        $customerArrNew[trim($customer)]=trim($lat)."^".trim($lng)."^".$station."^".$type;
    }
    //print_r($customerArrNew);    
}
$_SESSION['uniqueCustomerArrNew'] = json_encode($customerArrNew);

$plantArrNew=array();
$dataPlantArr=getCustomerPlantChillingRecord($account_id,1,$DbConnection);
if(count($dataPlantArr)>0)
{
    foreach($dataPlantArr as $dPValue)
    {
        $station= isset($dPValue['station_name'])?preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dPValue['station_name']):'';
        $customer=isset($dPValue['customer_no'])?$dPValue['customer_no']:'';
        $coord = isset($dPValue['station_coord'])?$dPValue['station_coord']:'';
        $type = isset($dPValue['type'])?$dPValue['type']:'';  	
        $coord1 = explode(',',$coord);
        $lat= isset($coord1[0])?substr(trim($coord1[0]),0,-1):'';
        $lng= isset($coord1[1])?substr(trim($coord1[1]),0,-1):'';
        $plantArrNew[trim($customer)]=trim($lat)."^".trim($lng)."^".$station."^".$type;
    }   
}
$_SESSION['uniquePlantArrNew'] = json_encode($plantArrNew);

$chillingArrNew=array();
$dataChillingPlantArr=getCustomerPlantChillingRecord($account_id,2,$DbConnection);
if(count($dataChillingPlantArr)>0)
{    
    foreach($dataChillingPlantArr as $dCPValue)
    {
        $station= isset($dCPValue['station_name'])?preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dCPValue['station_name']):'';
        $customer=isset($dCPValue['customer_no'])?$dCPValue['customer_no']:'';
        $coord = isset($dCPValue['station_coord'])?$dCPValue['station_coord']:'';
        $type = isset($dCPValue['type'])?$dCPValue['type']:'';  	
        $coord1 = explode(',',$coord);
        $lat= isset($coord1[0])?substr(trim($coord1[0]),0,-1):'';
        $lng= isset($coord1[1])?substr(trim($coord1[1]),0,-1):'';  
        //echo "customerNo=".$customer."<br>";
        $chillingArrNew[trim($customer)]=trim($lat)."^".trim($lng)."^".$station."^".$type;
    }
}
$_SESSION['uniqueChillingArrNew'] = json_encode($chillingArrNew);

$flag_station=0;
for($k=0;$k<$size_feature;$k++)
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
$routeArrMorningNew=array(array());
$routeArrEveningNew=array(array());
$transporterRouteArr=array();
if($flag_station==1)
{
    $fileEMPath="src/php/gps_report/".$account_id."/master";
    $filePath="src/php/gps_report/".$account_id."/master";
    foreach(glob($fileEMPath.'/*.*') as $fileEM) 
    {
        $strPosition="";
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
    }   

    if(file_exists($morningFileName))
    {
        //echo "in file";		
        $file = fopen($morningFileName,"r");     
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
    }
}
$_SESSION['uniqueRouteArrEveningNew'] = json_encode($routeArrEveningNew);
$_SESSION['uniqueRouteArrMorningNew'] = json_encode($routeArrMorningNew);
$_SESSION['uniqueRouteTransporters'] = json_encode($transporterRouteArr);
    
$routeMorningArr=getRouteMorning($account_id,1,$DbConnection);
//print_r($routeMorningArr);
$routeArrMorning=array();
if(count($routeMorningArr)>0)
{
   
    foreach($routeMorningArr as $rMorValue)
    {
        $explodedMRouteNo=isset($rMorValue['route_name_mor'])?explode("/",$rMorValue['route_name_mor']):0;  
        if(count($explodedMRouteNo))
        {
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
    }
    //print_r($routeArrMorning);
}
$_SESSION['uniqueRouteArrMorning'] = $routeArrMorning;


$routeEveningArr=getRouteEvening($account_id,1,$DbConnection);
$routeArrEvening=array();
if(count($routeEveningArr)>0)
{
    foreach($routeEveningArr as $rEvValue)
    {
        $explodedERouteNo=isset($rEvValue['route_name_ev'])?explode("/",$rEvValue['route_name_ev']):0; 
        if(count($explodedERouteNo))
        {
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
    }
    
    //echo "morningRoute<br>";
    //print_r($routeArrEvening);
}
$_SESSION['uniqueRouteArrEvening'] = $routeArrEvening;
?>
