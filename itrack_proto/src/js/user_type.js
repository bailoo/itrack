function check_usertypes(value)
{
  //alert("utype="+value);
  var usertype = document.manage1.elements['user_type[]']; 
  var usertype_len = usertype.length;	
  if(usertype_len!=undefined)
  {
    //alert("in if");
    for(var i=0;i<(usertype_len);i++)
    {	                
      if(usertype[i].checked==true)
      {
        var feature = document.manage1.elements['feature'+i+'[]'];
        var feature_len = feature.length; 
        if(feature_len!=undefined)
        {   	
          for(var j=0;j<(feature_len);j++)
          {	  
          feature[j].checked = true; 
          } 
        }
        else
        {
          feature.checked = true;
        }
      }
      else
      {
        var feature = document.manage1.elements['feature'+i+'[]'];
        var feature_len = feature.length;         
        if(feature_len!=undefined)
        {  
          for(var j=0;j<(feature_len);j++)
          {	  
          feature[j].checked = false; 
          }
        }
        else
        {
          feature.checked = false; 
        }        
      }
    }
  } 
  else
  {
    //alert("in else");
    if(usertype.checked==true)
    {
      var feature = document.manage1.elements['feature'+0+'[]'];
      //alert("feature="+feature);
      var feature_len = feature.length;
      //alert("feature_len="+feature_len); 
      if(feature_len!=undefined)
      {   	
        for(var j=0;j<(feature_len);j++)
        {	  
          feature[j].checked = true; 
        }
      }
      else
      {
        feature.checked = true;
      }
    } 
    else
    {
      var feature = document.manage1.elements['feature'+0+'[]'];
      var feature_len = feature.length;         
      if(feature_len!=undefined)
      {  
        for(var j=0;j<(feature_len);j++)
        {	  
        feature[j].checked = false; 
        }
      }
      else
      {
        feature.checked = false; 
      }        
    }  
  }
}

function check_features(selected_id, selected_value)
{  
    if(selected_id.checked==true)
    {
      var usertype = document.manage1.elements['user_type[]'];
      var usertype_len = usertype.length; 
      if(usertype_len!=undefined) 
      { 
        for(var i=0;i<(usertype_len);i++)
    		{
          if(usertype[i].checked==true)
        	{
            var feature="";
            
            feature = document.manage1.elements['feature'+i+'[]'];
            //feature[selected_value].checked = true; 
            var feature_len=0;
            feature_len = feature.length; 
            if(feature_len!=undefined)
            {  	
              for(var j=0;j<(feature_len);j++)
              {	  
                 if(j==(selected_value-1))
                 {
                  feature[j].checked = true;
                 } 
              } 
            }
            else
            {
                feature.checked = true;
            }        
          }     
        }
      }  
      else
      {
          if(usertype.checked==true)
        	{
            var feature="";            
            feature = document.manage1.elements['feature'+0+'[]'];
            //feature[selected_value].checked = true; 
            var feature_len=0;
            feature_len = feature.length; 
            if(feature_len!=undefined)
            {  	
              for(var j=0;j<(feature_len);j++)
              {	  
                 if(j==(selected_value-1))
                 {
                  feature[j].checked = true;
                 } 
              } 
            }
            else
            {
                feature.checked = true;
            }        
          }     
      }
    }    // first condition closed
    
    else if(selected_id.checked==false)
    {
      var usertype = document.manage1.elements['user_type[]'];
      var usertype_len = usertype.length;
      if(usertype_len!=undefined)
      { 
        for(var i=0;i<(usertype_len);i++)
    		{
          if(usertype[i].checked==true)
        	{
            var feature="";          
            feature = document.manage1.elements['feature'+i+'[]'];
            var feature_len=0;
            feature_len = feature.length; 
            if(feature_len!=undefined)
            {   	
              for(var j=0;j<(feature_len);j++)
              {	  
                   if(j==(selected_value-1))
                   {
                    feature[j].checked = false;
                   } 
              }
            }
            else
            {
               feature.checked = false;            
            }         
          }     
        }
      }
      else
      {
          if(usertype.checked==true)
        	{
            var feature="";          
            feature = document.manage1.elements['feature'+0+'[]'];
           // alert("feature="+feature);
            var feature_len=0;
            feature_len = feature.length; 
            //alert("feature_len="+feature_len);
            if(feature_len!=undefined)
            {   	
              for(var j=0;j<(feature_len);j++)
              {	  
                   if(j==(selected_value-1))
                   {
                    feature[j].checked = false;
                   } 
              }
            }
            else
            {
               feature.checked = false;            
            }         
          }          
      }  
    }        
}