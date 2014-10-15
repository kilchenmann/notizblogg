<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Implementing Gravatar in your PHP Application </title>
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
</head>
 
<body>
<u><h1>Integrate Gravatar in your PHP Application</h1></u><br>
  <div id="content">
  <div id = "notification">
  <p>Check your Gravatar using this Application.<br>
  <b>Note:</b><i>We aren't storing your email-ids here!</i></p>
  </div>
  <br/>
  <br/>
  <div id="show">
<form method="get" action="index.php">
E-mail: <input type="text" name="email" id="email" value="<?php echo $_GET['email']; ?>">&nbsp;<input name="submit" type="submit" id="submit" value="Show Avatar">
</form>
<?php
  if ($_GET['email'] != "" && isset($_GET['email'])) {
    $gravatarMd5 = md5($_GET['email']);
  } else {
    $gravatarMd5 = "";
  }
?>
<p>Gravatar associated with your e-mail <i><?php echo $_GET['email']; ?></i> is:<br/>
<br/><img src="http://www.gravatar.com/avatar/<?php echo $gravatarMd5; ?>" alt="">
</p>
</div>
<br>
<a href = "http://1stwebdesigner.com">Back to Tutorial</a>
</div>
</body>
</html>