<h2>Messwertedarstellung Wettersensoren</h2>

<?
	$sNavigationsQuelle = "allegrafik";
	$sSchaltflächenBeschriftung = "Aktualisieren";
	include('./Formular.php');
?>

<?

////////////////////////////////////////////////////////////////////
//VAR-Handling
			if(isset($_POST['iXBezIntervallWert'])){
				$iXBezIntervallWert = $_POST['iXBezIntervallWert'];
			}
			elseif(isset($_GET['iXBezIntervallWert'])){
				$iXBezIntervallWert = $_GET['iXBezIntervallWert'];
		    }
////////////////////////////////////////////////////////////////////


if($bGlobalDebug){
	echo "sFrmVonDatum is: ".$sFrmVonDatum;
	echo "sFrmBisDatum is: ".$sFrmBisDatum;
	echo "Datum von Formular: ".$oDatum;
}

    if (!isset($_GET['bSelbstAufruf'])) {
        $_GET['bSelbstAufruf'] = '';
    } 


//wenn von gleicher seite anhand aktualisierenbutton
if($_GET['bSelbstAufruf']){
	
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
		
//generiere alle grafiken
$sDatumVon=$sKonVonDatum;
$sDatumBis=$sKonBisDatum;

if($bGlobalDebug){
	echo "Von vor alle grafik generiereung: ".$sDatumVon;
	echo "Bis vor alle grafik generiereung: ".$sDatumBis;
}

$iBildBreite=ceil($_GET['iBrowserFensterBreite'] / 3);
$iBildHoehe = ceil($_GET['iBrowserFensterHoehe'] / 3);
$iAnzahlSensoren = 7;

$iAnzahlSensoren_additional = 5;
$isensor_id_add1 = 8; //temperatur dach
$isensor_id_add2 = 11; //windspitze
$isensor_id_add3 = 13; //Rgeenstärke
$isensor_id_add4 = 15; //barom. Druck
$isensor_id_add5 = 16; //Tauopunkt



$sURLSensor = "*";



	//////////////////////////////////////////////////
		 // Variable deklarieren 
		 $dir = "./kurven/"; 
		 // Variable deklarieren und Verzeichnis öffnen 
		 $verz = opendir($dir); 
		 // Verzeichnisinhalt auslesen 
		 while ($file = readdir ($verz))  
		 { 
		  // "." und ".." bei der Ausgabe unterdrücken 
		  if($file != "." && $file != "..")  
		  { 
		   // File löschen 
		   @unlink($dir.$file); 
		  } 
		 } 
		// Verzeichnis schließen 
		closedir($verz);  
	//////////////////////////////////////////////////



$iAktuelleZeit = time();
include ('./AlleJPGrafik.php');


$iBild

/*
 * "./kurven/kurve".$iAnzahlSensorenIndex."_".$iAktuelleZeit.".png"
 *
 * */

?>

<table border="0" cellspacing="1" width="100%" >
  <tr>
    <td width="33%">


	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W1&ETitel=Druck%20in%20hpa&kFarbe=darkgreen&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
	<?echo "<img border='0' src='./kurven/kurve1"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
	</a>
    </td>
	<td width="33%">

	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W0&ETitel=Temperatur%20in%20°C&kFarbe=red&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>		
	<?echo "<img border='0' src='./kurven/kurve0"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
	</a>
	</td>
    <td width="34%">

	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W5&ETitel=Windgeschwindigkeit%20in%20km/h&kFarbe=brown&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
	<?echo "<img border='0' src='./kurven/kurve5"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
	</a>
    </td>
  </tr>																											   
  <tr>

    <td width="33%">
 
 	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W3&ETitel=Regen%20in mm/m^2&kFarbe=black&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
	<?echo "<img border='0' src='./kurven/kurve3"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
	</a>
    </td>

    <td width="33%">

 	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W2&ETitel=Feuchte%20in%20%&kFarbe=darkblue&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
	<?echo "<img border='0' src='./kurven/kurve2"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
	</a>
    </td>
    <td width="34%">

	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W6&ETitel=Windrichtung%20in%20(0...360)°&kFarbe=darkred&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
	<?echo "<img border='0' src='./kurven/kurve6"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
	</a>
    </td>
  </tr>
  <tr>
    <td valign="top">  
		<p ><b><h3>Globalstrahlung:</h3></b></p>
		<p class="FormInfoText">Summe aus direkter Sonnenstrahlung und diffuser Himmelsstrahlung.</p>
    </td>

    <td width="33%" valign="top">
	<?echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W4&ETitel=Globalstrahlung%20in%20W/m^2&kFarbe=darkorchid4&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
	<?echo "<img border='0' src='./kurven/kurve4"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
 
	</a>
    </td>
 
    <td width="34%" valign="top">
    
    <p ><b><h3>Windrichtung:</h3></b></p>
    <p class="FormInfoText">
	Es bedeutet:

<table  border="1" align="left">
  <tr>
    <td  >
    0°</td>
    <td  >
   Wind aus Norden</td>
  </tr>
  <tr>
    <td  >

    90°</td>
    <td  >
Wind aus Osten</td>
  </tr>
  <tr>
    <td >

    180°</td>
    <td>
Wind aus Süden</td>
  </tr>
  <tr>
    <td>

    270°</td>
    <td>
Wind aus Westen</td>
  </tr>
  <tr>
    <td>

    360°</td>
    <td  valign="top" >
Wind aus Norden</td>
  </tr>
</table>
</p>

    </td>
  </tr>
  </table>
