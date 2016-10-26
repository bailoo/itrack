<!DOCTYPE html>
<html>
<head> 
    <!-- Bootstrap core CSS -->
    <link href="../../src/thirdparty/ast_bs/dist/css/bootstrap.css" rel="stylesheet">  
    <script src="../../src/thirdparty/ast_bs/dist/js/bootstrap-toggle.js"></script>  
    <!-- Custom Fonts -->
    <link href="../../src/thirdparty/ast_bs/bower_components/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     
<?php
    ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
    include_once('util_session_variable.php');
    include('util_php_mysqli_connectivity.php');
    include('common_js_css.php');
    include('manage_js_css.php');
?>
<meta charset='utf-8' />
<link href='mycal/assets/css/fullcalendar.css' rel='stylesheet' />
<link href='mycal/assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='mycal/assets/js/moment.min.js'></script>
<script src='mycal/assets/js/jquery.min.js'></script>
<script src='mycal/assets/js/jquery-ui.min.js'></script>
<script src='mycal/assets/js/fullcalendar.min.js'></script>
<script src='mycal/assets/js/jquery.qtip.js'></script>
<link href='mycal/assets/css/jquery.qtip.css' rel='stylesheet' />

<script>
var tooltip="";

$(document).ready(function() 
{
    $(".search").keyup(function () {
    var searchTerm = $(".search").val();
    var listItem = $('.results tbody').children('tr');
    var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
    
    $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
          return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
      }
    });

    $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
      $(this).attr('visible','false');
    });

    $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
      $(this).attr('visible','true');
    });

    var jobCount = $('.results tbody tr[visible="true"]').length;
      $('.counter').text(jobCount + ' item');

    if(jobCount == '0') {$('.no-result').show();}
      else {$('.no-result').hide();}
		  });
    //declare tooltip for popup
    tooltip = $('<div/>').qtip({
            id: 'calendar',
            prerender: true,
            content: {
                    text: ' ',
                    title: {
                            button: true
                    }
            },
            position: {
                    my: 'bottom center',
                    at: 'top center',
                    target: 'mouse',
                    viewport: $('#calendar'),
                    adjust: {
                            mouse: false,
                            scroll: true
                    }
            },
            show: false,
            hide: false,
            style: 'qtip-light'
    }).qtip('api');	
    
    //var initialLocaleCode = 'en';
    var zone = "05:30";  //Change this to your timezone
    var common_account_id=document.getElementById('common_id').value;
    //alert(common_account_id);
    $.ajax({
	url: 'action_assign_schedule_visit.php',
        type: 'POST', // Send post data
        data: 'type=fetch&cid='+common_account_id,
        async: false,
        success: function(s){
        	json_events = s;
			//alert(json_events);
        }
    });	

    var currentMousePos = {
	    x: -1,
	    y: -1
    };
    
    jQuery(document).on("mousemove", function (event) {
        currentMousePos.x = event.pageX;
        currentMousePos.y = event.pageY;
    });

    /* initialize the external events
    -----------------------------------------------------------------*/

    $('#external-events .fc-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
            });

    });

    /* initialize the calendar
    -----------------------------------------------------------------*/
	
    $('#calendar').fullCalendar({		  
        navLinks: true, // can click day/week names to navigate views
        eventLimit: true, // allow "more" link when too many events
        editable: true,
        droppable: true,
        selectable: true,
        selectHelper: true,		
        slotDuration: '00:30:00',
        weekNumbers: true,
      
        
        events: JSON.parse(json_events),
        //events: [{"id":"14","title":"New Event","start":"2015-01-24T16:00:00+04:00","allDay":false}],
        
        
        
	eventRender: function(event, element,view) {            
            element.find('.fc-title').append("<br/>Total Person:" + event.person);
            var theDate = event.start.format("YYYY-MM-DD HH:mm:SS");
            var endDate = event.dowend;
            if(event.dowend!="")
            {
                if (theDate >= endDate) {
                    if(event.dow!="")
                    {
                            //alert(theDate);
                            //alert(endDate);
                            return false;
                    }
                }
            }
            currDate=moment().format('YYYY-MM-DD HH:mm:ss');
            if (currDate >= theDate) {
                if(event.dow!="")
                {
                        return false;
                }
            }
	},
	utc: true,
        header: {
                left: 'prev,next today',
                center: 'title',
                //right: 'month'
                //right: 'month,agendaWeek,agendaDay,basicWeek,basicDay'
                right: 'month,basicWeek,basicDay'
        },	
        //new update event take place with in
        eventConstraint: {
                start: moment().format('YYYY-MM-DD'),
                end: '2100-01-01' // hard coded goodness unfortunately
        },
	
        //eventMouseover : function(data, event, view) {
	eventClick : function(data, event, view) {	
            var tlt=data.title;            
            if(data.end!=null)
            {
                var end_date_format=" to "+data.end.format("YYYY-MM-DD HH:mm:SS");
            }
            else
            {
                var end_date_format="";
            }
            var content = ' <div class="panel panel-primary">\n\
                                <div class="panel-heading">'+data.title+'</div>' + 
                                '<div class="panel-body"><p><b>From: '+data.start.format("YYYY-MM-DD HH:mm:SS")+ end_date_format +'</b> </p>' +
				'<p>Description:'+data.description +
                                '</p><br>Person To DeAssign:<br>'+data.person_name_checkbox  +
				'<br><hr><br>Person To Assign:<br>'+data.person_name_uncheckbox +
				'<br><hr><br><center><a class="btn btn-default" href=javascript:assigned_person("'+data.id+'") > Apply </a> </center></div></div>' ;
				
            tooltip.set({
                    'content.text': content
            })
            .reposition(event).show(event);
				
	},
			
        dayClick: function() { tooltip.hide() },
        eventResizeStart: function() { tooltip.hide() },
        eventDragStart: function() { tooltip.hide() },
        viewDisplay: function() { tooltip.hide() },
	//===============for new event adding=============//
	eventReceive: function(event,ev){
            var title = event.title;
            var start = event.start.format("YYYY-MM-DD HH:mm:SS");	
            //var start = event.start.format("YYYY-MM-DD HH:mm:SS");
            var decision = confirm("Do you really want to do add new?"); 
            if (decision) {
                var if_repeat=confirm("Do you want Repeative Event?"); 
                if(if_repeat)
                {
                    var content_content = '<div class="panel panel-success"><div class="panel-heading"><span class="close">×</span>-'+event.title+'</div>' +						
						'<div class="panel-body">Repeatative Event for Next: <select id="repeat_month"><option value="0" >Current</option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option></select> Month<br>'  +
						'<br><hr><br>Select Day: <input type="checkbox" name="chk_day" value="0">Sun &nbsp;<input type="checkbox" name="chk_day" value="1">Mon &nbsp;<input type="checkbox" name="chk_day" value="2">Tue &nbsp;<input type="checkbox" name="chk_day" value="3">Wed &nbsp;'+
						'<input type="checkbox" name="chk_day" value="4">Thrs &nbsp;<input type="checkbox" name="chk_day" value="5">Fri &nbsp;<input type="checkbox" name="chk_day" value="6">Sat &nbsp;<br>'+
						'<hr><br><center><a class="btn btn-default" href=javascript:new_repeat("'+event.id+'","'+encodeURI(title)+'","'+encodeURI(start)+'","'+zone+'") > Apply </a> </center></div></div>' ;
						
                    document.getElementById('myModal').innerHTML=content_content;
                    var modal = document.getElementById('myModal');
                    modal.style.display = "block";						
                    // Get the <span> element that closes the modal
                    var span = document.getElementsByClassName("close")[0];
                    // When the user clicks on <span> (x), close the modal
                    span.onclick = function() {
                            modal.style.display = "none";
                    }			
		}
		else
                {
                    var account_id=document.getElementById('account_id').value;
                    var common_account_id=document.getElementById('common_id').value;
                    
                    $.ajax({
                            url: 'action_assign_schedule_visit.php',
                            data: 'type=new&title='+title+'&startdate='+start+'&zone='+zone+'&account_id='+account_id+'&cid='+common_account_id,
                            type: 'POST',
                            dataType: 'json',
                            success: function(response){
                                    event.id = response.eventid;
                                    $('#calendar').fullCalendar('updateEvent',event);
                            },
                            error: function(e){
                                    console.log(e.responseText);

                            }
                    });
                    $('#calendar').fullCalendar('updateEvent',event);
                    console.log(event);
                }				
            }
            //reset event
            $('#calendar').fullCalendar('removeEvents');
            getFreshEvents();
	},
			
        //===========Drop Event==============================//
        editable: true,
        eventDrop: function(event, delta, revertFunc) {
            var title = event.title;
            var start = event.start.format();
            var end = (event.end == null) ? start : event.end.format();
            var account_id=document.getElementById('account_id').value;
            var common_account_id=document.getElementById('common_id').value;
            $.ajax({
                    url: 'action_assign_schedule_visit.php',
                    data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id+'&account_id='+account_id+'&cid='+common_account_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                            if(response.status != 'success')		    				
                            revertFunc();
                    },
                    error: function(e){		    			
                            revertFunc();
                            alert('Error processing your request: '+e.responseText);
                    }
            });
        },
			
        //-------------------------------------------------//
        /*eventClick: function(event, jsEvent, view) {
        console.log(event.id);
          var title = prompt('Event Title:', event.title, { buttons: { Ok: true, Cancel: false} });

                        var favorite_person = [];
                        $.each($("input[name='person']:checked"), function(){            
                                favorite_person.push($(this).val());
                        });
                        alert("My favourite person are: " + favorite_person.join(","));

          if (title){
              event.title = title;
              console.log('type=changetitle&title='+title+'&eventid='+event.id);
              $.ajax({
                                url: 'process.php',
                                data: 'type=changetitle&title='+title+'&eventid='+event.id+'&person='+favorite_person.join(", "),
                                type: 'POST',
                                dataType: 'json',
                                success: function(response){	
                                        if(response.status == 'success')			    			
                                        $('#calendar').fullCalendar('updateEvent',event);
                                },
                                error: function(e){
                                        alert('Error processing your request: '+e.responseText);
                                }
                        });
          }
        },*/
        eventResize: function(event, delta, revertFunc) {
            console.log(event);
            var title = event.title;
            var end = event.end.format();
            var start = event.start.format();
            var account_id=document.getElementById('account_id').value;
            var common_account_id=document.getElementById('common_id').value;
            $.ajax({
                url: 'action_assign_schedule_visit.php',
                data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id+'&account_id='+account_id+'&cid='+common_account_id,
                type: 'POST',
                dataType: 'json',
                success: function(response){
                        if(response.status != 'success')		    				
                        revertFunc();
                },
                error: function(e){		    			
                        revertFunc();
                        alert('Error processing your request: '+e.responseText);
                }
            });
        },
        eventDragStop: function (event, jsEvent, ui, view) {
            if (isElemOverDiv()) {
                var con = confirm('Are you sure to delete this event permanently?');
                if(con == true) 
                {
                    var account_id=document.getElementById('account_id').value;
                    var common_account_id=document.getElementById('common_id').value;
                    $.ajax({
                        url: 'action_assign_schedule_visit.php',
                        data: 'type=remove&eventid='+event.id+'&account_id='+account_id+'&cid='+common_account_id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response){
                                console.log(response);
                                if(response.status == 'success'){
                                        $('#calendar').fullCalendar('removeEvents');
                                getFreshEvents();
                        }
                        },
                        error: function(e){	
                                alert('Error processing your request: '+e.responseText);
                        }
                    });
                }   
            }
        }
    });
    
  
    
    function getFreshEvents(){
         var common_account_id=document.getElementById('common_id').value;
        $.ajax({
                url: 'action_assign_schedule_visit.php',
        type: 'POST', // Send post data
        data: 'type=fetch&cid='+common_account_id,
        async: false,
        success: function(s){
                freshevents = s;
                        //alert(freshevents);
        }
        });
        $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
    }
    function isElemOverDiv() {
        var trashEl = jQuery('#trash');

        var ofs = trashEl.offset();

        var x1 = ofs.left;
        var x2 = ofs.left + trashEl.outerWidth(true);
        var y1 = ofs.top;
        var y2 = ofs.top + trashEl.outerHeight(true);

        if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
            currentMousePos.y >= y1 && currentMousePos.y <= y2) {
            return true;
        }
        return false;
    }

});

