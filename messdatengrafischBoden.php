
<h2>Messwertedarstellung Bodensensoren</h2>

<?
	$sNavigationsQuelle = "allegrafikBoden";
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

$iBildBreite=ceil($_GET['iBrowserFensterBreite']*0.75);
$iBildHoehe = ceil($_GET['iBrowserFensterHoehe']*0.5);
$iBildBreiteProf=ceil($_GET['iBrowserFensterBreite']*0.25);
$iBildHoeheProf = ceil($_GET['iBrowserFensterHoehe']*0.5);


$iAnzahlSensoren = 7;
$sURLSensor = "*";



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



$iAktuelleZeit = time();
include ('./AlleJPGrafikBoden.php');


$iBild

/*
 * "./kurven/kurve".$iAnzahlSensorenIndex."_".$iAktuelleZeit.".png"
 *
 * */

?>

<table border="0" cellspacing="2" width="100%" >
  <tr>
    <td width="75%" style="padding:0px" rowspan=2 valign="bottom">
			<?//echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W1&ETitel=Druck%20in%20hpa&kFarbe=green&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>
			<?echo "<img width='100%' border='0' src='./kurven/kurve0"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreite height=$iBildHoehe'>"?>
			<?//echo "</a>"?>
    </td>
    
		<td class="bodenGraphsTab" valign="bottom">
			<?echo "<a href='#' onClick='wechsleZuBisDat()'> Zeitpunkt: $sKonBisDatum (ver�nderbar) </a>"; ?>
			<?echo "<br>"?>
			<?echo "Lufttemperatur: ".$oSensorYLuftTempWert." �C\n";?>
			<?echo "<br>"?>		
			<?echo "Bodentemperaturen:";?>
			
			
		</td>
  </tr>																											   
  <tr>
		<td align="right" valign="bottom" >
			<?//echo "<a href='./index.php?sNavigationsSeite=einzelnegrafik&bSelbstAufruf=yes&sFrmVonDatum=$sKonVonDatum&sFrmBisDatum=$sKonBisDatum&ESensor=W0&ETitel=Temperatur%20in%20�C&kFarbe=red&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe']."&iXBezIntervallWert=".$iXBezIntervallWert."&oZeitIntervall=".$oZeitIntervall." target='_blank'>";?>		
			<? //echo "<img border='0' src='./kurven/kurve1"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreiteProf height=$iBildHoeheProf'>"?>
			<? //echo "<img border='0' src='./kurven/kurve1"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreiteProf height=$iBildHoeheProf id='imgTmpProf' onclick=this.src='./kurven/kurve2"."_".$iAktuelleZeit.".png?".$iAktuelleZeit.";' alt='image' USEMAP='#mapProf'>"?>
			<? echo "<A HREF='javascript:wechsleProfBild()'> <img border='0' src='./kurven/kurve2"."_".$iAktuelleZeit.".png?".$iAktuelleZeit."' width=$iBildBreiteProf height=$iBildHoeheProf id='imgTmpProf' alt='F�r Detailansicht klicken' USEMAP='#mapProf'> </A>"?>
		</td>	

  </tr>

  </table>
