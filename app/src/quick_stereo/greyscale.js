// JavaScript Document

// Set and load the source image.
var mySrcImg = new Image();

function invertImg2(name) {
	mySrcImg.src = name;
	var canvas = document.getElementById("Grey");
	var context = canvas.getContext("2d");
	
	//canvas.width = mySrcImg.width;
	//canvas.height = mySrcImg.height;
	context.fillStyle = "rgb(0,200,0)";
	context.fillRect (0, 0, 300, 300);
	
	// Copy the image contents to the canvas
	context.drawImage(mySrcImg, 0, 0);
	
	// Get your image data.
	var myImageData;
	try {
		myImageData = context.getImageData(0, 0, canvas.width, canvas.height);
	} catch (e) {
		netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
		myImageData = context.getImageData(0, 0, canvas.width, canvas.height);
	}	
	// Loop over each pixel and invert the colours.
	var pixelComponents = myImageData.data;
	for (var i = 0; n = pixelComponents.length, i < n; i += 4) {
		var avg = (pixelComponents[i] + pixelComponents[i+1] + pixelComponents[i+2]) / 3;
		// One pixel:
		pixelComponents[i ] = pixelComponents[i ] = avg ; // red
		pixelComponents[i+1] = pixelComponents[i+1] = avg; // green
		pixelComponents[i+2] = pixelComponents[i+2] = avg; // blue
		// i+3 is alpha (the fourth element)
	}	
	// Draw the ImageData object at the given (x,y) coordinates.
	context.putImageData(myImageData, 0,0);
}

function invertImg(name) {
	mySrcImg.src = name;
	var canvas = document.getElementById("Grey");
	var context = canvas.getContext("2d");
	
	//canvas.width = mySrcImg.width;
	//canvas.height = mySrcImg.height;
	context.fillStyle = "rgb(0,200,0)";
	context.fillRect (0, 0, 300, 300);
	
	// Copy the image contents to the canvas
	context.drawImage(mySrcImg, 0, 0);
	
	// Get your image data.
	var myImageData;
	try {
		myImageData = context.getImageData(0, 0, canvas.width, canvas.height);
	} catch (e) {
		netscape.security.PrivilegeManager.enablePrivilege("UniversalBrowserRead");
		myImageData = context.getImageData(0, 0, canvas.width, canvas.height);
	}	
	// Loop over each pixel and invert the colours.
	var pixelComponents = myImageData.data;
	for (var i = 0; n = pixelComponents.length, i < n; i += 4) {
		var avg = (pixelComponents[i] + pixelComponents[i+1] + pixelComponents[i+2]) / 3;
		// One pixel:
		pixelComponents[i ] = pixelComponents[i ] = avg ; // red
		pixelComponents[i+1] = pixelComponents[i+1] = 255-2*Math.abs(127-avg); // green
		pixelComponents[i+2] = pixelComponents[i+2] = 255-avg; // blue
		// i+3 is alpha (the fourth element)
	}	
	// Draw the ImageData object at the given (x,y) coordinates.
	context.putImageData(myImageData, 0,0);
}