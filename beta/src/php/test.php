<?php
  include_once('util_session_variable.php');
  include_once("util_php_mysql_connectivity.php");
?>
<hr>
<?php

  /*
  $query="SELECT admin_id,account_id FROM account_admin_id";
  $result=mysql_query($query,$DbConnection);
  echo "<table align=cente>";
  echo "<tr><td>admin_id</td><td>ac_id</td></tr>";
	while ($row=mysql_fetch_object($result))
	{
    $ac_id=$row->account_id;
    $admin_id=$row->admin_id;
    echo "<tr><td>$admin_id</td><td>$ac_id</td></tr>";
    $query="UPDATE account_detail SET account_admin_id='$admin_id' WHERE account_id='$ac_id'";
    $result1=mysql_query($query,$DbConnection);
  }
  echo "</table>";
  */

$data=Array();
$data[]="1,UP-78-BT-6369,Truck,Chubeypur";
$data[]="2,UP-78-BT-6427,Truck,Chubeypur";
$data[]="3,UP-78-BT-6368,Truck,Chubeypur";
$data[]="4,UP-78-BT-6423,Truck,Chubeypur";
$data[]="5,UP-78-AT-6590,Truck,Chubeypur";
$data[]="6,UP-78-AT-8445,Truck,Chubeypur";
$data[]="7,MP-07-G-5697,Truck,Chubeypur";
$data[]="8,UP-78-BT-3803,Truck,Chubeypur";
$data[]="9,UP-78-BT-3802,Truck,Chubeypur";
$data[]="10,UP-78-BT-4305,Truck,Chubeypur";
$data[]="11,UP-78-BT-4283,Truck,Chubeypur";
$data[]="12,UAN-9218,Truck,Chubeypur";
$data[]="13,PB-10-BB-4986,Truck,Chubeypur";
$data[]="14,UP-78-9115,Truck,Chubeypur";
$data[]="15,UP-17-N-1782,Truck,Rania";
$data[]="16,UP-77-N-1754,Truck,Rania";
$data[]="17,UP-77-N-1755,Truck,Rania";
$data[]="18,UP-77-N-1756,Truck,Rania";
$data[]="19,UP-77-N-1770,Truck,Rania";
$data[]="20,UP-77-N-1781,Truck,Rania";
$data[]="21,UP-77-N-1796,Truck,Rania";
$data[]="22,UP-77-N-1797,Truck,Rania";
$data[]="23,UP-78-BN-9711,Truck,Rania";
$data[]="24,UP-78-BN-9713,Truck,Rania";
$data[]="25,UP-78-BN-9760,Truck,Rania";
$data[]="26,UP-78-BN-9970,Truck,Rania";
$data[]="27,UP-78-BN-9987,Truck,Rania";
$data[]="28,UP-78-BT-2704,Truck,Rania";
$data[]="29,UP-78-BT-4086,Truck,Rania";
$data[]="30,UP-78-BT-4088,Truck,Rania";
$data[]="31,UP-78-BT-4090,Truck,Rania";
$data[]="32,UP-78-BT-4134,Truck,Rania";
$data[]="33,UP-78-BT-4137,Truck,Rania";
$data[]="34,UP-78-BT-4138,Truck,Rania";
$data[]="35,UP-78-BT-4145,Truck,Rania";
$data[]="36,UP-78-BT-4304,Truck,Rania";
$data[]="37,UP-78-BT-8101,Truck,Rania";
$data[]="38,UP-78-BT-8102,Truck,Rania";
$data[]="39,UP-78-BT-8103,Truck,Rania";
$data[]="40,UP-78-BN-9758,Truck,Rania";
$data[]="41,UP-78-BN-9826,Truck,Rania";
$data[]="42,UP-78-BN-9730,Truck,Rania";
$data[]="43,UP-78-BN-9731,Truck,Rania";
$data[]="44,UP-17-N-1753,Truck,Rania";
$data[]="45,UP-17-N-1770,Truck,Rania";
$data[]="46,UP-17-N-1780,Truck,Rania";
$data[]="47,UP-78-BT-4089,Truck,Rania";
$data[]="48,UP-78-BT-4300,Truck,Rania";
$data[]="49,UP-78-BT-4301,Truck,Rania";
$data[]="50,UP-78-BT-4302,Truck,Rania";
$data[]="51,UP-78-BT-4303,Truck,Rania";
$data[]="52,UP-78-BT-4372,Truck,Rania";
$data[]="53,UP-78-BT-4430,Truck,Rania";
$data[]="54,UP-77-E-9656,MAX,CAR";
$data[]="55,UP-77-F-6400,MAX,CAR";
$data[]="56,UP-78-BA-1101,Innova,CAR";
$data[]="57,UP-78-BR-7216,Innova,CAR";
$data[]="58,UP-78-BS-0258,Swift,CAR";
$data[]="59,UP-78-BU-6825,Santro,CAR";
$data[]="60,UP-78-BW-5880,Ford,CAR";
$data[]="61,UP-78-AQ-5311,Santro,CAR";
$data[]="62,New Scorpio-2,Scorpio,CAR";
$data[]="63,New Scorpio,Scorpio,CAR";
$data[]="64,MP-15HA-0364,Truck,SAGAR";
$data[]="65,MP-15-G-2270,Truck,SAGAR";
$data[]="66,UP-78-BT-4303,Truck,SAGAR";
$data[]="67,MP-15-G-2269,Truck,SAGAR";
$data[]="68,MP-15-G-2307,Truck,SAGAR";
$data[]="69,UP-78-BT-0481,Truck,SAGAR";
$data[]="70,MP-15-G-2316,Truck,SAGAR";
$data[]="71,MP-15-G-2312,Truck,SAGAR";
$data[]="72,MP-15-G-2308,Truck,SAGAR";
$data[]="73,MP-09-GE-9649,Truck,SAGAR";
$data[]="74,MP-09-GF-0741,Truck,SAGAR";
$data[]="75,MP-09-GE-9648,Truck,SAGAR";
$data[]="76,MP-15-HA-0362,Truck,SAGAR";
$data[]="77,MP-15-HA-0361,Truck,SAGAR";
$data[]="78,MP-09-GE-9647,Truck,SAGAR";
$data[]="79,MP-09-GF-0641,Truck,SAGAR";
$data[]="80,MP-15-G-2268,Truck,SAGAR";
$data[]="81,MP-15-HA-0363,Truck,SAGAR";
$data[]="82,MP-09-GE-9646,Truck,SAGAR";
$data[]="83,MP-15-G-2271,Truck,SAGAR";
$data[]="84,UP-78-BT-4302,Truck,SAGAR";
$data[]="85,MP-09-GE-9165,Eicher,Jabalpur";
$data[]="86,MP-09-GE-9166,Eicher,Jabalpur";
$data[]="87,MP-09-GE-6341,Truck,Indore";
$data[]="88,MP-09-GE-9168,Truck,Indore";
$data[]="89,MP-09-GE-9341,Truck,Indore";
$data[]="90,MP-09-GE-9906,Truck,Indore";
$data[]="91,MP-09-GE-9167,Truck,Indore";
$data[]="92,MP-09-GF-1841,Truck,Indore";
$data[]="93,MP-09-GE-9441,Truck,Indore";
$data[]="94,MP-09-GF-1741,Truck,Indore";
$data[]="95,MP-09-GE-9904,Truck,Indore";
$data[]="96,RJ-02-GA-4709,Truck,Alwar";
$data[]="97,RJ-02-GA-4702,Truck,Alwar";
$data[]="98,RJ-02-GA-4703,Truck,Alwar";
$data[]="99,RJ-02-GA-4710,Truck,Alwar";
$data[]="100,RJ-02-GA-4707,Truck,Alwar";
$data[]="101,RJ-02-GA-3816,Truck,Alwar";
$data[]="102,RJ-02-G-7531,Eicher,Alwar";
$data[]="103,RJ-14-UB-7360,Bolero,RSM (Raj.)";
$data[]="104,DL-4C-NB-5393,Bolero,RSM ";
$data[]="105,UP-14-AT-8590,Truck,Sahibabad";
$data[]="106,UP-14-AT-8593,Truck,Sahibabad";
$data[]="107,UP-14-AT-8591,Truck,Sahibabad";
$data[]="108,UP-77-N-2330,Eicher,Sahibabad";
$data[]="109,UP-14-AT-8592,Truck,Sahibabad";
$data[]="110,UP-78-BT-5930,Truck,Noida";
$data[]="111,UP-78-BT-5301,Truck,Noida";
$data[]="112,UP-77-A-7613,Eicher,Noida";
$data[]="113,UP-78-BN-9726,Eicher,Noida";
$data[]="114,UP-78-AT-8166,Eicher,Noida";
$data[]="115,UP-78-B-0382,Eicher,D.O.";
$data[]="116,UTE-6277,Eicher,D.O.";
$data[]="117,UP-78-BN-9210,Eicher,D.O.";
$data[]="118,UP-78-BN-8165,Eicher,D.O.";
$data[]="119,UP-78-BT-5522,Eicher,D.O.";
$data[]="120,UP-78-AN-1932,Eicher,D.O.";
$data[]="121,UP-78-BN-8032,Eicher,D.O.";
$data[]="122,UP-78-BN-8033,Eicher,D.O.";
$data[]="123,UP-78-BT-3703,Eicher,D.O.";
$data[]="124,UP-78-BT-5892,Tata Ace,Godown";
$data[]="125,UP-78-BT-3552,Tata Ace,Godown";
$data[]="126,UP-78-BT-4183,Tata Ace,Godown";
$data[]="127,UP-78-BT-3897,Tata Ace,Godown";
$data[]="128,UP-78-BN-9725,Eicher,Haridwar";
$data[]="129,UP-78-BT-4282,Eicher,Haridwar";
$data[]="130,UP-78-BT-7519,Eicher,Haridwar";
$data[]="131,UP-78-BT-7520,Eicher,Haridwar";
$data[]="132,UP-78-AN-1588,Eicher,DadaNagar";
$data[]="133,UP-78-AN-1591,Eicher,DadaNagar";
$data[]="134,UAO-8-J-6663,Eicher,DadaNagar";
$data[]="135,HR-55-E-7606,Tempo,DadaNagar";
$data[]="136,HR-55-E-7607,Tempo,DadaNagar";
$data[]="137,MH-20-BT-0991,Eicher,Aurangabad";
$data[]="138,MH-20-BT-0309,Eicher,Aurangabad";
$data[]="139,CG-10-C-3634,Tata Ace,Prachar Bahan";
$data[]="140,HR-55-J-2333,Tata Ace,Prachar Bahan";
$data[]="141,RJ-02-GA-3276,Tata Ace,Prachar Bahan";
$data[]="142,CG-10-C-3633,Tata Ace,Prachar Bahan";
$data[]="143,MH-20-AT-8367,Tata Ace,Prachar Bahan";
$data[]="144,MH-20-AT-8368,Tata Ace,Prachar Bahan";
$data[]="145,PB-10-CN-5603,Tata Ace,Prachar Bahan";
$data[]="146,CG-08-B-2851,Tata Ace,Prachar Bahan";
$data[]="147,RJ-02-GA-3277,Tata Ace,Prachar Bahan";
$data[]="148,HR-55-J-2332,Tata Ace,Prachar Bahan";
$data[]="149,CG-08-B-2852,Tata Ace,Prachar Bahan";
$data[]="150,MP-09-LN-5954,Tata Ace,Prachar Bahan";
$data[]="151,MH-20-AT-8366,Tata Ace,Prachar Bahan";
$data[]="152,UP-78-BT-4185,Tata Ace,Prachar Bahan";
$data[]="153,DL-1L-K-6774,Tata Ace,Prachar Bahan";
$data[]="154,DL-1L-K-6773,Tata Ace,Prachar Bahan";
$data[]="155,UMO-9874,Eicher,Store";
$data[]="156,RJ-02-GA-7744,Tempo,Banther";
echo print_array("Data",$data);

  echo "<hr>";

  
  $names=Array();
  $data=Array();  

  $data1=Array();
  for ($i=0;$i<5;$i++) {$data1[]=$i*5;}
  $names[1]="Data1";
  $data[1]=$data1;

  $data2=Array();
  for ($i=1;$i<=10;$i++) {$data2[$i]=$i*7;}
  $names[2]="Data2";
  $data[2]=$data2;

  $data3=Array();
  for ($i=1;$i<=3;$i++) {$data3[20-2*$i]=$i+3;}
  $names[3]="Data3";
  $data[3]=$data3;
    
  // echo print_array($names[1],$data1);  
  // echo print_array($names[2],$data2);
  // echo "<hr>";
  
  echo print_arrays($names,$data);
  



  echo "<hr>";
  $query="SELECT column_name FROM information_schema.columns WHERE table_name='account_feature' AND ordinal_position>'8'";
  $result=mysql_query($query,$DbConnection);
	$feature_count=mysql_num_rows($result);
	$fi=0;
	$query="SELECT ";
	while ($row=mysql_fetch_object($result))
	{
    $fi++;
    ${"fname".$fi}=$row->column_name;
    if ($fi<$feature_count) $query.=${"fname".$fi}.",";
  }
  $query.=${"fname".$fi}." FROM account_feature WHERE account_id=".$account_id;
  // print_query($query);
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  for ($fi=1; $fi<=$feature_count; $fi++)
  {
    ${"fvalue".$fi}=$row->${"fname".$fi};
  }
  // echo "<br>";
  for ($fi=1; $fi<=$feature_count; $fi++)
  {
    echo ${"fname".$fi}." -> ".${"fvalue".$fi}."<br>";
  }
?>
<hr>
<?php
  $query="SELECT superuser as c1,user as c2,grp as c3, account_id as c4 FROM account";
  // print_query($query);
  $result=mysql_query($query,$DbConnection);
  $c=4;
  $i=1;
  echo '<table align="center" border="2">';
  while($row=mysql_fetch_object($result))
  {
    echo '<tr><td>'.$i++.'</td>';
    for ($j=1; $j<=$c; $j++)
    {
      ${"c$j"}=$row->{"c$j"};
      echo '<td>'.${"c$j"}.'</td>';
    }
    echo '</tr>';
  }
  echo '</table>';
?>
<hr>