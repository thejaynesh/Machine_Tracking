<!DOCTYPE html>
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
?>
<html>
	<head>
		<title>Transfer Report</title>
		<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width = device-width, initial-scale = 1">

    	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
    	<link rel="stylesheet" type="text/css" href="style5.css">
	</head>
	<body style="margin-left: 150px; margin-right: 300px; margin-top: 100px">
		<div style="text-align: center; padding-left: 110px;">
			<b>
				<span>Medi-Caps University, Indore</span>
				<br>
				<span>System Transfer Report</span>
			</b>	
		</div>
		<br>
		<div class="container">
			<span class="col-xs-9">
				<b>Reciept No.: &nbsp</b>
				<?php
					$srid=$_GET['strid'];
					echo($srid);
				?>
			</span>
			<span>
				<b>Date: &nbsp</b>
				<?php
					$sdate = $pdo->prepare("SELECT date_of_assignment FROM system_transfer_report WHERE system_transfer_report_id=:srid");
					$sdate->execute(array(':srid' => $srid));
					$sdaten = $sdate->fetch(PDO::FETCH_ASSOC); 
					echo($sdaten['date_of_assignment']);
				?>
			</span>
		</div>
	<br><br><br>
		<div class="container">
			<span class="col-xs-12">
				<b>Department: &nbsp</b>
				<?php
						$sdep = $pdo->prepare("SELECT department FROM system_transfer_report WHERE system_transfer_report_id=:srid");
						$sdep->execute(array(':srid' => $srid));
						$sdepn = $sdep->fetch(PDO::FETCH_ASSOC); 
						echo ($sdepn['department']);	
					?>
			</span>
		</div>
	<br>
		<div class="container">
			<span class="col-xs-12">
				<b>Room/Lab No.: &nbsp</b>
					<?php
						$slab = $pdo->prepare("SELECT name FROM lab JOIN system_transfer_report ON(system_transfer_report.lab_id=lab.lab_id) WHERE system_transfer_report_id=:srid");
						$slab->execute(array(':srid' => $srid));
						$slabn = $slab->fetch(PDO::FETCH_ASSOC); 
						echo ($slabn['name']);	
					?>
			</span>
		</div>
	<br>
		<div class="container">
			<span class="col-xs-12">
				<b>Purpose: &nbsp</b>
				<?php
						$spur = $pdo->prepare("SELECT purpose FROM system_transfer_report WHERE system_transfer_report_id=:srid");
						$spur->execute(array(':srid' => $srid));
						$spurn = $spur->fetch(PDO::FETCH_ASSOC); 
						echo ($spurn['purpose']);	
					?>
			</span>
		</div>
	<br>
		<div class="container">
			<span class="col-xs-12">
				<b>Computers Alloted: &nbsp</b>
				<?php
					$stmtcnt = $pdo->prepare("SELECT COUNT(*) FROM system_transfer_report_history WHERE system_transfer_report_id=:srid");
					$stmtcnt->execute(array(':srid' => $srid ));
					$row = $stmtcnt->fetch(PDO::FETCH_ASSOC);
					$stmtread = $pdo->query("SELECT MAC_ADDR, processor, ram, memory, os FROM machine");
            		echo ("<table class=\"table table-striped\">
                		 <tr><th>S.No.</th><th>Computer No.</th><th>Processor</th><th>Ram</th><th>Storage</th><th>O.S.</th></tr>");
            		if ($row['COUNT(*)']!=='0') 
            		{
            			for($i=1;$i<=$row['COUNT(*)'];$i++)
            			{
            				$sno = $pdo->prepare("SELECT system_transfer_report_history_id FROM system_transfer_report_history WHERE system_transfer_report_id=:srid");
            				$sno->execute(array(':srid' => $srid));
            				while($snon=$sno->fetch(PDO::FETCH_ASSOC))
            				{   
            					echo("<tr>");
            					echo("<td>$i</td>");

            					echo("<td>");
            					$smac = $pdo->prepare("SELECT MAC_ADDR FROM machine JOIN system_transfer_report_history ON(system_transfer_report_history.machine_id=machine.machine_id) WHERE system_transfer_report_history_id=:snon AND system_transfer_report_id=:srid");
            					$smac->execute(array(':srid' => $srid, ':snon' => $snon['system_transfer_report_history_id']));
            					$smacn=$smac->fetch(PDO::FETCH_ASSOC);
            					echo($smacn['MAC_ADDR']);
            					echo("</td>");

            					echo("<td>");
            					$sspec = $pdo->prepare("SELECT spec FROM specification JOIN hardware JOIN machine JOIN system_transfer_report_history ON(system_transfer_report_history.machine_id=machine.machine_id AND machine.processor=hardware.hardware_id AND hardware.description=specification.spec_id) WHERE system_transfer_report_history_id=:snon AND system_transfer_report_id=:srid");
            					$sspec->execute(array(':srid' => $srid, ':snon' => $snon['system_transfer_report_history_id']));
            					$sspecn=$sspec->fetch(PDO::FETCH_ASSOC);
            					echo($sspecn['spec']);
            					echo("</td>");

            					echo("<td>");
            					$sram = $pdo->prepare("SELECT spec FROM specification JOIN hardware JOIN machine JOIN system_transfer_report_history ON(system_transfer_report_history.machine_id=machine.machine_id AND machine.ram=hardware.hardware_id AND hardware.description=specification.spec_id) WHERE system_transfer_report_history_id=:snon AND system_transfer_report_id=:srid");
            					$sram->execute(array(':srid' => $srid, ':snon' => $snon['system_transfer_report_history_id']));
            					$sramn=$sram->fetch(PDO::FETCH_ASSOC);
            					echo($sramn['spec']);
            					echo("</td>");

            					echo("<td>");
            					$ssto = $pdo->prepare("SELECT spec FROM specification JOIN hardware JOIN machine JOIN system_transfer_report_history ON(system_transfer_report_history.machine_id=machine.machine_id AND machine.memory=hardware.hardware_id AND hardware.description=specification.spec_id) WHERE system_transfer_report_history_id=:snon AND system_transfer_report_id=:srid");
            					$ssto->execute(array(':srid' => $srid, ':snon' => $snon['system_transfer_report_history_id']));
            					$sston=$ssto->fetch(PDO::FETCH_ASSOC);
            					echo($sston['spec']);
            					echo("</td>");

            					echo("<td>");
            					$sos = $pdo->prepare("SELECT os FROM machine JOIN system_transfer_report_history ON(system_transfer_report_history.machine_id=machine.machine_id) WHERE system_transfer_report_history_id=:snon AND system_transfer_report_id=:srid");
            					$sos->execute(array(':srid' => $srid, ':snon' => $snon['system_transfer_report_history_id']));
            					$sosn=$sos->fetch(PDO::FETCH_ASSOC);
            					echo($sosn['os']);
            					echo("</td>");
            					echo("</tr>");
            					$i++;
            				}
            			}
            		}
				?>
				</table>
			</span>
		</div>
	<br><br><br><br><br>
		<div class="container"><!-- style="padding-left: 80px"-->
			<span class="col-xs-4">
				<b>Signature of <br>Faculty/Technician/Staff</b>
			</span>
			<span class="col-xs-2"><b>Rceived By</b></span>
			<span class="col-xs-3"><b>Faculty-In-Charge</b></span>
			<span><b>Store-In-Charge</b></span>
		</div>
	</body>
</html>