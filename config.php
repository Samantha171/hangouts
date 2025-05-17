<?php  
$host = '127.0.0.1';    
$user = 'root';  
$pass = '';  
$dbname = 'package';  // <-- Specify your database name here

// Establish Database Connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('❌ Could not connect: ' . mysqli_connect_error());
} 
echo '✅ Connected successfully to database: ' . $dbname . '<br/>';

// Sample Query to Test Database Connection
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

if ($result) {
    echo "✅ Database connection verified. Found tables:<br/>";
    while ($row = mysqli_fetch_row($result)) {
        echo "- " . $row[0] . "<br/>";
    }
} else {
    echo "❌ Database query failed: " . mysqli_error($conn);
}

mysqli_close($conn);  
?>