<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    if( $_SESSION['role'] != '0' )
    {
        die('ACCESS DENIED');
    }
    if(isset($_POST['cancel']))
    {
        header("Location: home.php");
        return;
    }

    if(isset($_POST['device-name']) )
    {
        if ( strlen($_POST['device-name']) < 1   || strlen($_POST['description']) < 1 || strlen($_POST['price']) < 1 || strlen($_POST['grn']) < 1 || strlen($_POST['qty'])<1)
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: adddevice.php');
            return;
        }
        else
        {
            $_POST['dop']=date('y-m-d',strtotime($_POST['dop']));  
            if($_POST['alert-server-new-device']=='1')
            {                    
                $read=$pdo->prepare('SELECT * from name where name = :name');
                $read->execute(array(':name'=>$_POST['device-name2']));
                $rowr=$read->fetch(PDO::FETCH_ASSOC);
                if($rowr == false)
                {
                    $req=$pdo->prepare('INSERT INTO name(name) VALUES(:name)');
                    $req->execute(array(':name'=>$_POST['device-name2']));
                    $devname=$_POST['device-name2'];    
                }
                else
                {
                    $devname=$_POST['device-name2'];
                }
                
            }
            else
                $devname=$_POST['device-name'];
            $req2 = $pdo->prepare("SELECT name_id from name where name = :name");
            $req2->execute(array(":name" => $devname));
            $row = $req2->fetch(PDO::FETCH_ASSOC);
            if($row == false)
            {
                $_SESSION['error'] = "Unexpected Error occured while adding Name".$devname;
                header("Location:home.php");
                return;
            }

            $name_id=$row['name_id'];
            if($_POST['alert-server-new-company']=='1')
            {
                    //This will insert in company if alert server new is 1 it is alert that will be issued if other device is selected. First entry will be made then id will be selected

                    $read=$pdo->prepare('SELECT * from company where name = :name');
                    $read->execute(array(':name'=>$_POST['company2']));
                    $rowr=$read->fetch(PDO::FETCH_ASSOC);

                    if($rowr == false)
                    {
                        $req=$pdo->prepare('INSERT INTO company(name) VALUES(:name)');
                        $req->execute(array(':name'=>$_POST['company2']));
                        $cmn=$_POST['company2'];
                    }
                    else
                    {
                        $cmn=$_POST['company2'];
                    }
            }
            else 
             $cmn=$_POST['company'];
            $req2 = $pdo->prepare("SELECT company_id from company where name = :name");
            $req2->execute(array(":name" => $cmn));
            $row = $req2->fetch(PDO::FETCH_ASSOC);
            if($row == false)
            {
                $_SESSION['error'] = "Unexpected Error occured while adding company".$cmn;
                header("Location:home.php");
                return;
            }
            $company_id=$row['company_id'];



            if($_POST['alert-server-new-supplier']=='1')
            {
                //This will insert in company if alert server new is 1 it is alert that will be issued if other device is selected. First entry will be made then id will be selected

                $req=$pdo->prepare('SELECT *,COUNT(*) from supplier where supname = :supname');
                $req->execute(array(':supname'=>$_POST['supplier']));
                $rowrr=$req->fetch(PDO::FETCH_ASSOC);
                if($rowr['COUNT(*)']==0)
                {
                    $req=$pdo->prepare('INSERT INTO supplier(supname) VALUES(:name)');
                    $req->execute(array(':name'=>$_POST['supplier2']));
                    $supname=$_POST['supplier2'];    
                    $stmt = $pdo->prepare("SELECT * FROM supplier WHERE supname = :name");
                    $stmt->execute(array(":name" => $supname));
                    $smn = $stmt->fetch(PDO::FETCH_ASSOC);
                    $smn = $_POST['supplier2'];
                }
                else
                {
                    $supname=$_POST['supplier2'];
                    $smn = $_POST['supplier2'];
                }

            }
            else 
                $smn=$_POST['supplier'];
           
            //Adding description
            if($_POST['alert-server-new-description']=='1')
            {
                $req=$pdo->prepare("SELECT * FROM hardware WHERE description = :desc and name =:name");
                $req->execute(array(":desc"=>$_POST['description2'],":name"=>$name_id));
                $rowr=$req->fetch(PDO::FETCH_ASSOC);
                if($rowr == false)
                {
                    $req=$pdo->prepare("INSERT INTO specification (spec,name_id) VALUES(:spec,:name_id)");
                    $req->execute(array(":spec"=>$_POST['description2'],":name_id"=>$name_id));
                }
                $stmt=$pdo->prepare("SELECT spec_id FROM specification WHERE spec=:spec AND name_id=:name");
                $stmt->execute(array(":spec"=>$_POST['description2'],":name"=>$name_id));
                $descriptionid=$stmt->fetch(PDO::FETCH_ASSOC);
                $descriptionid=$descriptionid['spec_id'];
            }
            else
            {
                $stmt=$pdo->prepare("SELECT spec_id FROM specification WHERE spec=:spec AND name_id=:name");
                $stmt->execute(array(":spec"=>$_POST['description'],":name"=>$name_id));
                $descriptionid=$stmt->fetch(PDO::FETCH_ASSOC);
                $descriptionid=$descriptionid['spec_id'];
            }
            $req2 = $pdo->prepare("SELECT sup_id from supplier where supname = :name");
            $req2->execute(array(":name" => $smn));
            $row = $req2->fetch(PDO::FETCH_ASSOC);
            $supplier_id=$row['sup_id'];





            for($i=0;$i<$_POST['qty'];$i++)
            {


                $stmt = $pdo->prepare('INSERT INTO hardware (company, description, price, grn, name, state,supplier, DOP) VALUES (:company, :description, :price, :grn, :name, 0,:smn, :dop)');
                $stmt->execute(array(
                    ':company' => $company_id,
                 ':description' => $descriptionid, 
                 ':price' => $_POST['price'], 
                 ':grn' => $_POST['grn'],
                 ':name' => $name_id,
                    ':smn' => $supplier_id,
                    ':dop' => $_POST['dop']));
            }
                $_SESSION['success'] = "Device Added Successfully";
                header('Location: home.php');
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
        <h1>ADD DEVICE</h1>
        </div>
        <div id="error" style="color: red; margin-left: 90px; margin-bottom: 20px;">
        </div>
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
        <div class="col-xs-5">
        <form method="POST" action="adddevice.php">

        <!--<div class="input-group">
        <span class="input-group-addon">Device Name </span>
        <input type="text" name="device_name" required class="form-control"> </div><br/>-->

        <div class="input-group">
        <span class="input-group-addon">Device Name </span>
        <select name="device-name" id="drop-name" class="form-control" onchange="Device();fetch_select(this.value);" required="">
        <?php
            
            $qr=$pdo->query("SELECT DISTINCT name from name");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                echo ($rowx['name']);
                echo '</option>';
            }
         ?>
        <option selected="">Other</option>
        </select>
        </div><br/>
        <div class="input-group">
            <span class="input-group-addon">New Device Name </span>
            <input name="device-name2" type="text" class="form-control" name="device_name" id="hide-drop-name" placeholder="Enter New Device Name" onchange="Other('hide-drop-name')">
        </div><br>
        <input type="text" name="alert-server-new-device" value="1" id="alert-server-new-device" hidden>
        
        <div class="input-group">
        <span class="input-group-addon">Company Name</span>
        <select id="drop-other-company" name="company" class="form-control" onchange="Company();" required="">
        <?php
            
            $qr=$pdo->query("SELECT DISTINCT name from company");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                echo ($rowx['name']);
                echo '</option>';
            }
         ?>
    <option selected="">Other</option>
    </select>
    </div><br>
    <div class="input-group">
        <span class="input-group-addon">New Company Name</span>   
        <input type="text" class="form-control" name="company2" id="hide-drop-other-company" onchange="Other('hide-drop-other')" placeholder="Enter New Company Name">
    </div><br>
    <input type="text" id="alert-server-new-company" name="alert-server-new-company" value="1" hidden>



     <div class="input-group">
        <span class="input-group-addon">Supplier</span>
        <select id="drop-supplier" name="supplier" class="form-control" onchange="Supplier();" required="">
        <?php
            
            $qr=$pdo->query("SELECT DISTINCT supname from supplier");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option>';
                echo ($rowx['supname']);
                echo '</option>';
            }
         ?>
    <option selected="">Other</option>
    </select>
    </div><br>
    <div class="input-group">
        <span class="input-group-addon">New Supplier Name</span>   
        <input type="text" class="form-control" name="supplier2" id="other-supplier" onchange="Other('other-supplier')" placeholder="Enter New Supplier Name">
    </div><br>
    <input type="text" id="alert-server-new-supplier"name="alert-server-new-supplier" value="1" hidden>



     <div class="input-group">
        <span class="input-group-addon">Description</span>
        <select id="drop-description" name="description" class="form-control" onchange="Description();" required="">
            <option selected="">Other</option>
    </select>
    </div><br>
    <div class="input-group">
        <span class="input-group-addon">New Description</span>   
        <input type="text" class="form-control" name="description2" id="other-description" onchange="Other('other-description')" placeholder="Specification of Device">
    </div><br>
    <input type="text" id="alert-server-new-description" name="alert-server-new-description" value="1" hidden>
    
        <div class="input-group">
        <span class="input-group-addon">Price of Purchase  &#8377</span>
        <input type="text" name="price" required class="form-control" id="pr" onchange="Number('pr')" placeholder="0000000"> </div><br/>

        <input type="text"  name="dop" hidden="" id="date" value = '<?= date('y-m-d') ?>'> 


        <div class="input-group">
        <span class="input-group-addon">GR No. </span>
        <input type="text" name="grn" required class="form-control" id="gr" placeholder="Good Reciept No./Bill No."> </div><br/>

        <div class="input-group">
        <span class="input-group-addon">Quantity </span>
        <input type="number" name="qty" required class="form-control" id="qty" onchange="Number('qty')"placeholder="Total Quantity" MIN=1> </div><br/>

        <input type="submit" value="Add Device" class="btn btn-info">
        <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
        </form>

    </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <script type="text/javascript">
        

    </script>
</body>
</html>
