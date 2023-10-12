// SmartiApps Facebook Login v1.0 by vetriselvan - http://smartiapps.com/
//© Copyright 2017 SmartiApps 
(function(d, s, id){
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
function fbLogin() {
    FB.login(function (response) {
        if (response.authResponse) {
            getFbUserDetails();
        } else {
		    document.getElementById("fbloginstatus").innerHTML = '<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> User has cancelled the Facebook Authorization.</div>';
        }
    }, {scope: 'email'});
	
}
function getFbUserDetails(){
    FB.api('/me', {fields: 'id,first_name,last_name,email,link,gender,locale,picture'},function (response) {
		if (response.error) {
            console.log(JSON.stringify(response.error));
			document.getElementById("fbloginstatus").innerHTML = '<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i>'+JSON.stringify(response.error)+'</div>';
        }
		else if(typeof response.email == 'undefined' || response.email.length == 0)
		{
			document.getElementById("fbloginstatus").innerHTML = '<div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i>Facebook did not retrive your email, So Please try with other facebook account!</div>';
		}
		else
		{
		// Send user details to server		
			try{
				$.ajax({
					url: 'index.php?route=extension/module/facebook_login/login',
					type: 'post',
					data: { email: response.email, firstname: response.first_name, lastname: response.last_name,password:"" },
					beforeSend: function() {
						//Todo
					},
					complete: function() {

					},
					success: function(json) {
						location.reload();
					},
					error: function(jqXHR, exception) {
						console.log(jqXHR.status);
						console.log(exception);		 
					}					
				});
			}
			catch (e) {
				console.log(e);
			}        
		}   
   });
}