// JavaScript Document
 
// Validation: MANAGE MODULE - main_add_account
/*function check_uncheck_features_button(fobj)
{
  if(fobj.all.value == "Select All")
  {
    set_all_features(fobj, true);	    
    fobj.all.value = "Select None";
  }
  else if(fobj.all.value == "Select None")
  {
    set_all_features(fobj, false);	    
    fobj.all.value = "Select All";
  }
  return true;
}

function set_all_features(fobj, fvalue)
{
  var fcount = document.getElementById("fcount").value;
  var fname=new Array();
  for (var i=1; i<=fcount; i++)
  {
    fname[i] = document.getElementById("fname"+i).value;
    if (document.getElementById(fname[i]).type=="checkbox") document.getElementById(fname[i]).checked=fvalue;
  }
}*/

function check_uncheck_features_button(fobj)
{
  if(fobj.all.value == "Select All")
  {
    set_all_features(fobj, true);	    
    fobj.all.value = "Select None";
  }
  else if(fobj.all.value == "Select None")
  {
    set_all_features(fobj, false);	    
    fobj.all.value = "Select All";
  }
  return true;
}

function set_all_features(fobj, fvalue)
{
  var fcount = document.getElementById("fcount").value;
  var fname=new Array();
  for (var i=1; i<=fcount; i++)
  {
    fname[i] = document.getElementById("fname"+i).value;
    if (document.getElementById(fname[i]).type=="checkbox") document.getElementById(fname[i]).checked=fvalue;
  }
}

function desable_all_features(fobj, fvalue)
{
  var fcount = document.getElementById("fcount").value;
  var fname=new Array();
  for (var i=1; i<=fcount; i++)
  {
    fname[i] = document.getElementById("fname"+i).value;
    if (document.getElementById(fname[i]).type=="checkbox") document.getElementById(fname[i]).disabled=fvalue;
  }
  fobj.all.disabled=fvalue;
}

function set_features_from_select(fobj)
{
  var fcount = document.getElementById("fcount").value;
  var fname = new Array();
  var sel_perm = document.getElementById("parm_name_" + document.getElementById("admin_perm").value).value;
  var sperm = sel_perm.split(":");
  for (var i=1; i<=fcount; i++)
  {
    fname[i] = document.getElementById("fname"+i).value;
    if (document.getElementById(fname[i]).type=="checkbox") document.getElementById(fname[i]).checked= sperm[i-1]=="1"?true:false;
  }
}

function show_admin_perm(option,fobj)
{
  if(option==1)
  {
    fobj.admin_perm.style.display="none";
    set_all_features(fobj, true);
    fobj.all.value = "Select None";
    desable_all_features(fobj,false);
  }
  else
  {
    fobj.admin_perm.style.display="";
    set_features_from_select(fobj);
    desable_all_features(fobj,true);
  }
}

/*
function show_dv_gid_ac_id(option,fobj)
{
  if(option==1)
  {
    fobj.dv_gid_ac_id.style.display="none";
  }
  else
  {
    fobj.dv_gid_ac_id.style.display="";
  }
}
*/

function set_company_type(option,fobj)
{
  if(option==0)
  {
    document.getElementById("company_type_same").checked=true;
    document.getElementById("company_type_same").disabled=true;
    document.getElementById("company_type_new").disabled=true;
  }
  else
  {
    document.getElementById("company_type_same").checked=true;
    document.getElementById("company_type_same").disabled=false;
    document.getElementById("company_type_new").disabled=false;
  }
}


/* 
 /// MANAGE MODULE - main_add_account
function check_uncheck_features(fobj)
{
  var fcount = document.getElementById("fcount").value;
  var fname=new Array();
  for (i=1; i<=fcount; i++)
  {
    fname[i] = document.getElementById("fname"+i).value;
  }
  if(fobj.all.checked)
  {	    
    for(var i=1;i<=fcount;i++)
    {
      // document.getElementById(fname[i]).checked=true;
      if (document.getElementById(fname[i]).type=="checkbox") document.getElementById(fname[i]).checked=true;
    }             
  }
  else if(fobj.all.checked==false)
  {
    for(var i=1;i<=fcount;i++)
    {
      // document.getElementById(fname[i]).checked=false;
      if (document.getElementById(fname[i]).type=="checkbox") document.getElementById(fname[i]).checked=false;
    }
  }
}
*/
   
function test_abc(fobj)
{
  return true;
}

function get_radio_button_value(rb)
{
  for (i=0; i<rb.length; i++)
  {
    if (rb[i].checked==true)
    {
      var rb_value = rb[i].value;
    }
  }
  return rb_value;
}   

	