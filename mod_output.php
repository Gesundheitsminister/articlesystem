<CMSPHP>

// Artikelsystem-Plugin - Ausgabemodul
// basiert auf dem Terminkalender-Ausgabe-Modul Version 1.2.5
// by mvsxyz
// Version 1.2.3
// by Alexander M. Korn
// amk@gmx.info

</CMSPHP>

<CMSPHP:CACHE>
    //Alle Variablen, die nur innerhalb des gecachten PHPs sichtbar sind, auch f&uuml;r dynamisches PHP zur Verf&uuml;gung stellen
    echo '<CMSPHP>';
    foreach($cms_mod['value'] AS $k => $v)
    {
        echo '$mvars["'.$k.'"] = "'. addslashes($v) .'";'."\n";
    }
    echo '</CMSPHP>';
</CMSPHP:CACHE>

<CMSPHP>

$_AS = array();

$_AS['basedir'] = $cfg_cms['cms_path'].'plugins/articlesystem/';

include $_AS['basedir'] .'inc/inc.mod_output.php';

unset($adodb,$rs, $_AS, $mvars, $mod);

</CMSPHP>