<!-- Bootstrap core CSS -->
<link href="src/thirdparty/ast_bs/dist/css/bootstrap.css" rel="stylesheet">
<link href="src/thirdparty/ast_bs/dist/css/simple-sidebar.css" rel="stylesheet">
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="src/thirdparty/ast_bs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="src/thirdparty/ast_bs/dashboard.css" rel="stylesheet">
<link href="src/thirdparty/ast_bs/theme.css" rel="stylesheet">
<!-- Bootstrap theme -->
<link href="src/thirdparty/ast_bs/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<!-- Bootstrap toggle -->
<link href="src/thirdparty/ast_bs/dist/css/bootstrap-toggle.css" rel="stylesheet">
<script src="src/thirdparty/ast_bs/dist/js/bootstrap-toggle.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>-->
<script type="text/javascript" src="src/js/jquery-2.1.1.min.js"></script> 
<script src="src/thirdparty/ast_bs/dist/js/bootstrap-toggle.js"></script>
<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="src/thirdparty/ast_bs/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="src/thirdparty/ast_bs/assets/js/ie-emulation-modes-warning.js"></script>
<!-- Custom Fonts -->
<link href="src/thirdparty/ast_bs/bower_components/font-awesome-4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
 <!-- DataTables CSS -->
<link href="src/thirdparty/ast_bs/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
<!-- DataTables Responsive CSS -->
<link href="src/thirdparty/ast_bs/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
 <script src="src/thirdparty/ast_bs/dist/js/bootstrap.min.js"></script>
<!--<script src="src/thirdparty/ast_bs/dist/js/jquery.js"></script>-->
  <!-- Menu Toggle Script -->
  <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script>
      $("#menu-toggle").click(function (e) {
          e.preventDefault();
          $("#wrapper").toggleClass("toggled");
      });
      function tab_refresh()
        {
            document.getElementById("response").innerHTML="";
        }
        $(function() {
            $( "#keyword_tanker" ).autocomplete({                
              source: 'src/php/keyword_tanker.php'
            });
        });
        /*
      $(document).ready(function() {
       
            // Defining the local dataset
            var cars = ['Audi', 'BMW', 'Bugatti', 'Ferrari', 'Ford', 'Lamborghini', 'Mercedes Benz', 'Porsche', 'Rolls-Royce', 'Volkswagen'];

            // Constructing the suggestion engine
            var cars = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local: cars
            });

            // Initializing the typeahead
            $('.keyword_tanker').typeahead
            (                    
                {
                    hint: true,
                    highlight: true, 
                    minLength: 1 
                },
                {
                    name: 'cars',
                    source: cars,
                    limit: 10 
                }
             );
                
              
            }); 
            */
           

  </script>
<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include('manage_route_vehicle_substation_inherit.php');
	include_once("util_account_detail.php");
	//echo "add##"; 
	$root=$_SESSION['root'];	
	$common_id1=$account_id;
	
	
        
    $current_date = date('m/d/Y');
    echo '<input type="hidden" id="current_date" value="'.$current_date.'"/>';
?>
<link href="src/thirdparty/ast_bs/dist/css/type_ahead.css" rel="stylesheet"> 
    <!--<script src="src/thirdparty/ast_bs/dist/js/typeahead.bundle.js"></script>-->

<div class="row" style="padding-left: 20px" >
    <div id="menu">   
        
        <header align="center"> <font color="#694c27"><i class="fa fa-truck">&nbsp;</i><b>Vehicle Gate Entry</b></font></header> 
               <div class="container">
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#home" onclick="tab_refresh()">By Tanker</a></li>
                  <li><a data-toggle="tab" href="#menu1" onclick="tab_refresh()">Pending Tanker</a></li>                 
                  <!--<li><a data-toggle="tab" href="#menu2" onclick="tab_refresh()">Last 10 Entry</a></li>
                  <li><a data-toggle="tab" href="#menu3" onclick="tab_refresh()">Search History Entry</a></li>-->
                </ul>

                <div class="tab-content" >
                  
                  <div id="home" class="tab-pane fade in active" >                    
                    <p>
                     
                        <div id="by_tanker_div">
                            <center>
                                <table border="0" class="manage_account_interface" cellpadding="0" cellspacing="0" style="padding-top:0px;" >
                                <tr>
                                    <td class="formLabel">Tanker No : </td>
                                    <td >
                                       
                                        <input type="text" id="keyword_tanker" name="keyword_tanker" tabindex3="0" size="46" class="keyword_tanker tt-query" spellcheck="false" style="width:300px"  >
                                      
                                    </td>

                                     <td class="formLabel" rowspan="2">&nbsp;<input type="button" class="btn btn-default btn-sm"  onclick="javascript:show_plant_gate_rawmilk('by_tanker')" value="Get Details"> </td>
                                </tr>


                            </table>
                            </center>
                        </div>
                    </p>
                  </div>
                    
                    
                  <div id="menu1" class="tab-pane fade">
                    <p>
                        <div id="by_login_div_transporter">
                            <center>
                                <table border="0" class="manage_account_interface" cellpadding="0" cellspacing="0" style="padding-top:0px" >
                                <tr>
                                    <td class="formLabel" rowspan="2">&nbsp;<input type="button" class="btn btn-default btn-sm"  onclick="javascript:show_plant_gate_rawmilk('by_pending_tanker')" value="Get Details"> </td>
                                </tr>

                            </table>
                            </center>
                        </div>
                    </p>
                  </div>
                    
                 <!--               
                  <div id="menu2" class="tab-pane fade">
                    <p>
                        <div id="by_orderno_div">
                            <center>
                                <table border="0" class="manage_account_interface" cellpadding="0" cellspacing="0" style="padding-top:0px" >
                                <tr>
                                    <td class="formLabel">OrderNo : </td>
                                    <td >
                                      <input type="text" id="keyword_order" tabindex3="0" size="46" class="form-control input-sm" style="width:300px">

                                    </td>

                                     <td class="formLabel" rowspan="2">&nbsp;<input type="button" class="btn btn-default btn-sm"  onclick="javascript:showorder_trip_complete('by_order')" value="Get Details"> </td>
                                </tr>

                            </table>
                            </center>
                        </div>
                    </p>
                  </div>
                    
                 <div id="menu3" class="tab-pane fade">
                    <p>
                        <div id="by_orderno_div">
                            <center>
                                <table border="0" class="manage_account_interface" cellpadding="0" cellspacing="0" style="padding-top:0px" >
                                <tr>
                                    <td class="formLabel">OrderNo : </td>
                                    <td >
                                      <input type="text" id="keyword_order" tabindex3="0" size="46" class="form-control input-sm" style="width:300px">

                                    </td>

                                     <td class="formLabel" rowspan="2">&nbsp;<input type="button" class="btn btn-default btn-sm"  onclick="javascript:showorder_trip_complete('by_order')" value="Get Details"> </td>
                                </tr>

                            </table>
                            </center>
                        </div>
                    </p>
                  </div>
                  
                 -->
                    
                </div>
              </div>
     
    
              <div id="response"></div>
     
        <hr>
        
         
     
    </div>

</div>
                      



    
   
    
    <div class="loading" style="display:none;" id="loader"><img src="images/loader.gif"> </div>
