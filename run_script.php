<?php
session_start();

//if(!isset($_SESSION['admin_name'])){
//    die("Unauthorized access");
//}

// Define the path to your bash script
$script = '/var/www/html/makingLogin/final_script2.sh';

// Execute the script and capture the output
$output = shell_exec($script);

// Add a success indicator to the output
if ($output) {
   echo "success: " . $output;
} else {
    echo "failure: Script did not run successfully.";
}
?>

