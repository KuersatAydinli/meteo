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


<form role="form" name="idFormular" id="idFormular" method="POST" action="<?echo "./index.php?sNavigationsSeite=$sNavigationsQuelle&bSelbstAufruf=yes&ESensor=".$_GET['ESensor']."&ETitel=".$_GET['ETitel']."&iSensorIdWert=".$iSensorIdWert."&iJahrIdWert=".$iJahrIdWert."&iMonatIdWert=".$iMonatIdWert."&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe'];?>">
    <div class="row">
        <?
        //erzeuge Datenbankverbindung
        $db_handler = new db_class;
        //hole alle sensorbezeichnungen und masseinheiten f�r grafik�berschrift


        $dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit,identifikation from Sensoren where Identifikation in (0,1,2,3,4,5,6,7,8,9,10,11,14,16,18,19)");
        //$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit,identifikation from Sensoren where Identifikation in (0,11,19,18,1,2,3,16,4,5,14,6,7,8,9,10)");

        //dann schreibe alle sensorbezeichnungen und masseinheiten in das Array f�r die Grafiktitel

        //echo "<td class='' width='10%'>";
        //echo "<SELECT NAME='iSensorIdWert'>";

        $k=0;
        while($oDbReihenTupel = mysql_fetch_row($dbResultat)) {
            if (k==0){
                //echo "<OPTION "; if(($iSensorIdWert==$oDbReihenTupel[2]) ||(!isset($iSensorIdWert))){echo "selected ";} echo "VALUE='$oDbReihenTupel[2]' >$oDbReihenTupel[0] $oDbReihenTupel[1]";

            }else{
                //echo "<OPTION "; if($iSensorIdWert==$oDbReihenTupel[2]){echo "selected ";} echo " VALUE='$oDbReihenTupel[2]'>$oDbReihenTupel[0] $oDbReihenTupel[1]";
            }
            $oTitel[$k]=$oDbReihenTupel[0]." ".$oDbReihenTupel[1];
            //echo $k."---".$oDbReihenTupel[0]."---",$oDbReihenTupel[1]."---".$oTitel[$k];
            $k++;

        }
        //echo "</SELECT>";
        //echo "</td>";

        echo "
<div class='col-sm-4'>
    <div class=\"form-group\">
        <label class='control-label' for=\"sensor\">Sensor: </label>
    ";

        echo "<td class='' width='10%'>";
        echo "<SELECT class='form-control' id='sensor' NAME='iSensorIdWert'>";
        echo "<OPTION "; if(($iSensorIdWert==0) ||(!isset($iSensorIdWert))){echo "selected ";} echo "VALUE='0' >$oTitel[0]";
        echo "<OPTION "; if($iSensorIdWert==11){echo "selected ";} echo " VALUE='11'>$oTitel[11]";
        echo "<OPTION "; if($iSensorIdWert==19){echo "selected ";} echo " VALUE='19'>$oTitel[15]";
        echo "<OPTION "; if($iSensorIdWert==18){echo "selected ";} echo " VALUE='18'>$oTitel[14]";
        echo "<OPTION "; if($iSensorIdWert==1){echo "selected ";} echo " VALUE='1'>$oTitel[1]";
        echo "<OPTION "; if($iSensorIdWert==2){echo "selected ";} echo " VALUE='2'>$oTitel[2]";
        echo "<OPTION "; if($iSensorIdWert==3){echo "selected ";} echo " VALUE='3'>$oTitel[3]";
        echo "<OPTION "; if($iSensorIdWert==16){echo "selected ";} echo " VALUE='16'>$oTitel[13]";
        echo "<OPTION "; if($iSensorIdWert==4){echo "selected ";} echo " VALUE='4'>$oTitel[4]";
        echo "<OPTION "; if($iSensorIdWert==5){echo "selected ";} echo " VALUE='5'>$oTitel[5]";
        echo "<OPTION "; if($iSensorIdWert==14){echo "selected ";} echo " VALUE='14'>$oTitel[12]";
        //Boden
        echo "<OPTION "; if($iSensorIdWert==6){echo "selected ";} echo " VALUE='6'>$oTitel[6]";
        echo "<OPTION "; if($iSensorIdWert==7){echo "selected ";} echo " VALUE='7'>$oTitel[7]";
        echo "<OPTION "; if($iSensorIdWert==8){echo "selected ";} echo " VALUE='8'>$oTitel[8]";
        echo "<OPTION "; if($iSensorIdWert==9){echo "selected ";} echo " VALUE='9'>$oTitel[9]";
        echo "<OPTION "; if($iSensorIdWert==10){echo "selected ";} echo " VALUE='10'>$oTitel[10]";

        echo "</SELECT>";
        echo "</div>
        </div>";

        /*
        *
        */
        echo "
