<!DOCTYPE html>
<?php
    session_start();
    require_once "pdo.php";
    if( !isset($_SESSION['id']) )
    {
        die('ACCESS DENIED');
    }
    
    if(isset($_POST['cancel']))
    {
        header("Location: home.php");
        return;
    }
?>
<html>
	<head>
    	<title>Transfer Request</title>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width = device-width, initial-scale = 1">

    	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    	<link rel="stylesheet" type="text/css" href="style5.css">
	</head>
	<body>
		<form style="margin-left: 150px; margin-right: 300px; margin-top: 100px">
			<div style="text-align: center; padding-left: 110px">
				<b>
					<span>Medi-Caps University, Indore</span><br>
					<span>Requisition for Computer Systems</span>
				</b>
			</div>
		<br>
			<div class="container">
				<span class="col-xs-9"><b>Reciept No.: &nbsp</b>
					<?php
						$tid=$_GET['trid'];
						echo ($tid);
					?>
				</span>
				<span  style="text-align: right;"><b>Date: &nbsp</b></span>
				<?php
					//$tid=$_GET['trid'];
					$rdate = $pdo->prepare("SELECT date_of_request FROM transfer_request WHERE transfer_request_id=:tid");
					$rdate->execute(array(':tid' => $tid));
					$rdaten = $rdate->fetch(PDO::FETCH_ASSOC); 
					echo($rdaten['date_of_request']);//$_GET['trid']
				?>
			</div>
		<br><br><br>
			<div class="container">
				<span class="col-xs-12">
					<b>Name: &nbsp</b>
					<?php
						$rname = $pdo->prepare("SELECT name FROM transfer_request WHERE 	transfer_request_id=:tid");
						$rname->execute(array(':tid' => $tid));
						$rnamen = $rname->fetch(PDO::FETCH_ASSOC); 
						echo ($rnamen['name']);	
					?>
				</span>
			</div>
		<br>
			<div class="container">
				<span class="col-xs-12">
					<b>Department: &nbsp</b>
					<?php
						$rdep = $pdo->prepare("SELECT department FROM transfer_request WHERE transfer_request_id=:tid");
						$rdep->execute(array(':tid' => $tid));
						$rdepn = $rdep->fetch(PDO::FETCH_ASSOC); 
						echo ($rdepn['department']);	
					?>
				</span>
			</div>
		<br>
			<div class="container">
				<span class="col-xs-12">
					<b>Purpose: &nbsp</b>
					<?php
						$rpur = $pdo->prepare("SELECT purpose FROM transfer_request WHERE transfer_request_id=:tid");
						$rpur->execute(array(':tid' => $tid));
						$rpurn = $rpur->fetch(PDO::FETCH_ASSOC); 
						echo ($rpurn['purpose']);	
					?>
				</span>
			</div>
		<br>
			<div class="container">
				<span class="col-xs-12">
					<b>Specifications of the Machine required:</b>
					<?php
						/*$stmtcnt = $pdo->query("SELECT COUNT(*) FROM transfer_request");
        				$row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
        				if($row['COUNT(*)']!=='0')
        				{
            				$i=1;*/
            				$stmtread = $pdo->query("SELECT processor, ram, hdd, os, quantity FROM transfer_request");
            				echo ("<table class=\"table table-striped\">
                				<tr> <th>Processor</th><th>Ram</th><th>Storage</th><th>O.S.</th><th>Quantity</th> </tr>");
            				/*while ( $row = $stmtread->fetch(PDO::FETCH_ASSOC) )
            				{*/
                				echo ("<tr>");
                				
                				echo ("<td>");
                				$rpro = $pdo->prepare("SELECT processor FROM transfer_request WHERE transfer_request_id=:tid");
								$rpro->execute(array(':tid' => $tid));
								$rpron = $rpro->fetch(PDO::FETCH_ASSOC); 
								if($rpron['processor']=="NULL")
								{
									echo "Any";
								}
								else
								{
									echo ($rpron['processor']);
								}
                				echo ("</td>");
                				
                				echo ("<td>");
                				$rram = $pdo->prepare("SELECT ram FROM transfer_request WHERE transfer_request_id=:tid");
								$rram->execute(array(':tid' => $tid));
								$rramn = $rram->fetch(PDO::FETCH_ASSOC); 
								if($rramn['ram']=="NULL")
								{
									echo "Any";
								}
								else
								{
									echo ($rramn['ram']);
								}
                				echo ("</td>");

                				echo ("<td>");
                				$rsto = $pdo->prepare("SELECT hdd FROM transfer_request WHERE transfer_request_id=:tid");
								$rsto->execute(array(':tid' => $tid));
								$rston = $rsto->fetch(PDO::FETCH_ASSOC); 
								if($rston['hdd']=="NULL")
								{
									echo "Any";
								}
								else
								{
									echo ($rston['hdd']);
								}
                				echo ("</td>");

                				echo ("<td>");
                				$ros = $pdo->prepare("SELECT os FROM transfer_request WHERE transfer_request_id=:tid");
								$ros->execute(array(':tid' => $tid));
								$rosn = $ros->fetch(PDO::FETCH_ASSOC); 
								if($rosn['os']=="NULL")
								{
									echo "Any";
								}
								else
								{
									echo ($rosn['os']);
								}
                				echo ("</td>");

                				echo ("<td>");
                				$rquan = $pdo->prepare("SELECT quantity FROM transfer_request WHERE transfer_request_id=:tid");
								$rquan->execute(array(':tid' => $tid));
								$rquann = $rquan->fetch(PDO::FETCH_ASSOC); 
								echo ($rquann['quantity']);
                				echo ("</td>");
                				//$i++;
            				//}
            				echo('</table>');
        				//}
					?>
				</span>
			</div>
		<br><br><br><br><br><br>
			<div class="container">
				<span class="col-xs-7" style="margin-left: 100px;">
					<b>Faculty Incharge</b>
				</span>
				<span>
					<b>Applicant</b>
				</span>
			</div>
		</form>
	</body>
</html>