<?php require_once 'include/head.php'; ?>
		<!-- WRAPPER START -->
		<div class="wrapper bg-dark-white">

			<!-- HEADER-AREA START -->
			<?php require_once 'include/header.php'; ?>
			<!-- HEADER-AREA END -->
			<!-- Mobile-menu start -->
			<?php require_once 'include/mobile-menu.php'; ?> 
			<!-- Mobile-menu end -->
			<!-- HEADING-BANNER START -->
			<div class="heading-banner-area overlay-bg">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="heading-banner">
								<div class="heading-banner-title">
									<h2>Registration</h2>
								</div>
								<div class="breadcumbs pb-15">
									<ul>
										<li><a href="index.html">Home</a></li>
										<li>Registration</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- HEADING-BANNER END -->
			<!-- SHOPPING-CART-AREA START -->
			<div class="login-area  pt-80 pb-80">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							<div class="customer-login text-left">
									<form action="" method="POST" onsubmit="return false;">	
									<h4 class="title-1 title-border text-uppercase mb-30">Login Seller</h4>
									<p class="text-gray">If you have an account with us, Please login!</p>
									<input type="text" placeholder="Email here..." name="email">
									<input type="password" placeholder="Password">
									<p><a href="#" class="text-gray">Forget your password?</a></p>
									<button type="submit" data-text="login" class="button-one submit-button mt-15">login</button>
								</form>
								</div>					
							</div>

							<!-- Register Seller -->
							<div class="col-lg-6">
								<form onsubmit="return false;" id="bregisterform">
								<div class="customer-login text-left">
									<h4 class="title-1 title-border text-uppercase mb-30">Register Seller</h4>
									<input type="text" placeholder="Your name here..." name="seller_name" />
									<input type="text" placeholder="Email address here..." name="seller_email" />
									<input type="password" placeholder="Password" name="seller_password" />
									<input type="password" placeholder="Confirm password" name="seller_confirm_password" />
									<input type="text" placeholder="Phone" name="seller_phone" />
									<input type="text" placeholder="Tax number" name="seller_tax" />
									<input type="text" placeholder="Tax Adminstration" name="seller_tax_administration" />
									<button type="submit" class="button-one submit-button mt-15" onclick="registerButton()">Regiter</button>
								</div>					
							</form>
							</div>
					</div>
				</div>
			</div>
			<!-- SHOPPING-CART-AREA END -->
			<!-- FOOTER START -->
<?php require_once 'include/footer.php'; ?>
<script>
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
};
</script>