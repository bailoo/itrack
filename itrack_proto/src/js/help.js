function display_page(filename,title)
{
  var poststr='title='+title;
 // alert("poststr="+poststr);
  makePOSTRequest(filename,poststr);
}
  
  