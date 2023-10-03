let url = "http://localhost/B2B";

	function registerButton() {
		var data = $('#bregisterform').serialize();
		$.ajax({
			url : url + '/controller/register.php',
			type : "POST",
			data,
			success : function(response) {
				if($.trim(response) == "empty") {
					alert('Empty');
				}else if($.trim(response) == "format") {
					alert('Email Error');
				}else if($.trim(response) == "match") {
					alert('Passport not confirmed');
				}else if($.trim(response) == "already") {
					alert('Email is already registered');
				}else if($.trim(response) == "error") {
					alert('ERROR');
				}else if($.trim(response) == "ok") {
					alert('Register Successfully');
					window.location.href = url;
				}
			}

		});
	}