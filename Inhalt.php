<?


if (isset($_GET['sNavigationsSeite'])) {
	if($_GET['sNavigationsSeite']=="datenexport"){
		include('./DatenExport.php');
	}else if($_GET['sNavigationsSeite']=="aktuellewerte"){
		include('./EinzelneWerte.php') ;	
	}else if($_GET['sNavigationsSeite']=="allegrafik"){
		include('./messdatengrafisch.php'); 
	}else if($_GET['sNavigationsSeite']=="allegrafikBoden"){
		include('./messdatengrafischBoden.php'); 		
	}else if($_GET['sNavigationsSeite']=="infoseite"){
		include('./Info.php'); 
	}else if($_GET['sNavigationsSeite']=="einzelnegrafik"){
		include('./messdatenGrafischEinzeln.php'); 
	}else if($_GET['sNavigationsSeite']==""){
		include('./EinzelneWerte.php') ;
	}else if($_GET['sNavigationsSeite']=="bilder"){
		include('./Bilder.php') ;
	}else if($_GET['sNavigationsSeite']=="publikationen"){
		include('./Publikationen.php') ;
	}else if($_GET['sNavigationsSeite']=="mittelwerte"){
		include('./Mittelwerte.php') ;
	}else if($_GET['sNavigationsSeite']=="klimadiagramm"){
		include('./Klimadiagramm.php') ;
	}
	
	
}else{
	include('./EinzelneWerte.php') ;
}




?>
