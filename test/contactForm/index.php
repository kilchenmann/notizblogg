<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Ajax Conact Form</title>
    <link href="style/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../core/lib/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>

    <div id="main">

        <div id="note"><h2>Message sent succesefully!</h2></div>

        <div id="fields">
            <h1>Contact Form</h1>
            <form action="" method="post" id="contactForm" class="dl_form">
                <dl><dt>First Name</dt><dd>
                    <input type="text" name="first_name" id="first_name" class="" />
                </dd></dl>
                <dl><dt>Last Name</dt><dd>
                    <input type="text" name="last_name" id="last_name" class="" />
                </dd></dl>
                <dl><dt>Email</dt><dd>
                    <input type="text" name="email" id="email" class="" />
                </dd></dl>
                <dl><dt>Phone</dt><dd>
                    <input type="text" name="phone" id="phone" class="" />
                </dd></dl>
                <dl><dt>Subject:</dt><dd>
                    <input type="text" name="subject" id="subject" class="" />
                </dd></dl>
                <dl><dt>Comment</dt><dd>
                    <textarea name="message" style="width:300px; height:150px;" id="message" class="" ></textarea>
                </dd></dl>
                <dl><dt>&nbsp;</dt><dd>
                    <input type="submit" value="submit" />
                </dd></dl>
            </form>
        </div>

        <div id="error">You haven't completed all required fileds!</div>

    </div>

</body>
</html>
