<?php
include 'db.class.php';
$db = new db();
/*$productsQry = "SELECT * FROM products";
$productsRes = $db->getAll($productsQry);
$itemsQry = "SELECT * FROM products WHERE is_completed = 0";
$itemsRes = $db->getAll($itemsQry);*/
//echo '<pre>';print_r($productsRes);echo '</pre>';
?>
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
		if (!localStorage.userid) {
			location.href='index.php';
		} else {
			$('#loggedUserName').html(localStorage.username);
		}
		window.fbAsyncInit = function() {
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
				location.href = 'index.php';
			});
		}
		
		// Format for product name
		function productNameCheck(evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if ((charCode > 47 && charCode <= 58) || (charCode > 64 && charCode <= 91) || (charCode > 96 && charCode <= 123) || charCode == 32 || charCode == 45 || charCode == 95)
				return true;
			else
				return false;
		}
		
		function addProduct() {
			var txtProduct = $('#txtProduct').val();
			if (txtProduct == '') {
				$('#outputContainer').addClass('label-danger');
				$('#outputContainer').html('Please enter the product name');
				$('#txtProduct').focus();
				return false;
			}
			$.ajax({
				type: "POST",
				url:  "ajaxProducts.php",
				cache: false,
				data:{userId:localStorage.userid, txtProduct:txtProduct, action:'add'},
				success: function(response){
					$('#outputContainer').removeClass('label-danger');
					$('#outputContainer').removeClass('label-success');
					$('#outputContainer').html('');
					if (response == 2) {
						$('#outputContainer').addClass('label-danger');
						$('#outputContainer').html('Product already exists.');
						return false;
					}
					$('#productsList').html(response);
					$('#outputContainer').addClass('label-success');
					$('#outputContainer').html('Product added successfully.');
					$('#txtProduct').val('');
				}
			});
		}
		
		function removeProduct(pid) {
			$.ajax({
				type: "POST",
				url:  "ajaxProducts.php",
				cache: false,
				data:{userId:localStorage.userid, pid:pid, action:'delete'},
				success: function(response){
					$('#outputContainer').removeClass('label-danger');
					$('#outputContainer').removeClass('label-success');
					$('#outputContainer').html('');
					$('#productsList').html(response);
					$('#outputContainer').addClass('label-success');
					$('#outputContainer').html('Product removed successfully.');
				}
			});
		}
		
		function completeStatus(complete) {
			var pid = complete.id;
			if (complete.checked == true) {
				var complete = 1;
			} else {
				var complete = 0;
			}
			$.ajax({
				type: "POST",
				url:  "ajaxProducts.php",
				cache: false,
				data:{userId:localStorage.userid, pid:pid, complete:complete, action:'complete'},
				success: function(response){
					$('#outputContainer').removeClass('label-danger');
					$('#outputContainer').removeClass('label-success');
					$('#outputContainer').html('');
					$('#productsList').html(response);
					$('#outputContainer').addClass('label-success');
					$('#outputContainer').html('Product status changed successfully.');
				}
			});
		}
		$(document).ready(function() {
			$.ajax({
				type: "POST",
				url:  "ajaxProducts.php",
				cache: false,
				data:{userId:localStorage.userid, action:'view'},
				success: function(response){
					$('#outputContainer').removeClass('label-danger');
					$('#outputContainer').removeClass('label-success');
					$('#outputContainer').html('');
					$('#productsList').html(response);
				}
			});
		});
		</script>
		<div class="container">
			<div class="loginHeader">
				Logged in as <script>document.write(localStorage.username);</script>.<br>
				<button name="btnFBLogin" id="btnFBLogin" class="btn btn-primary btn-facebook" onclick="fbLogout()"><i class="fa fa-facebook"></i>&nbsp;&nbsp;<span id="fbContent">Log Out<span></button>
			</div>
			<div style="clear:both;"></div>
			<div class="content">
				<h1 style="text-align:center;">SHOPPING LIST</h1>
				<div id="outputContainer" style="padding:10px; margin: 10px 0px;"></div>
				<form name="frmShoppingList" id="frmShoppingList" method="post" action="">
					<input type="text" name="txtProduct" id="txtProduct" maxlength="100" placeholder="What's in your mind?" class="searchText" onkeypress="return productNameCheck(event);">
					<input type="button" name="btnSubmit" id="btnSubmit" value="Submit" class="btn btn-primary" onclick="addProduct()">
				</form>
				<div id="productsList">
				</div>
			</div>
		</div>
	</body>
</html>