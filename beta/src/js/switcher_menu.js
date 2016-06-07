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
                manage_show_file(file_name );
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




	