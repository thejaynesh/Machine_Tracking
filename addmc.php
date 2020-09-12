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
    //ID OF NAMES
    
    $qr=$pdo->query("SELECT * from name where name = 'keyboard'");
    $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
    $keyboardiddb=$rowtmp['name_id'];

    $qr=$pdo->query("SELECT * from name where name = 'mouse'");
    $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
    $mouseiddb=$rowtmp['name_id'];

    $qr=$pdo->query("SELECT * from name where name = 'harddisk'");
    $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
    $memoryiddb=$rowtmp['name_id'];

    $qr=$pdo->query("SELECT * from name where name = 'processor'");
    $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
    $processoriddb=$rowtmp['name_id'];

    $qr=$pdo->query("SELECT * from name where name = 'ram'");
    $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
    $ramiddb=$rowtmp['name_id'];

    $qr=$pdo->query("SELECT * from name where name = 'monitor'");
    $rowtmp=$qr->fetch(PDO::FETCH_ASSOC);
    $monitoriddb=$rowtmp['name_id'];

    if(isset($_POST['mac_addr']) )
    {
        if ( strlen($_POST['mac_addr']) < 1 || strlen($_POST['price']) < 1 || strlen($_POST['dop']) < 1)
        {echo ("<table class=\"table table-striped\">
                <tr> <th>S.no.</th><th>I.D.</th><th>First Name</th><th>Last Name</th><th>Email</th> </tr>");
            $_SESSION['error'] = "All Fields are required";
            header('Location: addmc.php');
            return;
        }
        else
        {
            /*$stmt = $pdo->prepare('SELECT COUNT(*) FROM machine WHERE MAC_ADDR = :mac_addr');
            $stmt->execute(array(':mac_addr' => $_POST['mac_addr']));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row['COUNT(*)'] !== '0')
            {
                $_SESSION['error'] = "This Machine already exists";
                header('Location: addmc.php');
                return;
            }*/
            
            {
                $mcid=($_POST['mac_addr']); 
                if($_POST['alert-server-new']=='1')
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
                  else{
                    $cmn=$_POST['company'];
                  }  

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

//Adding suppierrrrrrrr

                if($_POST['alert-server-new-supplier']=='1')
                {
                    //This will insert in company if alert server new is 1 it is alert that will be issued if other device is selected. First entry will be made then id will be selected


                    $req=$pdo->prepare('SELECT * from supplier where supname = :supname');
                $req->execute(array(':supname'=>$_POST['supplier2']));
                $rowrr=$req->fetch(PDO::FETCH_ASSOC);
                if($rowrr == false)
                {
                    $req=$pdo->prepare('INSERT INTO supplier(supname) VALUES(:name)');
                    $req->execute(array(':name'=>$_POST['supplier2']));
                    $supname=$_POST['supplier2'];    
                }
                else
                {
                    $supname=$_POST['supplier'];
                }

                }
                else 
                    $smn=$_POST['supplier'];
                $req2 = $pdo->prepare("SELECT sup_id from supplier where supname = :name");
                $req2->execute(array(":name" => $smn));
                $row = $req2->fetch(PDO::FETCH_ASSOC);
                $supplier_id=$row['sup_id'];

                for($i = 0;$i<$_POST['qty'];$i++)
                {
                    $stmt = $pdo->prepare('SELECT COUNT(*) FROM machine WHERE MAC_ADDR = :mac_addr');
                    $stmt->execute(array(':mac_addr' => $mcid));
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($row['COUNT(*)'] !== '0')
                    {
                        $_SESSION['error'] = "Other Machines already exists<br>";
                        $mcid++;
                        continue;
                    }

                    $_POST['dop']=date('y-m-d',strtotime($_POST['dop']));
                    //RAM PROCESSOR HARDDISK MOUSE KEYBOARD monitor LIZARD
                    $stmt= $pdo->prepare("INSERT INTO hardware ( `company`, `description`, `grn`, `name`, `state`,`supplier`, `DOP`) values 
                        (:company,:description_ram,:grn,:ram,1, :smn, :dop ),
                        (:company,:description_processor,:grn,:processor,1, :smn, :dop),
                        (:company,:description_hd,:grn,:memory,1, :smn, :dop),
                        (:company,:description_mouse,:grn,:mouse,1, :smn, :dop),
                        (:company,:description_keyboard,:grn,:kb,1, :smn, :dop),
                        (:company,:description_monitor,:grn,:monitor,1, :smn, :dop)
                    ");
                    $stmt->execute(array(
                        ':description_ram'=>$_POST['ram'],
                        ':description_processor'=>$_POST['processor'],
                        ':description_hd'=>$_POST['harddisk'],
                        ':description_mouse'=>$_POST['mouse'],
                        ':description_keyboard'=>$_POST['keyboard'],
                        ':description_monitor'=>$_POST['monitor'],
                        ':grn'=>$_POST['grn'],
                        ':ram' => $ramiddb,
                        ':processor' => $processoriddb,
                        ':memory' => $memoryiddb,
                        ':mouse' => $mouseiddb,
                        ':kb' => $keyboardiddb,
                        ':monitor'=> $monitoriddb,
                        ':company'=>$company_id,
                        ':smn'=>$supplier_id,
                        ':dop'=>$_POST['dop']
                        ));
                    $ramid=$pdo->lastInsertId();
                    $keyboardid=$ramid+4;
                    $mouseid=$ramid+3;
                    $hdid=$ramid+2;
                    $processorid=$ramid+1;
                    $monitorid=$ramid+5;
                    $stmt = $pdo->prepare('INSERT INTO machine (MAC_ADDR, processor, ram, memory, dop, price, state, os, monitor, keyboard, mouse, grn) VALUES (:mac_addr, :processorid, :ramid, :hdid, :dop, :price, :state, :os, :monitorid, :keyboardid, :mouseid, :grn)');
                        $stmt->execute(array(':mac_addr' => $mcid, ':grn' => $_POST['grn'], ':dop' => $_POST['dop'], ':price' => $_POST['price'], ':state' => "ACTIVE", ':os' => $_POST['os'], ':processorid' => $processorid, ':ramid' => $ramid, ':hdid' => $hdid, ':monitorid' => $monitorid, ':keyboardid' => $keyboardid, ':mouseid' => $mouseid));
                    
                    $_SESSION['success'] .= "Machine ".$mcid." Added Successfully <br>";
                        $mcid++;
                }
                header('Location: home.php');
                return;
            }
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
    <h1>ADD MACHINE</h1>
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

    <form method="POST" action="addmc.php" class="col-xs-5">

    <div class="input-group">
    <span class="input-group-addon">MACHINE No.</span>
    <input type="text" name="mac_addr" required="" class="form-control" id="mac_addr" onchange="Number('mac_addr')" placeholder="Computer No. (only integers)"></div>
    <span style="color:#7386D5">If adding multiple PC then enter starting machine ID and rest will be assigned in succession</span>
    
    <br/>
    <div class="input-group">
    <span class="input-group-addon">GR Number</span>
    <input type="text" name="grn" required="" class="form-control" id="grn" placeholder="Good Reciept No./Bill No."> </div><br/>
    
    <div class="input-group">
    <span class="input-group-addon">Processor </span>
    <select id="drop-other" name="processor" class="form-control" onchange="Device();" required="">
        <?php
            
            $qr=$pdo->query("SELECT spec,spec_id from specification where name_id=5");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value ='.$rowx[spec_id].'>';
                echo ($rowx['spec']);
                echo '</option>';
            }
         ?>
   </select>
    </div></br>

    



    <div class="input-group">
    <span class="input-group-addon">RAM </span>
    <select id="drop-other" name="ram" class="form-control" onchange="Device();" required="">
        <?php
            
            $qr=$pdo->query("SELECT spec,spec_id from specification where name_id=6");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value ='.$rowx[spec_id].'>';
                echo ($rowx['spec']);
                echo '</option>';
            }
         ?>
   </select>
    </div></br>
    
    <div class="input-group">
    <span class="input-group-addon">Storage </span>
    <select id="drop-other" name="harddisk" class="form-control" onchange="Device();" required="">
        <?php
            
            $qr=$pdo->query("SELECT spec,spec_id from specification where name_id=4");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value ='.$rowx[spec_id].'>';
                echo ($rowx['spec']);
                echo '</option>';
            }
         ?>
   </select>
    </div></br>
    
    <div class="input-group">
    <span class="input-group-addon">Mouse</span>
    <select id="drop-other" name="mouse" class="form-control" onchange="Device();" required="">
        <?php
            
            $qr=$pdo->query("SELECT spec,spec_id from specification where name_id=1");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value ='.$rowx[spec_id].'>';
                echo ($rowx['spec']);
                echo '</option>';
            }
         ?>
   </select>
    </div></br>
    
    <div class="input-group">
    <span class="input-group-addon">Keyboard</span>
    <select id="drop-other" name="keyboard" class="form-control" onchange="Device();" required="">
        <?php
            
            $qr=$pdo->query("SELECT spec,spec_id from specification where name_id=2");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value ='.$rowx[spec_id].'>';
                echo ($rowx['spec']);
                echo '</option>';
            }
         ?>
   </select>
    </div></br>
    
    <div class="input-group">
    <span class="input-group-addon">Monitor</span>
    <select id="drop-other" name="monitor" class="form-control" onchange="Device();" required="">
        <?php
            
            $qr=$pdo->query("SELECT spec,spec_id from specification where name_id=3");
            while($rowx=$qr->fetch(PDO::FETCH_ASSOC))
            {
                echo '<option value ='.$rowx[spec_id].'>';
                echo ($rowx['spec']);
                echo '</option>';
            }
         ?>
   </select>
    </div></br>
    
    <div class="input-group"> 
    <span class="input-group-addon">OS </span>
    <select id="drop-other" name="os" class="form-control" onchange="Device();" required="">
       <option value="windows">Windows</option>
       <option value="linux">Linux</option>
       <option value="osx">OS X</option>
   </select>
     </div><br/>
    <div class="input-group">
    <span class="input-group-addon">Other Details</span>
    <input type="text" name="other" class="form-control"> </div><br/>   
    
    <div class="input-group">
    <span class="input-group-addon">Price of Purchase  &#8377 </span>
    <input type="text" name="price" required="" class="form-control" id="price" onchange="Number('price')" placeholder="Individaul PC Cost"> </div><br/>
    
    <!--div class="input-group">
    <span class="input-group-addon">Date of Purchase</span-->
    <input type="text"  name="dop"  id="date" value = '<?= date('y-m-d') ?>'> <!--/div><br/-->
    

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
        <input type="text" class="form-control" name="company2" id="hide-drop-other-company" onchange="Other('hide-drop-other')" placeholder="Company Name">
    </div><br>
    <input type="text" id="alert-server-new-company"name="alert-server-new" value="1" hidden>


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
        <input type="text" class="form-control" name="supplier2" id="other-supplier" onchange="Other('other-supplier')" placeholder="Supplier Name">
    </div><br>
    <input type="text" id="alert-server-new-supplier"name="alert-server-new-supplier" value="1" hidden>



    
    <span class="input-group">
    <span class="input-group-addon">Enter Quantity</span>
    <input type="number" required="" class="form-control" name="qty" min="1" placeholder="Number of Computers"></span>
    <br>
    <input type="submit" value="Add Machine" name="add" id="go" class="btn btn-info">

    <a class ="link-no-format" href="home.php"><div class="btn btn-my">Cancel</div></a>
    </form>

    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
