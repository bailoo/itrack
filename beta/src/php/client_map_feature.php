<?php
$dataCustomerArr=getCustomerPlantChillingRecord($account_id,0,$DbConnection);
$customerArr=array();
foreach($dataCustomerArr as $dCValue)
{
    $station= preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dCValue['station_name']);
    $customer=$dCValue['customer_no'];
    $coord = $dCValue['station_coord'];
    $type = $dCValue['type'];  	
    $coord1 = explode(',',$coord);
    $lat= substr(trim($coord1[0]),0,-1);
    $lng= substr(trim($coord1[1]),0,-1);
    $customerArrNew[trim($customer)]=trim($lat)."^".trim($lng)."^".$station."^".$type;
}
//print_r($customerArrNew);
$_SESSION['uniqueCustomerArrNew'] = json_encode($customerArrNew);
$dataPlantArr=getCustomerPlantChillingRecord($account_id,1,$DbConnection);
foreach($dataPlantArr as $dPValue)
{
    $station= preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dPValue['station_name']);
    $customer=$dPValue['customer_no'];
    $coord = $dPValue['station_coord'];
    $type = $dPValue['type'];  	
    $coord1 = explode(',',$coord);
    $lat= substr(trim($coord1[0]),0,-1);
    $lng= substr(trim($coord1[1]),0,-1);
    $plantArrNew[trim($customer)]=trim($lat)."^".trim($lng)."^".$station."^".$type;
}
$_SESSION['uniquePlantArrNew'] = json_encode($plantArrNew);

$dataChillingPlantArr=getCustomerPlantChillingRecord($account_id,2,$DbConnection);
foreach($dataChillingPlantArr as $dCPValue)
{
    $station= preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s','',$dCPValue['station_name']);
    $customer=$dCPValue['customer_no'];
    $coord = $dCPValue['station_coord'];
    $type = $dCPValue['type'];  	
    $coord1 = explode(',',$coord);
    $lat= substr(trim($coord1[0]),0,-1);
    $lng= substr(trim($coord1[1]),0,-1);  
    $chillingArrNew[trim($customer)]=trim($lat)."^".trim($lng)."^".$station."^".$type;
}
//print_r($chillingArrNew);
$_SESSION['uniqueChillingArrNew'] = json_encode($chillingArrNew);	
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
                    $routeArrEvening[$routeArr[$i]]=$routeArr[$i];
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
        $_SESSION['uniqueRouteArrEvening'] = $routeArrEvening;
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
                    $routeArrMorning[$routeArr[$i]]=$routeArr[$i];
                }
            }		
        }
        //print_r($routeArrMorningNew);
        $_SESSION['uniqueRouteArrMorningNew'] = json_encode($routeArrMorningNew);
        $_SESSION['uniqueRouteArrMorning'] = $routeArrMorning;
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

?>
