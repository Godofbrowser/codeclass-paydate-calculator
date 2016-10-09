<?php 
require_once('Paydate.php');
use Paydate_calculator\Paydate;

//lets check for the file containing the holidays and create an array from it.
$filename = "holidays.txt";
if(file_exists($filename)):
	$file = fopen($filename, 'r');
	$h = fread($file, $length = filesize($filename));
	preg_match_all("/\d{2}-\d{2}-\d{4}/", $h, $matches);
	$holidays = $matches[0];
	//print_r($holidays);
endif;


if(isset($_POST['submit']) && isset($_POST['qty']) && isset($_POST['monthlyPayday'])) {
	date_default_timezone_set('Africa/Lagos') ? '' : die('Timezone could not be set');
	$nextPaydate = new Paydate($_POST['monthlyPayday']);
	$nextPaydate->setHolidays($holidays);
	
	for($i=0;$i < $_POST['qty']; $i++) {

		while( $nextPaydate->isWeekend() )
		{ 
			$nextPaydate->increment(); 
		}
		while( $nextPaydate->isHoliday() )
		{ 
			$nextPaydate->decrement(); 
		}
		$data[$i]['initialPaydate'] = $nextPaydate->getInitialPaydate()->format('D, d M Y');
		$data[$i]['paydate'] = $nextPaydate->getPaydate()->format('D, d M Y');
		$nextPaydate->next();
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>PAYDATE CALCULATOR</title>
<meta name="generator" content="Bluefish 2.2.7" >
<meta name="author" content="Emmy" >
<meta name="date" content="2016-08-14T18:25:49+0100" >
<meta name="copyright" content="">
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<meta http-equiv="expires" content="0">
<style type="text/css">
	table{background-color: #B8AEAE; width: 500px; margin: 10px auto;}
	th:nth-child(1){width: 20%;}
	th:nth-child(2){width: 40px;}
	th:nth-child(3){width: 40px;}
	tr:nth-child(odd){background-color:#A5C9EC}
	tr:nth-child(even){background-color:#D2E5F7}
	th{padding: 10px; background-color: #F1FABC; text-transform: uppercase;}
	tr{padding: 10px}
	td{padding: 10px; text-align: center;}
	
	form{background-color: #F1FABC; width: 500px;padding:10px 0; margin: 10px auto;border-top-right-radius: 10px;border-top-left-radius: 10px;}
	h1{font-size: 22px; text-align: center;color: #A5C9EC;text-shadow: 1px 1px #000;}
	a{text-decoration: none;}a:hover{text-decoration: underline;}
	label{width: 80%;padding: 5px 10%; margin: 10px auto;}
	input[type="number"]{padding: 10px; width: 80%; margin-left: 9%; margin-top: 10px;}
	input[type="submit"]{padding: 10px; width: 60%; margin-left: 20%; margin-top: 10px;}
</style>
</head>
<body>
	<form method="post">
	<a href=""><h1>PAYDATE CALCULATOR</h1></a>
	<hr/>
	<label>Enter The exact day payments are to be made Monthly.</label>
	<input type="number" name="monthlyPayday" value="25" >
	<label>Enter Number of Payments to be Displayed</label>
	<input type="number" name="qty" value="10" >
	<input type="submit" name="submit" value="Display">
	</form>
	
	<?php if(isset($data)): ?>
		<table>
			<tr><th>s/n</th><th>Initial Date</th><th>Pay Date</th</tr>
			
			<?php
			foreach($data as $index=>$data){
				echo "<tr><td>".($index + 1)."</td><td>".$data['initialPaydate']."</td><td>".$data['paydate']."</td></tr>";
			}
			?>
			
		</table>
	<?php endif; ?>
</body>
</html>

