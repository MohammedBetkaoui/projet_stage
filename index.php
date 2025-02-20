<?php 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
   
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php';
    
?>
<?php
   if (isset($_SESSION['user_id'])) {
?>
<h1 class="welcome">hello <?php echo $_SESSION['username']; ?></h1>
<?php
   }else {
?>

<h1 class="welcome">Welcome to our website!</h1>
<?php
   }
   ?>
   <style>
    
    .welcome {
        text-align: center;
        margin-top: 100px;
    }


    
   </style>