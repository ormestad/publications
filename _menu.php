<?php
// Default menu items
$menu=new htmlList('ul',array('class' => 'menu'));
$menu->listItem($CONFIG['site']['name'],array('class' => 'menu-text'));
$menu->listItem('<a href="index.php">Dashboard</a>');

if($USER->auth>0) {
	// Logged in
	$menu->listItem('<a href="publications.php">All publications</a>');
	$menu->listItem('<a href="researchers.php">Researchers</a>');
	$menu->listItem('<a href="labs.php">Labs</a>');
	$menu->listItem('<a href="users.php">Users</a>');
	$menu->listItem('<a href="admin.php">Admin</a>');
	$user_status_button="<li>".$USER->data['user_email']."&nbsp;</li><li><button type=\"button\" class=\"small button\" onclick=\"location.href='logout.php'\">Logout</button></li>";
} else {
	// Not logged in
	$user_status_button="<li><button type=\"button\" class=\"small button\" onclick=\"location.href='login.php'\">Login</button></li>";
}

?>
<div class="top-bar">
	<div class="top-bar-left">
		<?php echo $menu->render(); ?>
	</div>

	<div class="top-bar-right">
		<ul class="menu">
			<?php echo $user_status_button; ?>
		</ul>
	</div>
</div>

<?php echo $ALERTS->render(); ?>
