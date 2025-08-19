// ConfTool JavaScript core functions.

// Give each window a unique name.
if ((!window.name) || (window.name=='_blank')) { var d=new Date(); window.name = 'CT'+d.getTime(); }

// Code against clickjacking
if (top.location!=location) top.location=self.location;

// Browser check
var agt=navigator.userAgent.toLowerCase();
var is_major = parseInt(navigator.appVersion);
var is_minor = parseFloat(navigator.appVersion);

var is_nav   = ((agt.indexOf('mozilla')!=-1) && (agt.indexOf('spoofer')==-1) && (agt.indexOf('compatible') == -1) && (agt.indexOf('opera')==-1) && (agt.indexOf('webtv')==-1) && (agt.indexOf('hotjava')==-1) && (agt.indexOf('safari')==-1));
var is_gecko = ((agt.indexOf('gecko') != -1) && (agt.indexOf('safari')==-1));
var is_firefox = is_gecko;
var is_opera = (agt.indexOf("opera") != -1);
var is_ie    = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));
var is_safari= ((agt.indexOf("safari") != -1 && agt.indexOf("chrome") == -1));
var is_chrome= (agt.indexOf("chrome") != -1);

// OS check
var is_win  = (agt.indexOf('win')!=-1);
var is_mac  = (agt.indexOf('mac')!=-1);

// Some basic cookie functions
function getCookieValue (pos) {
	var e=document.cookie.indexOf(";",pos);
	if (e==-1) e=document.cookie.length;
	return (unescape(document.cookie.substring(pos, e)));
}
function getCookie (name) {
	if (document.cookie.length<1) return(null);
	var l=name.length+1, i=0, j=0;
	while (i<document.cookie.length) {
		j=i+l;
		if (document.cookie.substring(i, j)==name+'=') return(getCookieValue(j));
		i = document.cookie.indexOf(" ", i)+1;
		if (i==0) return(null);
	}
	return(null);
}
function setCookie (name,value,expires,path,domain,secure) {
	document.cookie = name+"="+escape (value)+((expires)?"; expires="+expires.toGMTString():"")+((path)?"; path="+path:"")+((domain)?"; domain="+domain:"")+((secure)?"; secure":"");
}
function deleteCookie (name,path,domain) {
	if ( getCookie(name) ) { document.cookie = name+"="+((path)?"; path="+path:"")+((domain)?"; domain="+domain:"")+"; expires=Thu, 01-Jan-70 00:00:01 GMT"; }
}

// AddEvent to object, by John Resig http://ejohn.org/projects/flexible-javascript-events/
function addEvent( obj, type, fn ) {
   if (obj.addEventListener) {
      obj.addEventListener( type, fn, false );
   } else if (obj.attachEvent) {
      obj["e"+type+fn] = fn;
      obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
      obj.attachEvent( "on"+type, obj[type+fn] );
   }
}

// Get GET parameter from URL, based on http://www.netlobo.com/url_query_string_javascript.html
function getURLParameter( name ) {
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var rx = new RegExp( "[\\?&]"+name+"=([^&#]*)" );
  var res = rx.exec( window.location.href );
  if( res == null ) return ""; else return res[1];
}

// Function to get scroll position
function getScroll() {
 var y=0,x=0;
 if( typeof(window.pageYOffset)=='number' ) { y=window.pageYOffset; x=window.pageXOffset; }
 else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) { y=document.body.scrollTop; x=document.body.scrollLeft; }
 else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) { y=document.documentElement.scrollTop; x=document.documentElement.scrollLeft; }
 return x+','+y;
}
