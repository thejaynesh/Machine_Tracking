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
        header("Location: homev2.php");
        return;
    }

    if(isset($_POST['device-name']) )
    {
        if ( strlen($_POST['device-name']) < 1   || strlen($_POST['description']) < 1 || strlen($_POST['price']) < 1 || strlen($_POST['grn']) < 1 || strlen($_POST['qty'])<1)
        {
            $_SESSION['error'] = "All Fields are required";
            header('Location: adddevv2.php');
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
                header("Location:homev2.php");
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
                header("Location:homev2.php");
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
                header('Location: homev2.php');
                return;
        }
    }
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>DigiTrack</title>
 
    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="style3.css">
    <link rel="stylesheet" href="css/form-style.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">

    <!-- Font Awesome JS --> 
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    <style>

th, td {
  padding: 15px;
  text-align:center;
}
td:hover{
    background-color:#c394ff;
    text-decoration:underline;
}
</style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <?php if (isset($_SESSION['id'])&&$_SESSION['role']=='0') include "navnew.php"; 
                else if(isset($_SESSION['id'])&&$_SESSION['role']=='1')  include "navbar_faculty.php";
                else include "navbar_tech.php";?>
           <div class="container-fluid row" id="content">

        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn-info">
                        <i class="fas fa-align-left"></i>
                        <span>Menu</span>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="#"><?php
   echo "You are logged in as - ".$_SESSION['name']." ".$_SESSION['lname']
?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Sign Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <br>
            
   <center><h1>ADD DEVICE</h1></center>
   
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
        <div class="">
        <form method="POST" class="register-form"action="adddevv2.php">

        <!--<div class="input-group">
        <span class="input-group-addon">Device Name </span>
        <input type="text" name="device_name" required class="form-control"> </div><br/>-->
        <div class="form-row">
        <div class="form-group">
        <div class="form-input">
        <label class="required" >Device Name </label>
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
        </div>
        <div class="form-input">
            <label>New Device Name </label>
            <input name="device-name2" type="text" class="form-control" name="device_name" id="hide-drop-name" placeholder="Enter New Device Name" onchange="Other('hide-drop-name')">
        </div>
        <input type="text" name="alert-server-new-device" value="1" id="alert-server-new-device" hidden>
        
        <div class="form-input">
        <label class="required">Company Name</label>
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
    </div> 
    <div class="form-input">
        <label>New Company Name</label>   
        <input type="text" class="form-control" name="company2" id="hide-drop-other-company" onchange="Other('hide-drop-other')" placeholder="Enter New Company Name">
    </div>
    <input type="text" id="alert-server-new-company" name="alert-server-new-company" value="1" hidden>



     <div class="form-input">
        <label class="required">Supplier</label>
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
    </div>
        <div class="form-input">
        <label>New Supplier Name</label>   
        <input type="text" class="form-control" name="supplier2" id="other-supplier" onchange="Other('other-supplier')" placeholder="Enter New Supplier Name">
    </div>
    <input type="text" id="alert-server-new-supplier"name="alert-server-new-supplier" value="1" hidden>



     <div class="form-input">
        <label class="required">Description</label>
        <select id="drop-description" name="description" class="form-control" onchange="Description();" required="">
            <option selected="">Other</option>
    </select>
    </div>
    <div class="form-input">
        <label>New Description</label>   
        <input type="text" class="form-control" name="description2" id="other-description" onchange="Other('other-description')" placeholder="Specification of Device">
    </div>
    <input type="text" id="alert-server-new-description" name="alert-server-new-description" value="1" hidden>
    
        <div class="form-input">
        <label class="required">Price of Purchase  &#8377</label>
        <input type="text" name="price" required class="form-control" id="pr" onchange="Number('pr')" placeholder="0000000"> </div>

        <input type="text"  name="dop" hidden="" id="date" value = '<?= date('y-m-d') ?>'> 


        <div class="form-input">
        <label class="required">GR No. </label>
        <input type="text" name="grn" required class="form-control" id="gr" placeholder="Good Reciept No./Bill No."> </div>

        <div class="form-input">
        <label class="required">Quantity </label>
        <input type="number" name="qty" required class="form-control" id="qty" onchange="Number('qty')"placeholder="Total Quantity" MIN=1> </div>

        <div class="form-submit">
        
        <input type="submit" value="Add Machine" name="add" id="Submit" class="Submit">
        <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
            </div>
        </form>

    </div>
   </div>
    </div>

    <div class="overlay"></div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <!-- Popper.JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    
    <script type="text/javascript" src="script.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function () {
            $("#sidebar").mCustomScrollbar({
                theme: "minimal"
            });

            $('#dismiss, .overlay').on('click', function () {
                $('#sidebar').removeClass('active');
                $('.overlay').removeClass('active');
            });

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').addClass('active');
                $('.overlay').addClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });
    </script>
</body>

</html>