<?PHP
/*
    Contact Form from HTML Form Guide
    This program is free software published under the
    terms of the GNU Lesser General Public License.
    See this page for more info:
    http://www.html-form-guide.com/contact-form/php-contact-form-tutorial.html
*/
//include('phmailer/Mail.php');
//include('phmailer/Mail/mime.php');
require_once("./include/fgcontactform.php");
require_once("./include/captcha-creator.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$formproc = new FGContactForm();
$captcha = new FGCaptchaCreator('scaptcha');

$formproc->EnableCaptcha($captcha);

//1. Add your email address here.
//You can add more than one receipients.
//$formproc->AddRecipient('astaseen83@gmail.com'); //<<---Put your email address here


//2. For better security. Get a random tring from this link: http://tinyurl.com/randstr
// and put it here
$formproc->SetFormRandomKey('n91LqHNvMrpoXte');


if(isset($_POST['submitted']))
{
  if($formproc->ProcessForm())
   {
        $formproc->RedirectToURL("thank-you.php");
   }  
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Contact us</title>
      <link rel="STYLESHEET" type="text/css" href="contact.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <script type='text/javascript' src='scripts/fg_captcha_validator.js'></script>
</head>
<body >

<!-- Form Code Start -->
<form id='contactus' action='<?php echo $formproc->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Contact us</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>
<input type='hidden' name='<?php echo $formproc->GetFormIDInputName(); ?>' value='<?php echo $formproc->GetFormIDInputValue(); ?>'/>
<input type='text'  class='spmhidip' name='<?php echo $formproc->GetSpamTrapInputName(); ?>' />

<div class='short_explanation'>* required fields</div>

<div><span class='error'><?php echo $formproc->GetErrorMessage(); ?></span></div>
<table>
  <tr>
    <td>
         <div class='container'>
            <label for='name' >Your Full Name*: </label><br/>
            <input type='text' name='name' id='name' value='<?php echo $formproc->SafeDisplay('name') ?>' maxlength="50" />
            <span id='contactus_name_errorloc' class='error'></span>
    
        </div>
    </td>
    <td>
       <div class='container'>
          <label for='email' >Email Address*:</label><br/>
          <input type='text' name='email' id='email' value='<?php echo $formproc->SafeDisplay('email') ?>' maxlength="50" /><br/>
          <span id='contactus_email_errorloc' class='error'></span>
      </div>
    </td>
  </tr>
  <tr>
    <td>
       <div class='container'>
          <label for='organization' >Company/Organization*:</label><br/>
          <input type='text' name='organization' id='organization' value='<?php echo $formproc->SafeDisplay('organization') ?>' maxlength="50" /><br/>
          <span id='contactus_organization_errorloc' class='error'></span>
      </div>
      
    </td>
    <td>
       <div class='container'>
          <label for='place' >Location*:</label><br/>
          <input type='text' name='place' id='place' value='<?php echo $formproc->SafeDisplay('place') ?>' maxlength="50" /><br/>
          <span id='contactus_place_errorloc' class='error'></span>
      </div>
      
    </td>
  </tr>
  
  <tr>
 <td colspan=2>
    <div class='container'>
          <label for='ph' >Contact Number*:</label><br/>
          <input type='text' name='ph' id='ph' value='<?php echo $formproc->SafeDisplay('ph') ?>' maxlength="50" /><br/>
          <span id='contactus_ph_errorloc' class='error'></span>
      </div>
  </td>  
  </tr>
  
  <tr>
  <td colspan=2>
    <div class='container'>
          <label for='subject' >Subject*:</label><br/>
          <input type='text' name='subject' id='subject' value='<?php echo $formproc->SafeDisplay('subject') ?>'  /><br/>
          <span id='contactus_subject_errorloc' class='error'></span>
      </div>
  </td>
  </tr>  
  
  <tr>
  <td colspan=2>
    <div class='container'>
    <label for='message' >Message and Query:</label><br/>
    <span id='contactus_message_errorloc' class='error'></span>
    <textarea rows="10" cols="50" name='message' id='message'><?php echo $formproc->SafeDisplay('message') ?></textarea>
    </div>
  </td>
  </tr>
  
  <tr>
    <td colspan=2>
    <div class='container'>
    <div><img alt='Captcha image' src='show-captcha.php?rand=1' id='scaptcha_img' /></div>
    <label for='scaptcha' >Enter the code above here:</label>
    <input type='text' name='scaptcha' id='scaptcha' maxlength="10" /><br/>
    <span id='contactus_scaptcha_errorloc' class='error'></span>
    <div class='short_explanation'>Can't read the image?
    <a href='javascript: refresh_captcha_img();'>Click here to refresh</a>.</div>
      </div>
  
     </td>
  </tr>
  
  <tr>
    <td colspan=2>
       <div class='container'>
          <input type='submit' name='Submit' value='Submit' />
        </div>
    </td>
  </tr>
  
</table>


</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("contactus");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("name","req","Please provide your name");

    frmvalidator.addValidation("email","req","Please provide your email address");

    frmvalidator.addValidation("email","email","Please provide a valid email address");

    frmvalidator.addValidation("place","req","Please provide a valid Place Name");
    
    frmvalidator.addValidation("organization","req","Please provide your Organization Name");

    //frmvalidator.addValidation("subject","req","Please provide Subject");

    frmvalidator.addValidation("message","maxlen=2048","The message is too long!(more than 2KB!)");

    frmvalidator.addValidation("ph","req","Contact Number Required");

    frmvalidator.addValidation("scaptcha","req","Please enter the code in the image above");

    document.forms['contactus'].scaptcha.validator
      = new FG_CaptchaValidator(document.forms['contactus'].scaptcha,
                    document.images['scaptcha_img']);

    function SCaptcha_Validate()
    {
        return document.forms['contactus'].scaptcha.validator.validate();
    }

    frmvalidator.setAddnlValidationFunction("SCaptcha_Validate");

    function refresh_captcha_img()
    {
        var img = document.images['scaptcha_img'];
        img.src = img.src.substring(0,img.src.lastIndexOf("?")) + "?rand="+Math.random()*1000;
    }

// ]]>
</script>


</body>
</html>