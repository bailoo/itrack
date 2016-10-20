<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='assets/css/fullcalendar.css' rel='stylesheet' />
<link href='assets/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='assets/js/moment.min.js'></script>
<script src='assets/js/jquery.min.js'></script>
<script src='assets/js/jquery-ui.min.js'></script>
<script src='assets/js/fullcalendar.min.js'></script>
<script src='assets/js/jquery.qtip.js'></script>
<link href='assets/css/jquery.qtip.css' rel='stylesheet' />


<script>
var tooltip="";
$(document).ready(function() 
{	
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
	
	
	
	var opt = {
			autoOpen: true,
			modal: true,
			width: 250,
			height:350,
			title: 'Details'
			
			
	};

	var zone = "05:30";  //Change this to your timezone

	$.ajax({
		url: 'process.php',
        type: 'POST', // Send post data
        data: 'type=fetch',
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
			navLinks: true, // can click day/week names to navigate views
			slotDuration: '00:30:00',
			
			events: JSON.parse(json_events),
			//events: [{"id":"14","title":"New Event","start":"2015-01-24T16:00:00+04:00","allDay":false}],
			 
			 eventRender: function(event, element,view) {
								
				//alert(event.dowend);				
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
				
				/*
				if (event.allDay === 'true') {
				 event.allDay = true;
				} else {
				 event.allDay = false;
				}*/
			},
			
			utc: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
				//right: 'month,agendaWeek,agendaDay,basicWeek,basicDay'
				//right: 'month,basicWeek,basicDay'
			},
			
			
			
			/*
			views: {
				settimana: {
					type: 'agendaWeek',
					duration: {
						days: 7
					},
					title: 'Apertura',
					columnFormat: 'dddd', // Format the day to only show like 'Monday'
					hiddenDays: [0, 6] // Hide Sunday and Saturday?
				}
			},
			defaultView: 'settimana',*/
			
			
			//new update event take place with in
			eventConstraint: {
				start: moment().format('YYYY-MM-DD'),
				end: '2100-01-01' // hard coded goodness unfortunately
			},
			
			//==============mouse over==========
			
			//eventMouseover : function(data, event, view) {
			eventClick : function(data, event, view) {
				
				//var aaa=JSON.stringify({data});
				//var person_content = document.getElementById('person_account').innerHTML;
				var tlt=data.title;
				//alert(person_content);
				//$("#person_account").dialog(opt).dialog("open");
				if(data.end!=null)
				{
					var end_date_format=" to "+data.end.format("YYYY-MM-DD HH:mm:SS");
				}
				else
				{
					var end_date_format="";
				}
				var content = '<div style="background-color:orange;width:300px;"><h2>'+data.title+'</h2>' + 
                '<p><b>From: '+data.start.format("YYYY-MM-DD HH:mm:SS")+ end_date_format +'</b> </div>' +
				'<br>Person To DeAssign:<br>'+data.person_name_checkbox  +
				'<br><hr><br>Person To Assign:<br>'+data.person_name_uncheckbox +
				'<br><hr><br><center><a href=javascript:assigned_person("'+data.id+'") >[ Apply ]</a> </center>' ;
				
				//$(event.target).attr('title', content);
			
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
				var start = event.start.format("YYYY-MM-DD[T]HH:mm:SS");	
				//var start = event.start.format("YYYY-MM-DD HH:mm:SS");
				var decision = confirm("Do you really want to do add new?"); 
				if (decision) {
					var if_repeat=confirm("Do you want Repeative Event?"); 
					if(if_repeat)
					{
						//alert("Repeat Event");
						
						var content_content = '<div style="background-color:orange;width:300px;"><span class="close">×</span><br><h2>'+event.title+'</h2>' + 
						
						'<br>Repeatative Event for Next: <select id="repeat_month"><option value="0" >Current</option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option></select> Month<br>'  +
						'<br><hr><br>Select Day: <input type="checkbox" name="chk_day" value="0">Sun &nbsp;<input type="checkbox" name="chk_day" value="1">Mon &nbsp;<input type="checkbox" name="chk_day" value="2">Tue &nbsp;<input type="checkbox" name="chk_day" value="3">Wed &nbsp;<br>'+
						'<input type="checkbox" name="chk_day" value="4">Thrs &nbsp;<input type="checkbox" name="chk_day" value="5">Fri &nbsp;<input type="checkbox" name="chk_day" value="6">Sat &nbsp;<br>'+
						'<hr><br><center><a href=javascript:new_repeat("'+event.id+'","'+encodeURI(title)+'","'+start+'","'+zone+'") >[ Add New ]</a> </center>' ;
						
						//$(event.target).attr('title', content);
						//alert(content_content);
						document.getElementById('myModal').innerHTML=content_content;
						var modal = document.getElementById('myModal');
						modal.style.display = "block";						
						// Get the <span> element that closes the modal
						var span = document.getElementsByClassName("close")[0];
						// When the user clicks on <span> (x), close the modal
						span.onclick = function() {
							modal.style.display = "none";
						}
						/*tooltip.set({
							'content.text': content_content
						})
						.reposition(event).show(event);*/
					}
					else
					{
						$.ajax({
							url: 'process.php',
							data: 'type=new&title='+title+'&startdate='+start+'&zone='+zone,
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
		        $.ajax({
					url: 'process.php',
					data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
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
		        $.ajax({
					url: 'process.php',
					data: 'type=resetdate&title='+title+'&start='+start+'&end='+end+'&eventid='+event.id,
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
			    	if(con == true) {
						$.ajax({
				    		url: 'process.php',
				    		data: 'type=remove&eventid='+event.id,
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
		$.ajax({
			url: 'process.php',
	        type: 'POST', // Send post data
	        data: 'type=fetch',
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
		
		if (id){
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
		  $.ajax({
				url: 'process.php',
				data: 'type=assigned_deassigned_person&eventid='+id+'&assign_person='+favorite_person.join(",")+'&deassign_person_remain='+unfavorite_person_remain.join(","),
				type: 'POST',
				dataType: 'json',
				success: function(response){	
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
			$.ajax({
				url: 'process.php',
				data: 'type=new_repeat&title='+title+'&startdate='+start+'&zone='+zone+'&repeat_month='+repeat_month+'&dow='+dow,
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
		$.ajax({
			url: 'process.php',
	        type: 'POST', // Send post data
	        data: 'type=fetch',
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
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		 padding: 50px;
	}

	#trash{
		width:32px;
		height:32px;
		float:left;
		padding-bottom: 15px;
		position: relative;
	}
		
	#wrap {
		width: 1100px;
		margin: 0 auto;
	}
		
	#external-events {
		float: left;
		width: 150px;
		padding: 0 10px;
		border: 1px solid #ccc;
		background: #eee;
		text-align: left;
	}
		
	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
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
	padding-left: 500px; /* Location of the box */
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
	
	

</style>
</head>
<body>
	<div id='wrap'>
		<div id='external-events'>
			<p>
				<img src="assets/img/trashcan.png" id="trash" alt="">
			</p><br>
			<h5>Draggable Station</h5>
			<?php
				include('config.php');			
				$query = mysqli_query($con, "SELECT * FROM station where status=1");
				while($fetch = mysqli_fetch_array($query,MYSQLI_ASSOC))
				{
					echo"<div class='fc-event'>".$fetch['name']." [".$fetch['code']."]</div>";				
				}			
			?>
			
			
			
			<br>
			<br>
			<br>
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
</html>
