function DragZoomControl(opts_boxStyle, opts_other, opts_callbacks) {
	//alert('win');
  // Holds all information needed globally
  // Not all globals are initialized here

  this.globals = {
    draggingOn: false,
    cornerTopDiv: null,
    cornerRightDiv: null,
    cornerBottomDiv: null,
    cornerLeftDiv: null,
    mapPosition: null,
    outlineDiv: null,
    mapWidth: 0,
    mapHeight: 0,
    mapRatio: 0,
    startX: 0,
    startY: 0,
    borderCorrection: 0
  };

  //box style options
  this.globals.style = {
    opacity: .2,
    fillColor: "#000",
    border: "2px solid blue"
  };

  var style = this.globals.style;
  for (var s in opts_boxStyle) {
    style[s]=opts_boxStyle[s];
  }

  var borderStyleArray = style.border.split(' ');
  style.outlineWidth = parseInt(borderStyleArray[0].replace(/\D/g,''));
  style.outlineColor = borderStyleArray[2];
  style.alphaIE = 'alpha(opacity=' + (style.opacity * 100) + ')';
 
  // map context stack for back button
  this.globals.backStack = [];

  // Other options
  this.globals.options={
    buttonHTML: 'zoom ...',
    buttonStartingStyle: 
      {width: '52px', border: '1px solid black', padding: '2px'},
    buttonStyle: {background: '#FFF'},
    backButtonHTML: 'zoom back',
    backButtonStyle: {background: '#FFF', display: 'none'},
    buttonZoomingHTML: 'Drag a region on the map',
    buttonZoomingStyle: {background: '#FF0'},
    overlayRemoveTime: 6000,
    backButtonEnabled: false,
    stickyZoomEnabled: false,
    restrictedRectangleMap: true
  };
	
  for (var s in opts_other) {
    this.globals.options[s] = opts_other[s]
  }

  // callbacks: buttonclick, dragstart, dragging, dragend, backbuttonclick 
  if (opts_callbacks == null) {
    opts_callbacks = {}
  }
  this.globals.callbacks = opts_callbacks;
}

DragZoomControl.prototype = new GControl();

/**
 * Methods
 */

/**
 * Method called to save the map context before the zoom.
 * Back Button functionality:	
 * @param {text} text string for the back button
 */
DragZoomControl.prototype.saveMapContext = function(text) {
  if (this.globals.options.backButtonEnabled) {
    this.saveBackContext_(text,true);
    this.globals.backButtonDiv.style.display = 'block';
  }	
};

/**
 * Method called to initiate a dragZoom as if the user had clicked the dragZoom button.
 */
DragZoomControl.prototype.initiateZoom = function() {this.buttonclick_()};

/**
 * Method called to initiate a dragZoom back operation as if the user had clicked the dragZoom back button.
 * Back Button functionality:	
 */
DragZoomControl.prototype.initiateZoomBack = function() {if (this.globals.options.backButtonEnabled) this.backbuttonclick_()};	

/**
 * Creates a new button to control gzoom and appends to the button container div.
 * @param {DOM Node} buttonContainerDiv created in main .initialize code
 */
DragZoomControl.prototype.initButton_ = function(buttonContainerDiv) {
  var G = this.globals;
  var buttonDiv = document.createElement('div');
  buttonDiv.innerHTML = G.options.buttonHTML;
  buttonDiv.id = 'gzoom-control';
  DragZoomUtil.style([buttonDiv], {cursor: 'pointer', zIndex:200});
  DragZoomUtil.style([buttonDiv], G.options.buttonStartingStyle);
  DragZoomUtil.style([buttonDiv], G.options.buttonStyle);
  buttonContainerDiv.appendChild(buttonDiv);
  return buttonDiv;
};

/**												
 * Creates a second new button to control backup zoom and appends to the button container div.
 * @param {DOM Node} buttonContainerDiv created in main .initialize code
 */
