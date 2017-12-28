<!DOCTYPE html>
<html lang="en">
<head>
	<?php
	$system_name	=	$this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;
	$system_title	=	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Optimum Linkup Universal Concepts" />
	<meta name="author" content="optimumlinkup.com.ng" />
	
	<title><?php echo get_phrase('login');?> | <?php echo $system_title;?></title>
	

	<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="assets/css/bootstrap.css">
	<link rel="stylesheet" href="assets/css/neon-core.css">
	<link rel="stylesheet" href="assets/css/neon-theme.css">
	<link rel="stylesheet" href="assets/css/neon-forms.css">
	<link rel="stylesheet" href="assets/css/custom.css">

	<script src="assets/js/jquery-1.11.0.min.js"></script>

	<!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="shortcut icon" href="assets/images/favicon.png">
	
</head>
<body class="page-body login-page login-form-fall" data-url="http://neon.dev">


<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
var baseurl = '<?php echo base_url();?>';
</script>

<div class="login-container">
	
	<div class="login-header login-caret">
		
		<div class="login-content" style="width:100%;">
			
			<a href="<?php echo base_url();?>" class="logo">
				<img src="uploads/logo.png" height="60" alt="" />
			</a>
			
			<p class="description">
            	<h2 style="color:#cacaca; font-weight:100;">
					<?php echo $system_name;?>
              </h2>
           </p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>33%</h3>
				<span>checking login information...</span>
			</div>
		</div>
		
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<div class="form-login-error">
				<h3>Invalid Login</h3>
				<p>Please enter correct email and password!</p>
			</div>
			
			<form method="post" role="form" id="form_login">
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-users"></i>
						</div>
						
						<input type="text" class="form-control" name="email" id="email" value="admin@admin.com" autocomplete="off" data-mask="email" />
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="password" id="password" value="admin" autocomplete="off" />
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-danger btn-block btn-login">
						<i class="entypo-login"></i>
						Login Now
					</button>
				</div>
			</form>

			<a href="../school/Documentation" target="_blank"><button type="submit" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-book"></i>View Documentation</button>	</a>
			
						<a href="http://optimumlinkupsoftware.com/pricing.php"><button type="button"  class="btn btn-success"  data-toggle="tooltip" title="View user"><i class="entypo-paypal"></i>Buy Now</button></a>


			
			<div class="login-bottom-links">
				<a href="<?php echo base_url();?>index.php?login/forgot_password" class="link">
					<?php echo get_phrase('forgot_your_password');?> ?
				</a>
			</div>
		</div>
		
	</div>
	
</div>


		<div class="modal fade" id="a" role="dialog">
    	<div class="modal-dialog">
	  	<div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style=" color:#FF0000">&times;</button>
          <h4 class="modal-title" style="color:#006600"align="center">How To Purchase </h4>
        </div>
        <div class="modal-body">
		 Thanks for having interest in our software. To purhase this software, kindly chat with our online customer service agents on our website here or on our main website by 		clicking this link <a href="https://optimumlinkup.com.ng" target="_blank"><div style="color:#FF0000">HERE</div></a> OR chat with us on watsapp, telegram on +2348026634951, +2348161662924 OR message to optimumproblemsolver@gmail.com.
       <hr>
	 	<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
        </div>
      	</div>
      
    	</div>
		</div>


	<!-- Bottom Scripts -->
	<script src="assets/js/gsap/main-gsap.js"></script>
	<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/joinable.js"></script>
	<script src="assets/js/resizeable.js"></script>
	<script src="assets/js/neon-api.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/neon-login.js"></script>
	<script src="assets/js/neon-custom.js"></script>
	<script src="assets/js/neon-demo.js"></script>
	
	<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/588e0fa6af9fa11e7aa44047/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


</body>
</html>