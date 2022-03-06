<html>

<body>

<?php 

$host = 'localhost';
$username = 'root';
$password = '';
$cipher = 'AES-128-CBC';
$key = 'thesecretkey';
$conn = new mysqli($host, $username, $password);
$att = 0;
if ($conn->connect_error)
{
die('Connection failed: ' . $conn->connect_error);
}    
$sql = 'USE usertable;';
$conn->query($sql);


session_start();

if (isset($_POST['submit']))
{
$att = $_SESSION['att'];
$sql = "SELECT * FROM details";
$result = $conn->query($sql);
$found = "false";

if ($result->num_rows > 0)
{
while($row = $result->fetch_assoc())
{
$iv = hex2bin($row['iv']);
$medicalCard = hex2bin($row['medicalCard']);
$medicalCard = openssl_decrypt($medicalCard, $cipher, $key, OPENSSL_RAW_DATA, $iv);
if($medicalCard == $_POST['medicalCard'])
{
$name = hex2bin($row['name']);
$name= openssl_decrypt($name, $cipher, $key, OPENSSL_RAW_DATA, $iv);
$found = "True";
}
}
}
if($found == "false" || $name!= $_POST['name'])
{
echo '<p>Error! Incorrect Details</p>';

				
				$att++;
				
				if($att <=3)
				{
					$_SESSION['att'] = $att;
					echo  "There has been no record found with this Name and Medical Card combination - Try again.";
				}
				else
				{
					echo "Sorry - You have used all 3 attempts  Shutting down...";
					mysqli_close($conn);
				}
			
				

}
else
{
	header("Location: menu.html");

}	


}

buildPage($att);
function buildPage($att)
{
	echo "<body>
	<form method ='POST'>
	<div class='div1'>
	<h1>Login</h1>
	<h2>Attempt Number: $att</h2>
	<label class='align1' for='name'>Name</label>
	<input type = 'text' name = 'name' id = 'name' autocomplete = 'off'/><br><br>
	<label class='align1' for='medicalCard'>Medical Card</label>
	<input type = 'medicalCard' name = 'medicalCard' id = 'medicalCard'><br><br>
	<div class='myButton' >
	<input type='submit' value='Submit' name='submit' Id='mybutton'/>
	<input type='reset' value='Clear' Id='mybutton'/>
	</div>
	</div>
	</form>";
	
}

mysqli_close($conn);

?>

</body>
</html>