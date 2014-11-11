<?php
##############################################################################################################
//db connect
    /*$server     = 'localhost';
    $username   = 'root';
    $password   = '';
    $database   = 'yourDatabase';
    $connect    = mysql_connect($server, $username, $password ) or die(mysql_error.'error connecting to db');
    
    //select database
    mysql_select_db ($database, $connect) or die(mysql_error.'error selecting db');
    */
    
    if(!empty($_POST)){
        
        $first_name = $_POST['first_name'];
        $last_name  = $_POST['last_name'];
        $email      = $_POST['email'];
        $phone      = $_POST['phone'];
        $subject    = $_POST['subject'];
        $message    = $_POST['message'];
 
        if(!empty($first_name) && !empty($last_name) && !empty($email) &&
          !empty($phone) && !empty($subject) && !empty($message) )
        {
            echo json_encode(array(
                'error' => false,
            ));
            exit;
       
            /*
                mysql_query("INSERT into yourTable (id, first_name, last_name, email, phone, subject, message )
                    VALUES ('', '".$first_name."', '".$last_name."',
                   '".$email."', '".$phone."', '".$subject."',
                   '".$message."' )") or die(mysql_error());
            */
       
            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers .= "From: ".$email."\r\n";
            
            $to         = 'email';
            $subject    = 'Contact Form';
    
            $body       = 'From: '.$first_name.' - '.$last_name.'<br/>E-mail: '.$email.'<br/>Phone: '.$phone.'<br/>Subject: '.$subject.'<br/>Message: '.$message;
    
            $mail       = mail($to, $subject, $headers, $body);

            
       }else{
            echo json_encode(array(
                'error' => true,
                'msg'   => "You haven't completed all required fileds!"
            ));
            exit;
       }    
    }
