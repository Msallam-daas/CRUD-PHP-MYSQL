<?php
session_start();
$noNavbar="";
$pageTitle = 'Login';
if(isset($_SESSION['username'])){
    header('location:dashboard.php');  
}
include "./init.php";
include "./header.php";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $username=$_POST['user'];
    $password=$_POST['pass'];
    $hashedPass=sha1($password);

  //check if user exist in database

  $stmt=$con->prepare("SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
  $stmt->execute(array($username, $hashedPass));
  $row=$stmt->fetch();
  $count=$stmt->rowCount();

  if($count>0){
      $_SESSION['username']=$username;
      $_SESSION['ID']=$row['UserID'];
      header('location:dashboard.php');
      exit();
  }
  $stmt=$con->prepare("SELECT Username, Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 0");
  $stmt->execute(array($username, $hashedPass));
  $count=$stmt->rowCount();

  if($count>0){
      $_SESSION['username']=$username;
      header('location:home.php');
      exit();
  }

  
}

?>
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off"/>
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password"/>
<input class="btn btn-primary btn-block" type="submit" value="login"/>
</form>

<?php
include "./footer.php";
?>