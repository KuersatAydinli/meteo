<?

include_once("./MVIncludes/db_class.php");	

if($bGlobalDebug){
	echo "checked iSensorIdWert bei Formular: ".$_GET['iSensorIdWert'];
	echo "selected option bei Formular: ".$_GET['iXBezIntervallWert'];
}


    		if(isset($_POST['iSensorIdWert'])){
				$iSensorIdWert = $_POST['iSensorIdWert']; 
			}
			elseif(isset($_GET['iSensorIdWert'])){
				$iSensorIdWert = $_GET['iSensorIdWert'];
		    }else{
		    	$iSensorIdWert=0;
		    }
    
    		if(isset($_POST['iJahrIdWert'])){
				$iJahrIdWert = $_POST['iJahrIdWert']; 
			}
			elseif(isset($_GET['iJahrIdWert'])){
				$iJahrIdWert = $_GET['iJahrIdWert'];
		    }else{
		    	$iJahrIdWert=date("Y");
		    }

		    if(isset($_POST['iMonatIdWert'])){
				$iMonatIdWert = $_POST['iMonatIdWert']; 
			}
			elseif(isset($_GET['iMonatIdWert'])){
				$iMonatIdWert = $_GET['iMonatIdWert'];
		    }else{
		    	$iMonatIdWert=1;
		    }
		    
		    
    	if(isset($_GET['bSelbstAufruf'])){
			if($bGlobalDebug){echo "bSelbstAufruf";}
			
		}else{
			if($bGlobalDebug){echo "fromIndex";}
		}
?>



<form name="idFormular" id="idFormular" method="POST" action="<?echo "./index.php?sNavigationsSeite=$sNavigationsQuelle&bSelbstAufruf=yes&ESensor=".$_GET['ESensor']."&ETitel=".$_GET['ETitel']."&iSensorIdWert=".$iSensorIdWert."&iJahrIdWert=".$iJahrIdWert."&iMonatIdWert=".$iMonatIdWert."&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe'];?>">


<table border="0" cellspacing="1" width=<? echo "50%"//echo $iBrowserFensterBreite;?> class="">
<!--
  <tr class="FormInfoText2">Wählen Sie anhand der Auswahlmöglichkeit das Jahr aus und klicken Sie anschliessend auf ‚Aktualisieren‘. </tr>
-->  
  <tr class="" width="10%">
  <td width="" width="10%">Jahr:</td>  
   </tr>

  <tr class="">
    
	
	<? 
    
    echo "<td class='' width='10%'>";
 		echo "<SELECT NAME='iJahrIdWert'>";
 			$minMeteoJahr = 2004;
 			$minBodenJahr = 2008;
 			$aktJahr= date("Y");
 			echo "Rechne ab das Jahr $aktJahr";
 			echo "<OPTION "; if(($iJahrIdWert==$aktJahr) ||(!isset($iJahrIdWert))){echo "selected ";} echo "VALUE='$aktJahr' >$aktJahr";
 			for($i=1; $i <= ($aktJahr-$minMeteoJahr); $i++){
 				echo "<OPTION "; if(($iJahrIdWert==($aktJahr-$i)) ||(!isset($iJahrIdWert))){echo "selected ";} echo "VALUE='";
 				echo $aktJahr-$i;
 				echo "' >";
 				echo $aktJahr-$i;
; 				
 			}
		echo "</SELECT>";
	echo "</td>";
    
    //$seite = $_GET['sNavigationsSeite'];
    //echo "<td width=''>von <input type='text' id='sFrmVonDatum' name='sFrmVonDatum' size='20' value='$sDatumVon' onchange='chkMinDate(\"$seite\")'></td>";
    ?>
    
    

	<td width="">
	<?
		//if($_GET['sNavigationsSeite']=="datenexport"){
		//	echo "<input type='button' name='download' value='$sSchaltflächenBeschriftung' onClick='erstelleDownloadLink(\"alle\")'>";
		//	echo "<input type='radio' name='Radiodateityp' checked value='excel' onClick='wechsleDownloadBild()'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' onClick='wechsleDownloadBild()'> Excel-Datei<br></td>";
		//	echo "<input type='radio' name='RadiodateitypTable' checked value='luft'> Datenexport Luftwerte<input type='radio' name='RadiodateitypTable' value='boden'> Datenexport Bodenwerte<br></td>";
		//}else{			
			echo "<input type='submit' value='$sSchaltflächenBeschriftung' name='Aktualisieren' ></td>";
		//}
	//bei einzelner grafik könnne die werte auch in tabellarischer Form heruntergeladen werden
	
	/*
	if($_GET['sNavigationsSeite']=="einzelnegrafik"){
		$esen = $_GET['ESensor'];
		echo "<td ><input type='button' name='download' value = 'Download' onClick='erstelleDownloadLink(\"$esen\")'>";
		echo "<input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Excel-Datei<br></td>";
	}
	*/
	?>
	
  <?
	
  //if($_GET['sNavigationsSeite']=="datenexport"){
	/*
		echo "<td width=''>";
  		echo"<input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<br></td>";
		echo"<td><input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Excel-Datei<br></td>";
	*/
	//}
 
  ?>	
</table>
</form>