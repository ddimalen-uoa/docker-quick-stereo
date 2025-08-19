/*** At first dynamic checks ***/

// Take the variables needed and put the good display 
var min_disp = document.getElementById('min_disp');
var max_disp = document.getElementById('max_disp');

min_disp.style.display = 'none';
max_disp.style.display = 'none';

var min_disp_selection = document.getElementById('min_disp_selection');
var max_disp_selection = document.getElementById('max_disp_selection');

var disp_scale = document.getElementById('disp_scale');
var error_msg_disp_scale = document.getElementById('error_msg_disp_scale');
var smooth = document.getElementById('smooth');
var error_msg_smooth = document.getElementById('error_msg_smooth');
var error_msg_min_disp = document.getElementById('error_msg_min_disp');
var error_msg_max_disp = document.getElementById('error_msg_max_disp');

// List of the events tracked to adjust the corrections for the user
min_disp_selection.addEventListener('change', function() { 
	if(min_disp_selection.options[min_disp_selection.selectedIndex].innerHTML == "Manually entered")
	{
		min_disp.style.display = 'inline-block';
	}

	else
	{
		min_disp.style.display = 'none';
		error_msg_min_disp.style.display = 'none';
	}
}, false);

max_disp_selection.addEventListener('change', function() {
	if(max_disp_selection.options[max_disp_selection.selectedIndex].innerHTML == "Manually entered")
	{
		max_disp.style.display = 'inline-block';
	}

	else
	{
		max_disp.style.display = 'none';
		error_msg_max_disp.style.display = 'none';
	}
}, false);

min_disp.addEventListener('blur', function() {
	if(min_disp.value[0] == undefined)
	{
		error_msg_min_disp.style.display = 'block';
	}

	else
	{
		error_msg_min_disp.style.display = 'none';
	}
}, false);

max_disp.addEventListener('blur', function() {
	if(max_disp.value[0] == undefined || max_disp.value <= 0 || (max_disp.value - min_disp.value) <= 0)
	{
		error_msg_max_disp.style.display = 'block';
	}

	else
	{
		error_msg_max_disp.style.display = 'none';
	}
}, false);

disp_scale.addEventListener('blur', function() {
	if((disp_scale.value < 0 || disp_scale.value > 1) && disp_scale.value[0] != undefined)
	{
		error_msg_disp_scale.style.display = 'block';
	}

	else
	{
		error_msg_disp_scale.style.display = 'none';
	}
}, false);

smooth.addEventListener('blur', function() {
	if(smooth.value < 0 || smooth.value > 50)
	{
		error_msg_smooth.style.display = 'block';
	}

	else
	{
		error_msg_smooth.style.display = 'none';
	}
}, false);

/*** Form checking at the submission ***/

// Variables needed for the form 
var form  = document.getElementById('process_form');
var error_form = document.getElementById('error_form');
var error_file = document.getElementById('error_file');
var error_left_and_right = document.getElementById('error_left_and_right');

// Listener for the submit event of the form
form.addEventListener('submit', function(e) {
	e.preventDefault();	


	// Cases for the parameters
	if(((disp_scale.value <= 0 || disp_scale.value > 1) && disp_scale.value[0] != undefined) || (smooth.value < 0 || smooth.value > 50))
	{
		error_form.style.display = 'block';
	}
	else if(min_disp_selection.options[min_disp_selection.selectedIndex].innerHTML == "Manually entered" && min_disp.value[0] == undefined)
	{
		error_msg_min_disp.style.display = 'block'; // Added because not displayed if the user does not put the focus on it 
		error_form.style.display = 'block';
	}

	else if(max_disp_selection.options[max_disp_selection.selectedIndex].innerHTML == "Manually entered" && (max_disp.value[0] == undefined || max_disp.value <= 0 || (max_disp.value - min_disp.value) <= 0))
	{
		error_msg_max_disp.style.display = 'block'; // Added because not displayed if the user does not put the focus on it
		error_form.style.display = 'block';
	}
	else
	{
		form.submit();
	}

}, false);