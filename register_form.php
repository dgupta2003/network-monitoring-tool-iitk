<?php
    @include 'config.php';
    $error = array(); // Initialize error array
    
    if (isset($_POST['submit'])){
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = md5($_POST['password']);
        $cpass = md5($_POST['cpassword']);
        $user_type = $_POST['user_type'];
        
        $select = " SELECT * FROM user_form WHERE email = '$email' && password = '$pass' ";
        
        $result = mysqli_query($conn, $select);
        
        if(mysqli_num_rows($result) > 0) {
            $error[] = 'user already exists!';
        } else {
            if($pass != $cpass){
                $error[] = 'passwords do not match!';
            } else {
                $insert = "INSERT INTO user_form(name, email, password, user_type) VALUES('$name','$email','$pass','$user_type')";
                mysqli_query($conn, $insert);
                header('location:login_form.php');
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register form</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="container">
		<div class= "content">
        <form action="" method="post">
        
        <h1>Welcome to <span>Network Monitoring Tool</span></h1>
            <h3>registration form</h3>
            <?php
    		if(isset($error)){
        		foreach($error as $err){
            		echo '<span class="error-msg">'.$err.'</span>';
        		}
    		}
	?>  
      
            <input type="text" name="name" required placeholder="enter your name">
            <input type="email" name="email" required placeholder="enter your email">
            <input type="password" name="password" required placeholder="enter your password">
            <input type="password" name="cpassword" required placeholder="confirm your password">
            <select name="user_type">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
            <input type="submit" name="submit" value="register" class="form-btn">
            <p>already have an account? <a href="login_form.php">login now</a></p>
        </form>
    </div>
    </div>


</body>
</html>