<div class='col-sm-2 col-sm-push-1'>
    <div class='form-group'>
    <label class='control-label' for=\"jahr\">Jahr: </label>
    
    ";

        echo "<SELECT class='form-control' id='jahr' NAME='iJahrIdWert'>";
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
        echo "</div>
</div>";

        echo "
<div class='col-sm-2 col-sm-push-2'>
    <div class='form-group'>
    <label class='control-label' for=\"monat\">Monat: </label>
    
    ";

        echo "<SELECT class='form-control' id='monat' NAME='iMonatIdWert'>";
        echo "<OPTION "; if(($iMonatIdWert==1) ||(!isset($iMonatIdWert))){echo "selected ";} echo "VALUE='1' >Januar";
        echo "<OPTION "; if($iMonatIdWert==2){echo "selected ";} echo " VALUE='2'>Februar";
        echo "<OPTION "; if($iMonatIdWert==3){echo "selected ";} echo " VALUE='3'>März";
        echo "<OPTION "; if($iMonatIdWert==4){echo "selected ";} echo " VALUE='4'>April";
        echo "<OPTION "; if($iMonatIdWert==5){echo "selected ";} echo " VALUE='5'>Mai";
        echo "<OPTION "; if($iMonatIdWert==6){echo "selected ";} echo " VALUE='6'>Juni";
        echo "<OPTION "; if($iMonatIdWert==7){echo "selected ";} echo " VALUE='7'>Juli";
        echo "<OPTION "; if($iMonatIdWert==8){echo "selected ";} echo " VALUE='8'>August";
        echo "<OPTION "; if($iMonatIdWert==9){echo "selected ";} echo " VALUE='9'>September";
        echo "<OPTION "; if($iMonatIdWert==10){echo "selected ";} echo " VALUE='10'>Oktober";
        echo "<OPTION "; if($iMonatIdWert==11){echo "selected ";} echo " VALUE='11'>November";
        echo "<OPTION "; if($iMonatIdWert==12){echo "selected ";} echo " VALUE='12'>Dezember";
        echo "</SELECT>";
        echo "<div>
