
<!DOCTYPE HTML>
<html>
<body>
<form action="Enternew.html">
<input type="submit" value="back to enter data page">
</form>
      <?php
	$pname=$_POST["pname"];
	$foodprice=$_POST["price"];
	$foodstock=$_POST["stock"]; 
	try {
   require("connect.php");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO products (ID,Name, Price, Stock)
    VALUES (NULL,'$pname', '$foodprice', '$foodstock')";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "New record created successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
?> 

<form method="post" action="Adminsplash.php">
    <input type="submit" name="gohome" value="Back to Splash Page">
</form>

    </body>
</html>