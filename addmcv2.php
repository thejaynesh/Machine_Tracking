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
            header('Location: addmcv2.php');
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
                header('Location: addmcv2.php');
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
                    header("Location:homev2.php");
                    return;
                }
                $company_id=$row['company_id'];

//Adding supplier

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
                header('Location: homev2.php');
                return;
            }
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
            
   <center>         <h1>ADD MACHINE</h1></center>
    
    <div id="error" style="color: red; margin-left: 90px; margin-bottom: 5px;">
    </div>
    <?php
    if ( isset($_SESSION['error']) )
    {
        echo('<center><p style="color: red;">'.$_SESSION['error']."</p></center>\n");
        unset($_SESSION['error']);
    }
    if ( isset($_SESSION['success']))
        {
            echo('<center><p style="color: green;">'.$_SESSION['success']."</p></center>\n");
                unset($_SESSION['success']);
        }
    ?>

    <form method="POST" action="addmcv2.php" class="register-form" id="register-form">
    <div class="form-row">
    <div class="form-group">
    <div class="form-input">
         <label for="mc no." class="required">Machine No.</label>
             <input type="text" name="mac_addr" id="mac_Addr" onchange="Number('mac_addr')" required="" placeholder="Computer No. (only integers)">
                              </div>
    <span style="color:#7386D5">If adding multiple PC then enter starting machine ID and rest will be assigned in succession</span>
    
    <div class="form-input">
         <label for="gr no." class="required">GR Number</label>
             <input type="text" name="grn" id="grn" required="" placeholder="Good Reciept No./Bill No.">
                              </div>
    
    
    <div >
         <label for="processor" class="required" >Processor</label>
    <select name="processor" class="form-control" onchange="Device();" required="">
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
    </div>
    <div >
         <label for="ram" class="required" >RAM</label>
    <select  name="ram" class="form-control" onchange="Device();" required="">
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
    </div>
    
    <div >
         <label for="Storage" class="required" >Storage</label>
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
    </div>
    
    <div >
         <label for="Mouse" class="required" >Mouse</label>
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
    </div>
    
    <div >
         <label for="keyboard" class="required" >Keyboard</label>
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
    </div>
    
    <div >
         <label for="monitor" class="required" >Monitor</label>
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
        </div>
    <div >
         <label for="Storage" class="required" >Operating System</label>
    <select id="drop-other" name="os" class="form-control" onchange="Device();" required="">
       <option value="windows">Windows</option>
       <option value="linux">Linux</option>
       <option value="osx">OS X</option>
   </select>
     </div><br/>
     </div>
                            <div class="form-group">
                            <div class="form-input">
         <label for="input-group-addon" >Other Details</label>
    
    <input type="text" name="other" > </div>  
    
    <div class="form-input">
         <label for="input-group-addon" class="required">Price of Purchase  &#8377 </label>
    <input type="text" name="price" required=""  id="price" onchange="Number('price')" placeholder="Individual PC Cost"> </div>
    
    <div class="form-input">
         <label for="input-group-addon" class="required">Date of Purchase </label>
    <input type="text"  name="dop"  id="date" value = '<?= date('y-m-d') ?>'> </div>
    

    <div class="form-input">
         <label for="input-group-addon" class="required">Company Name </label>
        <select id="drop-other-company" name="company" class="form-control"  onchange="Company();" required="">
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
         <label for="input-group-addon" >New Company Name </label>
        <input type="text" class="form-control" name="company2" id="hide-drop-other-company" onchange="Other('hide-drop-other')" placeholder="Company Name">
    </div>
    <input type="text" id="alert-server-new-company"name="alert-server-new" value="1" hidden>


    <div class="form-input">
         <label for="input-group-addon" class="required">Supplier </label>
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
         <label for="input-group-addon" >New Supplier Name </label>   
        <input type="text" class="form-control" name="supplier2" id="other-supplier" onchange="Other('other-supplier')" placeholder="Supplier Name">
    </div>
    <input type="text" id="alert-server-new-supplier"name="alert-server-new-supplier" value="1" hidden>

<div class="form-input">
         <label for="input-group-addon" class="required">Enter Quantity </label>
    <input type="number" required="" class="form-control" name="qty" min="1" placeholder="Number of Computers"></span>
      <br>
    <div class="form-submit">
        
    <input type="submit" value="Add Machine" name="add" id="Submit" class="Submit">
    <input type="reset" value="Reset" class="submit" id="reset" name="reset" />
        </div>
    </form>

    </div>
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