</div>";



        //$seite = $_GET['sNavigationsSeite'];
        //echo "<td width=''>von <input type='text' id='sFrmVonDatum' name='sFrmVonDatum' size='20' value='$sDatumVon' onchange='chkMinDate(\"$seite\")'></td>";
        ?>
        </div>
    <div class="row">
        <div style="padding-top: 5px; padding-bottom: 5px" class="btn-group" data-toggle="buttons">
            <label class="btn btn-info active FormInfoText">
                <?php
                echo "<input type='radio' name='Radiodateityp' autocomplete='off' checked value='excel'> CSV"
                ?>
            </label>
            <label class="btn btn-info FormInfoText">
                <?php
                echo "<input type='radio' name='Radiodateityp' autocomplete='off' value='bUrlCvsAuswahl' > Excel"
                ?>
            </label>
        </div>

        <div class="form-group">
            <?
            //if($_GET['sNavigationsSeite']=="datenexport"){
            //	echo "<input type='button' name='download' value='$sSchaltfl�chenBeschriftung' onClick='erstelleDownloadLink(\"alle\")'>";
            //	echo "<input type='radio' name='Radiodateityp' checked value='excel' onClick='wechsleDownloadBild()'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' onClick='wechsleDownloadBild()'> Excel-Datei<br></td>";
            //	echo "<input type='radio' name='RadiodateitypTable' checked value='luft'> Datenexport Luftwerte<input type='radio' name='RadiodateitypTable' value='boden'> Datenexport Bodenwerte<br></td>";
            //}else{
            echo "<button class='form-control btn btn-default' type='submit' value='$sSchaltflächenBeschriftung' name='Aktualisieren' >Aktualisieren</td>";
            //}
            //bei einzelner grafik k�nnne die werte auch in tabellarischer Form heruntergeladen werden

            /*
            if($_GET['sNavigationsSeite']=="einzelnegrafik"){
                $esen = $_GET['ESensor'];
                echo "<td ><input type='button' name='download' value = 'Download' onClick='erstelleDownloadLink(\"$esen\")'>";
                echo "<input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Excel-Datei<br></td>";
            }
            */
            ?>

            <?
            echo "<button class='form-control btn btn-default' type='submit' value='$sSchaltflächenBeschriftungDownloadJahresWerte' name='Aktualisieren' onClick='erstelleDownloadLinkMW(\"mvJahr\")'>Download Monatswerte</td>";
            ?>

            <?
            echo "<button class='form-control btn btn-default' type='submit' value='$sSchaltflächenBeschriftungDownloadMonatsWerte' name='Aktualisieren' onClick='erstelleDownloadLinkMW(\"mvMonat\")'>Download Tageswerte</td>";
            //echo "<input type='radio' name='Radiodateityp' checked value='excel' onClick='wechsleDownloadBild()'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' onClick='wechsleDownloadBild()'> Excel-Datei<br></td>";
            ?>
        </div>
    </div>
</form>

<form name="idFormular" id="idFormular" method="POST" action="<?echo "./index.php?sNavigationsSeite=$sNavigationsQuelle&bSelbstAufruf=yes&ESensor=".$_GET['ESensor']."&ETitel=".$_GET['ETitel']."&iSensorIdWert=".$iSensorIdWert."&iJahrIdWert=".$iJahrIdWert."&iMonatIdWert=".$iMonatIdWert."&iBrowserFensterBreite=".$_GET['iBrowserFensterBreite']."&iBrowserFensterHoehe=".$_GET['iBrowserFensterHoehe'];?>">


<table border="0" cellspacing="1" width=<? echo "80%"//echo $iBrowserFensterBreite;?> class="">
<!--
  <tr class="FormInfoText2">W�hlen Sie anhand der Auswahlm�glichkeiten einen Sensor und das Jahr und wenn gew�nscht einen Monat aus und klicken Sie anschliessend auf �Aktualisieren�. </tr>
