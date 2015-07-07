<?php

    $date1 = $_POST['start_date'];
    $date2 = $_POST['end_date'];
    $date1 = str_replace("/","-",$date1);
    $date2 = str_replace("/","-",$date2);
    $date_1 = explode(" ",$date1);
    $date_2 = explode(" ",$date2);
    $datefrom = $date_1[0];
    $dateto = $date_2[0];
    
    $date1 = $startdate;
    $date2 = $enddate;
    $datefrom = $report_date1;
    $dateto = $report_date2;

    $userInterval = 0;

    $sortBy='g';
    $firstDataFlag=0;
    $endDateTS=strtotime($date2);
    $dataCnt=0;	
    //$userInterval = "0";
    $requiredData="All";

    $parameterizeData=new parameterizeData();
    $ioFoundFlag=0;

    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";
    $parameterizeData->speed="f";

    $finalVNameArr=array();
       
    for($i=0;$i<sizeof($Vehicle);$i++)
    {     
        $j=$i;
        while($Vehicle[$j]==$Vehicle[$i])
        {
                $j++;	//J LIMIT
        }
        $dataCnt=0;
        //$vehicle_info=get_vehicle_info($root,$vserial[$i]);
        //$vehicle_detail_local=explode(",",$vehicle_info);
        //$finalVNameArr[$i]=$vehicle_detail_local[0];
        //echo "vehcileName=".$finalVNameArr[$i]." vSerial=".$vehicle_detail_local[0]."<br>";

        $LastSortedDate = getLastSortedDate($IMEI[$i],$datefrom,$dateto);
        $SortedDataObject=new data();
        $UnSortedDataObject=new data();

        //echo "vname=".$finalVNameArr[$i]."imei=".$vserial[$i]."<br>";

        if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
        {	
            //echo "in if1";
            $type="sorted";
            readFileXml($IMEI[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
        }
        else if($LastSortedDate==null) //All Unsorted data
        {
            //echo "in if2";
            $type="unSorted";
            readFileXml($IMEI[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
        }
        else //Partially Sorted data
        {
            $LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
            //echo "in else";
            $type="sorted";					
            readFileXml($IMEI[$i],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);

            $type="unSorted";
            readFileXml($IMEI[$i],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
        }

        /*echo "udt1=".$UnSortedDataObject->deviceDatetime[0]."<br>";
        echo "udt2=".$UnSortedDataObject->deviceDatetime[1]."<br>";	
        echo "udt1=".$UnSortedDataObject->speedData[0]."<br>";
        echo "udt2=".$UnSortedDataObject->speedData[1]."<br>";
        echo "<br><br>";*/

        if(count($SortedDataObject->deviceDatetime)>0)
        {
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {			
                    $finalDateTimeArr[$IMEI[$i]][]=$SortedDataObject->deviceDatetime[$obi];
                    $finalLatitudeArr[$IMEI[$i]][]=$SortedDataObject->latitudeData[$obi];
                    $finalLongitudeArr[$IMEI[$i]][]=$SortedDataObject->longitudeData[$obi];
                    $finalSpeedArr[$IMEI[$i]][]=$SortedDataObject->speedData[$obi];				
                    ///$dataCnt++;
            }
        }
        if(count($UnSortedDataObject->deviceDatetime)>0)
        {
            $sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
            //var_dump($sortObjTmp);
            /*echo"sdt1=".$sortObjTmp->deviceDatetime[0]."<br>";
            echo "sdt2=".$sortObjTmp->deviceDatetime[1]."<br>";	
            echo "ss1=".$sortObjTmp->speedData[0]."<br>";
            echo "ss2=".$sortObjTmp->speedData[1]."<br>";
            echo "<br><br>";*/
            $sortedSize=sizeof($sortObjTmp->deviceDatetime);
            for($obi=0;$obi<$sortedSize;$obi++)
            {				
                    $finalDateTimeArr[$i][]=$sortObjTmp->deviceDatetime[$obi];	
                    $finalLatitudeArr[$i][]=$sortObjTmp->latitudeData[$obi];
                    $finalLongitudeArr[$i][]=$sortObjTmp->longitudeData[$obi];	
                    $finalSpeedArr[$i][]=$sortObjTmp->speedData[$obi];
                    //$dataCnt++;
            }
        }
        $SortedDataObject=null;			
        $sortObjTmp=null;
        $UnsortedDataObject =null;
        
        if($j>$i)
        {
                $i=$j-1;
        }        
    }
    $o_cassandra->close();

?>    
