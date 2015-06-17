	function expand(s)
	{
	
	 // document.getElementById("main_fleet").style.display="";
	  var td = s;
	  var d = td.getElementsByTagName("div").item(0);
   //alert("test"+d);
	  td.className = "menuHover";
	  d.className = "menuHover";
	}

function collapse(s)
{
  var td = s;
  var d1 = td.getElementsByTagName("div").item(0);
 // alert("test1="+d1);
  td.className = "menuNormal";
  d1.className = "menuNormal";
}

