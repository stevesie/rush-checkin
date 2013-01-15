base_url = '/';
base_request = '';
user_id = 0;

function addLoadEvent(func) {	
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
    	window.onload = func;
	}else {
		window.onload = function(){
		oldonload();
		func();
		}
	}
}

function set_request_url(req) {
	base_reqest = req;
}

function set_url_root(url_root) {
	base_url = url_root;
}

function set_user_id(value) {
	user_id = value;
}