<?php
    @include 'config.php';

    $error = array();
    
    session_start(); 
    
    if (isset($_POST['submit'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = md5($_POST['password']);
        $cpass = md5($_POST['cpassword']);
        $user_type = $_POST['user_type'];
        
        $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";
        
        $result = mysqli_query($conn, $select);
        
        if(mysqli_num_rows($result) > 0) {
           
           $row = mysqli_fetch_array($result);
           
           if($row['user_type'] == 'admin'){
           	$_SESSION['admin_name'] = $row['name'];
           	header('location:admin_page.php');
           }elseif($row['user_type'] == 'user'){
           	$_SESSION['user_name'] = $row['name'];
           	header('location:user_page.php');
           }
        }else{
        	$error[] = 'incorrect email or password!';
        }
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login form</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
		<div class = "content">
        <form action="" method="post">
        
        <h1>Welcome to <span>Network Monitoring Tool</span></h1>
            <h3>login form</h3>
            <?php
    		if(isset($error)){
        		foreach($error as $err){
            		echo '<span class="error-msg">'.$err.'</span>';
        		}
    		}
	?>  
            
            <input type="email" name="email" required placeholder="enter your email">
            <input type="password" name="password" required placeholder="enter your password">
            <input type="submit" name="submit" value="login" class="form-btn">
            <p>don't have an account? <a href="register_form.php">register now</a></p>
        </form>
        </div>
    </div>


</body>
</html>
