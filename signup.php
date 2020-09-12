<?php
    session_start();
    require_once "pdo.php";
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }

    $salt='new_ton56*';
    $signupcode='blue';


    if(isset($_POST['id']) )
    {
        if ( strlen($_POST['id']) < 1 || strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 || strlen($_POST['c_pass']) < 1 || strlen($_POST['supcd']) < 1|| strlen($_POST['contact_no']) < 1 )
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: signup.php');
            return;
        }
        else
        {
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM member WHERE id = :id');
            $stmt->execute(array(':id' => $_POST['id']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] !== '0')
            {
                $_SESSION['error'] = "This ID already exists";
                header('Location: signup.php');
                return;
            }

            if($_POST['pass'] === $_POST['c_pass'])
            {
                if(strlen($_POST['pass'])<8)
                {
                    $_SESSION['error'] = "Password must be atleast 8 character long";
                    header('Location: signup.php');
                    return;
                }
                else
                {
                    if($_POST['supcd']!==$signupcode)
                    {
                        $_SESSION['error'] = "Invalid Sign Up Code";
                        header('Location: signup.php');
                        return;           
                    }
                    $check = hash('md5', $salt.$_POST['pass']);
                    $stmt = $pdo->prepare('INSERT INTO member (id, first_name, last_name, email, pass_word,contact_no,role ) VALUES (:id, :fn, :ln, :em, :pw,:cn,"1")');
                    $stmt->execute(array(':id' => $_POST['id'], ':fn' => $_POST['first_name'], ':ln' => $_POST['last_name'], 
                      ':em' => $_POST['email'], 
                      ':pw' => $check,
                      ':cn' => $_POST['contact_no']));


                    $_SESSION['success'] = "Sign Up Successful";
                    header('Location: index.php');
                    return;
                }
            }
            else
            {
                $_SESSION['error'] = "Passwords do not match";
                header('Location: signup.php');
                return;
            }
        }
    }
?>
<html>
<head>
    <title>Medi-Caps Classroom</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="css/style.css" >
    <link rel="stylesheet" type="text/css" href="css/style_aos.css" >
    <link href="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <style>
        .input-group-addon {
        min-width:150px;
        text-align:left;
    }
    </style>
</head>
<body>
    <script>
      $(document).ready(function(){

         $("input[type=submit]").attr("disabled", "disabled");
        $('input').change(function(){
          var flag=0;
          if($('#pass').val()!=$('#cpass').val()) flag=1;
          if($('#pass').val().length==0||$('#cpass').val().length==0) flag=1;
          if(flag==0)
            {
              $("input[type=submit]").removeAttr("disabled"); 
            }
        });
        $('#pass').blur(function(){
          if($('#pass').val().length<8){
            $('#pwd').html('Password must be of atleast 8 characters');
            $('#pass').val('');
          }
          else {
            $('#pwd').html('');
          }
        });
        $('#cpass').blur(function(){
          if($('#cpass').val()!='')
          {
            if($('#pass').val()!=$('#cpass').val())
              $('#cpwd').text("Password does not match");
            else
            {
              $('#cpwd').css("color","WHITE");
              $('#cpwd').text("Password matched");
              $("input[type=submit]").removeAttr("disabled");             
            }
          }
        });
      });
    </script>
    <div class="background-add"></div>
    <div class="container">
    <div class="page-header">
    <h1 class="white align-center">Sign Up</h1>
    </div>
    <?php
    if ( isset($_SESSION['error']) )
    {
        echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <div data-aos="flip-left" class=row>
      <form method="POST" action="signup.php" class="col-xs-6 col-xs-offset-3">

      <div class="input-group">
      <span class="input-group-addon">ID</span>
      <input type="text" name="id" class="form-control" required placeholder="Enter user ID"> </div><br/>
      <div class="input-group">
      <span class="input-group-addon">First Name</span>
      <input type="text" name="first_name" class="form-control" required placeholder="Enter First Name"> </div><br/>
      <div class="input-group">
      <span class="input-group-addon">Last Name</span>
      <input type="text" name="last_name" class="form-control" required placeholder="Enter Last Name"> </div><br/>
      <div class="input-group">
      <span class="input-group-addon">Email</span>
      <input type="email" name="email" class="form-control" required placeholder="Enter a valid Email"> </div><br/>
      <div class="input-group">
      <span class="input-group-addon">Contact No.</span>
      <input type="text" name="contact_no" class="form-control" required placeholder="Enter a valid Contact number"> </div><br/>
      <div class="input-group">
      <span class="input-group-addon">Password</span>
      <input type="password" name="pass" id="pass" class="form-control" required placeholder="Enter Password(Minimum 8 character)"> </div><div class="errortext" id="pwd"></div><br/>
      <div class="input-group">
      <span class="input-group-addon">Confirm Password</span>
      <input type="password" name="c_pass" id="cpass" class="form-control" required placeholder="Confirm Password"> </div><div class="errortext" id="cpwd" ></div><br/>
      <div class="input-group">
      <span class="input-group-addon">Sign Up Code</span>
      <input type="password" name="supcd" id="supcd" class="form-control" required placeholder="Enter Sign Up Code"> </div><div class="errortext" id="cpwd" ></div><br/>
      <div class="row">
        <div class="col-xs-3 col-xs-offset-3">
          <input type="submit" value="Sign Up" class="btn btn-info">
        </div>
        <div class="col-xs-3 col-xs-offset-1">
          <a class ="link-no-format" href="index.php"><div class="btn btn-danger">Cancel</div></a>
      </div>
      </form>
    </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdn.rawgit.com/michalsnik/aos/2.1.1/dist/aos.js"></script>
    <script>
      $('.background-add').load('background.html');
    </script>
    <script>
      AOS.init();
	  </script>
</body>
</html>
