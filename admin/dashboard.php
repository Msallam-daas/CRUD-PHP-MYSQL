<?php
session_start();
if(isset($_SESSION['username'])){
    $pageTitle = 'Dashboard';

    include "./init.php";
    echo 'welcome';
    include "./footer.php"; 
}else{
    header('location:index.php');
    exit();
}
?>