DragZoomControl.prototype.initBackButton_ = function(buttonContainerDiv) {
  var G = this.globals;
  var backButtonDiv = document.createElement('div');
  backButtonDiv.innerHTML = G.options.backButtonHTML;
  backButtonDiv.id = 'gzoom-back';
  DragZoomUtil.style([backButtonDiv], {cursor: 'pointer', zIndex:200});
  DragZoomUtil.style([backButtonDiv], G.options.buttonStartingStyle);
  DragZoomUtil.style([backButtonDiv], G.options.backButtonStyle);
  buttonContainerDiv.appendChild(backButtonDiv);
  return backButtonDiv;
};

/**
 * Sets button mode to zooming or otherwise, changes CSS & HTML.
 * @param {String} mode Either "zooming" or not.
 */
DragZoomControl.prototype.setButtonMode_ = function(mode){
  var G = this.globals;
  if (mode == 'zooming') {
    G.buttonDiv.innerHTML = G.options.buttonZoomingHTML;
    DragZoomUtil.style([G.buttonDiv], G.options.buttonStartingStyle);
    DragZoomUtil.style([G.buttonDiv], G.options.buttonZoomingStyle);
  } else {
    G.buttonDiv.innerHTML = G.options.buttonHTML;
    DragZoomUtil.style([G.buttonDiv], G.options.buttonStartingStyle);
    DragZoomUtil.style([G.buttonDiv], G.options.buttonStyle);
  }
};

/**
 * Is called by GMap2's addOverlay method. Creates the zoom control
 * divs and appends to the map div.
 * @param {GMap2} map The map that has had this DragZoomControl added to it.
 * @return {DOM Object} Div that holds the gzoomcontrol button
 */ 
DragZoomControl.prototype.initialize = function(map) {
  var G = this.globals;
  var me = this;
  var mapDiv = map.getContainer();
 
  // Create div for both buttons	
    var buttonContainerDiv = document.createElement("div");	
    DragZoomUtil.style([buttonContainerDiv], {cursor: 'pointer', zIndex: 150});

  // create and init the zoom button
    //DOM:button
    var buttonDiv = this.initButton_(buttonContainerDiv);

  // create and init the back button				
    //DOM:button
    var backButtonDiv = this.initBackButton_(buttonContainerDiv);
  
  // Add the two buttons to the map 					
    mapDiv.appendChild(buttonContainerDiv);
 
  //DOM:map covers
    var zoomDiv = document.createElement("div");
    zoomDiv.id ='gzoom-map-cover';
    zoomDiv.innerHTML ='<div id="gzoom-outline" style="position:absolute;display:none;"></div><div id="gzoom-cornerTopDiv" style="position:absolute;display:none;"></div><div id="gzoom-cornerLeftDiv" style="position:absolute;display:none;"></div><div id="gzoom-cornerRightDiv" style="position:absolute;display:none;"></div><div id="gzoom-cornerBottomDiv" style="position:absolute;display:none;"></div>';
    DragZoomUtil.style([zoomDiv], {position: 'absolute', display: 'none', overflow: 'hidden', cursor: 'crosshair', zIndex: 101});
    mapDiv.appendChild(zoomDiv);
  
  // add event listeners
    GEvent.addDomListener(buttonDiv, 'click', function(e) {
      me.buttonclick_(e);
    });
    GEvent.addDomListener(backButtonDiv, 'click', function(e) {
      me.backbuttonclick_(e);
    });
    GEvent.addDomListener(zoomDiv, 'mousedown', function(e) {
      me.coverMousedown_(e);
    });
    GEvent.addDomListener(document, 'mousemove', function(e) {
      me.drag_(e);
    });
    GEvent.addDomListener(document, 'mouseup', function(e) {
      me.mouseup_(e);
    });
  
  // get globals
    G.mapPosition = DragZoomUtil.getElementPosition(mapDiv);
    G.outlineDiv = DragZoomUtil.gE("gzoom-outline");	
    G.buttonDiv = DragZoomUtil.gE("gzoom-control");
    G.backButtonDiv = DragZoomUtil.gE("gzoom-back");
    G.mapCover = DragZoomUtil.gE("gzoom-map-cover");
    G.cornerTopDiv = DragZoomUtil.gE("gzoom-cornerTopDiv");
    G.cornerRightDiv = DragZoomUtil.gE("gzoom-cornerRightDiv");
    G.cornerBottomDiv = DragZoomUtil.gE("gzoom-cornerBottomDiv");
    G.cornerLeftDiv = DragZoomUtil.gE("gzoom-cornerLeftDiv");
    G.map = map;
  
    G.borderCorrection = G.style.outlineWidth * 2;	
    this.setDimensions_();
  
  //styles
    this.initStyles_();

  // disable text selection on map cover
    G.mapCover.onselectstart = function() {return false}; 
    
  return buttonContainerDiv;
};

