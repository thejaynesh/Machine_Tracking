<?php
    session_start();
    require_once "pdo.php";
    
    if(isset($_POST['cancel']))
    {
        header("Location: index.php");
        return;
    }
    if(!isset($_SESSION['id']))
    {
        die("ACCESS DENIED");
    }

    if(isset($_POST['department']) )
    {
        if ( strlen($_POST['department']) < 1 || strlen($_POST['purpose']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: issue_request.php');
            return;
        }
        else
        {
                 
                
                $date=date('y-m-d');
                $stmt=$pdo->prepare("SELECT name_id from name WHERE name =:name");
                $stmt->execute(array(":name"=>$_POST['hardware']));
                $rowname=$stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $pdo->prepare('INSERT INTO `issue_request`( `department`, `id`, `purpose`, `date_of_request`, `name_of_hardware`) VALUES (:department,:id,:purpose, :dat,:hardware)');
                    $stmt->execute(array(':dat' => date('y-m-d'),
                      ':department' => $_POST['department'],
                       ':purpose' => $_POST['purpose'],
                       ':id'=>$_SESSION['id'],
                       ':hardware'=>$rowname['name_id']
                   ));
                    echo $rowname['name_id'].$_POST['hardware'];
                $_SESSION['success'] = "Issue Request Sent Successfully<br>";

                    if(isset($_SESSION['id']))
                        header("Location:home.php");
                    else
                        header('Location: index.php');
                        
                    return;
          

        }
    }
?>
<html>
<head>
    <title>Machine Tracking</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width = device-width, initial-scale = 1">

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    <link rel="stylesheet" type="text/css" href="style5.css">
    <style>
        .input-group-addon {
        min-width:150px;
        text-align:left;
    }
    </style>
</head>
<body>
                   <div class="wrapper">
        <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navbar.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
    <div class="container-fluid row" id="content">
    <div class="page-header">
    <h1>ISSUE HARDWARE REQUEST</h1>
    </div>
     <div id="error" style="color: red; margin-left: 90px; margin-bottom: 20px;"></div>
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

    <form method="POST" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">Department </span>
    <input type="text" name="department" required="" class="form-control" placeholder="Department Name" id="deprt" onchange="Names('deprt')"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Purpose</span>
    <input type="text" name="purpose" required="" class="form-control" id="purp" onchange="Purpose('purp')"> </div><br/>
    <div class="input-group">
    <span class="input-group-addon">Hardware Name</span>
    <select name="hardware" class="form-control">
           <?php
                $qr=$pdo->query("SELECT * FROM hardware WHERE state=0 GROUP BY description");
                while($row=$qr->fetch(PDO::FETCH_ASSOC))
                {
                    $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :name_id");
                    $pro->execute(array(':name_id' => $row['description']));
                    $name=$pdo->prepare("SELECT name from name where name_id = :name");
                    $name->execute(array(":name"=>$row['name']));
                    $namer=$name->fetch(PDO::FETCH_ASSOC);
                    $pron = $pro->fetch(PDO::FETCH_ASSOC);
                    echo "<option value ='". $namer['name']."'>".$namer['name'].' '.$pron['spec']."</option>";
                }
            ?>   
    </select>
    </div><br/>
    

    <input type="submit" value="Register Issue Request" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>