-->
  <tr class="" width="10%">
  <td width="" width="10%">Sensor:</td>
  <td width="" width="10%">Jahr:</td>
  <td width="" width="10%">Monat:</td>

   </tr>

  <tr class="">


	<?
	//erzeuge Datenbankverbindung
	$db_handler = new db_class;
	//hole alle sensorbezeichnungen und masseinheiten f�r grafik�berschrift


	$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit,identifikation from Sensoren where Identifikation in (0,1,2,3,4,5,6,7,8,9,10,11,14,16,18,19)");
	//$dbResultat = $db_handler->db_query("select Bezeichnung,Masseinheit,identifikation from Sensoren where Identifikation in (0,11,19,18,1,2,3,16,4,5,14,6,7,8,9,10)");

	//dann schreibe alle sensorbezeichnungen und masseinheiten in das Array f�r die Grafiktitel

	//echo "<td class='' width='10%'>";
	//echo "<SELECT NAME='iSensorIdWert'>";

	$k=0;
	while($oDbReihenTupel = mysql_fetch_row($dbResultat)) {
		if (k==0){
			//echo "<OPTION "; if(($iSensorIdWert==$oDbReihenTupel[2]) ||(!isset($iSensorIdWert))){echo "selected ";} echo "VALUE='$oDbReihenTupel[2]' >$oDbReihenTupel[0] $oDbReihenTupel[1]";

		}else{
			//echo "<OPTION "; if($iSensorIdWert==$oDbReihenTupel[2]){echo "selected ";} echo " VALUE='$oDbReihenTupel[2]'>$oDbReihenTupel[0] $oDbReihenTupel[1]";
		}
		$oTitel[$k]=$oDbReihenTupel[0]." ".$oDbReihenTupel[1];
		//echo $k."---".$oDbReihenTupel[0]."---",$oDbReihenTupel[1]."---".$oTitel[$k];
		$k++;

	}
	//echo "</SELECT>";
	//echo "</td>";

    echo "<td class='' width='10%'>";
 		echo "<SELECT NAME='iSensorIdWert'>";
 			echo "<OPTION "; if(($iSensorIdWert==0) ||(!isset($iSensorIdWert))){echo "selected ";} echo "VALUE='0' >$oTitel[0]";
 			echo "<OPTION "; if($iSensorIdWert==11){echo "selected ";} echo " VALUE='11'>$oTitel[11]";
 			echo "<OPTION "; if($iSensorIdWert==19){echo "selected ";} echo " VALUE='19'>$oTitel[15]";
 			echo "<OPTION "; if($iSensorIdWert==18){echo "selected ";} echo " VALUE='18'>$oTitel[14]";
 			echo "<OPTION "; if($iSensorIdWert==1){echo "selected ";} echo " VALUE='1'>$oTitel[1]";
 			echo "<OPTION "; if($iSensorIdWert==2){echo "selected ";} echo " VALUE='2'>$oTitel[2]";
 			echo "<OPTION "; if($iSensorIdWert==3){echo "selected ";} echo " VALUE='3'>$oTitel[3]";
 			echo "<OPTION "; if($iSensorIdWert==16){echo "selected ";} echo " VALUE='16'>$oTitel[13]";
 			echo "<OPTION "; if($iSensorIdWert==4){echo "selected ";} echo " VALUE='4'>$oTitel[4]";
 			echo "<OPTION "; if($iSensorIdWert==5){echo "selected ";} echo " VALUE='5'>$oTitel[5]";
 			echo "<OPTION "; if($iSensorIdWert==14){echo "selected ";} echo " VALUE='14'>$oTitel[12]";
 			//Boden
 			echo "<OPTION "; if($iSensorIdWert==6){echo "selected ";} echo " VALUE='6'>$oTitel[6]";
 			echo "<OPTION "; if($iSensorIdWert==7){echo "selected ";} echo " VALUE='7'>$oTitel[7]";
 			echo "<OPTION "; if($iSensorIdWert==8){echo "selected ";} echo " VALUE='8'>$oTitel[8]";
 			echo "<OPTION "; if($iSensorIdWert==9){echo "selected ";} echo " VALUE='9'>$oTitel[9]";
 			echo "<OPTION "; if($iSensorIdWert==10){echo "selected ";} echo " VALUE='10'>$oTitel[10]";

		echo "</SELECT>";
	echo "</td>";
