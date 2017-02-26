//
// Javascript utility functions
//
// Author: C. Semiz
//
// Changes:
// 	Version: 1.1/28.11.01
//		Added support for InternetExplorer 5.0
//
// Description:
//	1.0:
//		Tested under Suse Linux 7.3 with Netscape/Mozilla/Konqueror
//	1.1:
//  	Tested under Windows 2000 with Internet Explorer 5.0


/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

var isMozilla, isIE, isNetscape, isKonqueror

isMozilla = false
isIE = false
isKonqueror = false
isNetscape = false

if (parseInt(navigator.appVersion) >= 4) {
  if (navigator.appName == "Konqueror") {
  	isKonqueror = true

  } else if (navigator.appName == "Netscape" && parseInt(navigator.appVersion)== 5) {
    isMozilla = true

  } else if (navigator.appName == "Microsoft Internet Explorer") {
    isIE = true

  } else {
  	isNetscape = true
  }

} else {
	alert(
  	"You can't use dynamic positioning with your browser!. (Version below 4)"
  )
}

function who() {
	if (isMozilla) {
  	return "Mozilla (Linux)"
  } else if (isIE) {
  	return "Internet Explorer"
  } else if (isKonqueror) {
  	return "Konqueror (Linux)"
  } else if (isNetscape) {
  	return "Netscape (Linux)"
  } else {
  	return "unknown"
  }
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

function getInsideWindowHeight() {
  if (isKonqueror || isMozilla || isNetscape) {
    return window.innerHeight
  } else {
    return document.body.clientHeight
  }
}

function getInsideWindowWidth() {
  if (isKonqueror || isMozilla || isNetscape) {
    return window.innerWidth
  } else {
    return document.body.clientWidth
  }
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

function getObjById(id) {
	if (isIE) {
  	return document.all[id]
  } else {
		return document.getElementById(''+id+'')
  }
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

function getObjHeight(id) {
  return getObjById(id).offsetHeight
}

function getObjWidth(id) {
  return getObjById(id).offsetWidth
}

function getObjLeft(id) {
  return getObjById(id).offsetLeft
}

function getObjTop(id) {
    return getObjById(id).offsetTop
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

function shiftTo(id, left, top) {
	var styleObj = getObjById(id).style
  styleObj.left = left
  styleObj.top = top
}

function shiftBy(id, deltaX, deltaY) {
 	shiftTo(id, getObjLeft(id)+deltaX, getObjTop(id)+deltaY)
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

function getHTMLText(id) {
	return getObjById(id).innerHTML
}

function setHTMLText(id, htmltext) {
	return getObjById(id).innerHTML = htmltext
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */


function min(a, b) {
   return a < b ? a : b
}

function max(a, b) {
   return a > b ? a : b
}

function elts(obj) {
  var s = "Elements of " + obj.nodeName + "\n"
  var elt
	for(elt in obj) {
    s = s + elt +"; "
	}
	return s
}

function elts(obj) {
  var s = "Elements of " + obj.nodeName + "\n"
  var elt
	for(elt in obj) {
    s = s + elt +"; "
	}
	return s
}

function eltsHTML(obj) {
  var s = "<p>Elements of " + obj.nodeName + "</p><ul>"
  var elt
	for(elt in obj) {
    s = s + "<li>" + elt + "</li>"
	}
  s = s + "</ul>"
	return s
}

/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */

function alertHTML(msg) {
	alert("aH")
}
function xalertHTML(msg){

	var logWindow
	logWindow = window.open("", "logWindow", "")
  logWindow.document.write("<HTML><HEAD><TITLE>Log window</TITLE></HEAD>")
  logWindow.document.write("<BODY>" + msg + "</BODY></HTML>")
}


function rewrite(id, fw, fh) {
	var screenwidth = getInsideWindowWidth() * fw;
	var screenheight = screenwidth * fh;
	
	var o = getObjById(id);
	o.href=o.href + "&iw=" + screenwidth + "&ih=" + screenheight;
}




/* ****************************************************************** */
/* ****************************************************************** */
/* ****************************************************************** */


//in naviagtion.php
function navigationsLinks(link,zusatz) {
	bbreite = 0.85 * getInsideWindowWidth();
	bhoehe = getInsideWindowHeight();
	var hrefstr = "<a href=./index.php?sNavigationsSeite=" + link + "&iBrowserFensterBreite=" + bbreite + "&iBrowserFensterHoehe=" + bhoehe + zusatz + "id=a_" + link + ">";
	document.write(hrefstr);
}


//in einzelnewerte.php (original)
function linkerstellen(link,fromindex,sensor,titel,farbe,refLinie) {
	//bbreite = Math.ceil(screen.availWidth-(screen.availWidth/15));
	//bhoehe = Math.ceil(screen.availHeight/3);
	bbreite = Math.ceil(0.85 * getInsideWindowWidth());
	bhoehe = Math.ceil(getInsideWindowHeight()* 0.4);
	//alert(bhoehe)
	var hrefstr = "<a href=./index.php?sNavigationsSeite="+link+"&fromIndex="+fromindex+"&ESensor="+sensor+"&ETitel="+titel+"&iBrowserFensterBreite="+bbreite+"&iBrowserFensterHoehe="+bhoehe+"&kFarbe="+farbe+"&refLinie="+refLinie+"&iXBezIntervallWert=6&oZeitIntervall=6"+">";
	document.write(hrefstr);
}

//in einzelnewerte.php
function linkerstellen() {
	//bbreite = Math.ceil(screen.availWidth-(screen.availWidth/15));
	//bhoehe = Math.ceil(screen.availHeight/3);
	bbreite = Math.ceil(0.85 * getInsideWindowWidth());
	bhoehe = Math.ceil(getInsideWindowHeight()* 0.4);
	//alert(bhoehe)
	var hrefstr = "<a href=./index.php?sNavigationsSeite="+link+"&fromIndex="+fromindex+"&ESensor="+sensor+"&ETitel="+titel+"&iBrowserFensterBreite="+bbreite+"&iBrowserFensterHoehe="+bhoehe+"&kFarbe="+farbe+"&refLinie="+refLinie+"&iXBezIntervallWert=6&oZeitIntervall=6"+">";
	document.write(hrefstr);
}

function rewriteNavLink(id, fw, fh) {
	var o = getObjById(id);

	//alert (id);
	//Link neu zusammensetzen
	if(!(o == null)){
		var screenwidth = Math.ceil(getInsideWindowWidth() * fw);
		//var screenheight = Math.ceil(screenwidth * fh);
		var screenheight = Math.ceil(getInsideWindowHeight() * fh);
		//alert(screenheight);
		var first;
		
		if(o.id=="idFormular"){
			first = o.action.search(/&iBrowserFensterBreite/);
		}else{
			first = o.href.search(/&iBrowserFensterBreite/);
			//alert (first);
			
		}
			
		if(first != -1){
			if(o.id=="idFormular"){
				o.action = o.action.substr(0,first);	
			}else{
				o.href = o.href.substr(0,first);
				//alert(o.href);
			}		
		}
		
		if(o.id=="idFormular"){
			//alert ("Link erweitern: " + id);
			o.action = o.action + "&iBrowserFensterBreite=" + screenwidth + "&iBrowserFensterHoehe=" + screenheight;	
		}else{
			//alert ("Link erweitern nicht idFormular: " + id);
			o.href = o.href + "&iBrowserFensterBreite=" + screenwidth + "&iBrowserFensterHoehe=" + screenheight;	
			//alert(o.href);
		}
		
	}else{
		//alert("getObjById konte objekt nicht finden");		
	}
}

function linksAktualisieren(){
	navigationsLinkAktualisieren();
	einzelneMWLinkAktualisieren();
	formularActionAktualisieren();
}

function formularActionAktualisieren(){
	rewriteNavLink("idFormular", 0.95, 0.8);
}

function navigationsLinkAktualisieren(){
	rewriteNavLink("a_aktuellewerte", 0.95, 0.8);
	rewriteNavLink("a_allegrafik", 0.95, 0.8);
	rewriteNavLink("a_datenexport", 0.95, 0.8);
	rewriteNavLink("a_infoseite", 0.95, 0.8);
	rewriteNavLink("a_bilder", 0.95, 0.8);
	rewriteNavLink("a_allegrafikBoden", 0.95, 0.8);
}


function wechsleZuBisDat(){
	var o = getObjById("sFrmBisDatum");
	o.select();
}


function chkMinDate(source){
	//var o = getObjById("sFrmVonDatum");
	//window.alert(o.id);
	//window.alert(o.vlaue);
	//window.alert(source);
	
	minDateStr = "18.05.2008 00:00";
	minDateStrLuft = "17.12.2003 16:06";
	
	
	curDateStr = document.idFormular.sFrmVonDatum.value;
	var minDatum = new Date(umwandelnStringInZahl(minDateStr.substring(6,10)),umwandelnStringInZahl(minDateStr.substring(3,5))-1,umwandelnStringInZahl(minDateStr.substring(0,2)),umwandelnStringInZahl(minDateStr.substring(11,13)),umwandelnStringInZahl(minDateStr.substring(14,16)),0);
	var minDatumLuft = new Date(umwandelnStringInZahl(minDateStrLuft.substring(6,10)),umwandelnStringInZahl(minDateStrLuft.substring(3,5))-1,umwandelnStringInZahl(minDateStrLuft.substring(0,2)),umwandelnStringInZahl(minDateStrLuft.substring(11,13)),umwandelnStringInZahl(minDateStrLuft.substring(14,16)),0);
	var curDatum = new Date(umwandelnStringInZahl(curDateStr.substring(6,10)),umwandelnStringInZahl(curDateStr.substring(3,5))-1,umwandelnStringInZahl(curDateStr.substring(0,2)),umwandelnStringInZahl(curDateStr.substring(11,13)),umwandelnStringInZahl(curDateStr.substring(14,16)),0);
	
	
	if(source=="allegrafikBoden"){
		//window.alert(source);
		if (curDatum < minDatum){
			window.alert("Bodendaten sind erst ab 18.05.2008 00:00 vorhanden");
			document.idFormular.sFrmVonDatum.value = minDateStr;
		} 

	}else{
		if (curDatum < minDatumLuft){
			window.alert("Luftdaten sind erst ab 17.12.2003 16:10 vorhanden");
			document.idFormular.sFrmVonDatum.value = minDateStrLuft;
		}	
		
		
	}


}

function wechsleProfBild(){
	var o = getObjById("imgTmpProf");
	//window.alert(o.id);
	//window.alert(o.src);
	var str=o.src;
	
	var startPos = str.indexOf("/kurven/kurve");
	//window.alert(startPos);
	
	//window.alert(str.substring(startPos+13,startPos+14));
	
	var bildNr = str.substring(startPos+13,startPos+14);
	
	var str_pre = str.substring(0,startPos+13);
	var str_midFix = "/kurven/kurve";
	var str_post = str.substring(startPos+14,str.length);
	var str_new =  "";
	var alt_new = "";
	
	 if(bildNr=="1")
	 { 
		 str_new = str_pre  + "2" + str_post;
		 alt_new = "F�r Detailansicht klicken";
		  
	 } 
	  if(bildNr=="2")
	 { 
		  str_new = str_pre  + "1" + str_post;
		  alt_new = "F�r Normalansicht klicken";
		  o.USEMAP="#mapProf";
	 }
	  o.src = str_new;
	  o.alt = alt_new;
	  //window.alert(str_new);
	  //window.alert(o.src);

}


function einzelneMWLinkAktualisieren(){
	//window.alert("einzelneMWLinkAktualisieren_");
	rewriteNavLink("a_Temperatur", 0.95, 0.8);
	rewriteNavLink("a_Temperaturdach", 0.95, 0.8);
	rewriteNavLink("a_Taupunkt", 0.95, 0.8);
	rewriteNavLink("a_Druck", 0.95, 0.8);
	rewriteNavLink("a_Feuchte", 0.95, 0.8);
	rewriteNavLink("a_Regen", 0.95, 0.8);
	rewriteNavLink("a_Regenmomentan", 0.95, 0.8);
	rewriteNavLink("a_Globalstrahlung", 0.95, 0.8);
	rewriteNavLink("a_Windgeschwindigkeit", 0.95, 0.8);
	rewriteNavLink("a_Windspitze", 0.95, 0.8);
	rewriteNavLink("a_Windrichtung", 0.95, 0.8);
	rewriteNavLink("a_boden5", 0.95, 0.8);
	rewriteNavLink("a_boden10", 0.95, 0.8);
	rewriteNavLink("a_boden50", 0.95, 0.8);
	rewriteNavLink("a_boden100", 0.95, 0.8);
	//alert "einzelneMWLinkAktualisieren_";
	//window.alert("einzelneMWLinkAktualisieren_END");
 
	
}

// in index.php
function ersetzeText(sQuellText, sSuchText, sErsatzText){
    // pruefung
    if ((sQuellText == null) || (sSuchText == null))           { return null; }
    if ((sQuellText.length == 0) || (sSuchText.length == 0))   { return sQuellText; }

    // sErsatzText ?
    if ((sErsatzText == null) || (sErsatzText.length == 0))    { sErsatzText = ""; }

    var iLaengeSuchtext = sSuchText.length;
    var iLaengeErsatztext = sErsatzText.length;
    var iPosition = sQuellText.indexOf(sSuchText, 0);

    while (iPosition >= 0){
        sQuellText = sQuellText.substring(0, iPosition) + sErsatzText + sQuellText.substring(iPosition + iLaengeSuchtext);
        iPosition = sQuellText.indexOf(sSuchText, iPosition + iLaengeErsatztext);
    }
    return sQuellText;
} 

// in index.php
function erstelleDownloadLink(sensor) {
	var sKonVonDatum = ersetzeText(document.idFormular.sFrmVonDatum.value," ","%20");
	var sKonBisDatum = ersetzeText(document.idFormular.sFrmBisDatum.value," ","%20");

	//window.alert(sensor);
	
	var luftwerte;
	
	 
	
	if(sensor=="alle"){
		//window.alert("alle");
		luftwerte = document.idFormular.RadiodateitypTable[0].checked;
	}
	else{
		//window.alert("sensor");
		luftwerte = true;
	}
	
	if (luftwerte){
		var hrefstr = "ExcelExport.php?sDatumVon=" + sKonVonDatum + "&sDatumBis=" +sKonBisDatum;
		if(document.idFormular.Radiodateityp[0].checked) {
	  		hrefstr = hrefstr + "&bUrlCvsAuswahl=1";
	 	}
	 	if(sensor=="alle"){
	 		hrefstr = hrefstr + "&sURLSensor=*";
	 	}else{
	 		hrefstr = hrefstr + "&sURLSensor="+sensor;
	 	}
	 	self.location.href=hrefstr;
	}else{//bodenwerte
		var hrefstr = "ExcelExportBoden.php?sDatumVon=" + sKonVonDatum + "&sDatumBis=" +sKonBisDatum;
		if(document.idFormular.Radiodateityp[0].checked) {
	  		hrefstr = hrefstr + "&bUrlCvsAuswahl=1";
	 	}
	 	if(sensor=="alle"){
	 		hrefstr = hrefstr + "&sURLSensor=*";
	 	}else{
	 		hrefstr = hrefstr + "&sURLSensor="+sensor;
	 	}
	 	self.location.href=hrefstr;
		
	}
	
}



function erstelleDownloadLinkMW(sensor) {
	var iSensorIdWert = document.idFormular.iSensorIdWert.value;
	var iJahrIdWert = document.idFormular.iJahrIdWert.value;
	var iMonatIdWert = document.idFormular.iMonatIdWert.value;
	
	//var sKonBisDatum = ersetzeText(document.idFormular.sFrmBisDatum.value," ","%20");

	//window.alert(sensor);
	
	var jahr;
	
	//window.alert("iSensorIdWert.js: " + iSensorIdWert);
	
	if(sensor=="mvJahr"){
		//window.alert("mvJahr");
		jahr = true;
	}
	else{
		//window.alert("mvMonat");
		jahr = false;
	}
	
	if (jahr){
		//var hrefstr = "ExcelExportMW_Jahr.php?sDatumVon=" + sKonVonDatum + "&sDatumBis=" +sKonBisDatum;
		var hrefstr = "ExcelExportMW_Jahr.php?iSensorIdWert=" + iSensorIdWert + "&iJahrIdWert=" +iJahrIdWert + "&iMonatIdWert=" +iMonatIdWert;
		if(document.idFormular.Radiodateityp[0].checked) {
	  		hrefstr = hrefstr + "&bUrlCvsAuswahl=1";
	 	}else{
	 		hrefstr = hrefstr + "&bUrlCvsAuswahl=0";
	 	}
		//window.alert("hrefstr: " +hrefstr);
		//window.alert("mvJahr-iSensorIdWert"+iSensorIdWert);
	 	self.location.href=hrefstr;
	}else{//bodenwerte
		var hrefstr = "ExcelExportMW_Monat.php?iSensorIdWert=" + iSensorIdWert + "&iJahrIdWert=" +iJahrIdWert + "&iMonatIdWert=" +iMonatIdWert;
		if(document.idFormular.Radiodateityp[0].checked) {
			hrefstr = hrefstr + "&bUrlCvsAuswahl=1";
	 	}else{
	 		hrefstr = hrefstr + "&bUrlCvsAuswahl=0";
	 	}
	 	self.location.href=hrefstr;
		
	}
	
}





// in Formular
	function wechsleDownloadBild(){
		if(document.idFormular.Radiodateityp[0].checked) {		
			 document.exportbild.src="./ExcelDownloadcvs.jpg";
		}else if(document.idFormular.Radiodateityp[1].checked){
			 document.exportbild.src="./ExcelDownload.jpg";
		}
		
	}
	
// in Formular	
	function umwandelnStringInZahl(strWert){
		//wandelt einen String in eine Zahl um ("2"-->2, "02"-->2)
		var zahl = 0;
		if(strWert.charAt(0)=='0'){
			zahl = parseInt(strWert.substr(1,1));
		}else{
			zahl = parseInt(strWert);	
		}
		return zahl;		
	}

// in Formular
	function umwandelnZahlInString(zahlWert){
		//wandelt eine Zahl in einen String um (2-->"02", 10-->"10")
		var str = "";
		if(zahlWert>9){
			str = str+zahlWert;	
		}else{
			str = "0"+zahlWert;	
		}
		return str;	
	}
	
// in Formular
	function anpassenVonUndBis(source,seite) {
		/*
		window.alert(document.idFormular.oZeitIntervall[0].checked);
		window.alert(document.idFormular.oZeitIntervall[1].checked);
		window.alert(document.idFormular.oZeitIntervall[2].checked);
		window.alert(document.idFormular.oZeitIntervall[3].checked);
		window.alert(document.idFormular.oZeitIntervall[4].checked);
		window.alert(document.idFormular.oZeitIntervall[5].checked);
		*/
		//window.alert(source);
		//window.alert(seite);
		bis = document.idFormular.sFrmBisDatum.value;
		var bisDatum = new Date(umwandelnStringInZahl(bis.substring(6,10)),umwandelnStringInZahl(bis.substring(3,5))-1,umwandelnStringInZahl(bis.substring(0,2)),umwandelnStringInZahl(bis.substring(11,13)),umwandelnStringInZahl(bis.substring(14,16)),0);
		var bisTime = bisDatum.getTime();
		var vonTime=0;	
	if(document.idFormular.oZeitIntervall[0].checked) {		
  		 vonTime =   (bisTime-(6*60*60*1000));
  		 if(source=="xgrid")document.idFormular.iXBezIntervallWert.value = 6;
 	}else if(document.idFormular.oZeitIntervall[1].checked){
 		vonTime =   (bisTime-(12*60*60*1000));
 		if(source=="xgrid")document.idFormular.iXBezIntervallWert.value = 6;
 	}else if(document.idFormular.oZeitIntervall[2].checked){
 		vonTime =   (bisTime-(24*60*60*1000));
 		if(source=="xgrid")document.idFormular.iXBezIntervallWert.value = 6;
 	}else if(document.idFormular.oZeitIntervall[5].checked){
 		vonTime =   (bisTime-(365*24*60*60*1000));
 		if(source=="xgrid")document.idFormular.iXBezIntervallWert.value = 4320;
 	}else if(document.idFormular.oZeitIntervall[4].checked){
 		vonTime =   (bisTime-(30*24*60*60*1000));
 		if(source=="xgrid")document.idFormular.iXBezIntervallWert.value = 1008;
 	}else if(document.idFormular.oZeitIntervall[3].checked){
 		vonTime =   (bisTime-(7*24*60*60*1000));
 		if(source=="xgrid")document.idFormular.iXBezIntervallWert.value = 144;
 	}
 	var vonDatum = new Date();
 	vonDatum.setTime(vonTime);
	document.idFormular.sFrmVonDatum.value = umwandelnZahlInString(vonDatum.getDate())+"."+ umwandelnZahlInString((1+vonDatum.getMonth()))+"."+vonDatum.getFullYear()+" "+umwandelnZahlInString(vonDatum.getHours())+":"+umwandelnZahlInString(vonDatum.getMinutes());
	
	if (seite="allegrafikBoden"){
		chkMinDate(seite);
	}
	
}
	
	
	///////////////////
	function msgBox(msg) 
	{ 
	var box=window.confirm(msg)
	if(box==true){ 

	//DASWASBEIOKGEMACHTWIRD;

	} 
	else if(box==false){ 

	//DASWASBEIABBRECHENGEMACHTWIRD;

	} 
	}
	
	
	
	
	
	
	
	
	
	
	//////////////////////////
	function msgBoxYesNo(msg) 
	{ 
	var box=window.confirm(msg )// textangebe die mit der confirm-box ausgegeben wird. 
	if(box==true){ 
	window.location.href="http://www.googel.de"; // www-addressenangabe fuer wenn OK gedr�ckt wird. 
	} 
	else if(box==false){ 
	alert("schade"); // ausgabe f�r wenn abbrechen gedr�ckt wird. 
	} 
	} 

