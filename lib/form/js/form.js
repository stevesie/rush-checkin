function focus_first(form_id) {
	var f_el = document.forms[0];
	if(typeof form_id == typeof '') f_el = $(form_id);
	Form.focusFirstElement(f_el);
}

//addLoadEvent(focus_first);

function apply_form_callback(obj, form, specific_id) {

	$('#' + form.id +  ' .spinner').hide();
	
	$('#' + form.id +  ' input').attr('disabled', '');
	$('#' + form.id +  ' textarea').attr('disabled', '');

	
	if(!specific_id) {
		var clear_msg = $('.field_message');
		for(var i = 0; i < clear_msg.length; i++) {
			clear_msg[i].innerHTML = '';
		}
	}
	
	//if(!obj['load'] || specific_id) {
		
	var field_messages = obj['field_messages'];
	
	if(field_messages) {
		
		for(key in field_messages) {
			try{
				var msg = field_messages[key];
				if(!specific_id)
					$('#'+key + '_message').style.display = 'none';
				$('#'+key + '_message').innerHTML = msg[0];
				if(!specific_id)
					$('#'+ key + '_message').appear();
			}catch(e){;}
		}
	}
	
	var errors = obj['errors'];
	
	if(errors.length > 0 && $('#form_errors')) {
		$('#form_errors_ul').css('display','block');
		
		
		$('#form_errors_ul').html('');
		for(var i = 0; i < errors.length; i++) {
			//alert(list);
			$('#form_errors_ul').append('<li>' + errors[i] + '</li>');
		}
		
		$('#form_errors').css('display','block');
	}else{
		
		$('#form_errors').hide();
	}
	
	var messages = obj['messages'];
	
	if(messages.length > 0 && $('#form_messages')) {
		$('#form_messages_ul').css('display','block');
		
		
		$('#form_messages_ul').html('');
		for(var i = 0; i < messages.length; i++) {
			$('#form_messages_ul').append('<li>' + messages[i] + '</li>');
		}
		
		$('#form_messages_ul').show();
		$('#form_messages').show();
		$('#form_messages').css('display','block');
	}else{
		
		$('#form_messages').hide();
		
		if(obj['redirect']) {
			window.location = obj['redirect'];
		}
	}
	
	//}
	
	/*
	if(obj['load']) {
		form.submit();
	}
	*/
	
	if(obj['redirect']) {
			window.location = obj['redirect'];
		}
	
	return false;
	//return obj['valid'] == 'true';
}

function form_field_check(url, action, id, msg) {
	var pars = "ajax_action=" + action + "&" + $(id).name + "=" + $(id).value;
	
	new Ajax.Request(url,
	{
		method: 'post',
		parameters: pars,
		onSuccess: function(request) {
			apply_form_callback(request.responseText, false, id);
		}
	});
}

function attempt_submit(form, tinymce_name, tinymce_id) {

	var pars = $("#" + form.id).serialize(); //there is not commit variable on HTTP submit

	$('#' + form.id +  ' input').attr('disabled', 'true');
	$('#' + form.id +  ' textarea').attr('disabled', 'true');
	
	$('#' + form.id +  ' .spinner').show();
	
	//$('#form_errors').hide();
	
	if(tinymce_name && tinymce_id) {
		var str = tinymce_name + "=" + escape(tinyMCE.get(tinymce_id).getContent());
		var find_pos = pars.indexOf(tinymce_name + "=");
		var ender = pars.indexOf("&",find_pos);
		if(ender == -1) ender = pars.length;
		
		var par_pre = pars.substr(0,find_pos);
		var par_post = pars.substr(ender, pars.length);

		pars = par_pre + str + par_post;
	}
	
	pars = pars.replace("&commit=false","");
	
	pars = pars + "&commit=true";
	
	var action = form.action;
	var url = action;
	
	//alert(url);
	
	
//alert(pars);
	$.post(url, pars, function(data) {
			apply_form_callback(data,form);
		}, "json");

	return false; //leave it to the callback to submit the form and advance iff succesful submission
}

function php_urlencode(str) {
	str = escape(str);
	return str.replace(/[*+\/@]|%20/g,
	function (s) {
	switch (s) {
	case "*": s = "%2A"; break;
	case "+": s = "%2B"; break;
	case "/": s = "%2F"; break;
	case "@": s = "%40"; break;
	case "%20": s = "+"; break;
	}
	return s;
	}
	);
}


function focus_email(box) {
	if(box.value == 'email') box.value = '';
}

function blur_email(box) {
	if(box.value == '') box.value = 'email';
}

function focus_password(box) {
	//alert(box.type);
	
	if(box.type == 'text') {
		box.value = '';
		var np = box.cloneNode(true);
		np.type = 'password';
		box.parentNode.replaceChild(np,box);
		
		static_field = np;
		//document.getElementById("pass_field").focus();
		setTimeout('document.getElementById("pass_field").focus()',10);
		//box.type = 'password';
	}
}

function blur_password() {
	var box = static_field;
	//alert(box.value);
	if(box.value == '') {
		var np = box.cloneNode(true);
		np.type = 'text';
		box.parentNode.replaceChild(np,box);
		np.value = 'password';
	}
}