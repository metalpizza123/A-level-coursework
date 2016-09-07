<?php
session_start();
?>
<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>Buy Page</title>
</head>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo($_SESSION['username']);
echo($_SESSION['walletvalue']);

var_dump($_POST);
?>
    <form method="post" action="<?php echo "#" ?>">
    <p>Please Select a Product</p>
    <select name="formproduct" id="formproduct">
    <?php
    session_start(); 
try {   require("connect.php");
    // set the PDO error mode to exception
     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $stmt = $conn->prepare("SELECT Name FROM products"); 
     $stmt->execute();
     // set the resulting array to associative
     //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
while($data=$stmt->fetch(PDO::FETCH_ASSOC)){ 
    //this is where the table is actually produced
    echo( '<option value="'.$data['Name'].'">'.$data['Name']."</option>");
}
 }
       catch(PDOException $e) {
     echo "Error: " . $e->getMessage();
}
        ?>  
    </select>
    
   <br>
   <input type="submit" name="refreshtable" value="check item">
</form>
  <?php
  if (isset($_POST['eatingtable'])){
    $stmt7=$conn->prepare("SELECT * FROM `products` WHERE `Name`=:item");
       $stmt7->bindParam(":item",$_POST['formproduct']);
       $stmt7->execute();
       $data7=$stmt7->fetch(PDO::FETCH_ASSOC);
      $canibuy=$data7['Stock']-$_POST['quantity'];
      //deduct amount ordered from stock available
      echo($canibuy);
      if ($canibuy<0){
       echo("not enough stock");
                }
      else {
        echo  ('Your Order of '.($_POST['quantity']).' x '.($_POST['formproduct'].' has been placed.'));
        // need to add in the order.
        //I'll fetch EVERYTHING first
        $stmt3= $conn->prepare ("SELECT * FROM `products` WHERE 'Name'=:fooditem");
        $stmt3->bindParam(":fooditem",$_POST['formproduct']);
        $stmt3->execute();
        $data3=$stmt3->fetch(PDO::FETCH_ASSOC);
        $stmt4=$conn->prepare ("SELECT * FROM `Users` WHERE 'Username'=:loginuser");
        $stmt4->bindParam(":loginuser",$_SESSION['username']);
        $stmt4->execute();
        $data4=$stmt4->fetch(PDO::FETCH_ASSOC);
        //Bind everything to variables for the database entry
        $ProductID=$data3['ID'];
        $Productprice=$data3['Price']*$_POST['quantity'];
        $Newstock=$data3['Stock']-$_POST['quantity'];
        $Orderstock=$_POST['quantity'];
        $User=$_SESSION['username'];
        $tim=time();
        $stmt5=$conn->prepare("INSERT INTO `Orders` (OrderID,ProductID,Quantity,Price,OrderDate,UserID,Verified,Delivered) VALUES (Null,'$ProductID','$Orderstock','$Productprice','$tim','$User',False,False) ");
        $stmt5->execute();
        }
      }
  if (isset($_POST['refreshtable']) and !isset($_POST['eatingtable'])){
    //so this stuff pops out when you've pressed the first button to check the product
       $stmt2=$conn->prepare("SELECT * FROM `products` WHERE `Name`=:item");
       $stmt2->bindParam(":item",$_POST['formproduct']);
	     $stmt2->execute();
	     $data2=$stmt2->fetch(PDO::FETCH_ASSOC);
    echo ("there are currently ".$data2['Stock']."x".$_POST['formproduct']." left in stock.".'<br>');
       // now let them select how many they want diabetes is for 10 units
	     if ($data2['Stock']<1){ 
        // don't spawn the table 
        echo ("there isn't enough stock for you to order");
       }
       //all this stuff is for quantity, so i chose to do the whole thing in html
       else {

        echo('<form method="post" action="#">
          <input name="formproduct" value="' . $_POST['formproduct'] . '" />
          <select name="quantity" id="quantity">
        <option value="1"> 1 </option>
        <option value="2"> 2 </option>
        <option value="3"> 3 </option>
        <option value="4"> 4 </option>
        <option value="10">Diabetes</option>
        </select>
        <input type="submit" name="eatingtable" value="check item">
        </form>
              ') ;
      }
	
  }
?>

</body>
</html>