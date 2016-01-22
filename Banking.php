<?php

	$db_hostname = "";
	$db_username = "";
	$db_password = "";
	$db_schema = "";

	
	session_start();
	// Get the information that is posted to this page
	if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];}
	else{
	$username = $_POST['username'];
	$_SESSION['username'] = $username;} // Store username for later
	
	if(isset($_SESSION['password'])){
	$password = $_SESSION['password'];}
	else{
	$password = $_POST['password'];
	$_SESSION['password'] = $password;} // Store password for later
		
// Establish a link to the database
	$connection = mysql_connect($db_hostname, $db_username, $db_password);
	
	if(!$connection){
		echo 'Error connecting!';
		die();
	}
	
	echo 'Success on connection to DB! <br/>';


	// Select a database to work with!
	$database = mysql_select_db($db_schema, $connection);
	
	// Insert the information posted to this page into the database
	// ensuring they do not already exist in the database
	
	$sql = "SELECT COUNT(*) FROM accounts WHERE user_name = '". $username ."' AND password ='" . $password . ";";

    $result=mysql_query($sql);

   if($result == true)
   {
     echo "Username already exists";
    }

    else
   {
	$sql = "INSERT INTO accounts (user_name,password) VALUES ('" . $username . "', '" . $password . "')";
	
	$result = mysql_query($sql);
	
	if($result){
		echo	"<h1>Welcome " . $username . "!</h1>";
	}
}

?>


<html>	
	<head>
	<title>Worksheet 5 Banking</title>
	
	<IMG STYLE="position:absolute; TOP:20px; RIGHT:50px; WIDTH:130px; HEIGHT:130px" SRC="banking.jpg">
	
	<style>
	body{
	background-color:#E0EBEB;
	text-align: center;
	}
	</style>
	</head>

	
<body>
<style>
#masthead{
	width:100%;
	text-align: center;
	font-family:'stag-medium';
	margin:0 auto;
	font-size:2em;
	color: #293D3D;
	}

</style>

<div id="container">
		<header id= "masthead">
		
		<h1>Worksheet 5</h1>
		<h3>Banking</h3>
		
	</div>
	
	</header>
	<form action="bank.php" method="post">
	Enter Lodgment Amount <input type="text" name="lodgment"/>
	Enter Reference <input type="text" name="reference"/>
	<input type="submit" name="lodgeform" value="Update">
	</form>
	
	<?php
	
	
	// Get the lodgment information that is posted to this page
	if(isset($_POST['lodgeform'])){
	$Lodgment = $_POST['lodgment'];
	$Reference = $_POST['reference'];
	
	$sql = "INSERT INTO transactions (user_name,lodgments,reference) VALUES ('" . $username . "','" . $Lodgment ."','" . $Reference . "')";
	$BalanceUpdate = $Balance + $Lodgment;
	$UpdateAcc= mysql_query("Update accounts SET balance = '" . $BalanceUpdate . "' WHERE user_name = '" . $username . "';");
	$result = mysql_query($sql);
	
	if($result){
		echo '<br><h1>Lodgement updated</h1>';
	}else{
		echo '<br><h1>ERROR Lodgment unsuccessful.</h1>';
		  }
	}
	$sql = "SELECT balance FROM accounts WHERE user_name = '" . $username . "'";	
	$result = mysql_query($sql);
	
	while($row = mysql_fetch_array($result)){
		$Balance = $row['balance'];
	}
	
?> 
	   <form action="bank.php" method="post">
	Enter Withdraw Amount <input type="text" name="withdraw"/>
	Enter Reference <input type="text" name="reference"/>
	<input type="submit" name="withform" value="Update">
	</form>
