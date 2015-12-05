<?php

require_once ($pathToRoot.'/phpApi/libCommon.php');


/***
* Prints Cassandra query results in HTML 
*
* @param array		Results of CQL
*
* @return		TRUE 
*
*/
function printHTML($st_results)
{

	echo "\n";
	echo 'Printing Top 10 rows:'."\n";
	
	echo '<table style="width:100%">';
	echo '<tr>';
	echo '<td>imei</td>';
	echo '<td>DateTime</td>';
	echo '<td>Data</td>';
	echo'</tr>';
	
	foreach ($st_results as $row){
		echo '<tr>';
		foreach($row as $key=>$value){
				echo '<td>';
				echo $value;
				echo '</td>';
		}
		echo '</tr>';
	}
	
	echo'</table>';
}


/***
* Returns last seen data before given datetime from full data log
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $datetime	YYYY-MM-DD HH:MM:SS
* 
* @return array 	Results of the query 
*/
function getLastSeenDateTime($o_cassandra,$imei,$datetime)
{
	$TZ = '0530';

	$yy = substr($datetime,0,4);

	$date1 = substr($datetime,0,10);
	$date2 = substr(str_replace($yy,$yy-1,$datetime),0,10);

	$interval = new DateInterval('P1D');
	$start = new DateTime($date1);
	$end = new DateTime($date2);
	$start->add($interval);

	$st_results = array(); // initialize with empty array
 
	for ($date = $start; $date->sub($interval); $date >= $end)
	{
		$strDate = $date->format('Y-m-d');
		$s_cql = "SELECT * FROM log1 
			  WHERE 
			  imei = '$imei'
			  AND
			  date = '$strDate'
			  AND	
			  dtime < '$datetime+$TZ'
			  LIMIT 1
			  ;";
		$st_results = $o_cassandra->query($s_cql);
		if (!empty($st_results))
			break;
	}

	$dataType = TRUE;	// TRUE for fulldata, otherwise lastdata
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 
	$st_obj = logParser($st_results, $dataType, $orderAsc);
	return $st_obj;
}


/***
* Returns last seen data from last data table lastlog
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei	IMEI
* @param string $date	YYYY-MM-DD
* 
* @return array 	Results of the query 
*/
function getLastSeen($o_cassandra,$imei)
{
	$s_cql = "SELECT * FROM lastlog 
		  WHERE 
		  imei = '$imei'
		  ;";

	$st_results = $o_cassandra->query($s_cql);
	$dataType = FALSE;	// TRUE for fulldata, otherwise lastdata
	$orderAsc = FALSE;	// TRUE for ascending, otherwise descending (default) 
	$st_obj = logParser($st_results, $dataType, $orderAsc);
	return $st_obj;
}

/*function getLastSeen($DbConnection,$imei)
{
    $st_obj = new stdClass();
    $num=0;
    $query = "SELECT * FROM last_data WHERE IMEI='$imei'";
    $res = mysql_query($query,$DbConnection);
    $numrows = mysql_num_rows($res);
    if($numrows>0)
    {
        if($row=mysql_fetch_object($res)) {
            $st_obj->$num->a = $row->a;
            $st_obj->$num->b = $row->b;
            $st_obj->$num->c = $row->c;
            $st_obj->$num->d = $row->d;
            $st_obj->$num->e = $row->e;
            $st_obj->$num->f = $row->f;
            $st_obj->$num->g = $row->g;
            $st_obj->$num->h = $row->h;
            $st_obj->$num->i = $row->i;
            $st_obj->$num->j = $row->j;
            $st_obj->$num->k = $row->k;
            $st_obj->$num->l = $row->l;
            $st_obj->$num->m = $row->m;
            $st_obj->$num->n = $row->n;
            $st_obj->$num->o = $row->o;
            $st_obj->$num->p = $row->p;
            $st_obj->$num->q = $row->q;
            $st_obj->$num->r = $row->r;
            $st_obj->$num->s = $row->s;
            $st_obj->$num->t = $row->t;
            $st_obj->$num->u = $row->u;
            $st_obj->$num->ci = $row->ci;
            $st_obj->$num->ax = $row->ax;
            $st_obj->$num->ay = $row->ay;
            $st_obj->$num->az = $row->az;
            $st_obj->$num->mx = $row->mx;
            $st_obj->$num->my = $row->my;
            $st_obj->$num->mz = $row->mz;
            $st_obj->$num->bx = $row->bx;
            $st_obj->$num->by = $row->by;
            $st_obj->$num->bz = $row->bz;
        }
    }
    return $st_obj;
}*/



