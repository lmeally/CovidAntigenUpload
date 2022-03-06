<html>

<body>

<?php
$host = 'localhost';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password);
$cipher = 'AES-128-CBC';
$key = 'thesecretkey';

if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}


$sql = 'CREATE DATABASE IF NOT EXISTS userTable';
if (!$conn->query($sql) === TRUE) {
  die('Error creating database: ' . $conn->error);
}

$sql = 'USE userTable;';
if (!$conn->query($sql) === TRUE) {
  die('Error using database: ' . $conn->error);
}

$sql = 'CREATE TABLE IF NOT EXISTS details (
id int NOT NULL AUTO_INCREMENT,
iv varchar(256) NOT NULL,
name varchar(256) NOT NULL,
phoneNumber varchar(256) NOT NULL,
email varchar(256) NOT NULL,
medicalCard varchar(256) NOT NULL,
DOB varchar(256) NOT NULL,

PRIMARY KEY (id));';
if (!$conn->query($sql) === TRUE) {
  die('Error creating table: ' . $conn->error);
}
?>
<html>
<head>
<title>User Form</title> </head>
<body>
<h2>User Form</h2>
<form method="post"

<div class = "inputbox">
<label for ="name">Name:</label>
<input type ="text" id="name" name="name" required placeholder="Name" />
</div>

<div class = "inputbox">
<label for ="phoneNumber">Phone Number:</label>
<input type ="number" id="phoneNumber" name="phoneNumber" required placeholder="Phone Number" />
</div>

<div class = "inputbox">
<label for ="email">Email:</label>
<input type ="email" id="email" name= "email" required placeholder="Email" />
<
	

<div class = "inputbox">
<label for ="DOB">Date of Birth:</label>
<input type ="date" id="DOB"  name = "DOB" required placeholder="Date of Birth" />
</div>

<div class = "inputbox">
<label for ="medicalCard">Medical Card:</label>
<input type ="number" id="medicalCard"  name ="medicalCard" required placeholder="Medical" />
</div>




<div class="new-user" >
<input type="submit" value="Submit" name="new-user"/>
<input type="reset" value="Clear" />
</div>


</form>
</body>
</html>

<html>
<head>
<title>Secure Database</title> </head>
<body>
<h2>Secure DATABASE</h2>



<?php
if (isset($_POST['new-user'])) {
	$iv = random_bytes(16);
	$escaped_name = $conn -> real_escape_string($_POST['name']);
	$escaped_phoneNumber = $conn -> real_escape_string($_POST['phoneNumber']);
	$escaped_email = $conn -> real_escape_string($_POST['email']);
	$escaped_DOB = $conn -> real_escape_string($_POST['DOB']);
	$escaped_medicalCard = $conn -> real_escape_string($_POST['medicalCard']);
	$encrypted_name = openssl_encrypt($escaped_name, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$encrypted_phoneNumber = openssl_encrypt($escaped_phoneNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$encrypted_email = openssl_encrypt($escaped_email, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$encrypted_DOB = openssl_encrypt($escaped_DOB, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$encrypted_medicalCard = openssl_encrypt($escaped_medicalCard, $cipher, $key, OPENSSL_RAW_DATA, $iv);

$name_hex =bin2hex($encrypted_name);
$iv_hex = bin2hex($iv);
$phoneNumber_hex = bin2hex($encrypted_phoneNumber);
$email_hex = bin2hex($encrypted_email);
$DOB_hex = bin2hex($encrypted_DOB);
$medicalCard_hex = bin2hex($encrypted_medicalCard);

$sql = "Insert into details (id,iv,name,phoneNumber,email,DOB,medicalCard)
VALUES (NULL,'$iv_hex','$name_hex','$phoneNumber_hex','$email_hex','$DOB_hex','$medicalCard_hex')";
  if ($conn->query($sql) === TRUE) {
    echo '<p><i>User has been added succesfully</i></p>';
  } else {
    die('Error creating User: ' . $conn->error);
  }
}
?>

<h2>List of User</h2>


<?php
$sql = "SELECT * FROM details";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table><tr>ID━</tr><tr>Name</tr><tr>━━━Phone</tr><tr>━━━━━Email</tr><tr>━━━━DOB</tr><tr>━━Card ID</tr>";
  
  while($row = $result->fetch_assoc()) {
	$id= ($row['id']);
	$iv = hex2bin($row['iv']);
	$name = hex2bin($row['name']);
	$phoneNumber = hex2bin($row['phoneNumber']);
	$email = hex2bin($row['email']);
	$DOB = hex2bin($row['DOB']);
	$medicalCard = hex2bin($row['medicalCard']); 
    $unencrypted_name = openssl_decrypt($name, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$unencrypted_phoneNumber = openssl_decrypt($phoneNumber, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$unencrypted_email = openssl_decrypt($email, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$unencrypted_DOB = openssl_decrypt($DOB, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	$unencrypted_medicalCard = openssl_decrypt($medicalCard, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	echo "<tr><td>$id</td><td>$unencrypted_name</td><td>$unencrypted_phoneNumber</td><td>$unencrypted_email</td><td>$unencrypted_DOB</td><td>$unencrypted_medicalCard</td></tr>";
 }

 
 
  echo '</table>';
} else {
echo '<p>There are no users in the database</p>';}
?>
</body>
</html>