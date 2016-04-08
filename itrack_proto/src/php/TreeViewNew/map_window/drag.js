function beginDrag(elementToDrag, event){
	var deltaX = event.clientX - parseInt(elementToDrag.style.left);
	var deltaY = event.clientY - parseInt(elementToDrag.style.top);
	if (document.addEventListener){
		document.addEventListener("mousemove", moveHandler, true);
		document.addEventListener("mouseup", upHandler, true);
	}
	else if (document.attachEvent){
		document.attachEvent("onmousemove", moveHandler);
		document.attachEvent("onmouseup", upHandler);
	}
	else {
		var oldmovehandler = document.onmousemove;
		var olduphandler = document.onmouseup;
		document.onmousemove = moveHandler;
		document.onmouseup = upHandler;
	}
	if (event.stopPropagation) event.stopPropagation();
	else event.cancelBubble = true;
	if (event.preventDefault) event.preventDefault();
	else event.returnValue = false;
	function moveHandler(e){
		if (!e) e = window.event;
		elementToDrag.style.left = (e.clientX - deltaX) + "px";
		elementToDrag.style.top = (e.clientY - deltaY) + "px";
		if (e.stopPropagation) e.stopPropagation();
		else e.cancelBubble = true;
	}
	function upHandler(e){
		if (!e) e = window.event;
		if (document.removeEventListener){
			document.removeEventListener("mouseup", upHandler, true);
			document.removeEventListener("mousemove", moveHandler, true);
		}
		else if (document.detachEvent){
			document.detachEvent("onmouseup", upHandler);
			document.detachEvent("onmousemove", moveHandler);
		}
		else {
			document.onmouseup = olduphandler;
			document.onmousemove = oldmovehandler;
		}
		if (e.stopPropagation) e.stopPropagation();
		else e.cancelBubble = true;
	}
}