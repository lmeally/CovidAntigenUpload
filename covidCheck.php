<html>
<body>
<html>
<head>
<title>User Check</title> </head>
<body>
<h2>User CheckList</h2>
<form method ="post"
<div id="cough">
<label for ="yes_no_radio">Do you have a cough?<label>
<input type="radio" name="cough" id ="coughYes" value "yes" checked>Yes</input>
<input type="radio" name="cough" id ="coughNo" nvalue "no" checked>No</input>
</div>
</form>
<br>

<form method ="post"
<div id="tirednest">
<label for ="yes_no_radio">Are you feeling tired more often then usual?<label>
<input type="radio" name="tirednest" id="tirednestYes"  value "yes" checked>Yes</input>
<input type="radio" name="tirednest" id="tirednestNo" value "no" checked>No</input>
</div>
</form>
<br>

<form method ="post"
<div id="fever">
<label for ="fever">Do you have a fever?<label>
<input type="radio" name="fever" id= "feverYes" value "yes" checked>Yes</input>
<input type="radio" name="fever" id= "feverNO" value "no" checked>No</input>
</div>
</form>
<br>

<form method ="post"
<div id="contact">
<label for ="yes_no_radio">Have you been in contact with anyone that has tested positive in last 7 days?<label>
<input type="radio" name="contact" id="contactYes" value "yes" checked>Yes</input>
<input type="radio" name="contact" id="contactNo" value "no" checked>No</input>
</div>
</form>
<br><br/>

<div class="new-record" >
<input type="submit" value="Submit" name="new-record"/>
<input type="reset" value="Clear" />
</div>





<?php 
$host = 'localhost';
$username = 'root';
$password = '';
$cipher = 'AES-128-CBC';
$key = 'thesecretkey';
$conn = new mysqli($host, $username, $password);
$results = 0;
$cresults;
$_POST['contact']=$cresults;

if ($conn->connect_error)
{
die('Connection failed: ' . $conn->connect_error);
}    




$sql = 'USE usertable;';
$conn->query($sql);

$sql = 'CREATE TABLE IF NOT EXISTS userresults (
id int(255) NOT NULL,
iv varchar(256) NOT NULL,
name varchar(256) NOT NULL,
phoneNumber varchar(256) NOT NULL,
email varchar(256) NOT NULL,
medicalCard varchar(256) NOT NULL,
DOB varchar(256) NOT NULL,
result varchar(256) NOT NULL,

PRIMARY KEY (id))';;
$conn->query($sql);


$sql= 'INSERT INTO userresults (id, iv, name, phoneNumber, email, medicalCard, DOB, result)
SELECT id, iv, name, phoneNumber, email, medicalCard, DOB FROM details';
$conn->query($sql); 




session_start();



if (isset($_POST['new-record']))
{

echo "The close contact results is" . $cresults ;

$sql = "SELECT * FROM userresults";
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
echo "Your Results are" . $row["results"];
}
}
}




}


mysqli_close($conn);

?>

</body>
</html>









