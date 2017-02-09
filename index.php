<!DOCTYPE html>
<html>
	<head>
		<title>TODO List Application</title>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/all.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.css">
		<script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
		<script src="js/bootstrap.min.js" type="text/javascript"></script>
	</head>
	<body>
		<script>
		window.fbAsyncInit = function() {
			if (localStorage.userid) {
				$('#fbContent').html('Log Out');
				$('#btnFBLogin').attr('onclick', 'fbLogout()');
			} else {
				$('#fbContent').html('Signin with Facebook');
				$('#btnFBLogin').attr('onclick', 'fbLogin()');
			}
			FB.init({
				appId      : '262810687476970',
				cookie     : true,  // enable cookies to allow the server to access the session
				xfbml      : true,  // parse social plugins on this page
				version    : 'v2.8' // use graph api version 2.8
			});
			// Fetch the state of the person visiting this page
			FB.getLoginStatus(function(response) {
				//statusChangeCallback(response);
			});
		};

		// Load the SDK asynchronously
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		
		// Facebook logout function
		function fbLogout() {
			FB.logout(function(response) {
				// user is now logged out
				localStorage.removeItem("userid");
				localStorage.removeItem("uid");
				localStorage.removeItem("username");
				localStorage.removeItem("socialtype");
				$('#fbContent').html('Signin with Facebook');
				$('#btnFBLogin').attr('onclick', 'fbLogin()');
			});
		}
		
		// Facebook login function
		function fbLogin(){
			FB.login(function(response) {
				if (response.authResponse) {
					access_token = response.authResponse.accessToken; //get access token
					user_id = response.authResponse.userID; //get FB UID
					FB.api('/me', function(response) {
						localStorage.uid = response.id;
						localStorage.username = response.name;
						localStorage.socialtype = 'facebook';
						$('#fbContent').html('Log Out');
						$('#btnFBLogin').attr('onclick', 'fbLogout()');
						$.ajax({
							type: "POST",
							url:  "ajaxUsers.php",
							cache: false,
							data:{uid:response.id, uname:response.name, utype:'Facebook'},
							success: function(response){
								localStorage.userid = response;
								location.href = 'shopping_list.php';
							}
						});
					});
				} else {
					//user hit cancel button
					console.log('User cancelled login or did not fully authorize.');
				}
			}, {
				scope: 'public_profile,email'
			});
		}
		
		// alert object values
		function alertObject(obj){      
			for(var key in obj) {
				alert('key: ' + key + '\n' + 'value: ' + obj[key]);
				if( typeof obj[key] === 'object' ) {
					alertObject(obj[key]);
				}
			}
		}
		</script>
		<div class="loginContainer">
			<div id="fbButton">
				<button name="btnFBLogin" id="btnFBLogin" class="btn btn-primary btn-facebook" onclick="fbLogin()"><i class="fa fa-facebook"></i>&nbsp;&nbsp;<span id="fbContent">Signin with Facebook<span></button>
			</div>
			<div id="status">
			</div>
		</div>
	</body>
</html>