function assigned_person(id)
{
    if (id)
    {
	var favorite_person = [];					
        $.each($("input[name='assign_person_"+id+"']:checked"), function(){            
            favorite_person.push($(this).val());
        });
        //alert("My favourite person are: " + favorite_person.join(","));

        var unfavorite_person_remain = [];					
        $.each($("input[name='deassign_person_"+id+"']:checked"), function(){            
            unfavorite_person_remain.push($(this).val());
        });
	 //alert("My Unfavourite person are: " + unfavorite_person_remain.join(","));
        var description=document.getElementById('descriptionn_'+id).value;
        var account_id=document.getElementById('account_id').value;
        var common_account_id=document.getElementById('common_id').value;
        $.ajax({
            url: 'action_assign_schedule_visit.php',
            data: 'type=assigned_deassigned_person&description='+description+'&eventid='+id+'&assign_person='+favorite_person.join(",")+'&deassign_person_remain='+unfavorite_person_remain.join(",")+'&account_id='+account_id+'&cid_id='+common_account_id,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                   //alert(response.status)
                    if(response.status == 'success')
                            alert("success");
                            $('#calendar').fullCalendar('removeEvents');						
                            getFreshEventsAssigned();
                            tooltip.hide();
            },
            error: function(e){
                    alert('Error processing your request: '+e.responseText);
            }
        });
    }
}