/**
 * Required by GMaps API for controls. 
 * @return {GControlPosition} Default location for control
 */
DragZoomControl.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(3, 120));
};

/**
 * Function called when mousedown event is captured.
 * @param {Object} e 
 */
DragZoomControl.prototype.coverMousedown_ = function(e){
  var G = this.globals;
  var pos = this.getRelPos_(e);
  G.startX = pos.left;
  G.startY = pos.top;
  
  DragZoomUtil.style([G.mapCover], {background: 'transparent', opacity: 1, filter: 'alpha(opacity=100)'});
  DragZoomUtil.style([G.outlineDiv], {left: G.startX + 'px', top: G.startY + 'px', display: 'block', width: '1px', height: '1px'});
  G.draggingOn = true;

  G.cornerTopDiv.style.top = (G.startY - G.mapHeight) + 'px';
  G.cornerTopDiv.style.display ='block';
  G.cornerLeftDiv.style.left = (G.startX - G.mapWidth) +'px';
  G.cornerLeftDiv.style.top = G.startY + 'px';
  G.cornerLeftDiv.style.display = 'block';

  G.cornerRightDiv.style.left = G.startX + 'px';
  G.cornerRightDiv.style.top = G.startY + 'px';
  G.cornerRightDiv.style.display = 'block';
  G.cornerBottomDiv.style.left = G.startX + 'px';
  G.cornerBottomDiv.style.top = G.startY + 'px';
  G.cornerBottomDiv.style.width = '0px';
  G.cornerBottomDiv.style.display = 'block';

  // invoke the callback if provided
  if (G.callbacks.dragstart != null) {
    G.callbacks.dragstart(G.startX, G.startY);
  }

  return false;
};

/**
 * Function called when drag event is captured
 * @param {Object} e 
 */
DragZoomControl.prototype.drag_ = function(e){
  var G = this.globals;
  if(G.draggingOn) {
    var pos = this.getRelPos_(e);
    rect = this.getRectangle_(G.startX, G.startY, pos, G.mapRatio);

    if (rect.left) {
      addX = -rect.width;			
    } else { 
      addX = 0;
    }

    if (rect.top) {
      addY = -rect.height;
    } else {
      addY = 0;
    }

    DragZoomUtil.style([G.outlineDiv], {left: G.startX + addX + 'px', top: G.startY + addY + 'px', display: 'block', width: '1px', height: '1px'});	
	
    G.outlineDiv.style.width = rect.width + "px";
    G.outlineDiv.style.height = rect.height + "px";

    G.cornerTopDiv.style.height = ((G.startY + addY) - (G.startY - G.mapHeight)) + 'px';
    G.cornerLeftDiv.style.top = (G.startY + addY) + 'px';
    G.cornerLeftDiv.style.width = ((G.startX + addX) - (G.startX - G.mapWidth)) + 'px';
    G.cornerRightDiv.style.top = G.cornerLeftDiv.style.top;
    G.cornerRightDiv.style.left = (G.startX + addX + rect.width + G.borderCorrection) + 'px';
    G.cornerBottomDiv.style.top = (G.startY + addY + rect.height + G.borderCorrection) + 'px';
    G.cornerBottomDiv.style.left = (G.startX - G.mapWidth + ((G.startX + addX) - (G.startX - G.mapWidth))) + 'px';
    G.cornerBottomDiv.style.width = (rect.width + G.borderCorrection) + 'px';
		
    // invoke callback if provided
    if (G.callbacks.dragging != null) {
      G.callbacks.dragging(G.startX, G.startY, rect.endX, rect.endY)
    }
		
    return false;
  }  
};

