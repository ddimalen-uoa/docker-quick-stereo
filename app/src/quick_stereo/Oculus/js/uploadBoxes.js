// Upload choice cases (described in uploadChoiceCases.html)
var upload_choice_left_right = document.getElementById('upload_choice_left_right');
var upload_choice_leftright = document.getElementById('upload_choice_leftright');
var upload_choice_anaglyph = document.getElementById('upload_choice_anaglyph');
var upload_choice_mpo = document.getElementById('upload_choice_mpo');
var upload_choice_stereogram = document.getElementById('upload_choice_stereogram');

// Boxes where the user put his files (described in uploadBoxes.html)
var left_and_right_box = document.getElementById('left_and_right_box');
var leftright_box = document.getElementById('leftright_box');
var anaglyph_box = document.getElementById('anaglyph_box');
var mpo_box = document.getElementById('mpo_box');
var stereogram_box = document.getElementById('stereogram_box');

// Form inputs for the files (described in uploadBoxes.html)
var imageleft = document.getElementById('imageleft');
var imageright = document.getElementById('imageright');
var imageleftright = document.getElementById('imageleftright');
var anaglyph = document.getElementById('anaglyph');
var mpo = document.getElementById('mpo');
var stereogram = document.getElementById('stereogram');

function hideAll(){
	left_and_right_box.style.display = 'none';
	imageleft.disabled = true;
	imageright.disabled = true;
	leftright_box.style.display = 'none';
	imageleftright.disabled = true;
	anaglyph_box.style.display = 'none';
	anaglyph.disabled = true;
	mpo_box.style.display = 'none';
	mpo.disabled = true;
	stereogram_box.style.display = 'none';
	stereogram.disabled = true;
}

// Hide all to reset and display the left and right box
hideAll();
imageleft.disabled = false;
imageright.disabled = false;
left_and_right_box.style.display = 'block';

upload_choice_left_right.addEventListener('click', function() {
	hideAll();
	imageleft.disabled = false;
	imageright.disabled = false;
	document.getElementById('left_and_right_box').style.display = 'block';
}, false);

upload_choice_leftright.addEventListener('click', function() {
	hideAll();
	imageleftright.disabled = false;
	leftright_box.style.display = 'block';
}, false);

upload_choice_anaglyph.addEventListener('click', function() {
	hideAll();
	anaglyph.disabled = false;
	anaglyph_box.style.display = 'block';
}, false);

upload_choice_mpo.addEventListener('click', function() {
	hideAll();
	mpo.disabled = false;
	mpo_box.style.display = 'block';
}, false);

upload_choice_stereogram.addEventListener('click', function() {
	hideAll();
	stereogram.disabled = false;
	stereogram_box.style.display = 'block';
}, false);