/*
*
*/

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

    echo "<td class='' width='10%'>";
 		echo "<SELECT NAME='iMonatIdWert'>";
 			echo "<OPTION "; if(($iMonatIdWert==1) ||(!isset($iMonatIdWert))){echo "selected ";} echo "VALUE='1' >Januar";
 			echo "<OPTION "; if($iMonatIdWert==2){echo "selected ";} echo " VALUE='2'>Februar";
 			echo "<OPTION "; if($iMonatIdWert==3){echo "selected ";} echo " VALUE='3'>M�rz";
 			echo "<OPTION "; if($iMonatIdWert==4){echo "selected ";} echo " VALUE='4'>April";
 			echo "<OPTION "; if($iMonatIdWert==5){echo "selected ";} echo " VALUE='5'>Mai";
 			echo "<OPTION "; if($iMonatIdWert==6){echo "selected ";} echo " VALUE='6'>Juni";
 			echo "<OPTION "; if($iMonatIdWert==7){echo "selected ";} echo " VALUE='7'>Juli";
 			echo "<OPTION "; if($iMonatIdWert==8){echo "selected ";} echo " VALUE='8'>August";
 			echo "<OPTION "; if($iMonatIdWert==9){echo "selected ";} echo " VALUE='9'>September";
 			echo "<OPTION "; if($iMonatIdWert==10){echo "selected ";} echo " VALUE='10'>Oktober";
 			echo "<OPTION "; if($iMonatIdWert==11){echo "selected ";} echo " VALUE='11'>November";
 			echo "<OPTION "; if($iMonatIdWert==12){echo "selected ";} echo " VALUE='12'>Dezember";
		echo "</SELECT>";
	echo "</td>";



    //$seite = $_GET['sNavigationsSeite'];
    //echo "<td width=''>von <input type='text' id='sFrmVonDatum' name='sFrmVonDatum' size='20' value='$sDatumVon' onchange='chkMinDate(\"$seite\")'></td>";
    ?>




	<?
		 echo "<td class='' width='10%'>";
		//if($_GET['sNavigationsSeite']=="datenexport"){
		//	echo "<input type='button' name='download' value='$sSchaltfl�chenBeschriftung' onClick='erstelleDownloadLink(\"alle\")'>";
		//	echo "<input type='radio' name='Radiodateityp' checked value='excel' onClick='wechsleDownloadBild()'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' onClick='wechsleDownloadBild()'> Excel-Datei<br></td>";
		//	echo "<input type='radio' name='RadiodateitypTable' checked value='luft'> Datenexport Luftwerte<input type='radio' name='RadiodateitypTable' value='boden'> Datenexport Bodenwerte<br></td>";
		//}else{
			echo "<input type='submit' value='$sSchaltflächenBeschriftung' name='Aktualisieren' ></td>";
		//}
	//bei einzelner grafik k�nnne die werte auch in tabellarischer Form heruntergeladen werden

	/*
	if($_GET['sNavigationsSeite']=="einzelnegrafik"){
		$esen = $_GET['ESensor'];
		echo "<td ><input type='button' name='download' value = 'Download' onClick='erstelleDownloadLink(\"$esen\")'>";
		echo "<input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Excel-Datei<br></td>";
	}
	*/
	?>


	<?
		 echo "<td class='' width='10%'>";
			echo "<input type='button' value='$sSchaltflächenBeschriftungDownloadJahresWerte' name='Aktualisieren' onClick='erstelleDownloadLinkMW(\"mvJahr\")'></td>";
	?>

	<?
		 echo "<td class='' width='10%'>";
			echo "<input type='button' value='$sSchaltflächenBeschriftungDownloadMonatsWerte' name='Aktualisieren' onClick='erstelleDownloadLinkMW(\"mvMonat\")'></td>";
			//echo "<input type='radio' name='Radiodateityp' checked value='excel' onClick='wechsleDownloadBild()'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' onClick='wechsleDownloadBild()'> Excel-Datei<br></td>";
	?>


	<?
		 echo "<td width='10%' class='FormInfoText' ><input type='radio' name='Radiodateityp' checked value='excel' > CSV-Datei</td>";
	     echo "<td width='10%' class='FormInfoText' ><input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl' > Excel-Datei</td>";


	     //echo "<td class='FormInfoText' width='0%'><input type='radio' name='Radiodateityp' checked value='excel'> CSV-Datei<input type='radio' name='Radiodateityp' value='bUrlCvsAuswahl'> Excel-Datei<br></td>";

	?>

	</tr>

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