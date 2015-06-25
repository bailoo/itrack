<!--<link rel="shortcut icon" href="images/iesicon.ico">-->
<link rel="shortcut icon" href="../../webroot/images/3dvtslogo.png">
<link rel="stylesheet" href="../css/jquery-ui.css">
<link rel="stylesheet" href="../css/module_hide_show_div.css">
<link rel="stylesheet" href="../css/menu.css">
<script src="../js/jquery-1.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/manage.js"></script>
<script src="../js/ajax.js"></script>

<script type="text/javascript" src="../js/menu.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>

<script language="javascript" src="../js/ajax.js"></script>


<script language="javascript" src="../js/elabel.js"></script>

<script type="text/javascript" src="../dragzoom/gzoom.js"></script> 
<?php 
  include_once("live_invoice_js.php");
?>
<script type="text/javascript">
	document.write('<script type="text/javascript" src="../js/extlargemapcontrol'+(document.location.search.indexOf('packed')>-1?'_packed':'')+'.js"><'+'/script>');
</script>
<script language="javascript" src="../js/labeledmarker.js"></script>
<script language="javascript" src="../js/pdmarker.js"></script>
<script language="javascript" src="../js/toggle_panel.js"></script>


<style type="text/css">

.style1 {
font-size:10px;background-color:green;color:#ffffff;
}

.style2 {
font-size:10px;background-color:orange;color:#ffffff;
}
.style3 {
font-size:10px;background-color:red;color:#ffffff;
}
	
.divm {
 position:absolute;
 top:50%;
 right:50%;
 width:100px;
}

@media print 
	{
		.noprint
		{
			display: none;
		}
	}
	@media screen
	{ 
		.noscreen
		{ 
			display: none; 
		} 
	}

  .normal1 { background-color: #F8F8FF }
  .highlight1 { background-color: #C6DEFF }

  .normal2 { background-color: #FFFDF9 }
  .highlight2 { background-color: #C6DEFF }
 </style>
<script type="text/javascript">
	
function resize(){
//alert("Processing1");
//test_1();
setTimeout(function() {testText();}, 1000);
//testText(); 
	var dv = document.getElementById("map");    
	divHeight =  $(window).height();
	dv.style.height = divHeight - 30; 
//alert("Processing2");	
}
	
function blink(objId,color1,color2,interval){

	var spans = document.getElementsByTagName("span");
	for (var i = 0; i < spans.length; i++) {
		if (spans[i].className == 'blink1') {
			spans[i].style.color = color1;
		}
		if (spans[i].className == 'blink2') {
			spans[i].style.color = color1;
		}	
	}
	//var obj=document.all.getElementById(objId)
	//obj.style.color=color1
	setTimeout("blink('"+objId+"','"+color2+"','"+color1+"',"+interval+")",interval)
}	
</script>
<link rel="stylesheet" type="text/css" href="src/css/tree_view/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="src/css/tree_view/treeview.css" />