/** 
 * Function called when mouseup event is captured
 * @param {Event} e
 */
DragZoomControl.prototype.mouseup_ = function(e){
  var G = this.globals;
  if (G.draggingOn) {
    var pos = this.getRelPos_(e);
    G.draggingOn = false;
    
    var rect = this.getRectangle_(G.startX, G.startY, pos, G.mapRatio);

    if (rect.left) rect.endX = rect.startX - rect.width;
    if (rect.top) rect.endY = rect.startY - rect.height;
	
    this.resetDragZoom_();

    var nwpx = new GPoint(rect.startX, rect.startY);
    var nepx = new GPoint(rect.endX, rect.startY);
    var sepx = new GPoint(rect.endX, rect.endY);
    var swpx = new GPoint(rect.startX, rect.endY);
    var nw = G.map.fromContainerPixelToLatLng(nwpx); 
    var ne = G.map.fromContainerPixelToLatLng(nepx); 
    var se = G.map.fromContainerPixelToLatLng(sepx); 
    var sw = G.map.fromContainerPixelToLatLng(swpx); 

    var zoomAreaPoly = new GPolyline([nw, ne, se, sw, nw], G.style.outlineColor, G.style.outlineWidth + 1,.4);

    try{
      G.map.addOverlay(zoomAreaPoly);
      setTimeout (function() {G.map.removeOverlay(zoomAreaPoly)}, G.options.overlayRemoveTime);  
    }catch(e) {}
    polyBounds = zoomAreaPoly.getBounds();
    var ne = polyBounds.getNorthEast();
    var sw = polyBounds.getSouthWest();
    var se = new GLatLng(sw.lat(), ne.lng());
    var nw = new GLatLng(ne.lat(), sw.lng());
    drg_zoomLevel = G.map.getBoundsZoomLevel(polyBounds);
    drg_center = polyBounds.getCenter();
    G.map.setCenter(drg_center, drg_zoomLevel); // center & zoomLevel problem with IExplorer :-(

    // invoke callback if provided
    if (G.callbacks.dragend != null) {
      G.callbacks.dragend(nw, ne, se, sw, nwpx, nepx, sepx, swpx);
    }

    //re-init if sticky
    if (G.options.stickyZoomEnabled) {
      //GLog.write("stickyZoomEnabled, re-initting");
      this.initCover_();
      if (G.options.backButtonEnabled) this.saveBackContext_(G.options.backButtonHTML,false); // save the map context for back button
      G.backButtonDiv.style.display='none';
    }
  }
};

/**
 * Set the cover sizes according to the size of the map
 */
DragZoomControl.prototype.setDimensions_ = function() {
  var G = this.globals;
  var mapSize = G.map.getSize();
  G.mapWidth  = mapSize.width;
  G.mapHeight = mapSize.height;
  G.mapRatio  = G.mapHeight / G.mapWidth;
  // set left:0px in next <div>s in case we inherit text-align:center from map <div> in IE.
  DragZoomUtil.style([G.mapCover, G.cornerTopDiv, G.cornerRightDiv, G.cornerBottomDiv, G.cornerLeftDiv], 
    {left: '0px',width: G.mapWidth + 'px', height: G.mapHeight +'px'});
};

/**
 * Initializes styles based on global parameters
 */
DragZoomControl.prototype.initStyles_ = function(){
  var G = this.globals;
  DragZoomUtil.style([G.mapCover, G.cornerTopDiv, G.cornerRightDiv, G.cornerBottomDiv, G.cornerLeftDiv], 
    {filter: G.style.alphaIE, opacity: G.style.opacity, background:G.style.fillColor});
  G.outlineDiv.style.border = G.style.border;  
};

