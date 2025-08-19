// This code is based on "niceTitles" by Stuart Langridge
// see: http://kryogenix.org/code/browser/nicetitle/
addEvent(window, "load", makeNiceTitles);

var XHTMLNS = "http://www.w3.org/1999/xhtml";
var CURRENT_NICE_TITLE;

function makeNiceTitles() {
    if (!document.createElement || !document.getElementsByTagName) return;
    // add namespace methods to HTML DOM; this makes the script work in both
    // HTML and XML contexts.
    if(!document.createElementNS)
    {
        document.createElementNS = function(ns,elt) {
            return document.createElement(elt);
        }
    }

    if( !document.links )
    {
        document.links = document.getElementsByTagName("a");
    }
    for (var ti=0;ti<document.links.length;ti++) {
        var lnk = document.links[ti];
        if (lnk.title) {
            lnk.setAttribute("nicetitle",lnk.title);
            lnk.removeAttribute("title");
            addEvent(lnk,"mouseover",showNiceTitle);
            addEvent(lnk,"mouseout",hideNiceTitle);
            addEvent(lnk,"click",hideNiceTitle);
        }
    }
}

// Small function to add a new paragraph element with text to an provided xml element.
function ct_addXMLElement(parent,text,cssclass) {
    tn = document.createTextNode(text);
    el = document.createElementNS(XHTMLNS,"p");
    el.className = cssclass;
    el.appendChild(tn);
    parent.appendChild(el);
}


function showNiceTitle(e) {
    if (CURRENT_NICE_TITLE) hideNiceTitle(CURRENT_NICE_TITLE);
    if (!document.getElementsByTagName) return;
    if (window.event && window.event.srcElement) {
        lnk = window.event.srcElement
    } else if (e && e.target) {
        lnk = e.target
    }
    if (!lnk) return;
    // 3 is text node, 1 is element node
    if (lnk.nodeType == 3 || lnk.tagName.toLowerCase!='a') {
        // ascend parents until we hit the link
         lnk = getParent(lnk,'a');
    }
    if (!lnk) return;

    // create new div element to display tooltip
    var d = document.createElementNS(XHTMLNS,"div");
    d.className = "nicetitle";

	// set width
	dw = 350;
    d.style.width = dw + 'px';

 	dh = parseInt((d.offsetHeight)?d.offsetHeight:d.style.pixelHeight); // Does not work!
	dh = 20; // Minimum height, estimate height below.

    // add text as P elements
	ext_remark = lnk.getAttribute("ext_remark");
	if (ext_remark) {
	    ct_addXMLElement(d,lnk.getAttribute('ext_title'),'title');
    	ct_addXMLElement(d,ext_remark,'text');
    	dh += 18 + 12*(ext_remark.length/50);
	}
	int_remark = lnk.getAttribute("int_remark");
	if (int_remark) {
	    ct_addXMLElement(d,lnk.getAttribute('int_title'),'title');
    	ct_addXMLElement(d,int_remark,'text');
    	dh += 18 + 12*(int_remark.length/50);
	}
	out_remark = lnk.getAttribute("out_remark");
	if (out_remark) {
	    ct_addXMLElement(d,lnk.getAttribute('out_title'),'title');
    	ct_addXMLElement(d,int_remark,'text');
    	dh += 18 + 12*(int_remark.length/50);
	}
	nicetitle = lnk.getAttribute("nicetitle");
	if (nicetitle && !(ext_remark) && !(int_remark) && !(out_remark)) {
    	ct_addXMLElement(d,nicetitle,'text');
    	dh += 15;
	}

	// get the position of the link element
    /// mpos = findPosition(lnk);
    /// mx = mpos[0]; my = mpos[1];
	// alternatively get the position of the mouse cursor
    xy = getMousePosition(e);
    mx = parseInt(xy[0]); my = parseInt(xy[1]);
	if (mx==0 && my==0) return;

	// get window viewport size
	xy = getWindowSize();
	vw = parseInt(xy[0]); vh = parseInt(xy[1]);
	if (vw==0 || vh==0) return;

	// scrolling position...
	xy = getScroll(); // Defined in conftool.js!
	xy = xy.split(",");
	sx = parseInt(xy[0]); sy = parseInt(xy[1]);

	// offset to cursor position
	ox = 15; oy=25;

	// calculate and set position of nicetitle
	if ((mx + dw + 20) >= (vw + sx - ox)) {
		mx = (vw + sx) - dw - 30; // as right as possible, consider scrollbar!
	} else {
		mx = mx + ox;
	}

	if ((my + dh + 20) >= (vh + sy - oy)) {
		my = my - dh - oy; // show above cursor
	} else {
		my = my + oy;
	}

    d.style.left = mx + 'px';
    d.style.top  = my + 'px';

    document.getElementsByTagName("body")[0].appendChild(d);

    CURRENT_NICE_TITLE = d;
}

function hideNiceTitle(e) {
    if (!document.getElementsByTagName) return;
    if (CURRENT_NICE_TITLE) {
        document.getElementsByTagName("body")[0].removeChild(CURRENT_NICE_TITLE);
        CURRENT_NICE_TITLE = null;
    }
}

// Add an eventListener to browsers that can do it somehow.
// Originally by Scott Andrew.
function addEvent(obj, evType, fn){
  if (obj.addEventListener){
    obj.addEventListener(evType, fn, false);
    return true;
  } else if (obj.attachEvent){
	var r = obj.attachEvent("on"+evType, fn);
    return r;
  } else {
	return false;
  }
}

// Get next parent element of a specific type
function getParent(el, pTagName) {
	if (el == null) return null;
	else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
		return el;
	else
		return getParent(el.parentNode, pTagName);
}

// get position of mouse pointer
function getMousePosition(event) {
  var x=0, y=0;
  if (typeof(window.event) == 'object') {
    x = window.event.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft);
    y = window.event.clientY + (document.documentElement.scrollTop  || document.body.scrollTop);
  }
  else if (event.clientX) {
    x = event.clientX + window.scrollX;
    y = event.clientY + window.scrollY;
  }
  else {
  	x = event.pageX;
  	y = event.pageY;
 }
 return [x,y];
}

// find position of link element (alternatively used...)
function findPosition( oLink ) {
  if( oLink.offsetParent ) {
    for( var posX = 0, posY = 0; oLink.offsetParent; oLink = oLink.offsetParent ) {
      posX += oLink.offsetLeft;
      posY += oLink.offsetTop;
    }
    return [ posX, posY ];
  } else {
    return [ oLink.x, oLink.y ];
  }
}

// Get width and height of browser window
function getWindowSize(){
 if (document.getElementById){
  if (window.innerWidth) // Most browsers
   return [window.innerWidth, window.innerHeight];
  if (document.documentElement&&document.documentElement.clientWidth) // IE 6 and most DOM browsers
   return [document.documentElement.clientWidth, document.documentElement.clientHeight];
  if (document.body&&document.body.clientWidth) // some IE versions
   return [document.body.clientWidth, document.body.clientHeight];
 }
 return [0,0];
}

