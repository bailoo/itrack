function blackout_product_list(sw,imgdivpath)
   {
      //alert(sw);
      if (sw=="1") 
    	{
        document.getElementById("blackout").style.visibility = "visible";
      	document.getElementById("divpopup").style.visibility = "visible";
      	document.getElementById("blackout").style.display = "block";
      	document.getElementById("divpopup").style.display = "block";
      	document.getElementById("imagediv").src=imgdivpath ;
    	}
    	else
    	{
      	document.getElementById("blackout").style.visibility = "hidden";
      	document.getElementById("divpopup").style.visibility = "hidden";
      	document.getElementById("blackout").style.display = "none";
      	document.getElementById("divpopup").style.display = "none";
    	}	
   }  

function query_form() 
{
   blackout_product_list(1);
	 //alert("hi");
}
   