/**
 * Function called when the zoom button's click event is captured.
 */
DragZoomControl.prototype.buttonclick_ = function(){
  var G = this.globals;	
  G.backButtonDiv.style.display='none';
  if (G.mapCover.style.display == 'block') { // reset if clicked before dragging 
    this.resetDragZoom_();
    if (G.options.backButtonEnabled) {  
      this.restoreBackContext_();  // pop the backStack on a button reset
      if (G.backStack.length==0) G.backButtonDiv.style.display='none';
    }
  } else {
    this.initCover_();
    if ( G.options.backButtonEnabled ) this.saveBackContext_(G.options.backButtonHTML,false); // save the map context for back button
  }
};

/**
 * Back Button functionality:	
 * Function called when the back button's click event is captured.
 * calls the function to set the map context back to where it was before the zoom.
 */
DragZoomControl.prototype.backbuttonclick_ = function(){
  var G = this.globals;	
  if (G.options.backButtonEnabled && G.backStack.length > 0) {
    this.restoreBackContext_();
    // invoke the callback if provided
    if (G.callbacks['backbuttonclick'] != null) {
      G.callbacks.backbuttonclick(G.methodCall);
    }
  }
};

/** 
 * Back Button functionality:	
 * Saves the map context and pushes it on the backStack for later use by the back button
 */
DragZoomControl.prototype.saveBackContext_ = function(text,methodCall) {
  var G = this.globals;
  var backFrame = {};
  backFrame["center"] = G.map.getCenter();
  backFrame["zoom"] = G.map.getZoom();
  backFrame["maptype"] = G.map.getCurrentMapType();
  backFrame["text"] = G.backButtonDiv.innerHTML; // this saves the previous button text
  backFrame["methodCall"] = methodCall; //This determines if it was an internal or method call
  G.backStack.push(backFrame);
  G.backButtonDiv.innerHTML = text;
  // Back Button is turned on in resetDragZoom_()
};

/** 
 * Back Button functionality:	
 * Pops the previous map context off of the backStack and restores the map to that context
 */
DragZoomControl.prototype.restoreBackContext_ = function() {
  var G = this.globals;
  var backFrame = G.backStack.pop();
  G.map.setCenter(backFrame["center"],backFrame["zoom"],backFrame["maptype"]);
  G.backButtonDiv.innerHTML = backFrame["text"];
  G.methodCall = backFrame["methodCall"];
  if (G.backStack.length==0) G.backButtonDiv.style.display = 'none'; // if we're at the top of the stack, hide the back botton
};

/**
 * Shows the cover over the map
 */
DragZoomControl.prototype.initCover_ = function(){
  var G = this.globals;
  G.mapPosition = DragZoomUtil.getElementPosition(G.map.getContainer());
  this.setDimensions_();
  this.setButtonMode_('zooming');
  DragZoomUtil.style([G.mapCover], {display: 'block', background: G.style.fillColor});
  DragZoomUtil.style([G.outlineDiv], {width: '0px', height: '0px'});

  //invoke callback if provided
  if(G.callbacks['buttonclick'] != null){
    G.callbacks.buttonclick();
  }
};

/**
 * Gets position of the mouse relative to the map
 * @param {Object} e
 */
DragZoomControl.prototype.getRelPos_ = function(e) {
  var pos = DragZoomUtil.getMousePosition(e);
  var G = this.globals;
  return {top: (pos.top - G.mapPosition.top), 
          left: (pos.left - G.mapPosition.left)};
};

/**
 * Figures out the rectangle the user's trying to draw
 * @param {Number} startX 
 * @param {Number} startY
 * @param {Object} pos
 * @param {Number} ratio
 * @return {Object} Describes the rectangle
 */
