<?php

	@include 'config.php';
	
	session_start();
	
	if(!isset($_SESSION['user_name'])){
		header('location:login_form.php');
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user page</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="loading-overlay" id="loading-overlay">
        	<div class="loading-spinner"></div>
    	</div>
    	
    <div class="container">
        <div class="content">
        <form>
            <h3>hello, <span><?php echo $_SESSION['user_name'] ?></span></h3>
            <h1>welcome to <span>Network Monitoring Tool</span></h1>
            <p>NMT is a system that continuously scans the network for sluggish or malfunctioning components and alerts the administrator (via email, SMS, or other alarms) in the event of outages or other issues. </p>
            <button id="run-script-btn" class="btn">Run Script</button>
            
            <a href="logout.php" class="btn">logout</a>
            
        </form>
        </div>
    </div>
    
<script>
        
        document.getElementById('run-script-btn').addEventListener('click', function() {
        // Show loading animation
        document.getElementById('loading-overlay').style.display = 'block';

        fetch('run_script.php')
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    // Redirect to success page
                    window.location.href = 'success_page.php';
                } else {
                    // Display error message
                    alert(data);
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                // Hide loading animation when script finishes executing
                document.getElementById('loading-overlay').style.display = 'none';
            });
    });
   
    </script>    
    
    
</body>
</html>
