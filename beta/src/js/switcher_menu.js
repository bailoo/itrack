function manage_show_file_switcher(module,file_name,js_type)
{
        //alert(module);
        //alert(file_name);
        //check if not in current module then first load current module
            var pathArray = window.location.pathname.split( '/' );
            
            var secondLevel = pathArray[pathArray.length-1];
            //alert(secondLevel);
            if(secondLevel==module)
            {
                //alert(secondLevel);
               // manage_show_file(file_name );
                //alert("in"+js_type+"d");
                if( js_type == undefined || js_type == "" )
                {
                   // alert("ss");
                   manage_show_file(file_name);
                }
                else
                {
                    //alert("in"+js_type+"d");
                    
                    window[js_type](file_name);
                    //manage_show_file(file_name );
                }
            }
            else
            {
                var poststr = "drop_down_menu_module="+module+
                       "&drop_down_menu_file="+file_name+
                       "&js_type="+js_type;		
                //alert(poststr);

               $.ajax({
               type: "POST",
               url:'src/php/storesession.htm',
               data: poststr,
               success: function(response){
                       //console.log(response);
                       //alert("response="+response);		
                       window.location=module;  
                       //window.location=file_name; 

               },
               error: function()
               {
                       alert('An unexpected error has occurred! Please try later.');
               }
               });
            }
            
         
        
        //manage_show_file_switcher_file_name(file_name);

        //showManageLoadingMessage();	
        //makePOSTRequest(file_name, '');
}

function report_show_file_switcher(module,file_name,title,js_type)
{
    
    var pathArray = window.location.pathname.split( '/' );
    var secondLevel = pathArray[pathArray.length-1];
    //alert(secondLevel);
    if(secondLevel==module)
    {
        //alert("match"+js_type);
        window[js_type](file_name,title);
    }
    else
    {
        var poststr = "drop_down_menu_module="+module+
               "&drop_down_menu_file="+file_name+
               "&title_report="+title+
               "&js_type="+js_type;		
        //alert(poststr);

       $.ajax({
       type: "POST",
       url:'src/php/storesession.htm',
       data: poststr,
       success: function(response){
               //console.log(response);
               //alert("response="+response);		
               window.location=module;  
               //window.location=file_name; 

       },
       error: function()
       {
               alert('An unexpected error has occurred! Please try later.');
       }
       });
    }
}

function report_show_file_switcher_upload_file(module,file_name,title,type_upload,js_type)
{
    
    var pathArray = window.location.pathname.split( '/' );
    var secondLevel = pathArray[pathArray.length-1];
    //alert(secondLevel);
    if(secondLevel==module)
    {
        //alert("match"+js_type);
        window[js_type](file_name,type_upload,title);
    }
    else
    {
        var poststr = "drop_down_menu_module="+module+
               "&drop_down_menu_file="+file_name+
               "&title_report="+title+
               "&type_upload="+type_upload+
               "&js_type="+js_type;		
        //alert(poststr);

       $.ajax({
       type: "POST",
       url:'src/php/storesession.htm',
       data: poststr,
       success: function(response){
               //console.log(response);
               //alert("response="+response);		
               window.location=module;  
               //window.location=file_name; 

       },
       error: function()
       {
               alert('An unexpected error has occurred! Please try later.');
       }
       });
    }
}




	