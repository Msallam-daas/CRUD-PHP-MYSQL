<?php

/*
Categories => [Manage | Edit | Update | Add | Insert | Delet |Stats]

*/ 

$do=isset($_GET['do']) ? $_GET['do'] :'Manage';


//If The Page Is Main Page 

if($do == 'Manage'){
   echo 'Welcome you Are in Manage Category Page' ;
   echo "</br>";
   echo '<a href="?do=Add">Add New Category +<a/>';
}elseif($do == 'Add'){
    echo 'Welcome you Are in Add Category Page' ;

}elseif($do == 'Insert'){
    echo 'Welcome you Are in Insert Category Page' ;

}else{
    echo 'Error There/s No Page with This Name' ;
}