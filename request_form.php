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
        die("Please Login First");
    }
    else
    {
        $stmt=$pdo->prepare("SELECT first_name,last_name FROM member WHERE member_id = :id");
        $stmt->execute(array(":id"=>$_SESSION['id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $row['first_name'].' '.$row['last_name'];
    }
    if(isset($_POST['name']) )
    {
        if ( strlen($_POST['name']) < 1 || strlen($_POST['department']) < 1 || strlen($_POST['purpose']) < 1|| strlen($_POST['quantity']) < 1)
        {
            $_SESSION['error'] = "All Fields are required<br>";
            header('Location: request_form.php');
            return;
        }
        else
        {
                 
                
                $date=date('y-m-d');
                $stmt = $pdo->prepare('INSERT INTO transfer_request(date_of_request, name, department, purpose, processor, ram, hdd, os, quantity) VALUES (:dat, :name, :department, :purpose, :processor, :ram, :hdd, :os, :quantity)');
                    $stmt->execute(array(':dat' => date('y-m-d'), ':name' => $_POST['name'], ':department' => $_POST['department'], ':purpose' => $_POST['purpose'], ':processor' => $_POST['processor'], ':ram' => $_POST['ram'], ':hdd' => $_POST['hdd'], ':os' => $_POST['os'], ':quantity' => $_POST['quantity']));

                    $trid=$pdo->lastInsertId();
                    
                $_SESSION['success'] = "Request Sent Successfully<br>";
                    /*if(isset($_SESSION['id']))
                        header("Location:home.php");
                    else
                        header('Location: index.php');*/
                   // header("location:printform_request.php?trid=$trid");
                    //return;
                         echo("<script>
         window.open('printform_request.php?trid=$trid', '_blank'); 
</script>");
        echo("<script>window.open('home.php','_self')</script>");
            

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
    <h1>REQUEST COMPUTERS</h1>
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

    <form method="POST" action="request_form.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">Name </span>
    <input type="text" disabled="" required="" class="form-control" value="<?=$name?>" id="rname" onchange="Names('rname')"> </div><br/>
    <input type="text" name="name" hidden value="<?=$name?>">
    
    <div class="input-group">
    <span class="input-group-addon">Department </span>
    <input type="text" name="department" required="" class="form-control" placeholder="Department Name" id="deprt" onchange="Names('deprt')"> </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Purpose</span>
    <input type="text" name="purpose" required="" class="form-control" id="purp" onchange="Purpose('purp')"> </div><br/>

    <p>Required Specifications</p>
    <div class="input-group">
    <span class="input-group-addon">Processor</span>
    <select name=processor class="form-control" required="">
        <option value="NULL">Any</option>
        <?php
            
            $qr=$pdo->query("SELECT * from name where name = 'processor'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $processoriddb=$rowtmp['name_id'];

            $qr=$pdo->query("SELECT DISTINCT description from hardware WHERE name = $processoriddb");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Ram</span>
    <select name=ram class="form-control" required="">
        <option value="NULL">Any</option>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'ram'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $ramiddb=$rowtmp['name_id'];

            $qr=$pdo->query("SELECT DISTINCT description from hardware WHERE name = $ramiddb");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <div class="input-group">
    <span class="input-group-addon">Storage</span>
    <select name=hdd class="form-control" required="">
        <option value="NULL">Any</option>
        <?php
            $qr=$pdo->query("SELECT * from name where name = 'harddisk'");
            $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
            $memoryiddb=$rowtmp['name_id'];

            $qr=$pdo->query("SELECT DISTINCT description from hardware WHERE name = $memoryiddb");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                $pro = $pdo->prepare("SELECT spec FROM specification where spec_id = :spec_id");
                $pro->execute(array(':spec_id' => $rowx['description']));
                $pron = $pro->fetch(PDO::FETCH_ASSOC);
                echo($pron['spec']);
                echo '</option>';
            }
         ?>
    </select>
    </div><br/>

    <div class="input-group">
    <span class="input-group-addon">OS </span>
    <select id="drop-other" name="os" class="form-control" onchange="Device();" required="">
       <option value="NULL">Any</option>
       <option value="windows">Windows</option>
       <option value="linux">Linux</option>
       <option value="osx">OS X</option>
   </select>
     </div><br/>
    
    <div class="input-group">
    <span class="input-group-addon">Quantity</span>
    <input type="number" name="quantity" required class="form-control" placeholder="No. of Computers"> </div><br/>
    
    

    <input type="submit" value="Register Transfer Request" class="btn btn-info">
    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>