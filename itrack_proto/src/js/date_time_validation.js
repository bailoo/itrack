function home_date_time_validation(b)
{
  var currentTime = new Date();
  var month = currentTime.getMonth() + 1;     
  var day = currentTime.getDate();      
  var year = currentTime.getFullYear();     
  var startdate1;   
  var enddate1;   
  
  if(b==1 || b==2)
  {
    startdate1=document.thisform.start_date.value;		 enddate1=document.thisform.end_date.value;
  }
  else
  {
    startdate1 = "";  enddate1 = "";  
  }  
  var startlen = startdate1.length;
  var endlen = enddate1.length;
  var cp_startdate=new Date(startdate1);
  var cp_enddate=new Date(enddate1);   
  var cp_startdate1=cp_startdate.getTime();
  var cp_enddate1=cp_enddate.getTime();
  //alert(" cp_startdate="+cp_startdate1+" cp_enddate="+cp_enddate1);
  
  if(((startlen > 0)&&(startlen < 10))||((endlen > 0)&&(endlen < 10)))
  {
    alert("Incorrect date  format...enter yyyy-mm-dd");
    return false;
  } 
  if(cp_startdate1>cp_enddate1)
  {
    alert("Start date is greater than end date.Please correct it.");
    return false;
  }
 var difference_sec = (cp_enddate1 - cp_startdate1)/1000;
        var totalMinutes = difference_sec / 60;
       var minutesPerDay=24*60;
        
            var days = totalMinutes / minutesPerDay;
    if(days>30)
    {
        alert("Date ranage must be less than 30 days.");
        return false;
    }
  return true;
}

function route_date_time_validation(b)
{

  var currentTime = new Date();
  var month = currentTime.getMonth() + 1;     
  var day = currentTime.getDate();      
  var year = currentTime.getFullYear();     
  var startdate1;   
  //var enddate1;   
  
  if(b==1 || b==2)
  {
    startdate1=document.thisform.start_date.value+' 23:59:59';		 
	//enddate1=document.thisform.end_date.value;
  }
  else
  {
    startdate1 = "";  
	//enddate1 = "";  
  }  
  var startDateCmp=new Date(startdate1);
  startDateCmp=startDateCmp.getTime();
  //alert("startDateCmp="+startDateCmp);
  var startlen = startdate1.length;
  var currDate = new Date();
  currDate=currDate.getTime();
  //alert("currDate="+currDate);
  /*var cp_startdate=new Date(startdate1);
  var cp_startdate1=cp_startdate.getTime();
  //var endlen = enddate1.length;
  var cp_enddate=new Date(enddate1);
  var cp_enddate1=cp_enddate.getTime();*/
  

  //alert(" cp_startdate="+cp_startdate1+" cp_enddate="+cp_enddate1);
  
  //if(((startlen > 0)&&(startlen < 10))||((endlen > 0)&&(endlen < 10)))
  if(((startlen > 0)&&(startlen < 10)))
  {
    alert("Incorrect date  format...enter yyyy-mm-dd");
    return false;
  } 
  /*if(cp_startdate1>cp_enddate1)
  {
    alert("Start date is greater than end date.Please correct it.");
    return false;
  }*/
    //alert("currDate="+currDate);
  //if((currDate==startDateCmp)|| (startDateCmp>currDate))
  if((startDateCmp>currDate))
  {
	 alert("Start date is greater than today date.Please enter previous day.");
    return false;
  }
  return true;
}