<?php	   
	 // Get the withdraw information that is posted to this page
	if(isset($_POST['withform'])){
	$Withdraw = $_POST['withdraw'];
	$Reference = $_POST['reference'];
	
	$sql = "INSERT INTO transactions (user_name,withdrawal,reference) VALUES ('" . $username . "','" . $Withdraw ."','" . $Reference . "')";
	$BalanceUpdate2 = $Balance - $Withdraw;
	if($BalanceUpdate2<0){
	echo 'insufficent funds';
	}
	else{
	$UpdateAcc2= mysql_query("Update accounts SET balance = '" . $BalanceUpdate2 . "' WHERE user_name = '" . $username . "';");
	$result = mysql_query($sql);
	
	if($result){
		echo '<br><h1>Withdraw lodged</h1>';
	}else{
		echo '<br><h1>ERROR withdraw unsuccessful.</h1>';
		  }
		}
	}
	$sql = "SELECT balance FROM accounts WHERE user_name = '" . $username . "'";	
	$result = mysql_query($sql);
	
	while($row = mysql_fetch_array($result)){
		$Balance = $row['balance'];
	}    
	     
	   
	// Retrieve Blanace from the database	
	$sql = "SELECT balance FROM accounts WHERE user_name = '" . $username . "'";	
	
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)){
		echo	"<h4>Your balance is " . $row['balance'] . " in Euros</h4>";
		// store Balance for later lodgement and withdrawls Calcs
		$Balance = $row['balance'];
	}  
	     
	
	// List ONLY This users Transactions
	$sql = "SELECT * FROM accounts LEFT JOIN transactions ON accounts.user_name = transactions.user_name WHERE accounts.user_name = '" . $username . "'";
	$result = mysql_query($sql);
	echo 'Your Transactions are:';
	//Create Table for Transactions!
	echo "<table style='width:100%' border='1'>";	
		$columns = array(
	0   => "User Name",
	1	=>	"Balance",
	2	=>	"Lodgments",
	3	=>	"Withdrawal",
	4	=>	"Reference",);
	
	$count = count($columns);
	// Generate headers ..
	
	for ($i = 0; $i < $count; $i++) {
		echo	"<th>";
		echo	$columns[$i];
		echo	"</th>";
	}
	echo	"</tr>";
	
	while($row = mysql_fetch_array($result)){
  echo "<tr>";
  echo "<td>" . $row['user_name'] . "</td>";
  echo "<td>" . $row['balance'] . "</td>";
  echo "<td>" . $row['lodgments'] . "</td>";
  echo "<td>" . $row['withdrawal'] . "</td>";
  echo "<td>" . $row['reference'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
     
	
	
	$sql = "SELECT * FROM accounts";
	$result = mysql_query($sql);
	
	echo '<br><br><br>Users already registered!<br>';	
		//Create Table for ALL Users!
	echo "<table style='width:100%' border='1'>";	
		$columns = array(
	0   => "User Name",
	1	=>	"Balance",);
	
	$count = count($columns);
	// Generate headers ..
	
	for ($i = 0; $i < $count; $i++) {
		echo	"<th>";
		echo	$columns[$i];
		echo	"</th>";
	}
	echo	"</tr>";
	
	while($row = mysql_fetch_array($result)){
  echo "<tr>";
  echo "<td>" . $row['user_name'] . "</td>";
  echo "<td>" . $row['balance'] . "</td>";
  echo "</tr>";
  }
echo "</table>";	
		
	
	// List ALL TRANSACTIONS from ALL USERS!
	$sql = "SELECT * FROM accounts LEFT JOIN transactions ON accounts.user_name = transactions.user_name";
	$result = mysql_query($sql);
	echo 'ALL USER Transactions are:';
	//Create Table for ALL Transactions!
	echo "<table style='width:100%' border='1'>";	
		$columns = array(
	0   => "User Name",
	1	=>	"Balance",
	2	=>	"Lodgments",
	3	=>	"Withdrawal",
	4	=>	"Reference",);
	
	$count = count($columns);
	// Generate headers ..
	
	for ($i = 0; $i < $count; $i++) {
		echo	"<th>";
		echo	$columns[$i];
		echo	"</th>";
	}
	echo	"</tr>";
	
	while($row = mysql_fetch_array($result)){
  echo "<tr>";
  echo "<td>" . $row['user_name'] . "</td>";
  echo "<td>" . $row['balance'] . "</td>";
  echo "<td>" . $row['lodgments'] . "</td>";
  echo "<td>" . $row['withdrawal'] . "</td>";
  echo "<td>" . $row['reference'] . "</td>";
  echo "</tr>";
  }
echo "</table>";
	?>
	</body>
	<footer>
	<style>
	footer{
	text-align:center;
	font-family:'riesling';
	font-size:1em;
	}
	
	</style>
	<p>Julie-Anne Glennon 			Student Number 2864184</p>
	</footer>	
	
</html>
