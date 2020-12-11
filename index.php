<?php
    session_start();
    require_once "pdo.php";

    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }

    $salt='new_ton56*';

    if(isset($_POST['id']) && isset($_POST['pass']))
    {
        unset($_SESSION['id']);
        if ( strlen($_POST['id']) < 1 || strlen($_POST['pass']) < 1 )
        {
            $_SESSION['error'] = "User name and password are required<br>";
            header('Location: index.php');
            return;
        }
        else
        {
                $check = hash('md5', $salt.$_POST['pass']);
                $stmt = $pdo->prepare('SELECT * FROM member WHERE id = :id AND pass_word = :pw AND role = :role');
                $stmt->execute(array(':id' => $_POST['id'], ':pw' => $check, ':role' => $_POST['role']));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row !== false)
                {
                    $_SESSION['id'] = $row['member_id'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['name'] = $row['first_name'];
                    $_SESSION['lname'] = $row['last_name'];

                    header("Location: homev2.php");
                    return;
                }
                else
                {
                    $_SESSION['error'] = "Incorrect ID or Password<br>";
                    header("Location: index.php");
                    return;
                }
            
            
        }
    }
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>DigiTrack</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'>

      <link rel="stylesheet" href="loginassets/css/style.css">

  
</head>

<body>
  <div class="container">
	<header>
		<h1>
			<a href="#">
				<img src="img/digi_dark.png" alt="DigiTrack" >
			</a>
		</h1>
		
	</header>
	<br>
    <h3 class="text-center">Sign In</h3>
    <?php
        if ( isset($_SESSION['error']) )
        {
            echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
            unset($_SESSION['error']);
        }
        if ( isset($_SESSION['success']))
        {
            echo('<p style="color: green;">'.$_SESSION['success']."</p>\n");
            unset($_SESSION['success']);
        }
    ?>
	<form method="POST" action="index.php" >
                
                                        
                                            <input type="radio" name="role" id="cash" value="0" checked>
                                            &nbsp Administrator &nbsp
                                            <span class="check"></span>
                                        
                                            <input type="radio" name="role" id="cheque"  value="1" >
                                            &nbsp Faculty &nbsp
                                            <span class="check"></span>
                                        
                                            <input type="radio" name="role" id="demand"  value="2" >
                                            &nbsp Technician &nbsp
                                            <span class="check"></span>
                                        
                                    
                                    <hr>
		<label>
			<span class="label-text">User-Id</span>
			<input type="text" name="id" id="id" >
		</label>
		<label class="password">
			<span class="label-text">Password</span>
			
		
			<input type="password" name="pass" id="pass" >
		</label>
		<br>
		<div class="text-center">
			<input type="submit" value="Log in">
		</div>
	</form>
</div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

    <script src="loginassets/js/index.js"></script>

</body>
</html>