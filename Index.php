<?
//F�r Debug-Zwecke diese globale Variable einschalten
$bGlobalDebug = false;
//$bGlobalDebug = true;

if($bGlobalDebug){
	echo "Bildschirmbreite von Index aus: ".$_GET['iBrowserFensterBreite'];
	echo "iBrowserFensterHoehe von Index aus: ".$_GET['iBrowserFensterHoehe'];
}
?>

<html>

<head>
<META http-equiv="Content-Language" content="de-ch">
<META http-equiv="Content-Type" content="text/html; charset=windows-1252">
<!--<link	rel="stylesheet"	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link	rel="stylesheet"	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link	href="css/bootstrap.min.css"	rel="stylesheet">
<link	href="css/bootstrap-theme.min.css"	rel="stylesheet">-->
<link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
<link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">


<!--<script	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.2/angular.min.js"></script>
<script language="javascript" type="text/javascript" src="./utils.js">
</script>

<?
//header("Pragma: no-cache"); 
//header("Expires: 0"); 

/*
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

*/

/*
<meta name="keywords" content="soil temperatures Liechtenstein">

 * */
?>

<meta name="keywords" content="soil, temperatures, Liechtenstein, meteo">
<meta name="description" content="soil temperatures Liechtenstein">
<meta name="robots" content="all">

<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META http-equiv="expires"  value="0">
<LINK rel="stylesheet" type="text/css" href="./meteovaduz.css">

<title>Meteo Vaduz</title>

</head>

<!--
<body BACKGROUND="./Weatherhellgrossx2y4_komp.png" >
<body bgcolor="#6699FF" onResize="window.location.href=window.location.href" >
<body bgcolor="#6699FF" onLoad="linksAktualisieren()" onResize="linksAktualisieren()" >
<body bgcolor="#CCCCFF" onLoad="linksAktualisieren()" onResize="linksAktualisieren()" BACKGROUND="./Logo-GLOBE-FL_trans.gif">
<body bgcolor="#CCCCFF" onLoad="linksAktualisieren()" onResize="linksAktualisieren()" style="background-repeat&#58; no-repeat; background-image&#58; url&#40;./Logo-GLOBE-FL_trans.gif&#41;;">
-->

<body bgcolor="#CCCCFF" onLoad="linksAktualisieren()" onResize="linksAktualisieren()">
<!--<div>
    <label>Name:</label>
    <input type="text" ng-model="yourName" placeholder="Enter a name here">
    <hr>
    <h1>Hello {{yourName}}!</h1>
</div>-->
<?php 
//$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//echo urldecode($url);
?>


<?php include('./Kopf.php')?>
<?php include('./Navigation.php')?>
<?php include('./Inhalt.php')?>
<?php include('./Fuss.php')?>



</body>

</html>