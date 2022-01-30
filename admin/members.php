<?php

session_start();
if(isset($_SESSION['username'])){
    $pageTitle = 'Members';

    include "./init.php";
    $do=isset($_GET['do']) ? $_GET['do'] :'Manage';

    if($do == 'Manage'){//Manage Page
    
        $stmt= $con->prepare("SELECT * FROM users WHERE GroupID != 1 ");

    $stmt->execute();

    $rows =$stmt->fetchAll();
    
    
    ?>
<h1 class="text-center">Manage Member</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td>#ID</td>
                    <td>Username</td>
                    <td>Email</td>
                    <td>Full Name</td>
                    <td>Registerd Date</td>
                    <td>Control</td>
                </tr>
                 <?php
                     
                     foreach($rows as $row){
                         echo "<tr>";
                         echo "<td>" . $row['UserID'] . "</td>";
                         echo "<td>" . $row['Username'] . "</td>";
                         echo "<td>" . $row['Email'] . "</td>";
                         echo "<td>" . $row['FullName'] . "</td>";
                         echo "<td></td>";
                        echo "<td>
                         <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'>Edit</a>
                         <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger'>Delete</a>
                     </td>";
                         echo"</tr>";
                     }

                    ?>

                <tr>
                   
   
            </table>
        </div>
        <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i>Add New Member</a>
            </div>
    

   
    

  <?php  }elseif ($do == 'Add'){ //Add Page ?>

<h1 class="text-center">Add New Member</h1>
    <div class="container">
        <form class="form-container" action="?do=Insert" method="POST">

        <div class="form-group" >
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" class="form-control" autocomplete="off" required="required"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10 ">
                <input type="password" name="password" class="form-control"  autocomplete="new-password" required="required"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" class="form-control" required="required"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">FullName</label>
            <div class="col-sm-10">
                <input type="text" name="full"  class="form-control" required="required"/>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Add Member" class="btn btn-primary"/>
            </div>
        </div>

        </form>
    </div>
        
    
  <?php
  }elseif ($do == 'Insert'){  //Insert Page

    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        echo "<h1 class='text-center'>Insert Member</h1>";
        echo "<div class=container'>";


        $user  = $_POST['username'];
        $pass  = $_POST['password'];
        $email  = $_POST['email'];
        $name  = $_POST['full'];
       
        $hashPass=sha1($_POST['password']);
        
        //Insert the database with this info
        $stmt= $con->prepare("INSERT INTO 
        users (Username, Password, Email, FullName) 
        VALUES(:zuser, :zpass, :zmail, :zname) ");
        $stmt->execute(array(
            'zuser' => $user,
            'zpass' =>$pass,
             'zmail' =>$email,
              'zname' =>$name, 
            ));
        echo $stmt->rowCount() . 'Record Inserted';

    }else{
        echo 'Sorry You Cant Browse This Page';  
    }
    echo "</div>";
}
elseif ($do == 'Edit'){  //Edit Page
   $userid= isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval ($_GET['userid']): 0;
   $stmt=$con->prepare("SELECT * FROM users WHERE UserID= ?  LIMIT 1");
   $stmt->execute(array($userid));
   $row=$stmt->fetch();
   $count=$stmt->rowCount();
   if($count > 0 ){ ?>
    <h1 class="text-center">Edit Member</h1>
    <div class="container">
        <form class="form-container" action="?do=Update" method="POST">
        <input type="hidden" name="userid" value="<?php echo $userid ?>"/>

        <div class="form-group" >
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" class="form-control" value="<?php echo $row['Username']?>"autocomplete="off"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10 ">
            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>"/>
                <input type="password" name="newpassword" class="form-control"  autocomplete="new-password"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" value="<?php echo $row['Email']?>" class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">FullName</label>
            <div class="col-sm-10">
                <input type="text" name="full" value="<?php echo $row['FullName']?>" class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-primary"/>
            </div>
        </div>

        </form>
    </div>
      
<?php 
   } else {
       echo 'There no are such ID';
   }
}elseif ($do =='Update') {
    echo "<h1 class='text-center'>Update Member</h1>";
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id  = $_POST['userid'];
        $user  = $_POST['username'];
        $email  = $_POST['email'];
        $name  = $_POST['full'];

        $pass='';
        if(empty($_POST['newpassword'])){
            $pass=$_POST['oldpassword'];
        }else{
            $pass=sha1($_POST['newpassword']);
        }
        
        //Update the database with this info
        $stmt= $con->prepare("UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID =?");
        $stmt->execute(array($user, $email, $name, $pass, $id));
        echo $stmt->rowCount() . 'Record Updated';

    }else{
        echo 'Sorry You Cant Browse This Page';  
    }
    
}elseif ($do =='Delete') {
    $userid= isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval ($_GET['userid']): 0;
    $stmt=$con->prepare("SELECT * FROM users WHERE UserID= ?  LIMIT 1");
    $stmt->execute(array($userid));
    $count=$stmt->rowCount();
    if($count > 0 ){ 
        $stmt=$con->prepare("DELETE FROM users WHERE UserID = :zuser");
        $stmt->bindparam(":zuser", $userid);
        $stmt->execute();
        echo $stmt->rowCount() . 'Record Deleted';

    }else{
        echo "THIS ID IS NOT EXIST";
    }
} 
    include "./footer.php"; 
}else{
    header('location:index.php');
    exit();
}

?>