DragZoomControl.prototype.getRectangle_ = function(startX, startY, pos, ratio){
  var left = false;
  var top = false;
  var dX = pos.left - startX;
  var dY = pos.top - startY;	
  if (dX < 0) {
    dX = dX * -1;
    left = true;
  }
  if (dY < 0) {
    dY = dY * -1;
    top = true;
  }

  if (this.globals.options.restrictedRectangleMap == true) {

	  delta = dX > dY ? dX : dY;

	  return {
	    startX: startX,
	    startY: startY,
		
		endX: startX + dX,
	    endY: startY + dY,
	    //endX: startX + delta,
	    //endY: startY + parseInt(delta * ratio),

		width: dX,
	    height: dY,

	    //width: delta,
	    //height: parseInt(delta * ratio),
	    left:left,
	    top:top
	  }
	  
  } else {

    return {
	    startX: startX,
	    startY: startY,
	    endX: startX + dX,
	    endY: startY + dY,
	    width: dX,
	    height: dY,
	    left:left,
	    top:top

		//endX: startX + dX,
		//endY: startY + dY,
		//width: dX,
		//height: dY
	  }
  }
  
};

/** 
 * Resets CSS and button display when drag zoom done
 */
DragZoomControl.prototype.resetDragZoom_ = function() {
  var G = this.globals;
  DragZoomUtil.style([G.mapCover, G.cornerTopDiv, G.cornerRightDiv, G.cornerBottomDiv, G.cornerLeftDiv], 
    {display: 'none', opacity: G.style.opacity, filter: G.style.alphaIE});
  G.outlineDiv.style.display = 'none';	
  this.setButtonMode_('normal');
  if (G.options.backButtonEnabled  && (G.backStack.length > 0)) G.backButtonDiv.style.display = 'block'; // show the back button
};

/* utility functions in DragZoomUtil.namespace */
var DragZoomUtil={};

/**
 * Alias function for getting element by id
 * @param {String} sId
 * @return {Object} DOM object with sId id
 */
DragZoomUtil.gE = function(sId) {
  return document.getElementById(sId);
}

/**
 * A general-purpose function to get the absolute position
 * of the mouse.
 * @param {Object} e  Mouse event
 * @return {Object} Describes position
 */
DragZoomUtil.getMousePosition = function(e) {
  var posX = 0;
  var posY = 0;
  if (!e) var e = window.event;
  if (e.pageX || e.pageY) {
    posX = e.pageX;
    posY = e.pageY;
  } else if (e.clientX || e.clientY){
    posX = e.clientX + 
      (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
    posY = e.clientY + 
      (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
  }	
  return {left: posX, top: posY};  
};

/**
 * Gets position of element
 * @param {Object} element
 * @return {Object} Describes position
 */
DragZoomUtil.getElementPosition = function(element) {
  var leftPos = element.offsetLeft;          // initialize var to store calculations
  var topPos = element.offsetTop;            // initialize var to store calculations
  var parElement = element.offsetParent;     // identify first offset parent element  
  while (parElement != null ) {                // move up through element hierarchy
    leftPos += parElement.offsetLeft;      // appending left offset of each parent
    topPos += parElement.offsetTop;  
    parElement = parElement.offsetParent;  // until no more offset parents exist
  }
  return {left: leftPos, top: topPos};
};

/**
 * Applies styles to DOM objects 
 * @param {String/Object} elements Either comma-delimited list of ids 
 *   or an array of DOM objects
 * @param {Object} styles Hash of styles to be applied
 */
DragZoomUtil.style = function(elements, styles){
  if (typeof(elements) == 'string') {
    elements = DragZoomUtil.getManyElements(elements);
  }
  for (var i = 0; i < elements.length; i++){
    for (var s in styles) { 
      elements[i].style[s] = styles[s];
    }
  }
};

/**
 * Gets DOM elements array according to list of IDs
 * @param {String} elementsString Comma-delimited list of IDs
 * @return {Array} Array of DOM elements corresponding to s
 */
DragZoomUtil.getManyElements = function(idsString){		
  var idsArray = idsString.split(',');
  var elements = [];
  for (var i = 0; i < idsArray.length; i++){
    elements[elements.length] = DragZoomUtil.gE(idsArray[i])
  };
  return elements;
};