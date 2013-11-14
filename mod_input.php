<?php
// Artikelsystem RSS
// Version 0.9.5
// by Alexander M. Korn
// amk@gmx.info

$is_dev = false;

if($is_dev)
	include 'C:/_server/www/_sefrengo/mod_dev/Artikelsystem RSS/mod_input.php';
else {

//config mode
$mip_form['0']['desc'] = 'Konfigurationsmodus';
$mip_form['0']['cat'] = 'option';
$mip_form['0']['size'] = '1';
$mip_form['0']['option_desc'][] = 'Normal';
$mip_form['0']['option_val'][] =  '';
#$mip_form['0']['option_desc'][] = 'Erweitert';
#$mip_form['0']['option_val'][] =  'advanced';
$mip_form['0']['option_desc'][] = 'Ausgeblendet';
$mip_form['0']['option_val'][] =  'hidden';
$mip_form['0']['cms_var'] = 'MOD_VAR[0]';
$mip_form['0']['cms_val'] = $cms_mod['value']['0'];
$mip_form['0']['flag'] = 'reload';

// Seitenschaltung nach Anzahl
$mip_form['1']['cat'] = 'option';
$mip_form['1']['type'] = '';
$mip_form['1']['tab'] = '0';
$mip_form['1']['desc'] = 'XML-Generierung aktiviert';
$mip_form['1']['cms_var'] = 'MOD_VAR[1]';
$mip_form['1']['cms_val'] = $cms_mod['value']['1'];
$mip_form['1']['cms_val_default'] = '0';
$mip_form['1']['option_desc'][]= 'Ja';
$mip_form['1']['option_val'][]= '0';
$mip_form['1']['option_desc'][]= 'Nein';
$mip_form['1']['option_val'][]= '1';
$mip_form['1']['flag'] = 'reload';

// Seitenschaltung nach Anzahl
$mip_form['3']['cat'] = 'option';
$mip_form['3']['type'] = '';$mip_form['3']['rows'] = '1';
$mip_form['3']['tab'] = '0';
$mip_form['3']['desc'] = 'Artikeleinträge bis';
$mip_form['3']['cms_var'] = 'MOD_VAR[3]';
$mip_form['3']['cms_val'] = $cms_mod['value']['3'];
$mip_form['3']['cms_val_default'] = '0';
$mip_form['3']['option_desc'][]= 'zum aktuellen Tag - 23:59 Uhr';
$mip_form['3']['option_val'][]= '0';
$mip_form['3']['option_desc'][]= 'zum aktuellen Tag und aktueller Uhrzeit';
$mip_form['3']['option_val'][]= '1';


// Seitenschaltung nach Anzahl
$mip_form['4']['cat'] = 'option';
$mip_form['4']['type'] = '';
$mip_form['4']['tab'] = '0';
$mip_form['4']['desc'] = 'Test-Modus <small><br/>(Bei jedem Seitenaufruf XML-Datei erzeugen. Update-Intervall deaktiviert.)</small>';
$mip_form['4']['cms_var'] = 'MOD_VAR[4]';
$mip_form['4']['cms_val'] = $cms_mod['value']['4'];
$mip_form['4']['cms_val_default'] = '0';
$mip_form['4']['option_desc'][]= 'DEAKTIVIERT';
$mip_form['4']['option_val'][]= '0';
$mip_form['4']['option_desc'][]= '!!! A K T I V !!!';
$mip_form['4']['option_val'][]= '1';

// Seitenschaltung nach Anzahl
$mip_form['9']['cat'] = 'option';
$mip_form['9']['type'] = '';
$mip_form['9']['tab'] = '0';
$mip_form['9']['desc'] = '<strong>Spezielle Artikeldaten-Behandlung</strong><small><br/><strong>"Kapselung" </strong>erlaubt HTML in den Artikeldaten durch Kapselung<br/>aller Artikelelemente mittels &lt;![CDATA[ ... ]]&gt;-Bereiche.<br/><strong>"Zeichenkonvertierung"</strong> führt eine performancelastige Zeichenkonvertierung durch, um valides XML zu generieren.<br/><strong>"Keine Behandlung"</strong> gibt die Artikeldaten ohne besondere Behandlung aus.</small>';
$mip_form['9']['cms_var'] = 'MOD_VAR[9]';
$mip_form['9']['cms_val'] = $cms_mod['value']['9'];
$mip_form['9']['cms_val_default'] = '0';
$mip_form['9']['option_desc'][]= 'Kapselung';
$mip_form['9']['option_val'][]= '0';
$mip_form['9']['option_desc'][]= 'Zeichenkonvertierung';
$mip_form['9']['option_val'][]= '1';
$mip_form['9']['option_desc'][]= 'Kein Behandlung';
$mip_form['9']['option_val'][]= '2';

// Datumformat
$mip_form['10']['cat'] = 'txt';
$mip_form['10']['type'] = '';
$mip_form['10']['rows'] = '1';
$mip_form['10']['desc'] = '<strong>Datum<small> {startdate} {enddate} {date}</small></strong><br/>
<small>Tag: {day}, Monat: {month}, Jahr: {year}</small>';
$mip_form['10']['cms_var'] = 'MOD_VAR[10]';
$mip_form['10']['cms_val'] = $cms_mod['value']['10'];
$mip_form['10']['cms_val_default'] = '{day}.{month}.{year}';

// Zeitformat
$mip_form['11']['cat'] = 'txt';
$mip_form['11']['type'] = '';
$mip_form['11']['rows'] = '1';
$mip_form['11']['desc'] = '<strong>Zeit <small>{starttime} {endtime} {time}</small></strong><br/>
<small>Stunde: {hour}, Minute: {minute}</small>';
$mip_form['11']['cms_var'] = 'MOD_VAR[11]';
$mip_form['11']['cms_val'] = $cms_mod['value']['11'];
$mip_form['11']['cms_val_default'] = '{hour}:{minute} Uhr';

// Template Detailansicht
$mip_form['71']['desc'] = '<strong>Artikel-Detailseite</strong> <small>(idcatside oder 0 eingeben)<br/>(0 = automatisch - gemäß Kategorie-Routing<br/>oder Seite in welcher die XML-Datei erzeugt wurde)</small>';
$mip_form['71']['cat'] = 'txt';
$mip_form['71']['cms_var'] = 'MOD_VAR[71]';
$mip_form['71']['cms_val'] = $dedi_mod['value']['71'];
$mip_form['71']['cms_val_default'] = '0';

// Artikel aus dieser Kategorie anzeigen
$mip_form['8']['cat'] = 'option';
$mip_form['8']['type'] = '';
$mip_form['8']['rows'] = '1';
$mip_form['8']['desc'] = '<strong>Artikel aus Kategorie</strong>';
$mip_form['8']['cms_var'] = 'MOD_VAR[8]';
$mip_form['8']['cms_val'] = $cms_mod['value']['8'];
$mip_form['8']['cms_val_default'] = '0';
$mip_form['8']['option_desc'][] = 'Alle';
$mip_form['8']['option_val'][] = '0';


//AdoDB initialtisieren
$adodb =& $GLOBALS['sf_factory']->getObject('DATABASE', 'Ado');
$sql = "SELECT
            A.idlang, A.name
        FROM
            ".$cfg_cms['db_table_prefix']."lang A
        LEFT JOIN
            ".$cfg_cms['db_table_prefix']."clients_lang B USING(idlang)
        WHERE
            B.idclient='".$client."'
        ORDER BY
            idlang";
$rs = $adodb->Execute($sql);

$return_arr = array();
while (!$rs->EOF) {
    $AS_configtemp['lang'][$rs->fields[0]] = $rs->fields[1];
    $rs->MoveNext();
}

$rs->Close();

$sql = "SELECT idcategory, name, idlang FROM ".$cfg_cms['db_table_prefix']."plug_articlesystem_category WHERE idclient='".$client."' ORDER BY idlang,hash ASC"; // AND idlang='".$idlang."'
$rs = $adodb->Execute($sql);

$AS_configtemp['catinfo']='';

while (!$rs->EOF) {
		$AS_configtemp['catinfo'] .= '<tr><td><small>'.
																	$AS_configtemp['lang'][$rs->fields[2]].
																	'</small></td><td><small><strong>'.
																	$rs->fields[1].
																	'</strong></small></td><td><small>Id'.
																	'</small></td><td align="right"><small><strong>'.
																	$rs->fields[0].
																	'</strong></small></td></tr>';
    $mip_form['8']['option_desc'][] = '('.$AS_configtemp['lang'][$rs->fields[2]].') '.$rs->fields[1];
    $mip_form['8']['option_val'][] = $rs->fields[0];

    $rs->MoveNext();
}
$rs->Close();

unset($adodb, $sql, $rs);


// Template Listenansicht - Body
$mip_form['5']['desc'] = '<strong>Verbindungstemplate</strong>';
$mip_form['5']['cat'] = 'txtarea';
$mip_form['5']['rows'] = '14';
$mip_form['5']['type'] = 'long';
$mip_form['5']['cms_var'] = 'MOD_VAR[5]';
$mip_form['5']['cms_val'] = $cms_mod['value']['5'];
$mip_form['5']['cms_val_default'] = '<rss version="2.0">
	<channel>
	<title>RSS-Feed - {category}</title>
	<link>{site_url}</link>
	<description>Beschreibung des Feeds</description>
	<language>de-de</language>
	<copyright>urheberrechtliche Informationen</copyright>
	<pubDate>{date}</pubDate>
	{content1}
	</channel>
</rss>
';
// legend template single entry
$mip_form['90005']['cat'] = 'desc';
$mip_form['90005']['type'] = '';
$mip_form['90005']['desc'] = '<div id="legend1" style="display:none;text-align:right;"><small><strong>Inhaltselemente</strong><br/>
{site_url} {date} {time} 
{content1} {content2} <br/></small></div>
<div style="text-align:right;" onclick="this.innerHTML=document.getElementById(\'legend1\').innerHTML;"><small style="cursor: pointer;">Hilfe anzeigen<br /></small></div>';

// Template Listenansicht - Zeile
$mip_form['6']['desc'] = '<strong>Liste 1 {content1}</strong>';
$mip_form['6']['cat'] = 'txtarea';
$mip_form['6']['rows'] = '12';
$mip_form['6']['type'] = 'long';
$mip_form['6']['cms_var'] = 'MOD_VAR[6]';
$mip_form['6']['cms_val'] = $cms_mod['value']['6'];
$mip_form['6']['cms_val_default'] = '<item>
	<title>{title}</title>
	<description>{chop}{text}{/chop}</description>
	<link>{url}</link>
	<author>{custom:1}</author>
	<guid>{url}</guid>
</item>
';
// legend template single entry
$mip_form['90006']['cat'] = 'desc';
$mip_form['90006']['type'] = '';
$mip_form['90006']['desc'] = '<div id="legend2" style="display:none;text-align:right;"><small><strong>Inhaltselemente</strong><br/>
{startdate} {starttime} {enddate} {endtime} <br/>
{title} {teaser} {text} {category} <br/>
{image:x} {imageurl:x} {imagetitle:x} {imagedesc:x} {imagethumb:x} {imagethumburl:x}<br/>
{file:x} {fileurl:x} {filetitle:x} {filedesc:x}<br/>
{link:x} {linkurl:x} {linktitle:x} {linkdesc:x}<br/>
{custom:<em>1-10</em>} {custom_label:<em>1-10</em>}<br/>
<strong>Interaktion</strong><br/>{url} <em>(Detailansicht)</em><br/>
<strong>Inhaltslogik/-manipulation</strong><br/>{if_<em>Element</em>} ... {/if_<em>Element</em>} {if_not_<em>Element</em>} ... {/if_not_<em>Element</em>} {chop} ... {/chop}</small></div>
<div style="text-align:right;" onclick="this.innerHTML=document.getElementById(\'legend2\').innerHTML;"><small style="cursor: pointer;">Hilfe anzeigen<br /></small></div>';


// Template Detailansicht
$mip_form['7']['desc'] = '<strong>Liste 2 {content2}</strong>';
$mip_form['7']['cat'] = 'txtarea';
$mip_form['7']['rows'] = '7';
$mip_form['7']['type'] = 'long';
$mip_form['7']['cms_var'] = 'MOD_VAR[7]';
$mip_form['7']['cms_val'] = $cms_mod['value']['7'];
$mip_form['7']['cms_val_default'] = '';
//


// legend template single entry
$mip_form['90007']['cat'] = 'desc';
$mip_form['90007']['type'] = '';
$mip_form['90007']['desc'] = '<div id="legend3" style="display:none;text-align:right;"><small><strong>Inhaltselemente</strong><br/>
{turnus_type} {startdate} {starttime} {enddate} {endtime} <br/>
{title} {teaser} {text} {category}<br/>
{image:x} {imageurl:x} {imagetitle:x} {imagedesc:x} {imagethumb:x} {imagethumburl:x}<br/>
{file:x} {fileurl:x} {filetitle:x} {filedesc:x}<br/>
{link:x} {linkurl:x} {linktitle:x} {linkdesc:x}<br/>
{custom:<em>1-10</em>} {custom_label:<em>1-10</em>}<br/>
<strong>Interaktion</strong><br/>{url_back} <em>(Listenansicht)</em><br/>
<strong>Inhaltslogik/-manipulation</strong><br/>{if_<em>Element</em>} ... {/if_<em>Element</em>} {if_not_<em>Element</em>} ... {/if_not_<em>Element</em>} {chop} ... {/chop}</small></div>
<div style="text-align:right;" onclick="this.innerHTML=document.getElementById(\'legend3\').innerHTML;"><small style="cursor: pointer;">Hilfe anzeigen<br /></small></div>';


$mip_form['91003']['cat'] = 'desc';
$mip_form['91003']['type'] = '';
$mip_form['91003']['desc'] = '<strong>{chop} {/chop}</strong>';

//
$mip_form['1003']['cat'] = 'txt';
$mip_form['1003']['type'] = '';
$mip_form['1003']['desc'] = 'max. Zeichen';
$mip_form['1003']['cms_var'] = 'MOD_VAR[1003]';
$mip_form['1003']['cms_val'] = $cms_mod['value']['1003'];
$mip_form['1003']['cms_val_default'] = '200';

//
$mip_form['1004']['cat'] = 'chk';
$mip_form['1004']['type'] = '';
$mip_form['1004']['desc'] = 'in der Mitte kürzen';
$mip_form['1004']['option_var']['0'] = 'MOD_VAR[1004]';
$mip_form['1004']['option_val']['0'] = $cms_mod['value']['1004'];
$mip_form['1004']['option_desc']['0'] = 'ja';
$mip_form['1004']['option_val_select']['0'] = 'true';

// 
$mip_form['1005']['cat'] = 'txt';
$mip_form['1005']['type'] = '';
$mip_form['1005']['desc'] = 'Anhängsel';
$mip_form['1005']['cms_var'] = 'MOD_VAR[1005]';
$mip_form['1005']['cms_val'] = $cms_mod['value']['1005'];
$mip_form['1005']['cms_val_default'] = ' ... ';



// Anzahl der Eintraege
$mip_form['48']['cat'] = 'option';
$mip_form['48']['desc'] = '<strong>Anzahl der Einträge</strong>';
$mip_form['48']['cms_var'] = 'MOD_VAR[48]';
$mip_form['48']['cms_val'] = $cms_mod['value']['48'];
$mip_form['48']['cms_val_default'] = '10';
$mip_form['48']['option_desc']['0'] = '5';
$mip_form['48']['option_val']['0'] = '5';
$mip_form['48']['option_desc']['1'] = '10';
$mip_form['48']['option_val']['1'] = '10';
$mip_form['48']['option_desc']['2'] = '15';
$mip_form['48']['option_val']['2'] = '15';
$mip_form['48']['option_desc']['3'] = '20';
$mip_form['48']['option_val']['3'] = '20';
$mip_form['48']['option_desc']['4'] = '25';
$mip_form['48']['option_val']['4'] = '25';
$mip_form['48']['option_desc']['5'] = '50';
$mip_form['48']['option_val']['5'] = '50';

// Anzahl der Eintraege
$mip_form['480']['cat'] = 'option';
$mip_form['480']['desc'] = '<strong>Update-Intervall</strong>';
$mip_form['480']['cms_var'] = 'MOD_VAR[480]';
$mip_form['480']['cms_val'] = $cms_mod['value']['480'];
$mip_form['480']['cms_val_default'] = '15';
$mip_form['480']['option_desc']['0'] = '5 min';
$mip_form['480']['option_val']['0'] = '5';
$mip_form['480']['option_desc']['1'] = '15 min';
$mip_form['480']['option_val']['1'] = '15';
$mip_form['480']['option_desc']['2'] = '30 min';
$mip_form['480']['option_val']['2'] = '30';
$mip_form['480']['option_desc']['3'] = '1 h';
$mip_form['480']['option_val']['3'] = '60';
$mip_form['480']['option_desc']['4'] = '3 h';
$mip_form['480']['option_val']['4'] = '180';
$mip_form['480']['option_desc']['5'] = '6 h';
$mip_form['480']['option_val']['5'] = '360';
$mip_form['480']['option_desc']['6'] = '12 h';
$mip_form['480']['option_val']['6'] = '720';
$mip_form['480']['option_desc']['7'] = '24 h';
$mip_form['480']['option_val']['7'] = '1140';


$mip_form['300']['cat'] = 'txtarea';
$mip_form['300']['type'] = '';
$mip_form['300']['rows'] = '7';
$mip_form['300']['desc'] = '<strong>Kategorie-Routing</strong><br/><small>Bestimmt anhand der Ordner- o. Seiten-ID die zu listenden Artikel einer Kategorie und/oder generiert die Artikel-Links entsprechend.<br />Beispiele:<br />
<strong>idcat:13 > 1 </strong><i><br/>(Ist idcat 13 aktiv, wird die Artikelkategorie mit der id 1 gelistet)</i><br />
<strong>idcatside:34 > 2 </strong><i><br/>(Ist idcatside 34 aktiv, wird die Artikelkategorie mit der id 2 gelistet)</i><br/>
<strong>idcatside:27 > 1,2 </strong><i><br/>(Ist idcatside 27 aktiv, werden die Artikelkategorien mit der id 1 und 2 gelistet)</i></small>';
$mip_form['300']['cms_var'] = 'MOD_VAR[300]';
$mip_form['300']['cms_val'] = $cms_mod['value']['300'];

// Anzahl der Eintraege
$mip_form['302']['cat'] = 'option';
$mip_form['302']['desc'] = 'Kategorie-Routing nur für Artikel-Link-Generierung <small>{url}</small> nutzen <small><br/>(Es werden Artikel aller Kategorien gelistet,<br/>sofern nicht bei "Artikel aus Kategorie" anders definiert.)</small>';
$mip_form['302']['cms_var'] = 'MOD_VAR[302]';
$mip_form['302']['cms_val'] = $cms_mod['value']['302'];
$mip_form['302']['cms_val_default'] = '0';
$mip_form['302']['option_desc']['0'] = 'Nein';
$mip_form['302']['option_val']['0'] = '0';
$mip_form['302']['option_desc']['1'] = 'Ja';
$mip_form['302']['option_val']['1'] = '1';


$mip_form['301']['cat'] = 'desc';
$mip_form['301']['type'] = '';
$mip_form['301']['desc'] = '<div id="legend30" style="display:none;text-align:right;"><small><table align="right" cellspacing="5" cellpadding="0" border="0">'.$AS_configtemp['catinfo'].'</table></small></div>
<div style="text-align:right;margin-bottom:-20px;" onclick="this.innerHTML=document.getElementById(\'legend30\').innerHTML;"><small style="cursor: pointer;">Verfügbare Kategorien anzeigen</small></div>';

$mip_form['400']['cat'] = 'txtarea';
$mip_form['400']['type'] = '';
$mip_form['400']['rows'] = '5';
$mip_form['400']['desc'] = 'Sortierung der Einträge<br/><small><strong><em>DATENFELD</strong> (s.u.) > <strong>SORTIERUNG</strong> (ASC = aufsteigend, DESC = Absteigend)</em><br/>
<br/>startdate starttime enddate endtime<br/>
created lastedit<br/>
category title teaser text<br/>
custom1-10</small>';
$mip_form['400']['cms_var'] = 'MOD_VAR[400]';
$mip_form['400']['cms_val'] = $cms_mod['value']['400'];
$mip_form['400']['cms_val_default'] = 'startdate > DESC
starttime > DESC 
enddate > DESC
endtime >DESC
created > DESC
title > ASC';



$mip_form['99999']['cat'] = 'desc';
$mip_form['99999']['type'] = '';
$mip_form['99999']['desc'] = '';

$mip_form['99998']['cat'] = 'desc';
$mip_form['99998']['type'] = '';
$mip_form['99998']['desc'] = '';

// Navigation template vorwaerts aktiv
$mip_form['800']['cat'] = 'txt';
$mip_form['800']['desc'] = '<strong>Modulkennung</strong> <small><br/>(muss mit Artikelsystem-Ausgabe-Modul und -RSS-Modul übereinstimmen)</small>';
$mip_form['800']['cms_var'] = 'MOD_VAR[800]';
$mip_form['800']['cms_val'] = $cms_mod['value']['800'];
$mip_form['800']['cms_val_default'] = '';



// Navigation template vorwaerts aktiv
$mip_form['900']['cat'] = 'txt';
$mip_form['900']['desc'] = '<strong>Pfad/Dateiname </strong><small><br/>Elemente: {category_id} {category_name}</small>';
$mip_form['900']['cms_var'] = 'MOD_VAR[900]';
$mip_form['900']['cms_val'] = $cms_mod['value']['900'];
$mip_form['900']['cms_val_default'] = 'rss/feed_{category_id}.xml';

$mip_form['920']['desc'] = '<strong>Ausgabe-Template</strong><small><br/>Elemente: {rss_file}</small>';
$mip_form['920']['cat'] = 'txtarea';
$mip_form['920']['rows'] = '4';
$mip_form['920']['type'] = 'long';
$mip_form['920']['cms_var'] = 'MOD_VAR[920]';
$mip_form['920']['cms_val'] = $cms_mod['value']['920'];
$mip_form['920']['cms_val_default'] = '<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="{rss_file}" />';


mip_formsp($mip_form['0']);//configmode

$mip_form['no_rss_creation'] = array(480,48,3,9,4,5,90005,6,90006,7,10,11,400,91003,1003,1004,1005,99999,71,302);

$mip_form['hide_in_advanced_mode'] = array() ;
$mip_form['hide_in_standard_mode'] = array_merge(array() ,  $mip_form['hide_in_advanced_mode'] );

//if simple mode
if($cms_mod['value']['0'] == '' ){
   foreach ($mip_form['hide_in_standard_mode']  AS $ke=>$va){
     $mip_form[$va]['cat'] = 'hidden';
   }
 }


//hidden config
if($cms_mod['value']['0'] == 'hidden'){
  foreach($cms_mod['value'] AS $ke=>$va){
    if($ke != '0'){
    ?>
      <input name="MOD_VAR[<?php echo $ke; ?>]" type="hidden" value="<?php echo htmlentities($va,ENT_COMPAT,'UTF-8');?>">
    <?php
     }
  }
}
//simple, advanced and all config (not hidden config)
else if($cms_mod['value']['0'] == '' || $cms_mod['value']['0'] == 'advanced' || $cms_mod['value']['0'] == 'all'){
	
	//if simple mode
	if($cms_mod['value']['0'] == ''){
	   foreach ($mip_form['hide_in_standard_mode']  AS $ke=>$va){

	     $mip_form[$va]['cat'] = 'hidden';
  		 $mip_form[$va]['cms_var'] = 'MOD_VAR[' . $va . ']';
			 $mip_form[$va]['cms_val'] = $cms_mod['value'][$va];		     
	     
	   }
	 }
	
	//if simple mode
	if($cms_mod['value']['0'] == 'advanced'){
	   foreach ($mip_form['hide_in_advanced_mode']  AS $ke=>$va){

	     $mip_form[$va]['cat'] = 'hidden';
  		 $mip_form[$va]['cms_var'] = 'MOD_VAR[' . $va . ']';
			 $mip_form[$va]['cms_val'] = $cms_mod['value'][$va];		  

	   }
	 }

	//if simple mode
	if($cms_mod['value']['1'] == '1'){
	   foreach ($mip_form['no_rss_creation']  AS $ke=>$va){

	     $mip_form[$va]['cat'] = 'hidden';
  		 $mip_form[$va]['cms_var'] = 'MOD_VAR[' . $va . ']';
			 $mip_form[$va]['cms_val'] = $cms_mod['value'][$va];		  

	   }
	 }
	 
		mip_forms_tabpane_beginp();
		mip_forms_tabitem_beginp('Allgemeine Optionen');	

		mip_formsp($mip_form['480']); 

		mip_formsp($mip_form['48']);  
		mip_formsp($mip_form['99999']); //br	 
		mip_formsp($mip_form['3']);  
		mip_formsp($mip_form['400']); //sort
		mip_formsp($mip_form['99999']); //br	
		mip_formsp($mip_form['920']);
	
		mip_formsp($mip_form['900']);		
		mip_formsp($mip_form['99998']); //br


		mip_formsp($mip_form['99999']); //br
		mip_formsp($mip_form['8']); //category

		mip_formsp($mip_form['301']);	
		mip_formsp($mip_form['300']);		
		mip_formsp($mip_form['302']);	

		mip_formsp($mip_form['99998']); //br
		mip_formsp($mip_form['71']);
		mip_formsp($mip_form['99999']); //br
		mip_formsp($mip_form['1']);		
		mip_formsp($mip_form['99999']); //br
		mip_formsp($mip_form['4']);		
		mip_forms_tabitem_endp();


	if($cms_mod['value']['1'] != '1')
		mip_forms_tabitem_beginp('XML-Templates');	
		mip_formsp($mip_form['5']); //tpl: list->body
		mip_formsp($mip_form['90005']);
		mip_formsp($mip_form['6']); //tpl: list->row
		mip_formsp($mip_form['90006']);
		mip_formsp($mip_form['99999']); //br
		mip_formsp($mip_form['7']); //tpl: detail
		mip_formsp($mip_form['90006']);
	if($cms_mod['value']['1'] != '1')
		mip_forms_tabitem_endp();


	if($cms_mod['value']['1'] != '1')
		mip_forms_tabitem_beginp('Element-Templates');
		mip_formsp($mip_form['10']); //date_format
		mip_formsp($mip_form['11']); //time_format
	if($cms_mod['value']['1'] != '1')
		mip_forms_tabitem_endp();


		mip_forms_tabitem_beginp('Spezielle Einstellungen');	

			

		mip_formsp($mip_form['9']);  	
		mip_formsp($mip_form['99999']); //br	
		mip_formsp($mip_form['91003']);
		mip_formsp($mip_form['1003']); //chop
		mip_formsp($mip_form['1004']); //chop	
		mip_formsp($mip_form['1005']); //chop	
		mip_formsp($mip_form['99999']); //br
		mip_formsp($mip_form['800']);
		mip_forms_tabitem_endp();		
}

}
unset($mip_form,$AS_configtemp);

?>