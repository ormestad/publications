<?php
require 'lib/global.php';

// Process login (field "p" is created in uservalidation.js and contain the SHA512 hash of the password so raw password will never be sent to server)
if(isset($_POST['user_email']) && isset($_POST['p'])) {
	if($USER->login($_POST['user_email'],$_POST['p'])) {
		header('location:index.php');
	} else {
		$html="<p>Could not log in...</p>";
	}
} else {
	$theform=new htmlForm('login.php');
	$theform->addInput('Username',array('type' => 'email', 'name' => 'user_email', 'required' => '', 'autocomplete' => 'off'));
	$theform->addInput('Password',array('type' => 'password', 'name' => 'password', 'required' => ''));
	$theform->addInput(FALSE,array('type' => 'button', 'value' => 'Login', 'class' => 'button', 'onclick' => 'formhash(this.form);'));
	$html=$theform->render();
}

// Render Page
//=================================================================================================
?>

<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $CONFIG['site']['name']; ?></title>
	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/app.css">
	<link rel="stylesheet" href="css/icons/foundation-icons.css" />
</head>

<body>
<?php require '_menu.php'; ?>

<div class="row">
<br>
	<div class="large-6 columns">
	<?php echo $html; ?>
	</div>

	<div class="large-5 large-offset-1 columns">
		<h3>Register</h3>
		If you are not yet a user of ngipubs, please: </br></br>
		<a class='button' href="/signup.php">Sign Up</a>
	</div>
</div>

<script src="js/vendor/jquery.js"></script>
<script src="js/vendor/what-input.js"></script>
<script src="js/vendor/foundation.js"></script>
<script src="js/app.js"></script>
<script src="js/sha512.js"></script>
<script src="js/uservalidation.js"></script>
</body>

</html>
