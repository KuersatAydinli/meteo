
<?
/*
include_once("./MVIncludes/db_class.php");	
$db_handler = new db_class;
$dbResultat0 = $db_handler->db_query("select MAX(Messzeit)from Messwerte");
if ($oDbReihenTupel0 =mysql_fetch_array($dbResultat0)){
	$sLetztesMesswertDatum = $oDbReihenTupel0[0];
}else{
	//Fehlerseite einblenden
	echo "Kein aktueller Datensatz";
	return;
}
$oDatumZeit = split(" ",$sLetztesMesswertDatum);
$oDatumTeile = split("-",$oDatumZeit[0]);
$oZeitTeile = split(":",$oDatumZeit[1]);
$oDatum = mktime($oZeitTeile[0],$oZeitTeile[1],$oZeitTeile[2],$oDatumTeile[1],$oDatumTeile[2],$oDatumTeile[0]);
*/
?>

<p><h2>Messwertedarstellung</h2></p>





<?
$sNavigationsQuelle = "einzelnegrafik";
$sSchaltflächenBeschriftung = "Aktualisieren";
include('./Formular.php');

if($bGlobalDebug){
	echo "sFrmVonDatum_GET is: ".$_GET['sFrmVonDatum'];
	echo "sFrmVonDatu_POST is: ".$_POST['sFrmVonDatum'];
	echo "sFrmBisDatum is: ".$sFrmBisDatum;
	echo "Sensor is: ".$ESensor;
}

		//wenn von gleicher seite anhand aktualisierenbutton
		if(isset($_GET['bSelbstAufruf'])){
			if($bGlobalDebug){echo "bSelbstAufruf";}
			if(isset($_POST['sFrmVonDatum'])){
				$sKonVonDatum = $_POST['sFrmVonDatum']; 
				$sKonBisDatum = $_POST['sFrmBisDatum'];
				
			}
			elseif(isset($_GET['sFrmVonDatum'])){
				$sKonVonDatum = $_GET['sFrmVonDatum']; 
				$sKonBisDatum = $_GET['sFrmBisDatum'];
		    }
			
		}else{
			if($bGlobalDebug){echo "fromIndex";}
			$sKonVonDatum = Date('d.m.Y H:i',$oDatum - 21600);	
			$sKonBisDatum = 	Date('d.m.Y H:i',$oDatum);
			
		}
		$sDatumVon=$sKonVonDatum;
		$sDatumBis=$sKonBisDatum;
		
?>


<table border="0" cellspacing="1" width="100%" >
  <tr>
    <td width="33%">
	<?
		if ($bGlobalDebug){
			echo "Ihr Bildshirmbreite: ".$_GET['iBrowserFensterBreite'];
			echo "Ihr Bildshirmbreite: ".$_GET['iBrowserFensterHoehe'];
			echo "Sensor ist: ".$_GET['ESensor'];
			echo "Titel ist:".$_GET['ETitel'];
			echo "von:".$sKonVonDatum;
			echo "bis:".$sKonBisDatum;
		}
	?>
	<?
		$sensor = $_GET['ESensor'];
		$titel=$_GET['ETitel'];
		$kFarbe=$_GET['kFarbe'];
		
		//echo "kFarbe -- ist: ".$kFarbe;
		
		
		$iBildBreite=$_GET['iBrowserFensterBreite'];
		$iBildHoehe= round($_GET['iBrowserFensterHoehe'] / 2) ;

			if(isset($_POST['iXBezIntervallWert'])){
				$iXBezIntervallWert = $_POST['$iXBezIntervallWert']; 
			}
			elseif(isset($_GET['iXBezIntervallWert'])){
				$iXBezIntervallWert = $_GET['$iXBezIntervallWert'];
		    }
		
		
		//$iXBezIntervallWert=$_POST['iXBezIntervallWert'];
		

		$iAnzahlSensoren = 1;
		$sURLSensor = $_GET['ESensor'];
		
		
		$iAktuelleZeit = time();
		//$sMapURL = "./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&from=$sDatumVon&sFrmBisDatum=$sDatumBis&ESensor=$sensor&ETitel=$titel&iBrowserFensterBreite=$iBildBreite&iBrowserFensterHoehe=$iBildHoehe&kFarbe=$kFarbe&iXBezIntervallWert=$iXBezIntervallWert";
		
	
		//////////////////////////////////////////////////
		 // Variable deklarieren 
		 $dir = "./kurven/"; 
		 // Variable deklarieren und Verzeichnis �ffnen 
		 $verz = opendir($dir); 
		 // Verzeichnisinhalt auslesen 
		 while ($file = readdir ($verz))  
		 { 
		  // "." und ".." bei der Ausgabe unterdr�cken 
		  if($file != "." && $file != "..")  
		  { 
		   // File l�schen 
		   @unlink($dir.$file); 
		  } 
		 } 
		// Verzeichnis schlie�en 
		closedir($verz);  
	//////////////////////////////////////////////////
	
		
		//generiere Kurvenbild
		include_once ('./AlleJPGrafik.php');
		//echo "<img border='0' src='./kurven/kurve0.png?$iAktuelleZeit' USEMAP='#mvgraphmap' width='$iBildBreite' height='$iBildHoehe'>";
		echo "<img border='0' src='".$fileName."?".$iAktuelleZeit."' USEMAP='#mvgraphmap' width='$iBildBreite' height='$iBildHoehe'>";
		//echo "<img border='0' src='".$fileNameProf."?".$iAktuelleZeit."' USEMAP='#mvgraphmap' width='$iBildBreiteProf' height='$iBildHoeheProf'>";

		//@unlink("./kurven/kurve0.png");
		//@unlink($fileName);
		
	?>
	<br>Um den genauen Wert bzw. die genaue Messzeit eines Wertes einzublenden, fahren Sie mit der Maus auf den gew�nschten Wert. 
	</td>

  </tr>
  </table>

