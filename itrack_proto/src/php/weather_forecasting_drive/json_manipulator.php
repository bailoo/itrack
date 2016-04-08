<?php
header('Access-Control-Allow-Origin: *');

//interval forcasting  1:3hr  2:6hr 3:9hr 4:12hr  only 1
//temp,humidity rainfall, wind, time
//userId  password  geoCoord fromDate toDate interval

$latitude =trim($_GET['lat']);
$longitude=trim($_GET['lon']);

$fromDate=$_GET['fromDate'];
$toDate=$_GET['toDate'];
$geoCoord=round($latitude,2).",".round($longitude,2);
//echo $geoCoord;
$data=array();
//$data=array('userId'=>'wrms','password'=>'wrms@007','interval'=>'1','toDate'=> '2016/03/02 11:30','fromDate'=>'2016/03/01 15:30','geoCoord'=>'27.12,78.49');
$data=array('userId'=>'wrms','password'=>'wrms@007','interval'=>'1','toDate'=>$toDate,'fromDate'=>$fromDate,'geoCoord'=>$geoCoord);
//$json=CallAPI('POST','http://localhost/rest_myTransporter/rest_weathers/getWeatherForecasting.json',$data);
$json=CallAPI('POST','http://wrgfs.co.in/gfs/rest_weathers/getWeatherForecasting.json',$data);

//echo $json;
$data_return = json_decode($json, true);
//print_r($data_return);
$pic_icon="wr_cloudy.png";
$tmp_status="";
$wind_status="";
$wind_direction_status="";
$rain_status="";
$humidity_status="";

$tbl='<div class="main">
			<ul class="tabs">';
			$size_row=sizeof($data_return);
			$j=1;$i=0;
			//echo "size=".$size_row;
			foreach($data_return as $row_data)
			{
				$flag_content=0;
				$tmp_status="";
				$wind_status="";
				$wind_direction_status="";
				$rain_status="";
				$humidity_status="";
				$flag_Date="";
				foreach($row_data as $row)
				{
					$date=$row['Date'];					
					$time=str_replace("_",".",$row['Time']);
					$tmp=$row['Temperature'];
					$wind=$row['WindSpeed'];
					$windDirection=$row['WindDirection'];
					$rainfall=$row['Rainfall'];
					$humidity=$row['Humidity'];
					if($flag_Date!=$date)
					{
						//echo "DATE";
						if($j>1)
						{
							$tbl=$tbl.'	</tbody>
								</table>
							  </div>
							</li>';	
						}
						$flag_Date=$date;
						if($j==1)
						{
							$tbl=$tbl.'<li>
							  <input type="radio" checked name="tabs" id="tab'.$j.'">
							  <label for="tab'.$j.'">'.$date.'</label>
							  <div id="tab-content'.$j.'" class="tab-content animated fadeIn">
							   <table width=100% class="table-style-two">
								<thead>
									<tr><th>Time(h:m)</th><th>Tmp.</th><th>WindSpeed</th><th>WindDirection</th><th>Rainfall</th><th>Humidity</th></tr>
								</thead>
								<tbody>
							   ';
							   $tmp_status=$tmp;
							   $wind_status=$wind;
							   $wind_direction_status=$windDirection;
							   $rain_status=$rainfall;
							   $humidity_status=$humidity;
							$tbl=$tbl.'	<tr><td>'.$time.'</td><td>'.$tmp.'</td><td>'.$wind.'</td><td>'.$windDirection.'</td><td>'.$rainfall.'</td><td>'.$humidity.'</td></tr>';
						}
						else
						{
							$tbl=$tbl.'<li>
							  <input type="radio"  name="tabs" id="tab'.$j.'">
							  <label for="tab'.$j.'">'.$date.'</label>
							  <div id="tab-content'.$j.'" class="tab-content animated fadeIn">
							   <table width=100% class="table-style-two">
								<thead>
									<tr><th>Time(h:m)</th><th>Tmp.</th><th>WindSpeed</th><th>WindDirection</th><th>Rainfall</th><th>Humidity</th></tr>
								</thead>
								<tbody>
							   ';
						$tbl=$tbl.'	<tr><td>'.$time.'</td><td>'.$tmp.'</td><td>'.$wind.'</td><td>'.$windDirection.'</td><td>'.$rainfall.'</td><td>'.$humidity.'</td></tr>';
						}
						
					}
					else
					{
						$tbl=$tbl.'	<tr><td>'.$time.'</td><td>'.$tmp.'</td><td>'.$wind.'</td><td>'.$windDirection.'</td><td>'.$rainfall.'</td><td>'.$humidity.'</td></tr>';
					}
					
								
					
					
				  $j++;
				}
				
				$i++;
				//echo $i;
			}
			
		
				
				$tbl=$tbl.'	</tbody>
						</table>
					  </div>
					</li>';	
		
			
			$tbl=$tbl.'</ul>
	   </div>';

//echo "<input type=textarea value='$tbl'>";
//=====Setting Status
if($rain_status>0 && $rain_status < 2.0)
{
	$pic_icon="wr_drizzle.png";
}
else if($rain_status>2.0)
{
	$pic_icon="wr_rainy.png";
}
else if($tmp_status <= 15 && $tmp_status!='')
{
	$pic_icon="wr_mcloudy.png";
}
else if($tmp_status >15 && $tmp_status <28)
{
	$pic_icon="wr_sunny.png";
}
else if($tmp_status >=28)
{
	$pic_icon="wr_hazy.png";
}
else
{
	$pic_icon="wr_nd.png";
}

echo $tbl."~`".$pic_icon."~`";
//print_r($data_return);

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}
?>