<!DOCTYPE html>
<html>
<head>
	<link type='text/css' rel='stylesheet' media='screen' href='../common/css/screen.css' />
	<script type='text/javascript' src='../common/jquery/jquery-1.7.2.min.js'></script>
</head>
<body>

<?php
if (isset ($_REQUEST["access"]))
{
?>
	<script type="text/javascript">
		alert("There's no access for you!");
	</script>
<?php	
	header('Location: index.php?access=denied');
	exit;
} else {
?>
<div class="lens">

<form class="login" action="<?php echo SITE_URL."/".BASE_FOLDER."checklogin.php"; ?>" method="post">
	<input type="text" name="name" size="20" placeholder="name" autofocus /><br />
	<input type="password" name="pwd" placeholder='losung' size="20" /><br />
	<button class="button" type="submit" value="LOGIN">LOGIN</button>
</form>
</div>
<?php 
}
?>
<script type="text/javascript">
	$("body").css("overflow", "hidden");
	$(".lens").fadeTo("slow", 1);
	$(".lens").css({"width":$(window).width()+"px", "height":$(window).height()+"px", "padding":"22px", "cursor":"move"});
	$(".lens").html("<p class='warning'>There's no access for you!</p><br>");
	$(".lens").append("<p class='warning'>Login or <a href='../index.php'>go back</a></p><br>");
	$(".lens .set").css({"width":"300px"});
	$(".lens").append("<div class='login'><form action='../checklogin.php' method='post'><input type='text' name='name' size='20' placeholder='name' autofocus /><br /><input type='password' name='pwd' placeholder='losung' size='20' /><br /><button class='button' type='submit' value='LOGIN'>LOGIN</button></form></div>");
</script>
</body>
</html>