/***
* Parses and Converts array returned by CQL to object
*
* @param array		Results of CQL
* @param array		Param filter	
* @param boolean	Full or Last Seen 
* @param boolean	Order by Ascending or Descending (default)	
*
* @return object	Object with names of entities
*
* return json_decode(json_encode($st_results),FALSE);
*/
function logParser($st_results, $dataType, $orderAsc)
{
	$st_obj = new stdClass();			
	$full_params = array('a','b','c','d','e','f','i','j','k','l','m','n','o','p','q','r','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	$last_params = array('a','b','c','d','e','f','h','i','j','k','l','m','n','o','p','q','r','s','t','u','ci','ax','ay','az','mx','my','mz','bx','by','bz');
	$gps_params = ($dataType)?$full_params:$last_params;
	$paramSize = sizeof($gps_params);
	$resArray = ($orderAsc)?array_reverse($st_results):$st_results;

	$num = 0;
	//$TZDIFF = 19800;	// Asia/Kolkata
	$TZDIFF = 0;		// date takes system time zone
	foreach ($resArray as $row)
	{
		$st_obj->$num = new stdClass;
		$st_obj->$num->g = date('Y-m-d@H:i:s',$row['stime']/1000-$TZDIFF);	// device time is stored as row key as timestamp in milisecond
		if ($dataType) $st_obj->$num->h = date('Y-m-d@H:i:s',$row['dtime']/1000-$TZDIFF);	// device time is stored as row key as timestamp in milisecond

		$i = 0;
		
		foreach (str_getcsv($row['data'], ";") as $gps_val)
		{
			if ($i == $paramSize)
				break; 
			$st_obj->$num->$gps_params[$i++] = $gps_val;
		}
		$num++;
	}
	
	return $st_obj;
}

/***
* Runs CQL query on Cassandra datastore
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $date		YYYY-MM-DD
* 
* @return array 	Results of the query 
*/
function getLogByDate($o_cassandra, $imei, $date, $deviceTime, $orderAsc)
{

	$table = ($deviceTime)?'log1':'log2';
	$qtime = ($deviceTime)?'dtime':'stime';

	$s_cql2 = "SELECT * FROM $table
		WHERE
		imei = '$imei'
		AND
		date = '$date'
		;";
	$st_results = $o_cassandra->query($s_cql2);

	$dataType = TRUE;	// TRUE for fulldata, otherwise lastdata
	$st_obj = logParser($st_results, $dataType, $orderAsc);
		
	return $st_obj;
}

/***
* Runs CQL query on Cassandra datastore
* 
* @param object $o_cassandra	Cassandra object 
* @param string $imei		IMEI
* @param string $datetime1	YYYY-MM-DD HH:MM:SS
* @param string $datetime2	YYYY-MM-DD HH:MM:SS
* 
* @return array 	Results of the query 
*/
function getImeiDateTimes($o_cassandra, $imei, $datetime1, $datetime2, $deviceTime, $orderAsc)
{

	$TZ = '0530';

	$table = ($deviceTime)?'log1':'log2';
	$qtime = ($deviceTime)?'dtime':'stime';

	$dateList = getDateList($datetime1,$datetime2);
	$s_cql2 = "SELECT * FROM $table
		WHERE
		imei = '$imei'
		AND
		date IN $dateList
		AND
		$qtime >= '$datetime1+$TZ'
		AND
		$qtime <= '$datetime2+$TZ'
		;";
	$st_results = $o_cassandra->query($s_cql2);

	$dataType = TRUE;	// TRUE for fulldata, otherwise lastdata
	$st_obj = logParser($st_results, $dataType, $orderAsc);
		
	return $st_obj;
}

/***
* Runs CQL query on Cassandra datastore
* 
* @param string $imei	IMEI
* 
* @return boolean 	true if imei has logged on a given day, false otherwise 
*/
function hasImeiLogged($o_cassandra, $imei, $date)
{

	$table = 'log1';
	$s_cql2 = "SELECT * FROM $table
		WHERE
		imei = '$imei'
		AND
		date = '$date'
		LIMIT 1
		;";
	$st_results = $o_cassandra->query($s_cql2);

	return !(empty($st_results));

}

/***
* Runs CQL query on Cassandra datastore
* 
* @param string $imei	IMEI
* 
* @return boolean 	true if imei has logged Today 
*/
function hasImeiLoggedToday($o_cassandra, $imei)
{

	$table = 'lastlog';

	$s_cql2 = "SELECT * FROM $table
		WHERE
		imei = '$imei'
		;";
	$st_results = $o_cassandra->query($s_cql2);

	$row = $st_results[0];
	$TZDIFF = 0;		// date takes system time zone
	$stime = date('Y-m-d@H:i:s',$row['stime']/1000-$TZDIFF);	// server time is stored as row key as timestamp in milisecond
	return $stime;

}
function getLastSeenDate($DbConnection,$imei) 
{
    $Date=null;
    $query = "select date(g) AS date1 FROM last_data WHERE IMEI='$imei'";
    $res = mysql_query($query,$DbConnection);
    $numrows = mysql_num_rows($res);
    
    if($numrows>0) {
        if($row=mysql_fetch_object($res)) {
            $Date = $row->date1;
        }
    }	
    return $Date;
}


?>