function new_repeat(id,title,start,zone)
{
    if (id){
    //alert(decodeURI(title));alert(start);alert(zone);
    var repeat_month=document.getElementById('repeat_month').value;
    var dow = [];					
    $.each($("input[name='chk_day']:checked"), function(){            
            dow.push($(this).val());
    });
    //alert("My dow date are: " + dow.join(","));		
    //alert(document.getElementById('').value);
    var account_id=document.getElementById('account_id').value;
    var common_account_id=document.getElementById('common_id').value;
    $.ajax({
            url: 'action_assign_schedule_visit.php',
            data: 'type=new_repeat&title='+title+'&startdate='+start+'&zone='+zone+'&repeat_month='+repeat_month+'&dow='+dow+'&account_id='+account_id+'&cid='+common_account_id,
            type: 'POST',
            dataType: 'json',
            success: function(response){
                    alert("Success");
            },
            error: function(e){
                    console.log(e.responseText);

            }
    });
    $('#calendar').fullCalendar('removeEvents');						
    getFreshEventsAssigned();
    //tooltip.hide();
    var modal = document.getElementById('myModal');
    modal.style.display = "none";
    }
}
	
function getFreshEventsAssigned(){
    var common_account_id=document.getElementById('common_id').value;
    $.ajax({
            url: 'action_assign_schedule_visit.php',
    type: 'POST', // Send post data
    data: 'type=fetch&cid='+common_account_id,
    async: false,
    success: function(s){
            freshevents = s;
                    //alert(freshevents);
    }
    });
    $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
}

