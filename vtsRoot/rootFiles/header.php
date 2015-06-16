<?php
echo'
<link rel="stylesheet" type="text/css" media="all" href="webroot/css/common.css">

<link rel="stylesheet" type="text/css" media="all" href="webroot/css/menucss.css">

<link href="webroot/css/creative/themes/2/js-image-slider.css" rel="stylesheet" type="text/css" />
<script src="webroot/css/creative/themes/2/js-image-slider.js" type="text/javascript"></script>
<link href="webroot/css/creative/generic.css" rel="stylesheet" type="text/css" />

<!-- jQuery library (served from Google) -->
<script src="webroot/jquery.bxslider/jquery.min.js"></script>
<!-- bxSlider Javascript file -->
<script src="webroot/jquery.bxslider/jquery.bxslider.min.js"></script>
<!-- bxSlider CSS file -->
<link href="webroot/jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />



<link rel="stylesheet" type="text/css" media="all" href="webroot/css/css_allpagecontent1.css">
<link rel="stylesheet" type="text/css" media="all" href="webroot/css/css_allpagecontent.css">
<link rel="stylesheet" type="text/css" media="all" href="webroot/css/css_topheader.css">
<link rel="stylesheet" type="text/css" media="all" href="webroot/css/menu.css">
<style type="text/css">
      
      /* attributes of the container element of textbox */
      .loginboxdiv{
      margin:0px;
      height:21px;
      width:146px;
      background:url(webroot/images/login_bg.gif) no-repeat bottom;
      }
      /* attributes of the input box */
      .loginbox
      {
      background:none;
      border:none;
      width:134px;
      height:15px;
      margin:0;
      padding: 2px 7px 0px 7px;
      font-family:Verdana, Arial, Helvetica, sans-serif;
      font-size:11px;
      }

      
  </style>
  
  <script type="text/javascript">


 $(document).ready(function() { 
           $(document).ready(function(){
            $(".slider1").bxSlider({
              slideWidth: 300,
              minSlides: 2,
              maxSlides: 3,
              slideMargin: 10,
              auto: true,
              autoControls: true
              
            });
          });
    });
    

</script>
'; ?>