function add_comment() {
	
	$('#add_comment').show("slow");
	$('#add_comment textarea').focus();
	
}

function new_rush() {
	
	$('#reg_div').show("fast");
	$('#form_first_name').focus();
	//$("#reg_div:first-child input").focus();
}

function signin_form() {

	$('#signin_form').show("fast");
	$('#qcard').hide("fast");
	$('#click_link').hide("fast");
}