</script>
<style>

	body {
		margin-top: 30px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		padding: 20px;
	}
	
	#wrap {
		width: 1200px;
		margin: 0 auto;
	}
		
	#external-events {
		float: left;
		width: 250px;
                height: 600px;                
		padding: 0 10px;
		border: 1px solid #ccc;
		background: #eee;
		text-align: left;
	}
		
	#external-events .fc-event {
		margin: 10px 0;
		cursor: pointer;
	}
		
	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
	}
		
	#external-events p input {
		margin: 0;
		vertical-align: middle;
	}

	#calendar {
		float: right;
		width: 900px;
	}
	
	
	
	/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
	padding-left: 200px; /* Location of the box */
        padding-right: 200px;
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
	
.results tr[visible='false'],
.no-result{
  display:none;
}

.results tr[visible='true']{
  display:table-row;
}

.counter{
  padding:8px; 
  color:#ccc;
}	

</style>
</head>
<body>
   <?php
    include('module_frame_header_newpage.php');
    //echo "<br><br><br>account_id".$account_id;
    $common_id=$_REQUEST['common_id'];
    echo "<input type='hidden' id='common_id' value='$common_id'>";
    echo "<input type='hidden' id='account_id' value='$account_id'>";
   // echo $common_id;
   ?>
    <div id='wrap'>
       
	<div id='external-events'>
            <p>
                Drag Here & Click to Delete <i class="fa fa-trash-o fa-lg" aria-hidden="true" id="trash"></i>

            </p>
            <div class="panel panel-primary">
                <div class="panel-heading">Draggable Station&nbsp;<input type="text" class="search form-control" placeholder="Search Here"></div>
               
                <div class="panel-body">
                    <table class="table table-hover results" width="100%">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th >My Station</th>
                          </tr>
                        </thead>
                        <tbody>
                    <?php
                            //include('config.php');			
                            $query = mysqli_query($con, "SELECT * FROM station_person where status=1 and user_account_id=$common_id");
                            $cnt=1;
                            while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
                            {
                               echo"<tr>  <th scope='row'>".$cnt."</th>";
                                    echo"<th><div class='fc-event'>".$fetch['station_name']." [".$fetch['customer_no']."]</div></th></tr>";				
                            
                                    $cnt++;
                            }			
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr>
			
			<!--
			<div id='person_account'>
				<h4>Person</h4>
				<div class='fc-event'>
				<?php			
					$query = mysqli_query($con, "SELECT * FROM person where status=1");
					//$bin_person=array();
					while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
					{
						if($fetch['gender']==1)
						{
							$gender="Male";
						}
						else
						{
							$gender="Female";
						}
						echo"<input type='checkbox' name='person' value='".$fetch['person_id']."'>".$fetch['person_name']."~".$gender."&nbsp<br>";
						//$bin_person[$fetch['person_id']]=$fetch['person_name']."~".$gender;				
					}	
						//print_r($bin_person);
				?>
				
				</div>
				
				
			</div>-->
	</div>
	<div id='calendar'></div>
        <div style='clear:both'></div>
		
        <!-- The Modal -->
        <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
                  <span class="close">×</span>
                  <p id="contentOnDrag"></p>
            </div>
	</div>
		
	</div>
</body>

<!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->        
        <script src="../../src/thirdparty/ast_bs/dist/js/bootstrap.min.js"></script>        
        <!-- Menu Toggle Script -->
        <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        
       
           
       
      </script>
</html>
