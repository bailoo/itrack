var toggle_flag = 0;
var cache_vlist;

function slideExample2(elementId, headerElement)
{
   cache_vlist = document.getElementById('examplePanel2').innerHTML;
   var element = document.getElementById(elementId);
   //if(element.up == null || element.down)
   if(toggle_flag == 0)
   {
      toggle_flag =1;
      //animate(elementId, 20, 0, 0, 600, 250, null);
      //element.up = true;
      //element.down = false;     
      headerElement.style.width = '10px';
      headerElement.innerHTML = '<img src="./images/icon/slide_arrow2.png">';      
      //alert("two"+document.getElementById('map_col').style.width+" ,"+document.getElementById('examplePanel2').style.width);
      
      document.getElementById('examplePanel2').style.display = "none";
      //document.getElementById('exampleHeader2').style.width = "8%";
      document.getElementById('examplePanel2').style.width = "0%";           
      document.getElementById('map_col').style.width = "99%";                      
   }
   else if(toggle_flag == 1)
   {
      toggle_flag =0;
      //animate(elementId, 20, 0, 130, 600, 250, null);
      //element.down = true;
      //element.up = false;

      headerElement.style.width = '7px';
      headerElement.innerHTML = '<img src="./images/icon/slide_arrow1.png">';      
      //alert("one"+document.getElementById('map_col').style.width+" ,"+document.getElementById('examplePanel2').style.width);
      
      document.getElementById('examplePanel2').style.display = "";
      //document.getElementById('exampleHeader2').style.width = "8%";
      document.getElementById('examplePanel2').style.width = "95%"; 
      document.getElementById('examplePanel2').innerHTML = cache_vlist;
      //alert("list="+document.getElementById('examplePanel2').innerHTML);
      document.getElementById('map_col').style.width = "85%"; 
   }
}

//This code was created by the fine folks at Switch On The Code - http://blog.paranoidferret.com
//This code can be used for any purpose
function animate(elementID, newLeft, newTop, newWidth,newHeight, time, callback)
{
  var el = document.getElementById(elementID);
  if(el == null)
    return;
 
  var cLeft = parseInt(el.style.left);
  var cTop = parseInt(el.style.top);
  var cWidth = parseInt(el.style.width);
  var cHeight = parseInt(el.style.height);
 
  var totalFrames = 1;
  if(time> 0)
    totalFrames = time/40;

  var fLeft = newLeft - cLeft;
  if(fLeft != 0)
    fLeft /= totalFrames;
 
  var fTop = newTop - cTop;
  if(fTop != 0)
    fTop /= totalFrames;
 
  var fWidth = newWidth - cWidth;
  if(fWidth != 0)
    fWidth /= totalFrames;
 
  var fHeight = newHeight - cHeight;
  if(fHeight != 0)
    fHeight /= totalFrames;
   
  doFrame(elementID, cLeft, newLeft, fLeft,
      cTop, newTop, fTop, cWidth, newWidth, fWidth,
      cHeight, newHeight, fHeight, callback);
}

function doFrame(eID, cLeft, nLeft, fLeft,
      cTop, nTop, fTop, cWidth, nWidth, fWidth,
      cHeight, nHeight, fHeight, callback)
{
   var el = document.getElementById(eID);
   if(el == null)
     return;

  cLeft = moveSingleVal(cLeft, nLeft, fLeft);
  cTop = moveSingleVal(cTop, nTop, fTop);
  cWidth = moveSingleVal(cWidth, nWidth, fWidth);
  cHeight = moveSingleVal(cHeight, nHeight, fHeight);

  el.style.left = Math.round(cLeft) + 'px';
  el.style.top = Math.round(cTop) + 'px';
  el.style.width = Math.round(cWidth) + 'px';
  el.style.height = Math.round(cHeight) + 'px';
 
  if(cLeft == nLeft && cTop == nTop && cHeight == nHeight
    && cWidth == nWidth)
  {
    if(callback != null)
      callback();
    return;
  }
   
  setTimeout( 'doFrame("'+eID+'",'+cLeft+','+nLeft+','+fLeft+','
    +cTop+','+nTop+','+fTop+','+cWidth+','+nWidth+','+fWidth+','
    +cHeight+','+nHeight+','+fHeight+','+callback+')', 40);
}

function moveSingleVal(currentVal, finalVal, frameAmt)
{
  if(frameAmt == 0 || currentVal == finalVal)
    return finalVal;
 
  currentVal += frameAmt;
  if((frameAmt> 0 && currentVal>= finalVal)
    || (frameAmt <0 && currentVal <= finalVal))
  {
    return finalVal;
  }
  return currentVal;
}