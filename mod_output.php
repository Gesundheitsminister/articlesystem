<CMSPHP>

// Artikelsystem RSS
// by Alexander M. Korn
// amk@gmx.info

$is_dev = false;
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
//print_r($mvars);

$_AS['basedir'] = $cfg_cms['cms_path'].'plugins/articlesystem/';

if($is_dev)
	include 'C:/_server/www/_sefrengo/mod_dev/Artikelsystem RSS/mod_output.php';
else {


// DB Definition

$_AS['db'] = $mvars['1000000'];

if (empty($_AS['db']))
	$_AS['db']='articlesystem';

include_once $_AS['basedir'] . 'inc/fnc.articlesystem_utilities.php';
include_once $_AS['basedir'] . 'inc/fnc.articlesystem_generate.php'; 
include_once $_AS['basedir'] . 'inc/paginator.php';
    
   
    
if(! function_exists(asxml_str_chop)){
	/**
	* Chop a string into a smaller string.
	* @author			Aidan Lister <aidan@php.net>
	* @author2		Alexander M. Korn <amk@gmx.info>
	* @version		?
	* @link				http://aidanlister.com/repos/v/function.str_chop.php
	* @param				mixed	 $string	 The string you want to shorten
	* @param				int		 $length	 The length you want to shorten the string to
	* @param				bool	 $center	 If true, chop in the middle of the string
	* @param				mixed	 $append	 String appended if it is shortened
	*/
	function asxml_str_chop($string, $length = 60, $center = true, $append = null)
	{
		global $mvars;
		if (empty($mvars[9]))
			$string=str_replace(array('<![CDATA[',']]>'),array('',''),$string);

		$string=strip_tags($string);
		$string=trim($string);
		if ($mvars[9]==1)		
			$string=asxml_safe_cr($string,'rev');
		
		 // Set the default append string
		if ($append === null) {
				 $append = ($center === true) ? ' ... ' : ' ...';
		}

		preg_match_all('#\&(.*)\;#sU',$string,$entities);
		$entities[0]=array_unique($entities[0]);
		

		
		if (is_array($entities[0]) && !empty($entities[0]))
			foreach($entities[0] as $v)
				$string=(str_replace( $v,utf8_encode(html_entity_decode( $v)),$string));
		
		if ($center==="true")
			$center=true;			
		
		 // Get some measurements
		 $len_string = strlen($string);
		 $len_append = strlen($append);
		 
		if ($len_string > $length) {
			
		 // If the string is longer than the maximum length, we need to chop it
		 
				 // Check if we want to chop it in half
			if ($center === true) {
				// Get the lengths of each segment
				$len_start = $length / 2;
				$len_end = $len_start - $len_append;
				
				// Get each segment
				$seg_start = substr($string, 0, $len_start);
				$seg_end = substr($string, $len_string - $len_end, $len_end);
				
				$seg_start = substr( $seg_start, 0, strrpos ( $seg_start, " "));
				$seg_end = substr( $seg_end, strpos ( $seg_end, " ")+1);
				
				// Stick them together
				$string = $seg_start . $append . $seg_end;
			} else {
				// Otherwise, just chop the end off
				$string = substr($string, 0, $length - $len_append);
				$string = substr($string, 0, strrpos ($string, " ")) . $append;
			}
		} 

		if ($mvars[9]==1)		
			return asxml_safe_cr($string,ENT_COMPAT,'UTF-8');	
		elseif (empty($mvars[9]))
			return '<![CDATA['.$string.']]>';
		else
			return $string;
	}
}

// create link
if (!function_exists('asxml_createLinkUrl')) {
	function asxml_createLinkUrl($langid, $newidcatside, $is_online) {
		global $cfg_client, $sess, $view;
		// backend view
		if (isset($view) && $view != "") {
			if ($is_online == "1") {
				return $sess->url($cfg_client["contentfile"].'?idcatside='.$newidcatside.'&lang='.$langid.'&view='.$view);
			}
		// frontend view
		} else {
			if ($is_online == "1") {
				// apache mod_rewrite support = 1
				if ($cfg_client['url_rewrite'] == '1') {
					$url_rewrite_in = array(
						"'(?<!/)".$cfg_client['contentfile']."\?idcat=([1-9][0-9]*)(&|&amp;)lang=([1-9][0-9]?)'",
						"'(?<!/)".$cfg_client['contentfile']."\?idcatside=([1-9][0-9]*)(&|&amp;)lang=([1-9][0-9]?)'"
					);
					$url_rewrite_out = array(
						"cat\\1-\\3.html",
						"page\\1-\\3.html"
					);
					$linkUrl = $sess->url($cfg_client["contentfile"].'?idcatside='.$newidcatside.'&amp;lang='.$langid);
					$linkUrl = preg_replace($url_rewrite_in, $url_rewrite_out, $linkUrl);
				// apache mod_rewrite support = 2
				} elseif ($cfg_client['url_rewrite'] == '2') {
					$linkUrl = rewriteGetPageUrl($newidcatside, $langid, true);
				} else {
					$linkUrl = $sess->url($cfg_client["contentfile"].'?idcatside='.$newidcatside.'&amp;lang='.$langid);
				}

				return $linkUrl;
			}
		}
	}
}
if (!function_exists('asxml_snippetReplace')) {
	function asxml_snippetReplace($content)
	{
		global $_AS;

		//no snippet replacement found
		if ($_AS['snippet_replacement_found'] === false)
		{
			return $content;
		}
		//figure out if snippet replacement exist
		else if ($_AS['snippet_replacement_found'] == 'unknown')
		{
			if (file_exists($GLOBALS['cfg_cms']['cms_path']. 'plugins/snippet_replacement/inc/class.SnippetReplacement.php'))
			{
				include_once $GLOBALS['cfg_cms']['cms_path']. 'plugins/snippet_replacement/inc/class.SnippetReplacement.php';
				$_AS['snippet_object']  =& new snippetReplacement();
				$_AS['snippet_replacement_found'] = true;
			}
			else
			{
				$_AS['snippet_replacement_found'] = false;
			}
		}
		
		//replace
		if ($_AS['snippet_replacement_found'] == true)
		{
			$content = $_AS['snippet_object']->replace($content, 'sr_lang', $GLOBALS['client'], $GLOBALS['lang']);
		}
		
		return $content;
	}
}

if(! function_exists(asxml_safe_cr)){
	function asxml_safe_cr($string,$mode=''){
	 $cr = array(
	  // http://intertwingly.net/stories/2004/04/14/i18n.html#CleaningWindows
	  '&#128;' => '&#8364;',
	  '&#129;' => '',
	  '&#130;' => '&#8218;',
	  '&#131;' => '&#402;',
	  '&#132;' => '&#8222;',
	  '&#133;' => '&#8230;',
	  '&#134;' => '&#8224;',
	  '&#135;' => '&#8225;',
	  '&#136;' => '&#710;',
	  '&#137;' => '&#8240;',
	  '&#138;' => '&#352;',
	  '&#139;' => '&#8249;',
	  '&#140;' => '&#338;',
	  '&#141;' => '',
	  '&#142;' => '&#381;',
	  '&#143;' => '',
	  '&#144;' => '',
	  '&#145;' => '&#8216;',
	  '&#146;' => '&#8217;',
	  '&#147;' => '&#8220;',
	  '&#148;' => '&#8221;',
	  '&#149;' => '&#8226;',
	  '&#150;' => '&#8211;',
	  '&#151;' => '&#8212;',
	  '&#152;' => '&#732;',
	  '&#153;' => '&#8482;',
	  '&#154;' => '&#353;',
	  '&#155;' => '&#8250;',
	  '&#156;' => '&#339;',
	  '&#157;' => '',
	  '&#158;' => '&#382;',
	  '&#159;' => '&#376;',
	
	  // HTML and MathML entities
	  // http://golem.ph.utexas.edu/~distler/blog/NumericEntities.html
	  '&alpha;' => '&#x0391;',
	  '&beta;' => '&#x0392;',
	  '&epsilon;' => '&#x0395;',
	  '&zeta;' => '&#x0396;',
	  '&eta;' => '&#x0397;',
	  '&iota;' => '&#x0399;',
	  '&kappa;' => '&#x039a;',
	  '&mu;' => '&#x039c;',
	  '&nu;' => '&#x039d;',
	  '&omicron;' => '&#x039f;',
	  '&rho;' => '&#x03a1;',
	  '&tau;' => '&#x03a4;',
	  '&chi;' => '&#x03a7;',
	  '&epsilon;' => '&#x03b5;',
	  '&zeta;' => '&#x03b6;',
	  '&omicron;' => '&#x03bf;',
	  '&sigmaf;' => '&#x03c2;',
	  '&thetasym;' => '&#x03d1;',
	  '&upsih;' => '&#x03d2;',
	  '&oline;' => '&#x203e;',
	  '&frasl;' => '&#x2044;',
	  '&alefsym;' => '&#x2135;',
	  '&crarr;' => '&#x21b5;',
	  '&empty;' => '&#x2205;',
	  '&hearts;' => '&#x2265;',
	  '&zwnj;' => '&#x200c;',
	  '&zwj;' => '&#x200d;',
	  '&lrm;' => '&#x200e;',
	  '&rlm;' => '&#x200f;',
	  '&sbquo;' => '&#x201a;',
	  '&bdquo;' => '&#x201e;',
	  '&lsaquo;' => '&#x2039;',
	  '&rsaquo;' => '&#x203a;',
	  '&euro;' => '&#x20ac;',
	  '&angzarr;' => '&#x0237c;',
	  '&cirmid;' => '&#x02aef;',
	  '&cudarrl;' => '&#x02938;',
	  '&cudarrr;' => '&#x02935;',
	  '&cularr;' => '&#x021b6;',
	  '&cularrp;' => '&#x0293d;',
	  '&curarr;' => '&#x021b7;',
	  '&curarrm;' => '&#x0293c;',
	  '&darr;' => '&#x021a1;',
	  '&darr;' => '&#x021d3;',
	  '&ddarr;' => '&#x021ca;',
	  '&ddotrahd;' => '&#x02911;',
	  '&dfisht;' => '&#x0297f;',
	  '&dhar;' => '&#x02965;',
	  '&dharl;' => '&#x021c3;',
	  '&dharr;' => '&#x021c2;',
	  '&duarr;' => '&#x021f5;',
	  '&duhar;' => '&#x0296f;',
	  '&dzigrarr;' => '&#x027ff;',
	  '&erarr;' => '&#x02971;',
	  '&harr;' => '&#x021d4;',
	  '&harr;' => '&#x02194;',
	  '&harrcir;' => '&#x02948;',
	  '&harrw;' => '&#x021ad;',
	  '&hoarr;' => '&#x021ff;',
	  '&imof;' => '&#x022b7;',
	  '&laarr;' => '&#x021da;',
	  '&larr;' => '&#x0219e;',
	  '&larrbfs;' => '&#x0291f;',
	  '&larrfs;' => '&#x0291d;',
	  '&larrhk;' => '&#x021a9;',
	  '&larrlp;' => '&#x021ab;',
	  '&larrpl;' => '&#x02939;',
	  '&larrsim;' => '&#x02973;',
	  '&larrtl;' => '&#x021a2;',
	  '&latail;' => '&#x0291b;',
	  '&latail;' => '&#x02919;',
	  '&lbarr;' => '&#x0290e;',
	  '&lbarr;' => '&#x0290c;',
	  '&ldca;' => '&#x02936;',
	  '&ldrdhar;' => '&#x02967;',
	  '&ldrushar;' => '&#x0294b;',
	  '&ldsh;' => '&#x021b2;',
	  '&lfisht;' => '&#x0297c;',
	  '&lhar;' => '&#x02962;',
	  '&lhard;' => '&#x021bd;',
	  '&lharu;' => '&#x021bc;',
	  '&lharul;' => '&#x0296a;',
	  '&llarr;' => '&#x021c7;',
	  '&llhard;' => '&#x0296b;',
	  '&loarr;' => '&#x021fd;',
	  '&lrarr;' => '&#x021c6;',
	  '&lrhar;' => '&#x021cb;',
	  '&lrhard;' => '&#x0296d;',
	  '&lsh;' => '&#x021b0;',
	  '&lurdshar;' => '&#x0294a;',
	  '&luruhar;' => '&#x02966;',
	  '&map;' => '&#x02905;',
	  '&map;' => '&#x021a6;',
	  '&midcir;' => '&#x02af0;',
	  '&mumap;' => '&#x022b8;',
	  '&nearhk;' => '&#x02924;',
	  '&nearr;' => '&#x021d7;',
	  '&nearr;' => '&#x02197;',
	  '&nesear;' => '&#x02928;',
	  '&nharr;' => '&#x021ce;',
	  '&nharr;' => '&#x021ae;',
	  '&nlarr;' => '&#x021cd;',
	  '&nlarr;' => '&#x0219a;',
	  '&nrarr;' => '&#x021cf;',
	  '&nrarr;' => '&#x0219b;',
	  '&nrarrc;' => '&#x02933;&#x00338;',
	  '&nrarrw;' => '&#x0219d;&#x00338;',
	  '&nvharr;' => '&#x02904;',
	  '&nvlarr;' => '&#x02902;',
	  '&nvrarr;' => '&#x02903;',
	  '&nwarhk;' => '&#x02923;',
	  '&nwarr;' => '&#x021d6;',
	  '&nwarr;' => '&#x02196;',
	  '&nwnear;' => '&#x02927;',
	  '&olarr;' => '&#x021ba;',
	  '&orarr;' => '&#x021bb;',
	  '&origof;' => '&#x022b6;',
	  '&raarr;' => '&#x021db;',
	  '&rarr;' => '&#x021a0;',
	  '&rarrap;' => '&#x02975;',
	  '&rarrbfs;' => '&#x02920;',
	  '&rarrc;' => '&#x02933;',
	  '&rarrfs;' => '&#x0291e;',
	  '&rarrhk;' => '&#x021aa;',
	  '&rarrlp;' => '&#x021ac;',
	  '&rarrpl;' => '&#x02945;',
	  '&rarrsim;' => '&#x02974;',
	  '&rarrtl;' => '&#x02916;',
	  '&rarrtl;' => '&#x021a3;',
	  '&rarrw;' => '&#x0219d;',
	  '&ratail;' => '&#x0291c;',
	  '&ratail;' => '&#x0291a;',
	  '&rbarr;' => '&#x02910;',
	  '&rbarr;' => '&#x0290f;',
	  '&rbarr;' => '&#x0290d;',
	  '&rdca;' => '&#x02937;',
	  '&rdldhar;' => '&#x02969;',
	  '&rdsh;' => '&#x021b3;',
	  '&rfisht;' => '&#x0297d;',
	  '&rhar;' => '&#x02964;',
	  '&rhard;' => '&#x021c1;',
	  '&rharu;' => '&#x021c0;',
	  '&rharul;' => '&#x0296c;',
	  '&rlarr;' => '&#x021c4;',
	  '&rlhar;' => '&#x021cc;',
	  '&roarr;' => '&#x021fe;',
	  '&rrarr;' => '&#x021c9;',
	  '&rsh;' => '&#x021b1;',
	  '&ruluhar;' => '&#x02968;',
	  '&searhk;' => '&#x02925;',
	  '&searr;' => '&#x021d8;',
	  '&searr;' => '&#x02198;',
	  '&seswar;' => '&#x02929;',
	  '&simrarr;' => '&#x02972;',
	  '&slarr;' => '&#x02190;',
	  '&srarr;' => '&#x02192;',
	  '&swarhk;' => '&#x02926;',
	  '&swarr;' => '&#x021d9;',
	  '&swarr;' => '&#x02199;',
	  '&swnwar;' => '&#x0292a;',
	  '&uarr;' => '&#x0219f;',
	  '&uarr;' => '&#x021d1;',
	  '&uarrocir;' => '&#x02949;',
	  '&udarr;' => '&#x021c5;',
	  '&udhar;' => '&#x0296e;',
	  '&ufisht;' => '&#x0297e;',
	  '&uhar;' => '&#x02963;',
	  '&uharl;' => '&#x021bf;',
	  '&uharr;' => '&#x021be;',
	  '&uuarr;' => '&#x021c8;',
	  '&varr;' => '&#x021d5;',
	  '&varr;' => '&#x02195;',
	  '&xharr;' => '&#x027fa;',
	  '&xharr;' => '&#x027f7;',
	  '&xlarr;' => '&#x027f8;',
	  '&xlarr;' => '&#x027f5;',
	  '&xmap;' => '&#x027fc;',
	  '&xrarr;' => '&#x027f9;',
	  '&xrarr;' => '&#x027f6;',
	  '&zigrarr;' => '&#x021dd;',
	  '&ac;' => '&#x0223e;',
	  '&ace;' => '&#x0223e;&#x00333;',
	  '&amalg;' => '&#x02a3f;',
	  '&barvee;' => '&#x022bd;',
	  '&barwed;' => '&#x02306;',
	  '&barwed;' => '&#x02305;',
	  '&bsolb;' => '&#x029c5;',
	  '&cap;' => '&#x022d2;',
	  '&capand;' => '&#x02a44;',
	  '&capbrcup;' => '&#x02a49;',
	  '&capcap;' => '&#x02a4b;',
	  '&capcup;' => '&#x02a47;',
	  '&capdot;' => '&#x02a40;',
	  '&caps;' => '&#x02229;&#x0fe00;',
	  '&ccaps;' => '&#x02a4d;',
	  '&ccups;' => '&#x02a4c;',
	  '&ccupssm;' => '&#x02a50;',
	  '&coprod;' => '&#x02210;',
	  '&cup;' => '&#x022d3;',
	  '&cupbrcap;' => '&#x02a48;',
	  '&cupcap;' => '&#x02a46;',
	  '&cupcup;' => '&#x02a4a;',
	  '&cupdot;' => '&#x0228d;',
	  '&cupor;' => '&#x02a45;',
	  '&cups;' => '&#x0222a;&#x0fe00;',
	  '&cuvee;' => '&#x022ce;',
	  '&cuwed;' => '&#x022cf;',
	  '&dagger;' => '&#x02021;',
	  '&dagger;' => '&#x02020;',
	  '&diam;' => '&#x022c4;',
	  '&divonx;' => '&#x022c7;',
	  '&eplus;' => '&#x02a71;',
	  '&hercon;' => '&#x022b9;',
	  '&intcal;' => '&#x022ba;',
	  '&iprod;' => '&#x02a3c;',
	  '&loplus;' => '&#x02a2d;',
	  '&lotimes;' => '&#x02a34;',
	  '&lthree;' => '&#x022cb;',
	  '&ltimes;' => '&#x022c9;',
	  '&midast;' => '&#x0002a;',
	  '&minusb;' => '&#x0229f;',
	  '&minusd;' => '&#x02238;',
	  '&minusdu;' => '&#x02a2a;',
	  '&ncap;' => '&#x02a43;',
	  '&ncup;' => '&#x02a42;',
	  '&oast;' => '&#x0229b;',
	  '&ocir;' => '&#x0229a;',
	  '&odash;' => '&#x0229d;',
	  '&odiv;' => '&#x02a38;',
	  '&odot;' => '&#x02299;',
	  '&odsold;' => '&#x029bc;',
	  '&ofcir;' => '&#x029bf;',
	  '&ogt;' => '&#x029c1;',
	  '&ohbar;' => '&#x029b5;',
	  '&olcir;' => '&#x029be;',
	  '&olt;' => '&#x029c0;',
	  '&omid;' => '&#x029b6;',
	  '&ominus;' => '&#x02296;',
	  '&opar;' => '&#x029b7;',
	  '&operp;' => '&#x029b9;',
	  '&oplus;' => '&#x02295;',
	  '&osol;' => '&#x02298;',
	  '&otimes;' => '&#x02a37;',
	  '&otimes;' => '&#x02297;',
	  '&otimesas;' => '&#x02a36;',
	  '&ovbar;' => '&#x0233d;',
	  '&plusacir;' => '&#x02a23;',
	  '&plusb;' => '&#x0229e;',
	  '&pluscir;' => '&#x02a22;',
	  '&plusdo;' => '&#x02214;',
	  '&plusdu;' => '&#x02a25;',
	  '&pluse;' => '&#x02a72;',
	  '&plussim;' => '&#x02a26;',
	  '&plustwo;' => '&#x02a27;',
	  '&prod;' => '&#x0220f;',
	  '&race;' => '&#x029da;',
	  '&roplus;' => '&#x02a2e;',
	  '&rotimes;' => '&#x02a35;',
	  '&rthree;' => '&#x022cc;',
	  '&rtimes;' => '&#x022ca;',
	  '&sdot;' => '&#x022c5;',
	  '&sdotb;' => '&#x022a1;',
	  '&setmn;' => '&#x02216;',
	  '&simplus;' => '&#x02a24;',
	  '&smashp;' => '&#x02a33;',
	  '&solb;' => '&#x029c4;',
	  '&sqcap;' => '&#x02293;',
	  '&sqcaps;' => '&#x02293;&#x0fe00;',
	  '&sqcup;' => '&#x02294;',
	  '&sqcups;' => '&#x02294;&#x0fe00;',
	  '&ssetmn;' => '&#x02216;',
	  '&sstarf;' => '&#x022c6;',
	  '&subdot;' => '&#x02abd;',
	  '&sum;' => '&#x02211;',
	  '&supdot;' => '&#x02abe;',
	  '&timesb;' => '&#x022a0;',
	  '&timesbar;' => '&#x02a31;',
	  '&timesd;' => '&#x02a30;',
	  '&tridot;' => '&#x025ec;',
	  '&triminus;' => '&#x02a3a;',
	  '&triplus;' => '&#x02a39;',
	  '&trisb;' => '&#x029cd;',
	  '&tritime;' => '&#x02a3b;',
	  '&uplus;' => '&#x0228e;',
	  '&veebar;' => '&#x022bb;',
	  '&wedbar;' => '&#x02a5f;',
	  '&wreath;' => '&#x02240;',
	  '&xcap;' => '&#x022c2;',
	  '&xcirc;' => '&#x025ef;',
	  '&xcup;' => '&#x022c3;',
	  '&xdtri;' => '&#x025bd;',
	  '&xodot;' => '&#x02a00;',
	  '&xoplus;' => '&#x02a01;',
	  '&xotime;' => '&#x02a02;',
	  '&xsqcup;' => '&#x02a06;',
	  '&xuplus;' => '&#x02a04;',
	  '&xutri;' => '&#x025b3;',
	  '&xvee;' => '&#x022c1;',
	  '&xwedge;' => '&#x022c0;',
	  '&dlcorn;' => '&#x0231e;',
	  '&drcorn;' => '&#x0231f;',
	  '&gtlpar;' => '&#x02995;',
	  '&langd;' => '&#x02991;',
	  '&lbrke;' => '&#x0298b;',
	  '&lbrksld;' => '&#x0298f;',
	  '&lbrkslu;' => '&#x0298d;',
	  '&lceil;' => '&#x02308;',
	  '&lfloor;' => '&#x0230a;',
	  '&lmoust;' => '&#x023b0;',
	  '&lparlt;' => '&#x02993;',
	  '&ltrpar;' => '&#x02996;',
	  '&rangd;' => '&#x02992;',
	  '&rbrke;' => '&#x0298c;',
	  '&rbrksld;' => '&#x0298e;',
	  '&rbrkslu;' => '&#x02990;',
	  '&rceil;' => '&#x02309;',
	  '&rfloor;' => '&#x0230b;',
	  '&rmoust;' => '&#x023b1;',
	  '&rpargt;' => '&#x02994;',
	  '&ulcorn;' => '&#x0231c;',
	  '&urcorn;' => '&#x0231d;',
	  '&gnap;' => '&#x02a8a;',
	  '&gne;' => '&#x02269;',
	  '&gne;' => '&#x02a88;',
	  '&gnsim;' => '&#x022e7;',
	  '&gvne;' => '&#x02269;&#x0fe00;',
	  '&lnap;' => '&#x02a89;',
	  '&lne;' => '&#x02268;',
	  '&lne;' => '&#x02a87;',
	  '&lnsim;' => '&#x022e6;',
	  '&lvne;' => '&#x02268;&#x0fe00;',
	  '&nap;' => '&#x02249;',
	  '&nape;' => '&#x02a70;&#x00338;',
	  '&napid;' => '&#x0224b;&#x00338;',
	  '&ncong;' => '&#x02247;',
	  '&ncongdot;' => '&#x02a6d;&#x00338;',
	  '&nequiv;' => '&#x02262;',
	  '&nge;' => '&#x02267;&#x00338;',
	  '&nge;' => '&#x02271;',
	  '&nges;' => '&#x02a7e;&#x00338;',
	  '&ngg;' => '&#x022d9;&#x00338;',
	  '&ngsim;' => '&#x02275;',
	  '&ngt;' => '&#x0226b;&#x020d2;',
	  '&ngt;' => '&#x0226f;',
	  '&ngtv;' => '&#x0226b;&#x00338;',
	  '&nle;' => '&#x02266;&#x00338;',
	  '&nle;' => '&#x02270;',
	  '&nles;' => '&#x02a7d;&#x00338;',
	  '&nll;' => '&#x022d8;&#x00338;',
	  '&nlsim;' => '&#x02274;',
	  '&nlt;' => '&#x0226a;&#x020d2;',
	  '&nlt;' => '&#x0226e;',
	  '&nltri;' => '&#x022ea;',
	  '&nltrie;' => '&#x022ec;',
	  '&nltv;' => '&#x0226a;&#x00338;',
	  '&nmid;' => '&#x02224;',
	  '&npar;' => '&#x02226;',
	  '&npr;' => '&#x02280;',
	  '&nprcue;' => '&#x022e0;',
	  '&npre;' => '&#x02aaf;&#x00338;',
	  '&nrtri;' => '&#x022eb;',
	  '&nrtrie;' => '&#x022ed;',
	  '&nsc;' => '&#x02281;',
	  '&nsccue;' => '&#x022e1;',
	  '&nsce;' => '&#x02ab0;&#x00338;',
	  '&nsim;' => '&#x02241;',
	  '&nsime;' => '&#x02244;',
	  '&nsmid;' => '&#x02224;',
	  '&nspar;' => '&#x02226;',
	  '&nsqsube;' => '&#x022e2;',
	  '&nsqsupe;' => '&#x022e3;',
	
	  '&nsub;' => '&#x02284;',
	  '&nsube;' => '&#x02ac5;&#x00338;',
	  '&nsube;' => '&#x02288;',
	  '&nsup;' => '&#x02285;',
	  '&nsupe;' => '&#x02ac6;&#x00338;',
	  '&nsupe;' => '&#x02289;',
	  '&ntgl;' => '&#x02279;',
	  '&ntlg;' => '&#x02278;',
	  '&nvap;' => '&#x0224d;&#x020d2;',
	  '&nvdash;' => '&#x022af;',
	  '&nvdash;' => '&#x022ae;',
	  '&nvdash;' => '&#x022ad;',
	  '&nvdash;' => '&#x022ac;',
	  '&nvge;' => '&#x02265;&#x020d2;',
	  '&nvgt;' => '&#x0003e;&#x020d2;',
	  '&nvle;' => '&#x02264;&#x020d2;',
	  '&nvltrie;' => '&#x022b4;&#x020d2;',
	  '&nvrtrie;' => '&#x022b5;&#x020d2;',
	  '&nvsim;' => '&#x0223c;&#x020d2;',
	  '&parsim;' => '&#x02af3;',
	  '&prnap;' => '&#x02ab9;',
	  '&prne;' => '&#x02ab5;',
	  '&prnsim;' => '&#x022e8;',
	  '&rnmid;' => '&#x02aee;',
	
	  '&scnap;' => '&#x02aba;',
	  '&scne;' => '&#x02ab6;',
	  '&scnsim;' => '&#x022e9;',
	  '&simne;' => '&#x02246;',
	  '&solbar;' => '&#x0233f;',
	  '&subne;' => '&#x02acb;',
	  '&subne;' => '&#x0228a;',
	  '&supne;' => '&#x02acc;',
	  '&supne;' => '&#x0228b;',
	  '&vnsub;' => '&#x02282;&#x020d2;',
	  '&vnsup;' => '&#x02283;&#x020d2;',
	  '&vsubne;' => '&#x02acb;&#x0fe00;',
	  '&vsubne;' => '&#x0228a;&#x0fe00;',
	  '&vsupne;' => '&#x02acc;&#x0fe00;',
	  '&vsupne;' => '&#x0228b;&#x0fe00;',
	  '&ang;' => '&#x02220;',
	  '&ange;' => '&#x029a4;',
	  '&angmsd;' => '&#x02221;',
	  '&angmsdaa;' => '&#x029a8;',
	  '&angmsdab;' => '&#x029a9;',
	  '&angmsdac;' => '&#x029aa;',
	  '&angmsdad;' => '&#x029ab;',
	  '&angmsdae;' => '&#x029ac;',
	  '&angmsdaf;' => '&#x029ad;',
	  '&angmsdag;' => '&#x029ae;',
	  '&angmsdah;' => '&#x029af;',
	  '&angrtvb;' => '&#x022be;',
	  '&angrtvbd;' => '&#x0299d;',
	  '&bbrk;' => '&#x023b5;',
	  '&bbrktbrk;' => '&#x023b6;',
	  '&bemptyv;' => '&#x029b0;',
	  '&beth;' => '&#x02136;',
	  '&boxbox;' => '&#x029c9;',
	  '&bprime;' => '&#x02035;',
	  '&bsemi;' => '&#x0204f;',
	  '&cemptyv;' => '&#x029b2;',
	  '&cire;' => '&#x029c3;',
	  '&cirscir;' => '&#x029c2;',
	  '&comp;' => '&#x02201;',
	  '&daleth;' => '&#x02138;',
	  '&demptyv;' => '&#x029b1;',
	  '&ell;' => '&#x02113;',
	  '&empty;' => '&#x02205;',
	  '&emptyv;' => '&#x02205;',
	  '&gimel;' => '&#x02137;',
	  '&iiota;' => '&#x02129;',
	  '&image;' => '&#x02111;',
	  '&imath;' => '&#x00131;',
	  '&jmath;' => '&#x0006a;',
	  '&laemptyv;' => '&#x029b4;',
	  '&lltri;' => '&#x025fa;',
	  '&lrtri;' => '&#x022bf;',
	  '&mho;' => '&#x02127;',
	  '&nang;' => '&#x02220;&#x020d2;',
	  '&nexist;' => '&#x02204;',
	  '&os;' => '&#x024c8;',
	  '&planck;' => '&#x0210f;',
	  '&plankv;' => '&#x0210f;',
	  '&raemptyv;' => '&#x029b3;',
	  '&range;' => '&#x029a5;',
	  '&real;' => '&#x0211c;',
	  '&tbrk;' => '&#x023b4;',
	  '&trpezium;' => '&#x0fffd;',
	  '&ultri;' => '&#x025f8;',
	  '&urtri;' => '&#x025f9;',
	  '&vzigzag;' => '&#x0299a;',
	  '&weierp;' => '&#x02118;',
	  '&ape;' => '&#x02a70;',
	  '&ape;' => '&#x0224a;',
	  '&apid;' => '&#x0224b;',
	  '&asymp;' => '&#x02248;',
	  '&barv;' => '&#x02ae7;',
	  '&bcong;' => '&#x0224c;',
	  '&bepsi;' => '&#x003f6;',
	  '&bowtie;' => '&#x022c8;',
	  '&bsim;' => '&#x0223d;',
	  '&bsime;' => '&#x022cd;',
	  '&bsolhsub;' => '&#x0005c;&#x02282;',
	  '&bump;' => '&#x0224e;',
	  '&bumpe;' => '&#x02aae;',
	  '&bumpe;' => '&#x0224f;',
	  '&cire;' => '&#x02257;',
	  '&colon;' => '&#x02237;',
	  '&colone;' => '&#x02a74;',
	  '&colone;' => '&#x02254;',
	  '&congdot;' => '&#x02a6d;',
	  '&csub;' => '&#x02acf;',
	  '&csube;' => '&#x02ad1;',
	  '&csup;' => '&#x02ad0;',
	  '&csupe;' => '&#x02ad2;',
	  '&cuepr;' => '&#x022de;',
	  '&cuesc;' => '&#x022df;',
	  '&dashv;' => '&#x02ae4;',
	  '&dashv;' => '&#x022a3;',
	  '&easter;' => '&#x02a6e;',
	  '&ecir;' => '&#x02256;',
	  '&ecolon;' => '&#x02255;',
	  '&eddot;' => '&#x02a77;',
	  '&edot;' => '&#x02251;',
	  '&efdot;' => '&#x02252;',
	  '&eg;' => '&#x02a9a;',
	  '&egs;' => '&#x02a96;',
	  '&egsdot;' => '&#x02a98;',
	  '&el;' => '&#x02a99;',
	  '&els;' => '&#x02a95;',
	  '&elsdot;' => '&#x02a97;',
	  '&equest;' => '&#x0225f;',
	  '&equivdd;' => '&#x02a78;',
	  '&erdot;' => '&#x02253;',
	  '&esdot;' => '&#x02250;',
	  '&esim;' => '&#x02a73;',
	  '&esim;' => '&#x02242;',
	  '&fork;' => '&#x022d4;',
	  '&forkv;' => '&#x02ad9;',
	  '&frown;' => '&#x02322;',
	  '&gap;' => '&#x02a86;',
	  '&ge;' => '&#x02267;',
	  '&gel;' => '&#x02a8c;',
	  '&gel;' => '&#x022db;',
	  '&ges;' => '&#x02a7e;',
	  '&gescc;' => '&#x02aa9;',
	  '&gesdot;' => '&#x02a80;',
	  '&gesdoto;' => '&#x02a82;',
	  '&gesdotol;' => '&#x02a84;',
	  '&gesl;' => '&#x022db;&#x0fe00;',
	  '&gesles;' => '&#x02a94;',
	  '&gg;' => '&#x022d9;',
	  '&gl;' => '&#x02277;',
	  '&gla;' => '&#x02aa5;',
	  '&gle;' => '&#x02a92;',
	  '&glj;' => '&#x02aa4;',
	  '&gsim;' => '&#x02273;',
	  '&gsime;' => '&#x02a8e;',
	  '&gsiml;' => '&#x02a90;',
	  '&gt;' => '&#x0226b;',
	  '&gtcc;' => '&#x02aa7;',
	  '&gtcir;' => '&#x02a7a;',
	  '&gtdot;' => '&#x022d7;',
	  '&gtquest;' => '&#x02a7c;',
	  '&gtrarr;' => '&#x02978;',
	  '&homtht;' => '&#x0223b;',
	  '&lap;' => '&#x02a85;',
	  '&lat;' => '&#x02aab;',
	  '&late;' => '&#x02aad;',
	  '&lates;' => '&#x02aad;&#x0fe00;',
	  '&le;' => '&#x02266;',
	  '&leg;' => '&#x02a8b;',
	  '&leg;' => '&#x022da;',
	  '&les;' => '&#x02a7d;',
	  '&lescc;' => '&#x02aa8;',
	  '&lesdot;' => '&#x02a7f;',
	  '&lesdoto;' => '&#x02a81;',
	  '&lesdotor;' => '&#x02a83;',
	  '&lesg;' => '&#x022da;&#x0fe00;',
	  '&lesges;' => '&#x02a93;',
	  '&lg;' => '&#x02276;',
	  '&lge;' => '&#x02a91;',
	  '&ll;' => '&#x022d8;',
	  '&lsim;' => '&#x02272;',
	  '&lsime;' => '&#x02a8d;',
	  '&lsimg;' => '&#x02a8f;',
	  '&lt;' => '&#x0226a;',
	  '&ltcc;' => '&#x02aa6;',
	  '&ltcir;' => '&#x02a79;',
	  '&ltdot;' => '&#x022d6;',
	  '&ltlarr;' => '&#x02976;',
	  '&ltquest;' => '&#x02a7b;',
	  '&ltrie;' => '&#x022b4;',
	  '&mcomma;' => '&#x02a29;',
	  '&mddot;' => '&#x0223a;',
	  '&mid;' => '&#x02223;',
	  '&mlcp;' => '&#x02adb;',
	  '&models;' => '&#x022a7;',
	  '&mstpos;' => '&#x0223e;',
	  '&pr;' => '&#x02abb;',
	  '&pr;' => '&#x0227a;',
	  '&prap;' => '&#x02ab7;',
	  '&prcue;' => '&#x0227c;',
	  '&pre;' => '&#x02ab3;',
	  '&pre;' => '&#x02aaf;',
	  '&prsim;' => '&#x0227e;',
	  '&prurel;' => '&#x022b0;',
	  '&ratio;' => '&#x02236;',
	  '&rtrie;' => '&#x022b5;',
	  '&rtriltri;' => '&#x029ce;',
	  '&sc;' => '&#x02abc;',
	  '&sc;' => '&#x0227b;',
	  '&scap;' => '&#x02ab8;',
	  '&sccue;' => '&#x0227d;',
	  '&sce;' => '&#x02ab4;',
	  '&sce;' => '&#x02ab0;',
	  '&scsim;' => '&#x0227f;',
	  '&sdote;' => '&#x02a66;',
	  '&sfrown;' => '&#x02322;',
	  '&simg;' => '&#x02a9e;',
	  '&simge;' => '&#x02aa0;',
	  '&siml;' => '&#x02a9d;',
	  '&simle;' => '&#x02a9f;',
	  '&smid;' => '&#x02223;',
	  '&smile;' => '&#x02323;',
	  '&smt;' => '&#x02aaa;',
	  '&smte;' => '&#x02aac;',
	  '&smtes;' => '&#x02aac;&#x0fe00;',
	  '&spar;' => '&#x02225;',
	  '&sqsub;' => '&#x0228f;',
	  '&sqsube;' => '&#x02291;',
	  '&sqsup;' => '&#x02290;',
	  '&sqsupe;' => '&#x02292;',
	  '&ssmile;' => '&#x02323;',
	  '&sub;' => '&#x022d0;',
	  '&sube;' => '&#x02ac5;',
	  '&subedot;' => '&#x02ac3;',
	  '&submult;' => '&#x02ac1;',
	  '&subplus;' => '&#x02abf;',
	  '&subrarr;' => '&#x02979;',
	  '&subsim;' => '&#x02ac7;',
	  '&subsub;' => '&#x02ad5;',
	  '&subsup;' => '&#x02ad3;',
	  '&sup;' => '&#x022d1;',
	  '&supdsub;' => '&#x02ad8;',
	  '&supe;' => '&#x02ac6;',
	  '&supedot;' => '&#x02ac4;',
	  '&suphsol;' => '&#x02283;&#x0002f;',
	  '&suphsub;' => '&#x02ad7;',
	  '&suplarr;' => '&#x0297b;',
	  '&supmult;' => '&#x02ac2;',
	  '&supplus;' => '&#x02ac0;',
	  '&supsim;' => '&#x02ac8;',
	  '&supsub;' => '&#x02ad4;',
	  '&supsup;' => '&#x02ad6;',
	  '&thkap;' => '&#x02248;',
	  '&thksim;' => '&#x0223c;',
	  '&topfork;' => '&#x02ada;',
	  '&trie;' => '&#x0225c;',
	  '&twixt;' => '&#x0226c;',
	  '&vbar;' => '&#x02aeb;',
	  '&vbar;' => '&#x02ae8;',
	  '&vbarv;' => '&#x02ae9;',
	  '&vdash;' => '&#x022ab;',
	  '&vdash;' => '&#x022a9;',
	  '&vdash;' => '&#x022a8;',
	  '&vdash;' => '&#x022a2;',
	  '&vdashl;' => '&#x02ae6;',
	  '&vltri;' => '&#x022b2;',
	  '&vprop;' => '&#x0221d;',
	  '&vrtri;' => '&#x022b3;',
	  '&vvdash;' => '&#x022aa;',
	  '&alpha;' => '&#x003b1;',
	  '&beta;' => '&#x003b2;',
	  '&chi;' => '&#x003c7;',
	  '&delta;' => '&#x00394;',
	  '&delta;' => '&#x003b4;',
	  '&epsi;' => '&#x003f5;',
	  '&epsiv;' => '&#x003b5;',
	  '&eta;' => '&#x003b7;',
	  '&gamma;' => '&#x00393;',
	  '&gamma;' => '&#x003b3;',
	  '&gammad;' => '&#x003dc;',
	  '&gammad;' => '&#x003dd;',
	  '&iota;' => '&#x003b9;',
	  '&kappa;' => '&#x003ba;',
	  '&kappav;' => '&#x003f0;',
	  '&lambda;' => '&#x0039b;',
	  '&lambda;' => '&#x003bb;',
	  '&mu;' => '&#x003bc;',
	  '&nu;' => '&#x003bd;',
	  '&omega;' => '&#x003a9;',
	  '&omega;' => '&#x003c9;',
	  '&phi;' => '&#x003a6;',
	  '&phi;' => '&#x003d5;',
	  '&phiv;' => '&#x003c6;',
	  '&pi;' => '&#x003a0;',
	  '&pi;' => '&#x003c0;',
	  '&piv;' => '&#x003d6;',
	  '&psi;' => '&#x003a8;',
	  '&psi;' => '&#x003c8;',
	  '&rho;' => '&#x003c1;',
	  '&rhov;' => '&#x003f1;',
	  '&sigma;' => '&#x003a3;',
	  '&sigma;' => '&#x003c3;',
	  '&sigmav;' => '&#x003c2;',
	  '&tau;' => '&#x003c4;',
	  '&theta;' => '&#x00398;',
	  '&theta;' => '&#x003b8;',
	  '&thetav;' => '&#x003d1;',
	  '&upsi;' => '&#x003d2;',
	  '&upsi;' => '&#x003c5;',
	  '&xi;' => '&#x0039e;',
	  '&xi;' => '&#x003be;',
	  '&zeta;' => '&#x003b6;',
	  '&afr;' => '&#x1d504;',
	  '&afr;' => '&#x1d51e;',
	  '&bfr;' => '&#x1d505;',
	  '&bfr;' => '&#x1d51f;',
	  '&cfr;' => '&#x0212d;',
	  '&cfr;' => '&#x1d520;',
	  '&dfr;' => '&#x1d507;',
	  '&dfr;' => '&#x1d521;',
	  '&efr;' => '&#x1d508;',
	  '&efr;' => '&#x1d522;',
	  '&ffr;' => '&#x1d509;',
	  '&ffr;' => '&#x1d523;',
	  '&gfr;' => '&#x1d50a;',
	  '&gfr;' => '&#x1d524;',
	  '&hfr;' => '&#x0210c;',
	  '&hfr;' => '&#x1d525;',
	  '&ifr;' => '&#x02111;',
	  '&ifr;' => '&#x1d526;',
	  '&jfr;' => '&#x1d50d;',
	  '&jfr;' => '&#x1d527;',
	  '&kfr;' => '&#x1d50e;',
	  '&kfr;' => '&#x1d528;',
	  '&lfr;' => '&#x1d50f;',
	  '&lfr;' => '&#x1d529;',
	  '&mfr;' => '&#x1d510;',
	  '&mfr;' => '&#x1d52a;',
	  '&nfr;' => '&#x1d511;',
	  '&nfr;' => '&#x1d52b;',
	  '&ofr;' => '&#x1d512;',
	  '&ofr;' => '&#x1d52c;',
	  '&pfr;' => '&#x1d513;',
	  '&pfr;' => '&#x1d52d;',
	  '&qfr;' => '&#x1d514;',
	  '&qfr;' => '&#x1d52e;',
	  '&rfr;' => '&#x0211c;',
	  '&rfr;' => '&#x1d52f;',
	  '&sfr;' => '&#x1d516;',
	  '&sfr;' => '&#x1d530;',
	  '&tfr;' => '&#x1d517;',
	  '&tfr;' => '&#x1d531;',
	  '&ufr;' => '&#x1d518;',
	  '&ufr;' => '&#x1d532;',
	  '&vfr;' => '&#x1d519;',
	  '&vfr;' => '&#x1d533;',
	  '&wfr;' => '&#x1d51a;',
	  '&wfr;' => '&#x1d534;',
	  '&xfr;' => '&#x1d51b;',
	  '&xfr;' => '&#x1d535;',
	  '&yfr;' => '&#x1d51c;',
	  '&yfr;' => '&#x1d536;',
	  '&zfr;' => '&#x02128;',
	  '&zfr;' => '&#x1d537;',
	  '&aopf;' => '&#x1d538;',
	  '&bopf;' => '&#x1d539;',
	  '&copf;' => '&#x02102;',
	  '&dopf;' => '&#x1d53b;',
	  '&eopf;' => '&#x1d53c;',
	  '&fopf;' => '&#x1d53d;',
	  '&gopf;' => '&#x1d53e;',
	  '&hopf;' => '&#x0210d;',
	  '&iopf;' => '&#x1d540;',
	  '&jopf;' => '&#x1d541;',
	  '&kopf;' => '&#x1d542;',
	  '&lopf;' => '&#x1d543;',
	  '&mopf;' => '&#x1d544;',
	  '&nopf;' => '&#x02115;',
	  '&oopf;' => '&#x1d546;',
	  '&popf;' => '&#x02119;',
	  '&qopf;' => '&#x0211a;',
	  '&ropf;' => '&#x0211d;',
	  '&sopf;' => '&#x1d54a;',
	  '&topf;' => '&#x1d54b;',
	  '&uopf;' => '&#x1d54c;',
	  '&vopf;' => '&#x1d54d;',
	  '&wopf;' => '&#x1d54e;',
	  '&xopf;' => '&#x1d54f;',
	  '&yopf;' => '&#x1d550;',
	  '&zopf;' => '&#x02124;',
	  '&ascr;' => '&#x1d49c;',
	  '&ascr;' => '&#x1d4b6;',
	  '&bscr;' => '&#x0212c;',
	  '&bscr;' => '&#x1d4b7;',
	  '&cscr;' => '&#x1d49e;',
	  '&cscr;' => '&#x1d4b8;',
	  '&dscr;' => '&#x1d49f;',
	  '&dscr;' => '&#x1d4b9;',
	  '&escr;' => '&#x02130;',
	  '&escr;' => '&#x0212f;',
	  '&fscr;' => '&#x02131;',
	  '&fscr;' => '&#x1d4bb;',
	  '&gscr;' => '&#x1d4a2;',
	  '&gscr;' => '&#x0210a;',
	  '&hscr;' => '&#x0210b;',
	  '&hscr;' => '&#x1d4bd;',
	  '&iscr;' => '&#x02110;',
	  '&iscr;' => '&#x1d4be;',
	  '&jscr;' => '&#x1d4a5;',
	  '&jscr;' => '&#x1d4bf;',
	  '&kscr;' => '&#x1d4a6;',
	  '&kscr;' => '&#x1d4c0;',
	  '&lscr;' => '&#x02112;',
	  '&lscr;' => '&#x1d4c1;',
	  '&mscr;' => '&#x02133;',
	  '&mscr;' => '&#x1d4c2;',
	  '&nscr;' => '&#x1d4a9;',
	  '&nscr;' => '&#x1d4c3;',
	  '&oscr;' => '&#x1d4aa;',
	  '&oscr;' => '&#x02134;',
	  '&pscr;' => '&#x1d4ab;',
	  '&pscr;' => '&#x1d4c5;',
	  '&qscr;' => '&#x1d4ac;',
	  '&qscr;' => '&#x1d4c6;',
	  '&rscr;' => '&#x0211b;',
	  '&rscr;' => '&#x1d4c7;',
	
	  '&sscr;' => '&#x1d4ae;',
	  '&sscr;' => '&#x1d4c8;',
	  '&tscr;' => '&#x1d4af;',
	  '&tscr;' => '&#x1d4c9;',
	  '&uscr;' => '&#x1d4b0;',
	  '&uscr;' => '&#x1d4ca;',
	  '&vscr;' => '&#x1d4b1;',
	  '&vscr;' => '&#x1d4cb;',
	  '&wscr;' => '&#x1d4b2;',
	  '&wscr;' => '&#x1d4cc;',
	  '&xscr;' => '&#x1d4b3;',
	  '&xscr;' => '&#x1d4cd;',
	  '&yscr;' => '&#x1d4b4;',
	  '&yscr;' => '&#x1d4ce;',
	  '&zscr;' => '&#x1d4b5;',
	  '&zscr;' => '&#x1d4cf;',
	  '&acd;' => '&#x0223f;',
	  '&aleph;' => '&#x02135;',
	  '&and;' => '&#x02a53;',
	  '&and;' => '&#x02227;',
	  '&andand;' => '&#x02a55;',
	  '&andd;' => '&#x02a5c;',
	  '&andslope;' => '&#x02a58;',
	  '&andv;' => '&#x02a5a;',
	  '&angrt;' => '&#x0221f;',
	  '&angsph;' => '&#x02222;',
	  '&angst;' => '&#x0212b;',
	  '&ap;' => '&#x02248;',
	  '&apacir;' => '&#x02a6f;',
	  '&awconint;' => '&#x02233;',
	  '&awint;' => '&#x02a11;',
	  '&becaus;' => '&#x02235;',
	  '&bernou;' => '&#x0212c;',
	  '&bne;' => '&#x0003d;&#x020e5;',
	  '&bnequiv;' => '&#x02261;&#x020e5;',
	  '&bnot;' => '&#x02aed;',
	  '&bnot;' => '&#x02310;',
	  '&bottom;' => '&#x022a5;',
	  '&cap;' => '&#x02229;',
	  '&cconint;' => '&#x02230;',
	  '&cirfnint;' => '&#x02a10;',
	  '&compfn;' => '&#x02218;',
	  '&cong;' => '&#x02245;',
	  '&conint;' => '&#x0222f;',
	  '&conint;' => '&#x0222e;',
	  '&ctdot;' => '&#x022ef;',
	  '&cup;' => '&#x0222a;',
	  '&cwconint;' => '&#x02232;',
	  '&cwint;' => '&#x02231;',
	  '&cylcty;' => '&#x0232d;',
	  '&disin;' => '&#x022f2;',
	  '&dot;' => '&#x000a8;',
	  '&dotdot;' => '&#x020dc;',
	  '&dsol;' => '&#x029f6;',
	  '&dtdot;' => '&#x022f1;',
	  '&dwangle;' => '&#x029a6;',
	  '&elinters;' => '&#x0fffd;',
	  '&epar;' => '&#x022d5;',
	  '&eparsl;' => '&#x029e3;',
	  '&equiv;' => '&#x02261;',
	  '&eqvparsl;' => '&#x029e5;',
	  '&exist;' => '&#x02203;',
	  '&fltns;' => '&#x025b1;',
	  '&fnof;' => '&#x00192;',
	  '&forall;' => '&#x02200;',
	  '&fpartint;' => '&#x02a0d;',
	  '&ge;' => '&#x02265;',
	  '&hamilt;' => '&#x0210b;',
	  '&iff;' => '&#x021d4;',
	  '&iinfin;' => '&#x029dc;',
	  '&imped;' => '&#x001b5;',
	  '&infin;' => '&#x0221e;',
	  '&infintie;' => '&#x029dd;',
	  '&int;' => '&#x0222c;',
	  '&int;' => '&#x0222b;',
	  '&intlarhk;' => '&#x02a17;',
	  '&isin;' => '&#x02208;',
	  '&isindot;' => '&#x022f5;',
	  '&isine;' => '&#x022f9;',
	  '&isins;' => '&#x022f4;',
	  '&isinsv;' => '&#x022f3;',
	  '&isinv;' => '&#x02208;',
	  '&lagran;' => '&#x02112;',
	  '&lang;' => '&#x0300a;',
	  '&lang;' => '&#x02329;',
	  '&larr;' => '&#x021d0;',
	  '&lbbrk;' => '&#x03014;',
	  '&le;' => '&#x02264;',
	  '&loang;' => '&#x03018;',
	  '&lobrk;' => '&#x0301a;',
	  '&lopar;' => '&#x02985;',
	  '&lowast;' => '&#x02217;',
	  '&minus;' => '&#x02212;',
	  '&mnplus;' => '&#x02213;',
	  '&nabla;' => '&#x02207;',
	  '&ne;' => '&#x02260;',
	  '&nedot;' => '&#x02250;&#x00338;',
	  '&nhpar;' => '&#x02af2;',
	  '&ni;' => '&#x0220b;',
	  '&nis;' => '&#x022fc;',
	  '&nisd;' => '&#x022fa;',
	  '&niv;' => '&#x0220b;',
	  '&not;' => '&#x02aec;',
	  '&notin;' => '&#x02209;',
	  '&notindot;' => '&#x022f5;&#x00338;',
	  '&notine;' => '&#x022f9;&#x00338;',
	  '&notinva;' => '&#x02209;',
	  '&notinvb;' => '&#x022f7;',
	  '&notinvc;' => '&#x022f6;',
	  '&notni;' => '&#x0220c;',
	  '&notniva;' => '&#x0220c;',
	  '&notnivb;' => '&#x022fe;',
	  '&notnivc;' => '&#x022fd;',
	  '&nparsl;' => '&#x02afd;&#x020e5;',
	  '&npart;' => '&#x02202;&#x00338;',
	  '&npolint;' => '&#x02a14;',
	  '&nvinfin;' => '&#x029de;',
	  '&olcross;' => '&#x029bb;',
	  '&or;' => '&#x02a54;',
	  '&or;' => '&#x02228;',
	  '&ord;' => '&#x02a5d;',
	  '&order;' => '&#x02134;',
	  '&oror;' => '&#x02a56;',
	  '&orslope;' => '&#x02a57;',
	  '&orv;' => '&#x02a5b;',
	  '&par;' => '&#x02225;',
	  '&parsl;' => '&#x02afd;',
	  '&part;' => '&#x02202;',
	  '&permil;' => '&#x02030;',
	  '&perp;' => '&#x022a5;',
	  '&pertenk;' => '&#x02031;',
	  '&phmmat;' => '&#x02133;',
	  '&pointint;' => '&#x02a15;',
	  '&prime;' => '&#x02033;',
	  '&prime;' => '&#x02032;',
	  '&profalar;' => '&#x0232e;',
	  '&profline;' => '&#x02312;',
	  '&profsurf;' => '&#x02313;',
	  '&prop;' => '&#x0221d;',
	  '&qint;' => '&#x02a0c;',
	  '&qprime;' => '&#x02057;',
	  '&quatint;' => '&#x02a16;',
	  '&radic;' => '&#x0221a;',
	  '&rang;' => '&#x0300b;',
	  '&rang;' => '&#x0232a;',
	  '&rarr;' => '&#x021d2;',
	  '&rbbrk;' => '&#x03015;',
	  '&roang;' => '&#x03019;',
	  '&robrk;' => '&#x0301b;',
	  '&ropar;' => '&#x02986;',
	  '&rppolint;' => '&#x02a12;',
	  '&scpolint;' => '&#x02a13;',
	  '&sim;' => '&#x0223c;',
	  '&simdot;' => '&#x02a6a;',
	  '&sime;' => '&#x02243;',
	  '&smeparsl;' => '&#x029e4;',
	  '&square;' => '&#x025a1;',
	  '&squarf;' => '&#x025aa;',
	  '&strns;' => '&#x000af;',
	  '&sub;' => '&#x02282;',
	  '&sube;' => '&#x02286;',
	  '&sup;' => '&#x02283;',
	  '&supe;' => '&#x02287;',
	  '&tdot;' => '&#x020db;',
	  '&there4;' => '&#x02234;',
	  '&tint;' => '&#x0222d;',
	  '&top;' => '&#x022a4;',
	  '&topbot;' => '&#x02336;',
	  '&topcir;' => '&#x02af1;',
	  '&tprime;' => '&#x02034;',
	  '&utdot;' => '&#x022f0;',
	  '&uwangle;' => '&#x029a7;',
	  '&vangrt;' => '&#x0299c;',
	  '&veeeq;' => '&#x0225a;',
	  '&verbar;' => '&#x02016;',
	  '&wedgeq;' => '&#x02259;',
	  '&xnis;' => '&#x022fb;',
	  '&boxdl;' => '&#x02557;',
	  '&boxdl;' => '&#x02556;',
	  '&boxdl;' => '&#x02555;',
	  '&boxdl;' => '&#x02510;',
	  '&boxdr;' => '&#x02554;',
	  '&boxdr;' => '&#x02553;',
	  '&boxdr;' => '&#x02552;',
	  '&boxdr;' => '&#x0250c;',
	  '&boxh;' => '&#x02550;',
	  '&boxh;' => '&#x02500;',
	  '&boxhd;' => '&#x02566;',
	  '&boxhd;' => '&#x02564;',
	  '&boxhd;' => '&#x02565;',
	  '&boxhd;' => '&#x0252c;',
	  '&boxhu;' => '&#x02569;',
	  '&boxhu;' => '&#x02567;',
	  '&boxhu;' => '&#x02568;',
	  '&boxhu;' => '&#x02534;',
	  '&boxul;' => '&#x0255d;',
	  '&boxul;' => '&#x0255c;',
	  '&boxul;' => '&#x0255b;',
	  '&boxul;' => '&#x02518;',
	  '&boxur;' => '&#x0255a;',
	  '&boxur;' => '&#x02559;',
	  '&boxur;' => '&#x02558;',
	  '&boxur;' => '&#x02514;',
	  '&boxv;' => '&#x02551;',
	  '&boxv;' => '&#x02502;',
	  '&boxvh;' => '&#x0256c;',
	  '&boxvh;' => '&#x0256b;',
	  '&boxvh;' => '&#x0256a;',
	  '&boxvh;' => '&#x0253c;',
	  '&boxvl;' => '&#x02563;',
	  '&boxvl;' => '&#x02562;',
	  '&boxvl;' => '&#x02561;',
	  '&boxvl;' => '&#x02524;',
	  '&boxvr;' => '&#x02560;',
	  '&boxvr;' => '&#x0255f;',
	  '&boxvr;' => '&#x0255e;',
	  '&boxvr;' => '&#x0251c;',
	  '&acy;' => '&#x00410;',
	  '&acy;' => '&#x00430;',
	  '&bcy;' => '&#x00411;',
	  '&bcy;' => '&#x00431;',
	  '&chcy;' => '&#x00427;',
	  '&chcy;' => '&#x00447;',
	  '&dcy;' => '&#x00414;',
	  '&dcy;' => '&#x00434;',
	  '&ecy;' => '&#x0042d;',
	  '&ecy;' => '&#x0044d;',
	  '&fcy;' => '&#x00424;',
	  '&fcy;' => '&#x00444;',
	  '&gcy;' => '&#x00413;',
	  '&gcy;' => '&#x00433;',
	  '&hardcy;' => '&#x0042a;',
	  '&hardcy;' => '&#x0044a;',
	  '&icy;' => '&#x00418;',
	  '&icy;' => '&#x00438;',
	  '&iecy;' => '&#x00415;',
	  '&iecy;' => '&#x00435;',
	  '&iocy;' => '&#x00401;',
	  '&iocy;' => '&#x00451;',
	  '&jcy;' => '&#x00419;',
	  '&jcy;' => '&#x00439;',
	  '&kcy;' => '&#x0041a;',
	  '&kcy;' => '&#x0043a;',
	  '&khcy;' => '&#x00425;',
	  '&khcy;' => '&#x00445;',
	  '&lcy;' => '&#x0041b;',
	  '&lcy;' => '&#x0043b;',
	  '&mcy;' => '&#x0041c;',
	  '&mcy;' => '&#x0043c;',
	  '&ncy;' => '&#x0041d;',
	  '&ncy;' => '&#x0043d;',
	  '&numero;' => '&#x02116;',
	  '&ocy;' => '&#x0041e;',
	  '&ocy;' => '&#x0043e;',
	  '&pcy;' => '&#x0041f;',
	  '&pcy;' => '&#x0043f;',
	  '&rcy;' => '&#x00420;',
	  '&rcy;' => '&#x00440;',
	  '&scy;' => '&#x00421;',
	  '&scy;' => '&#x00441;',
	  '&shchcy;' => '&#x00429;',
	  '&shchcy;' => '&#x00449;',
	  '&shcy;' => '&#x00428;',
	  '&shcy;' => '&#x00448;',
	  '&softcy;' => '&#x0042c;',
	  '&softcy;' => '&#x0044c;',
	  '&tcy;' => '&#x00422;',
	  '&tcy;' => '&#x00442;',
	  '&tscy;' => '&#x00426;',
	  '&tscy;' => '&#x00446;',
	  '&ucy;' => '&#x00423;',
	  '&ucy;' => '&#x00443;',
	  '&vcy;' => '&#x00412;',
	  '&vcy;' => '&#x00432;',
	  '&yacy;' => '&#x0042f;',
	  '&yacy;' => '&#x0044f;',
	  '&ycy;' => '&#x0042b;',
	  '&ycy;' => '&#x0044b;',
	  '&yucy;' => '&#x0042e;',
	  '&yucy;' => '&#x0044e;',
	  '&zcy;' => '&#x00417;',
	  '&zcy;' => '&#x00437;',
	  '&zhcy;' => '&#x00416;',
	  '&zhcy;' => '&#x00436;',
	  '&djcy;' => '&#x00402;',
	  '&djcy;' => '&#x00452;',
	  '&dscy;' => '&#x00405;',
	  '&dscy;' => '&#x00455;',
	  '&dzcy;' => '&#x0040f;',
	  '&dzcy;' => '&#x0045f;',
	  '&gjcy;' => '&#x00403;',
	  '&gjcy;' => '&#x00453;',
	  '&iukcy;' => '&#x00406;',
	  '&iukcy;' => '&#x00456;',
	  '&jsercy;' => '&#x00408;',
	  '&jsercy;' => '&#x00458;',
	  '&jukcy;' => '&#x00404;',
	  '&jukcy;' => '&#x00454;',
	  '&kjcy;' => '&#x0040c;',
	  '&kjcy;' => '&#x0045c;',
	  '&ljcy;' => '&#x00409;',
	  '&ljcy;' => '&#x00459;',
	  '&njcy;' => '&#x0040a;',
	  '&njcy;' => '&#x0045a;',
	  '&tshcy;' => '&#x0040b;',
	  '&tshcy;' => '&#x0045b;',
	  '&ubrcy;' => '&#x0040e;',
	  '&ubrcy;' => '&#x0045e;',
	  '&yicy;' => '&#x00407;',
	  '&yicy;' => '&#x00457;',
	  '&acute;' => '&#x000b4;',
	  '&breve;' => '&#x002d8;',
	  '&caron;' => '&#x002c7;',
	  '&cedil;' => '&#x000b8;',
	  '&circ;' => '&#x002c6;',
	  '&dblac;' => '&#x002dd;',
	  '&die;' => '&#x000a8;',
	  '&dot;' => '&#x002d9;',
	  '&grave;' => '&#x00060;',
	  '&macr;' => '&#x000af;',
	  '&ogon;' => '&#x002db;',
	  '&ring;' => '&#x002da;',
	  '&tilde;' => '&#x002dc;',
	  '&uml;' => '&#x000a8;',
	  '&aacute;' => '&#x000c1;',
	  '&aacute;' => '&#x000e1;',
	  '&acirc;' => '&#x000c2;',
	  '&acirc;' => '&#x000e2;',
	  '&aelig;' => '&#x000c6;',
	  '&aelig;' => '&#x000e6;',
	  '&agrave;' => '&#x000c0;',
	  '&agrave;' => '&#x000e0;',
	  '&aring;' => '&#x000c5;',
	  '&aring;' => '&#x000e5;',
	  '&atilde;' => '&#x000c3;',
	  '&atilde;' => '&#x000e3;',
	  '&auml;' => '&#x000c4;',
	  '&auml;' => '&#x000e4;',
	  '&ccedil;' => '&#x000c7;',
	  '&ccedil;' => '&#x000e7;',
	  '&eacute;' => '&#x000c9;',
	  '&eacute;' => '&#x000e9;',
	  '&ecirc;' => '&#x000ca;',
	  '&ecirc;' => '&#x000ea;',
	  '&egrave;' => '&#x000c8;',
	  '&egrave;' => '&#x000e8;',
	  '&eth;' => '&#x000d0;',
	  '&eth;' => '&#x000f0;',
	  '&euml;' => '&#x000cb;',
	  '&euml;' => '&#x000eb;',
	  '&iacute;' => '&#x000cd;',
	  '&iacute;' => '&#x000ed;',
	  '&icirc;' => '&#x000ce;',
	  '&icirc;' => '&#x000ee;',
	  '&igrave;' => '&#x000cc;',
	  '&igrave;' => '&#x000ec;',
	  '&iuml;' => '&#x000cf;',
	  '&iuml;' => '&#x000ef;',
	  '&ntilde;' => '&#x000d1;',
	  '&ntilde;' => '&#x000f1;',
	  '&oacute;' => '&#x000d3;',
	  '&oacute;' => '&#x000f3;',
	  '&ocirc;' => '&#x000d4;',
	  '&ocirc;' => '&#x000f4;',
	  '&ograve;' => '&#x000d2;',
	  '&ograve;' => '&#x000f2;',
	  '&oslash;' => '&#x000d8;',
	  '&oslash;' => '&#x000f8;',
	  '&otilde;' => '&#x000d5;',
	  '&otilde;' => '&#x000f5;',
	  '&ouml;' => '&#x000d6;',
	  '&ouml;' => '&#x000f6;',
	  '&szlig;' => '&#x000df;',
	  '&thorn;' => '&#x000de;',
	  '&thorn;' => '&#x000fe;',
	  '&uacute;' => '&#x000da;',
	  '&uacute;' => '&#x000fa;',
	  '&ucirc;' => '&#x000db;',
	  '&ucirc;' => '&#x000fb;',
	  '&ugrave;' => '&#x000d9;',
	  '&ugrave;' => '&#x000f9;',
	  '&uuml;' => '&#x000dc;',
	  '&uuml;' => '&#x000fc;',
	  '&yacute;' => '&#x000dd;',
	  '&yacute;' => '&#x000fd;',
	  '&yuml;' => '&#x000ff;',
	  '&abreve;' => '&#x00102;',
	  '&abreve;' => '&#x00103;',
	  '&amacr;' => '&#x00100;',
	  '&amacr;' => '&#x00101;',
	  '&aogon;' => '&#x00104;',
	  '&aogon;' => '&#x00105;',
	  '&cacute;' => '&#x00106;',
	  '&cacute;' => '&#x00107;',
	  '&ccaron;' => '&#x0010c;',
	  '&ccaron;' => '&#x0010d;',
	  '&ccirc;' => '&#x00108;',
	  '&ccirc;' => '&#x00109;',
	  '&cdot;' => '&#x0010a;',
	  '&cdot;' => '&#x0010b;',
	  '&dcaron;' => '&#x0010e;',
	  '&dcaron;' => '&#x0010f;',
	  '&dstrok;' => '&#x00110;',
	  '&dstrok;' => '&#x00111;',
	  '&ecaron;' => '&#x0011a;',
	  '&ecaron;' => '&#x0011b;',
	  '&edot;' => '&#x00116;',
	  '&edot;' => '&#x00117;',
	  '&emacr;' => '&#x00112;',
	  '&emacr;' => '&#x00113;',
	  '&eng;' => '&#x0014a;',
	  '&eng;' => '&#x0014b;',
	  '&eogon;' => '&#x00118;',
	  '&eogon;' => '&#x00119;',
	  '&gacute;' => '&#x001f5;',
	  '&gbreve;' => '&#x0011e;',
	  '&gbreve;' => '&#x0011f;',
	  '&gcedil;' => '&#x00122;',
	  '&gcirc;' => '&#x0011c;',
	  '&gcirc;' => '&#x0011d;',
	  '&gdot;' => '&#x00120;',
	  '&gdot;' => '&#x00121;',
	  '&hcirc;' => '&#x00124;',
	  '&hcirc;' => '&#x00125;',
	  '&hstrok;' => '&#x00126;',
	  '&hstrok;' => '&#x00127;',
	  '&idot;' => '&#x00130;',
	  '&ijlig;' => '&#x00132;',
	  '&ijlig;' => '&#x00133;',
	  '&imacr;' => '&#x0012a;',
	  '&imacr;' => '&#x0012b;',
	  '&inodot;' => '&#x00131;',
	  '&iogon;' => '&#x0012e;',
	  '&iogon;' => '&#x0012f;',
	  '&itilde;' => '&#x00128;',
	  '&itilde;' => '&#x00129;',
	  '&jcirc;' => '&#x00134;',
	  '&jcirc;' => '&#x00135;',
	  '&kcedil;' => '&#x00136;',
	  '&kcedil;' => '&#x00137;',
	  '&kgreen;' => '&#x00138;',
	  '&lacute;' => '&#x00139;',
	  '&lacute;' => '&#x0013a;',
	  '&lcaron;' => '&#x0013d;',
	  '&lcaron;' => '&#x0013e;',
	  '&lcedil;' => '&#x0013b;',
	  '&lcedil;' => '&#x0013c;',
	  '&lmidot;' => '&#x0013f;',
	  '&lmidot;' => '&#x00140;',
	  '&lstrok;' => '&#x00141;',
	  '&lstrok;' => '&#x00142;',
	  '&nacute;' => '&#x00143;',
	  '&nacute;' => '&#x00144;',
	  '&napos;' => '&#x00149;',
	  '&ncaron;' => '&#x00147;',
	  '&ncaron;' => '&#x00148;',
	  '&ncedil;' => '&#x00145;',
	  '&ncedil;' => '&#x00146;',
	  '&odblac;' => '&#x00150;',
	  '&odblac;' => '&#x00151;',
	  '&oelig;' => '&#x00152;',
	  '&oelig;' => '&#x00153;',
	  '&omacr;' => '&#x0014c;',
	  '&omacr;' => '&#x0014d;',
	  '&racute;' => '&#x00154;',
	  '&racute;' => '&#x00155;',
	  '&rcaron;' => '&#x00158;',
	  '&rcaron;' => '&#x00159;',
	  '&rcedil;' => '&#x00156;',
	  '&rcedil;' => '&#x00157;',
	  '&sacute;' => '&#x0015a;',
	  '&sacute;' => '&#x0015b;',
	  '&scaron;' => '&#x00160;',
	  '&scaron;' => '&#x00161;',
	  '&scedil;' => '&#x0015e;',
	  '&scedil;' => '&#x0015f;',
	  '&scirc;' => '&#x0015c;',
	  '&scirc;' => '&#x0015d;',
	  '&tcaron;' => '&#x00164;',
	  '&tcaron;' => '&#x00165;',
	  '&tcedil;' => '&#x00162;',
	  '&tcedil;' => '&#x00163;',
	  '&tstrok;' => '&#x00166;',
	  '&tstrok;' => '&#x00167;',
	  '&ubreve;' => '&#x0016c;',
	  '&ubreve;' => '&#x0016d;',
	  '&udblac;' => '&#x00170;',
	  '&udblac;' => '&#x00171;',
	  '&umacr;' => '&#x0016a;',
	  '&umacr;' => '&#x0016b;',
	  '&uogon;' => '&#x00172;',
	  '&uogon;' => '&#x00173;',
	  '&uring;' => '&#x0016e;',
	  '&uring;' => '&#x0016f;',
	  '&utilde;' => '&#x00168;',
	  '&utilde;' => '&#x00169;',
	  '&wcirc;' => '&#x00174;',
	  '&wcirc;' => '&#x00175;',
	  '&ycirc;' => '&#x00176;',
	  '&ycirc;' => '&#x00177;',
	  '&yuml;' => '&#x00178;',
	  '&zacute;' => '&#x00179;',
	  '&zacute;' => '&#x0017a;',
	  '&zcaron;' => '&#x0017d;',
	  '&zcaron;' => '&#x0017e;',
	  '&zdot;' => '&#x0017b;',
	  '&zdot;' => '&#x0017c;',
	  '&ast;' => '&#x0002a;',
	  '&brvbar;' => '&#x000a6;',
	  '&bsol;' => '&#x0005c;',
	  '&cent;' => '&#x000a2;',
	  '&colon;' => '&#x0003a;',
	  '&comma;' => '&#x0002c;',
	  '&commat;' => '&#x00040;',
	  '&copy;' => '&#x000a9;',
	  '&curren;' => '&#x000a4;',
	  '&darr;' => '&#x02193;',
	  '&deg;' => '&#x000b0;',
	  '&divide;' => '&#x000f7;',
	  '&dollar;' => '&#x00024;',
	  '&equals;' => '&#x0003d;',
	  '&excl;' => '&#x00021;',
	  '&frac12;' => '&#x000bd;',
	  '&frac14;' => '&#x000bc;',
	  '&frac18;' => '&#x0215b;',
	  '&frac34;' => '&#x000be;',
	  '&frac38;' => '&#x0215c;',
	  '&frac58;' => '&#x0215d;',
	  '&frac78;' => '&#x0215e;',
	  '&half;' => '&#x000bd;',
	  '&horbar;' => '&#x02015;',
	  '&hyphen;' => '&#x02010;',
	  '&iexcl;' => '&#x000a1;',
	  '&iquest;' => '&#x000bf;',
	  '&laquo;' => '&#x000ab;',
	  '&larr;' => '&#x02190;',
	  '&lcub;' => '&#x0007b;',
	  '&ldquo;' => '&#x0201c;',
	  '&lowbar;' => '&#x0005f;',
	  '&lpar;' => '&#x00028;',
	  '&lsqb;' => '&#x0005b;',
	  '&lsquo;' => '&#x02018;',
	  '&micro;' => '&#x000b5;',
	  '&middot;' => '&#x000b7;',
	  '&nbsp;' => '&#x000a0;',
	  '&not;' => '&#x000ac;',
	  '&num;' => '&#x00023;',
	  '&ohm;' => '&#x02126;',
	  '&ordf;' => '&#x000aa;',
	  '&ordm;' => '&#x000ba;',
	  '&para;' => '&#x000b6;',
	  '&percnt;' => '&#x00025;',
	  '&period;' => '&#x0002e;',
	  '&plus;' => '&#x0002b;',
	  '&plusmn;' => '&#x000b1;',
	  '&pound;' => '&#x000a3;',
	  '&quest;' => '&#x0003f;',
	  '&raquo;' => '&#x000bb;',
	  '&rarr;' => '&#x02192;',
	  '&rcub;' => '&#x0007d;',
	  '&rdquo;' => '&#x0201d;',
	  '&reg;' => '&#x000ae;',
	  '&rpar;' => '&#x00029;',
	  '&rsqb;' => '&#x0005d;',
	  '&rsquo;' => '&#x02019;',
	  '&sect;' => '&#x000a7;',
	  '&semi;' => '&#x0003b;',
	  '&shy;' => '&#x000ad;',
	  '&sol;' => '&#x0002f;',
	  '&sung;' => '&#x0266a;',
	  '&sup1;' => '&#x000b9;',
	  '&sup2;' => '&#x000b2;',
	  '&sup3;' => '&#x000b3;',
	  '&times;' => '&#x000d7;',
	  '&trade;' => '&#x02122;',
	  '&uarr;' => '&#x02191;',
	  '&verbar;' => '&#x0007c;',
	  '&yen;' => '&#x000a5;',
	  '&blank;' => '&#x02423;',
	  '&blk12;' => '&#x02592;',
	  '&blk14;' => '&#x02591;',
	  '&blk34;' => '&#x02593;',
	  '&block;' => '&#x02588;',
	  '&bull;' => '&#x02022;',
	  '&caret;' => '&#x02041;',
	  '&check;' => '&#x02713;',
	  '&cir;' => '&#x025cb;',
	  '&clubs;' => '&#x02663;',
	  '&copysr;' => '&#x02117;',
	  '&cross;' => '&#x02717;',
	  '&dagger;' => '&#x02021;',
	  '&dagger;' => '&#x02020;',
	  '&dash;' => '&#x02010;',
	  '&diams;' => '&#x02666;',
	  '&dlcrop;' => '&#x0230d;',
	  '&drcrop;' => '&#x0230c;',
	  '&dtri;' => '&#x025bf;',
	  '&dtrif;' => '&#x025be;',
	  '&emsp;' => '&#x02003;',
	  '&emsp13;' => '&#x02004;',
	  '&emsp14;' => '&#x02005;',
	  '&ensp;' => '&#x02002;',
	  '&female;' => '&#x02640;',
	  '&ffilig;' => '&#x0fb03;',
	  '&fflig;' => '&#x0fb00;',
	  '&ffllig;' => '&#x0fb04;',
	  '&filig;' => '&#x0fb01;',
	  '&flat;' => '&#x0266d;',
	  '&fllig;' => '&#x0fb02;',
	  '&frac13;' => '&#x02153;',
	  '&frac15;' => '&#x02155;',
	  '&frac16;' => '&#x02159;',
	  '&frac23;' => '&#x02154;',
	  '&frac25;' => '&#x02156;',
	  '&frac35;' => '&#x02157;',
	  '&frac45;' => '&#x02158;',
	  '&frac56;' => '&#x0215a;',
	  '&hairsp;' => '&#x0200a;',
	  '&hearts;' => '&#x02665;',
	  '&hellip;' => '&#x02026;',
	  '&hybull;' => '&#x02043;',
	  '&incare;' => '&#x02105;',
	  '&ldquor;' => '&#x0201e;',
	  '&lhblk;' => '&#x02584;',
	  '&loz;' => '&#x025ca;',
	  '&lozf;' => '&#x029eb;',
	  '&lsquor;' => '&#x0201a;',
	  '&ltri;' => '&#x025c3;',
	  '&ltrif;' => '&#x025c2;',
	  '&male;' => '&#x02642;',
	  '&malt;' => '&#x02720;',
	  '&marker;' => '&#x025ae;',
	  '&mdash;' => '&#x02014;',
	  '&mldr;' => '&#x02026;',
	  '&natur;' => '&#x0266e;',
	  '&ndash;' => '&#x02013;',
	  '&nldr;' => '&#x02025;',
	  '&numsp;' => '&#x02007;',
	  '&phone;' => '&#x0260e;',
	  '&puncsp;' => '&#x02008;',
	  '&rdquor;' => '&#x0201d;',
	  '&rect;' => '&#x025ad;',
	  '&rsquor;' => '&#x02019;',
	  '&rtri;' => '&#x025b9;',
	  '&rtrif;' => '&#x025b8;',
	  '&rx;' => '&#x0211e;',
	  '&sext;' => '&#x02736;',
	  '&sharp;' => '&#x0266f;',
	  '&spades;' => '&#x02660;',
	  '&squ;' => '&#x025a1;',
	  '&squf;' => '&#x025aa;',
	  '&star;' => '&#x02606;',
	  '&starf;' => '&#x02605;',
	  '&target;' => '&#x02316;',
	  '&telrec;' => '&#x02315;',
	  '&thinsp;' => '&#x02009;',
	  '&uhblk;' => '&#x02580;',
	  '&ulcrop;' => '&#x0230f;',
	  '&urcrop;' => '&#x0230e;',
	  '&utri;' => '&#x025b5;',
	  '&utrif;' => '&#x025b4;',
	  '&vellip;' => '&#x022ee;',
	  '&af;' => '&#x02061;',
	  '&aopf;' => '&#x1d552;',
	  '&asympeq;' => '&#x0224d;',
	  '&bopf;' => '&#x1d553;',
	  '&copf;' => '&#x1d554;',
	  '&cross;' => '&#x02a2f;',
	  '&dd;' => '&#x02145;',
	  '&dd;' => '&#x02146;',
	  '&dopf;' => '&#x1d555;',
	  '&downarrowbar;' => '&#x02913;',
	  '&downbreve;' => '&#x00311;',
	  '&downleftrightvector;' => '&#x02950;',
	  '&downleftteevector;' => '&#x0295e;',
	  '&downleftvectorbar;' => '&#x02956;',
	  '&downrightteevector;' => '&#x0295f;',
	  '&downrightvectorbar;' => '&#x02957;',
	  '&ee;' => '&#x02147;',
	  '&emptysmallsquare;' => '&#x025fb;',
	  '&emptyverysmallsquare;' => '&#x025ab;',
	  '&eopf;' => '&#x1d556;',
	  '&equal;' => '&#x02a75;',
	  '&filledsmallsquare;' => '&#x025fc;',
	  '&filledverysmallsquare;' => '&#x025aa;',
	  '&fopf;' => '&#x1d557;',
	  '&gopf;' => '&#x1d558;',
	  '&greatergreater;' => '&#x02aa2;',
	  '&hat;' => '&#x0005e;',
	  '&hopf;' => '&#x1d559;',
	  '&horizontalline;' => '&#x02500;',
	  '&ic;' => '&#x02063;',
	  '&ii;' => '&#x02148;',
	  '&iopf;' => '&#x1d55a;',
	  '&it;' => '&#x02062;',
	  '&jopf;' => '&#x1d55b;',
	  '&kopf;' => '&#x1d55c;',
	  '&larrb;' => '&#x021e4;',
	  '&leftdownteevector;' => '&#x02961;',
	  '&leftdownvectorbar;' => '&#x02959;',
	  '&leftrightvector;' => '&#x0294e;',
	  '&leftteevector;' => '&#x0295a;',
	  '&lefttrianglebar;' => '&#x029cf;',
	  '&leftupdownvector;' => '&#x02951;',
	  '&leftupteevector;' => '&#x02960;',
	  '&leftupvectorbar;' => '&#x02958;',
	  '&leftvectorbar;' => '&#x02952;',
	  '&lessless;' => '&#x02aa1;',
	  '&lopf;' => '&#x1d55d;',
	  '&mapstodown;' => '&#x021a7;',
	  '&mapstoleft;' => '&#x021a4;',
	  '&mapstoup;' => '&#x021a5;',
	  '&mediumspace;' => '&#x0205f;',
	  '&mopf;' => '&#x1d55e;',
	  '&nbump;' => '&#x0224e;&#x00338;',
	  '&nbumpe;' => '&#x0224f;&#x00338;',
	  '&nesim;' => '&#x02242;&#x00338;',
	  '&newline;' => '&#x0000a;',
	  '&nobreak;' => '&#x02060;',
	  '&nopf;' => '&#x1d55f;',
	  '&notcupcap;' => '&#x0226d;',
	  '&nothumpequal;' => '&#x0224f;&#x00338;',
	  '&notlefttrianglebar;' => '&#x029cf;&#x00338;',
	  '&notnestedgreatergreater;' => '&#x02aa2;&#x00338;',
	  '&notnestedlessless;' => '&#x02aa1;&#x00338;',
	  '&notrighttrianglebar;' => '&#x029d0;&#x00338;',
	  '&notsquaresubset;' => '&#x0228f;&#x00338;',
	  '&notsquaresuperset;' => '&#x02290;&#x00338;',
	  '&notsucceedstilde;' => '&#x0227f;&#x00338;',
	  '&oopf;' => '&#x1d560;',
	  '&overbar;' => '&#x000af;',
	  '&overbrace;' => '&#x0fe37;',
	  '&overbracket;' => '&#x023b4;',
	  '&overparenthesis;' => '&#x0fe35;',
	  '&planckh;' => '&#x0210e;',
	  '&popf;' => '&#x1d561;',
	  '&product;' => '&#x0220f;',
	  '&qopf;' => '&#x1d562;',
	  '&rarrb;' => '&#x021e5;',
	  '&rightdownteevector;' => '&#x0295d;',
	  '&rightdownvectorbar;' => '&#x02955;',
	  '&rightteevector;' => '&#x0295b;',
	  '&righttrianglebar;' => '&#x029d0;',
	  '&rightupdownvector;' => '&#x0294f;',
	  '&rightupteevector;' => '&#x0295c;',
	  '&rightupvectorbar;' => '&#x02954;',
	  '&rightvectorbar;' => '&#x02953;',
	  '&ropf;' => '&#x1d563;',
	  '&roundimplies;' => '&#x02970;',
	  '&ruledelayed;' => '&#x029f4;',
	  '&sopf;' => '&#x1d564;',
	  '&tab;' => '&#x00009;',
	  '&thickspace;' => '&#x02009;&#x0200a;&#x0200a;',
	  '&topf;' => '&#x1d565;',
	  '&underbar;' => '&#x00332;',
	  '&underbrace;' => '&#x0fe38;',
	  '&underbracket;' => '&#x023b5;',
	  '&underparenthesis;' => '&#x0fe36;',
	  '&uopf;' => '&#x1d566;',
	  '&uparrowbar;' => '&#x02912;',
	  '&upsilon;' => '&#x003a5;',
	  '&verticalline;' => '&#x0007c;',
	  '&verticalseparator;' => '&#x02758;',
	  '&vopf;' => '&#x1d567;',
	  '&wopf;' => '&#x1d568;',
	  '&xopf;' => '&#x1d569;',
	  '&yopf;' => '&#x1d56a;',
	  '&zerowidthspace;' => '&#x0200b;',
	  '&zopf;' => '&#x1d56b;',
	  '&angle;' => '&#x02220;',
	  '&applyfunction;' => '&#x02061;',
	  '&approx;' => '&#x02248;',
	  '&approxeq;' => '&#x0224a;',
	  '&assign;' => '&#x02254;',
	  '&backcong;' => '&#x0224c;',
	  '&backepsilon;' => '&#x003f6;',
	  '&backprime;' => '&#x02035;',
	  '&backsim;' => '&#x0223d;',
	  '&backsimeq;' => '&#x022cd;',
	  '&backslash;' => '&#x02216;',
	  '&barwedge;' => '&#x02305;',
	  '&because;' => '&#x02235;',
	  '&because;' => '&#x02235;',
	  '&bernoullis;' => '&#x0212c;',
	  '&between;' => '&#x0226c;',
	  '&bigcap;' => '&#x022c2;',
	  '&bigcirc;' => '&#x025ef;',
	  '&bigcup;' => '&#x022c3;',
	  '&bigodot;' => '&#x02a00;',
	  '&bigoplus;' => '&#x02a01;',
	  '&bigotimes;' => '&#x02a02;',
	  '&bigsqcup;' => '&#x02a06;',
	  '&bigstar;' => '&#x02605;',
	  '&bigtriangledown;' => '&#x025bd;',
	  '&bigtriangleup;' => '&#x025b3;',
	  '&biguplus;' => '&#x02a04;',
	  '&bigvee;' => '&#x022c1;',
	  '&bigwedge;' => '&#x022c0;',
	  '&bkarow;' => '&#x0290d;',
	  '&blacklozenge;' => '&#x029eb;',
	  '&blacksquare;' => '&#x025aa;',
	  '&blacktriangle;' => '&#x025b4;',
	  '&blacktriangledown;' => '&#x025be;',
	  '&blacktriangleleft;' => '&#x025c2;',
	  '&blacktriangleright;' => '&#x025b8;',
	  '&bot;' => '&#x022a5;',
	  '&boxminus;' => '&#x0229f;',
	  '&boxplus;' => '&#x0229e;',
	  '&boxtimes;' => '&#x022a0;',
	  '&breve;' => '&#x002d8;',
	  '&bullet;' => '&#x02022;',
	  '&bumpeq;' => '&#x0224e;',
	  '&bumpeq;' => '&#x0224f;',
	  '&capitaldifferentiald;' => '&#x02145;',
	  '&cayleys;' => '&#x0212d;',
	  '&cedilla;' => '&#x000b8;',
	  '&centerdot;' => '&#x000b7;',
	  '&centerdot;' => '&#x000b7;',
	  '&checkmark;' => '&#x02713;',
	  '&circeq;' => '&#x02257;',
	  '&circlearrowleft;' => '&#x021ba;',
	  '&circlearrowright;' => '&#x021bb;',
	  '&circledast;' => '&#x0229b;',
	  '&circledcirc;' => '&#x0229a;',
	  '&circleddash;' => '&#x0229d;',
	  '&circledot;' => '&#x02299;',
	  '&circledr;' => '&#x000ae;',
	  '&circleds;' => '&#x024c8;',
	  '&circleminus;' => '&#x02296;',
	  '&circleplus;' => '&#x02295;',
	  '&circletimes;' => '&#x02297;',
	  '&clockwisecontourintegral;' => '&#x02232;',
	  '&closecurlydoublequote;' => '&#x0201d;',
	  '&closecurlyquote;' => '&#x02019;',
	  '&clubsuit;' => '&#x02663;',
	  '&coloneq;' => '&#x02254;',
	  '&complement;' => '&#x02201;',
	  '&complexes;' => '&#x02102;',
	  '&congruent;' => '&#x02261;',
	  '&contourintegral;' => '&#x0222e;',
	  '&coproduct;' => '&#x02210;',
	  '&counterclockwisecontourintegral;' => '&#x02233;',
	  '&cupcap;' => '&#x0224d;',
	  '&curlyeqprec;' => '&#x022de;',
	  '&curlyeqsucc;' => '&#x022df;',
	  '&curlyvee;' => '&#x022ce;',
	  '&curlywedge;' => '&#x022cf;',
	  '&curvearrowleft;' => '&#x021b6;',
	  '&curvearrowright;' => '&#x021b7;',
	  '&dbkarow;' => '&#x0290f;',
	  '&ddagger;' => '&#x02021;',
	  '&ddotseq;' => '&#x02a77;',
	  '&del;' => '&#x02207;',
	  '&diacriticalacute;' => '&#x000b4;',
	  '&diacriticaldot;' => '&#x002d9;',
	  '&diacriticaldoubleacute;' => '&#x002dd;',
	  '&diacriticalgrave;' => '&#x00060;',
	  '&diacriticaltilde;' => '&#x002dc;',
	  '&diamond;' => '&#x022c4;',
	  '&diamond;' => '&#x022c4;',
	  '&diamondsuit;' => '&#x02666;',
	  '&differentiald;' => '&#x02146;',
	  '&digamma;' => '&#x003dd;',
	  '&div;' => '&#x000f7;',
	  '&divideontimes;' => '&#x022c7;',
	  '&doteq;' => '&#x02250;',
	  '&doteqdot;' => '&#x02251;',
	  '&dotequal;' => '&#x02250;',
	  '&dotminus;' => '&#x02238;',
	  '&dotplus;' => '&#x02214;',
	  '&dotsquare;' => '&#x022a1;',
	  '&doublebarwedge;' => '&#x02306;',
	  '&doublecontourintegral;' => '&#x0222f;',
	  '&doubledot;' => '&#x000a8;',
	  '&doubledownarrow;' => '&#x021d3;',
	  '&doubleleftarrow;' => '&#x021d0;',
	  '&doubleleftrightarrow;' => '&#x021d4;',
	  '&doublelefttee;' => '&#x02ae4;',
	  '&doublelongleftarrow;' => '&#x027f8;',
	  '&doublelongleftrightarrow;' => '&#x027fa;',
	  '&doublelongrightarrow;' => '&#x027f9;',
	  '&doublerightarrow;' => '&#x021d2;',
	  '&doublerighttee;' => '&#x022a8;',
	  '&doubleuparrow;' => '&#x021d1;',
	  '&doubleupdownarrow;' => '&#x021d5;',
	  '&doubleverticalbar;' => '&#x02225;',
	  '&downarrow;' => '&#x02193;',
	  '&downarrow;' => '&#x021d3;',
	  '&downarrow;' => '&#x02193;',
	  '&downarrowuparrow;' => '&#x021f5;',
	  '&downdownarrows;' => '&#x021ca;',
	  '&downharpoonleft;' => '&#x021c3;',
	  '&downharpoonright;' => '&#x021c2;',
	  '&downleftvector;' => '&#x021bd;',
	  '&downrightvector;' => '&#x021c1;',
	  '&downtee;' => '&#x022a4;',
	  '&downteearrow;' => '&#x021a7;',
	  '&drbkarow;' => '&#x02910;',
	  '&element;' => '&#x02208;',
	  '&emptyset;' => '&#x02205;',
	  '&eqcirc;' => '&#x02256;',
	  '&eqcolon;' => '&#x02255;',
	  '&eqsim;' => '&#x02242;',
	  '&eqslantgtr;' => '&#x02a96;',
	  '&eqslantless;' => '&#x02a95;',
	  '&equaltilde;' => '&#x02242;',
	  '&equilibrium;' => '&#x021cc;',
	  '&exists;' => '&#x02203;',
	  '&expectation;' => '&#x02130;',
	  '&exponentiale;' => '&#x02147;',
	  '&exponentiale;' => '&#x02147;',
	  '&fallingdotseq;' => '&#x02252;',
	  '&forall;' => '&#x02200;',
	  '&fouriertrf;' => '&#x02131;',
	  '&geq;' => '&#x02265;',
	  '&geqq;' => '&#x02267;',
	  '&geqslant;' => '&#x02a7e;',
	  '&gg;' => '&#x0226b;',
	  '&ggg;' => '&#x022d9;',
	  '&gnapprox;' => '&#x02a8a;',
	  '&gneq;' => '&#x02a88;',
	  '&gneqq;' => '&#x02269;',
	  '&greaterequal;' => '&#x02265;',
	  '&greaterequalless;' => '&#x022db;',
	  '&greaterfullequal;' => '&#x02267;',
	  '&greaterless;' => '&#x02277;',
	  '&greaterslantequal;' => '&#x02a7e;',
	  '&greatertilde;' => '&#x02273;',
	  '&gtrapprox;' => '&#x02a86;',
	  '&gtrdot;' => '&#x022d7;',
	  '&gtreqless;' => '&#x022db;',
	  '&gtreqqless;' => '&#x02a8c;',
	  '&gtrless;' => '&#x02277;',
	  '&gtrsim;' => '&#x02273;',
	  '&gvertneqq;' => '&#x02269;&#x0fe00;',
	  '&hacek;' => '&#x002c7;',
	  '&hbar;' => '&#x0210f;',
	  '&heartsuit;' => '&#x02665;',
	  '&hilbertspace;' => '&#x0210b;',
	  '&hksearow;' => '&#x02925;',
	  '&hkswarow;' => '&#x02926;',
	  '&hookleftarrow;' => '&#x021a9;',
	  '&hookrightarrow;' => '&#x021aa;',
	  '&hslash;' => '&#x0210f;',
	  '&humpdownhump;' => '&#x0224e;',
	  '&humpequal;' => '&#x0224f;',
	  '&iiiint;' => '&#x02a0c;',
	  '&iiint;' => '&#x0222d;',
	  '&im;' => '&#x02111;',
	  '&imaginaryi;' => '&#x02148;',
	  '&imagline;' => '&#x02110;',
	  '&imagpart;' => '&#x02111;',
	  '&implies;' => '&#x021d2;',
	  '&in;' => '&#x02208;',
	  '&integers;' => '&#x02124;',
	  '&integral;' => '&#x0222b;',
	  '&intercal;' => '&#x022ba;',
	  '&intersection;' => '&#x022c2;',
	  '&intprod;' => '&#x02a3c;',
	  '&invisiblecomma;' => '&#x02063;',
	  '&invisibletimes;' => '&#x02062;',
	  '&langle;' => '&#x02329;',
	  '&laplacetrf;' => '&#x02112;',
	  '&lbrace;' => '&#x0007b;',
	  '&lbrack;' => '&#x0005b;',
	  '&leftanglebracket;' => '&#x02329;',
	  '&leftarrow;' => '&#x02190;',
	  '&leftarrow;' => '&#x021d0;',
	  '&leftarrow;' => '&#x02190;',
	  '&leftarrowbar;' => '&#x021e4;',
	  '&leftarrowrightarrow;' => '&#x021c6;',
	  '&leftarrowtail;' => '&#x021a2;',
	  '&leftceiling;' => '&#x02308;',
	  '&leftdoublebracket;' => '&#x0301a;',
	  '&leftdownvector;' => '&#x021c3;',
	  '&leftfloor;' => '&#x0230a;',
	  '&leftharpoondown;' => '&#x021bd;',
	  '&leftharpoonup;' => '&#x021bc;',
	  '&leftleftarrows;' => '&#x021c7;',
	  '&leftrightarrow;' => '&#x02194;',
	  '&leftrightarrow;' => '&#x021d4;',
	  '&leftrightarrow;' => '&#x02194;',
	  '&leftrightarrows;' => '&#x021c6;',
	  '&leftrightharpoons;' => '&#x021cb;',
	  '&leftrightsquigarrow;' => '&#x021ad;',
	  '&lefttee;' => '&#x022a3;',
	  '&leftteearrow;' => '&#x021a4;',
	  '&leftthreetimes;' => '&#x022cb;',
	  '&lefttriangle;' => '&#x022b2;',
	  '&lefttriangleequal;' => '&#x022b4;',
	  '&leftupvector;' => '&#x021bf;',
	  '&leftvector;' => '&#x021bc;',
	  '&leq;' => '&#x02264;',
	  '&leqq;' => '&#x02266;',
	  '&leqslant;' => '&#x02a7d;',
	  '&lessapprox;' => '&#x02a85;',
	  '&lessdot;' => '&#x022d6;',
	  '&lesseqgtr;' => '&#x022da;',
	  '&lesseqqgtr;' => '&#x02a8b;',
	  '&lessequalgreater;' => '&#x022da;',
	  '&lessfullequal;' => '&#x02266;',
	  '&lessgreater;' => '&#x02276;',
	  '&lessgtr;' => '&#x02276;',
	  '&lesssim;' => '&#x02272;',
	  '&lessslantequal;' => '&#x02a7d;',
	  '&lesstilde;' => '&#x02272;',
	  '&ll;' => '&#x0226a;',
	  '&llcorner;' => '&#x0231e;',
	  '&lleftarrow;' => '&#x021da;',
	  '&lmoustache;' => '&#x023b0;',
	  '&lnapprox;' => '&#x02a89;',
	  '&lneq;' => '&#x02a87;',
	  '&lneqq;' => '&#x02268;',
	  '&longleftarrow;' => '&#x027f5;',
	  '&longleftarrow;' => '&#x027f8;',
	  '&longleftarrow;' => '&#x027f5;',
	  '&longleftrightarrow;' => '&#x027f7;',
	  '&longleftrightarrow;' => '&#x027fa;',
	  '&longleftrightarrow;' => '&#x027f7;',
	  '&longmapsto;' => '&#x027fc;',
	  '&longrightarrow;' => '&#x027f6;',
	  '&longrightarrow;' => '&#x027f9;',
	  '&longrightarrow;' => '&#x027f6;',
	  '&looparrowleft;' => '&#x021ab;',
	  '&looparrowright;' => '&#x021ac;',
	  '&lowerleftarrow;' => '&#x02199;',
	  '&lowerrightarrow;' => '&#x02198;',
	  '&lozenge;' => '&#x025ca;',
	  '&lrcorner;' => '&#x0231f;',
	  '&lsh;' => '&#x021b0;',
	  '&lvertneqq;' => '&#x02268;&#x0fe00;',
	  '&maltese;' => '&#x02720;',
	  '&mapsto;' => '&#x021a6;',
	  '&measuredangle;' => '&#x02221;',
	  '&mellintrf;' => '&#x02133;',
	  '&minusplus;' => '&#x02213;',
	  '&mp;' => '&#x02213;',
	  '&multimap;' => '&#x022b8;',
	  '&napprox;' => '&#x02249;',
	  '&natural;' => '&#x0266e;',
	  '&naturals;' => '&#x02115;',
	  '&nearrow;' => '&#x02197;',
	  '&negativemediumspace;' => '&#x0200b;',
	  '&negativethickspace;' => '&#x0200b;',
	  '&negativethinspace;' => '&#x0200b;',
	  '&negativeverythinspace;' => '&#x0200b;',
	  '&nestedgreatergreater;' => '&#x0226b;',
	  '&nestedlessless;' => '&#x0226a;',
	  '&nexists;' => '&#x02204;',
	  '&ngeq;' => '&#x02271;',
	  '&ngeqq;' => '&#x02267;&#x00338;',
	  '&ngeqslant;' => '&#x02a7e;&#x00338;',
	  '&ngtr;' => '&#x0226f;',
	  '&nleftarrow;' => '&#x021cd;',
	  '&nleftarrow;' => '&#x0219a;',
	  '&nleftrightarrow;' => '&#x021ce;',
	  '&nleftrightarrow;' => '&#x021ae;',
	  '&nleq;' => '&#x02270;',
	  '&nleqq;' => '&#x02266;&#x00338;',
	  '&nleqslant;' => '&#x02a7d;&#x00338;',
	  '&nless;' => '&#x0226e;',
	  '&nonbreakingspace;' => '&#x000a0;',
	  '&notcongruent;' => '&#x02262;',
	  '&notdoubleverticalbar;' => '&#x02226;',
	  '&notelement;' => '&#x02209;',
	  '&notequal;' => '&#x02260;',
	  '&notequaltilde;' => '&#x02242;&#x00338;',
	  '&notexists;' => '&#x02204;',
	  '&notgreater;' => '&#x0226f;',
	  '&notgreaterequal;' => '&#x02271;',
	  '&notgreaterfullequal;' => '&#x02266;&#x00338;',
	  '&notgreatergreater;' => '&#x0226b;&#x00338;',
	  '&notgreaterless;' => '&#x02279;',
	  '&notgreaterslantequal;' => '&#x02a7e;&#x00338;',
	  '&notgreatertilde;' => '&#x02275;',
	  '&nothumpdownhump;' => '&#x0224e;&#x00338;',
	  '&notlefttriangle;' => '&#x022ea;',
	  '&notlefttriangleequal;' => '&#x022ec;',
	  '&notless;' => '&#x0226e;',
	  '&notlessequal;' => '&#x02270;',
	  '&notlessgreater;' => '&#x02278;',
	  '&notlessless;' => '&#x0226a;&#x00338;',
	  '&notlessslantequal;' => '&#x02a7d;&#x00338;',
	  '&notlesstilde;' => '&#x02274;',
	  '&notprecedes;' => '&#x02280;',
	  '&notprecedesequal;' => '&#x02aaf;&#x00338;',
	  '&notprecedesslantequal;' => '&#x022e0;',
	  '&notreverseelement;' => '&#x0220c;',
	  '&notrighttriangle;' => '&#x022eb;',
	  '&notrighttriangleequal;' => '&#x022ed;',
	  '&notsquaresubsetequal;' => '&#x022e2;',
	  '&notsquaresupersetequal;' => '&#x022e3;',
	  '&notsubset;' => '&#x02282;&#x020d2;',
	  '&notsubsetequal;' => '&#x02288;',
	  '&notsucceeds;' => '&#x02281;',
	  '&notsucceedsequal;' => '&#x02ab0;&#x00338;',
	  '&notsucceedsslantequal;' => '&#x022e1;',
	  '&notsuperset;' => '&#x02283;&#x020d2;',
	  '&notsupersetequal;' => '&#x02289;',
	  '&nottilde;' => '&#x02241;',
	  '&nottildeequal;' => '&#x02244;',
	  '&nottildefullequal;' => '&#x02247;',
	  '&nottildetilde;' => '&#x02249;',
	  '&notverticalbar;' => '&#x02224;',
	  '&nparallel;' => '&#x02226;',
	  '&nprec;' => '&#x02280;',
	  '&npreceq;' => '&#x02aaf;&#x00338;',
	  '&nrightarrow;' => '&#x021cf;',
	  '&nrightarrow;' => '&#x0219b;',
	  '&nshortmid;' => '&#x02224;',
	  '&nshortparallel;' => '&#x02226;',
	  '&nsimeq;' => '&#x02244;',
	  '&nsubset;' => '&#x02282;&#x020d2;',
	  '&nsubseteq;' => '&#x02288;',
	  '&nsubseteqq;' => '&#x02ac5;&#x00338;',
	  '&nsucc;' => '&#x02281;',
	  '&nsucceq;' => '&#x02ab0;&#x00338;',
	  '&nsupset;' => '&#x02283;&#x020d2;',
	  '&nsupseteq;' => '&#x02289;',
	  '&nsupseteqq;' => '&#x02ac6;&#x00338;',
	  '&ntriangleleft;' => '&#x022ea;',
	  '&ntrianglelefteq;' => '&#x022ec;',
	  '&ntriangleright;' => '&#x022eb;',
	  '&ntrianglerighteq;' => '&#x022ed;',
	  '&nwarrow;' => '&#x02196;',
	  '&oint;' => '&#x0222e;',
	  '&opencurlydoublequote;' => '&#x0201c;',
	  '&opencurlyquote;' => '&#x02018;',
	  '&orderof;' => '&#x02134;',
	  '&parallel;' => '&#x02225;',
	  '&partiald;' => '&#x02202;',
	  '&pitchfork;' => '&#x022d4;',
	  '&plusminus;' => '&#x000b1;',
	  '&pm;' => '&#x000b1;',
	  '&poincareplane;' => '&#x0210c;',
	  '&prec;' => '&#x0227a;',
	  '&precapprox;' => '&#x02ab7;',
	  '&preccurlyeq;' => '&#x0227c;',
	  '&precedes;' => '&#x0227a;',
	  '&precedesequal;' => '&#x02aaf;',
	  '&precedesslantequal;' => '&#x0227c;',
	  '&precedestilde;' => '&#x0227e;',
	  '&preceq;' => '&#x02aaf;',
	  '&precnapprox;' => '&#x02ab9;',
	  '&precneqq;' => '&#x02ab5;',
	  '&precnsim;' => '&#x022e8;',
	  '&precsim;' => '&#x0227e;',
	  '&primes;' => '&#x02119;',
	  '&proportion;' => '&#x02237;',
	  '&proportional;' => '&#x0221d;',
	  '&propto;' => '&#x0221d;',
	  '&quaternions;' => '&#x0210d;',
	  '&questeq;' => '&#x0225f;',
	  '&rangle;' => '&#x0232a;',
	  '&rationals;' => '&#x0211a;',
	  '&rbrace;' => '&#x0007d;',
	  '&rbrack;' => '&#x0005d;',
	  '&re;' => '&#x0211c;',
	  '&realine;' => '&#x0211b;',
	  '&realpart;' => '&#x0211c;',
	  '&reals;' => '&#x0211d;',
	  '&reverseelement;' => '&#x0220b;',
	  '&reverseequilibrium;' => '&#x021cb;',
	  '&reverseupequilibrium;' => '&#x0296f;',
	  '&rightanglebracket;' => '&#x0232a;',
	  '&rightarrow;' => '&#x02192;',
	  '&rightarrow;' => '&#x021d2;',
	  '&rightarrow;' => '&#x02192;',
	  '&rightarrowbar;' => '&#x021e5;',
	  '&rightarrowleftarrow;' => '&#x021c4;',
	  '&rightarrowtail;' => '&#x021a3;',
	  '&rightceiling;' => '&#x02309;',
	  '&rightdoublebracket;' => '&#x0301b;',
	  '&rightdownvector;' => '&#x021c2;',
	  '&rightfloor;' => '&#x0230b;',
	  '&rightharpoondown;' => '&#x021c1;',
	  '&rightharpoonup;' => '&#x021c0;',
	  '&rightleftarrows;' => '&#x021c4;',
	  '&rightleftharpoons;' => '&#x021cc;',
	  '&rightrightarrows;' => '&#x021c9;',
	  '&rightsquigarrow;' => '&#x0219d;',
	  '&righttee;' => '&#x022a2;',
	  '&rightteearrow;' => '&#x021a6;',
	  '&rightthreetimes;' => '&#x022cc;',
	  '&righttriangle;' => '&#x022b3;',
	  '&righttriangleequal;' => '&#x022b5;',
	  '&rightupvector;' => '&#x021be;',
	  '&rightvector;' => '&#x021c0;',
	  '&risingdotseq;' => '&#x02253;',
	  '&rmoustache;' => '&#x023b1;',
	  '&rrightarrow;' => '&#x021db;',
	  '&rsh;' => '&#x021b1;',
	  '&searrow;' => '&#x02198;',
	  '&setminus;' => '&#x02216;',
	  '&shortdownarrow;' => '&#x02193;',
	  '&shortleftarrow;' => '&#x02190;',
	  '&shortmid;' => '&#x02223;',
	  '&shortparallel;' => '&#x02225;',
	  '&shortrightarrow;' => '&#x02192;',
	  '&shortuparrow;' => '&#x02191;',
	  '&simeq;' => '&#x02243;',
	  '&smallcircle;' => '&#x02218;',
	  '&smallsetminus;' => '&#x02216;',
	  '&spadesuit;' => '&#x02660;',
	  '&sqrt;' => '&#x0221a;',
	  '&sqsubset;' => '&#x0228f;',
	  '&sqsubseteq;' => '&#x02291;',
	  '&sqsupset;' => '&#x02290;',
	  '&sqsupseteq;' => '&#x02292;',
	  '&square;' => '&#x025a1;',
	  '&squareintersection;' => '&#x02293;',
	  '&squaresubset;' => '&#x0228f;',
	  '&squaresubsetequal;' => '&#x02291;',
	  '&squaresuperset;' => '&#x02290;',
	  '&squaresupersetequal;' => '&#x02292;',
	  '&squareunion;' => '&#x02294;',
	  '&star;' => '&#x022c6;',
	  '&straightepsilon;' => '&#x003f5;',
	  '&straightphi;' => '&#x003d5;',
	  '&subset;' => '&#x022d0;',
	  '&subset;' => '&#x02282;',
	  '&subseteq;' => '&#x02286;',
	  '&subseteqq;' => '&#x02ac5;',
	  '&subsetequal;' => '&#x02286;',
	  '&subsetneq;' => '&#x0228a;',
	  '&subsetneqq;' => '&#x02acb;',
	  '&succ;' => '&#x0227b;',
	  '&succapprox;' => '&#x02ab8;',
	  '&succcurlyeq;' => '&#x0227d;',
	  '&succeeds;' => '&#x0227b;',
	  '&succeedsequal;' => '&#x02ab0;',
	  '&succeedsslantequal;' => '&#x0227d;',
	  '&succeedstilde;' => '&#x0227f;',
	  '&succeq;' => '&#x02ab0;',
	  '&succnapprox;' => '&#x02aba;',
	  '&succneqq;' => '&#x02ab6;',
	  '&succnsim;' => '&#x022e9;',
	  '&succsim;' => '&#x0227f;',
	  '&suchthat;' => '&#x0220b;',
	  '&sum;' => '&#x02211;',
	  '&superset;' => '&#x02283;',
	  '&supersetequal;' => '&#x02287;',
	  '&supset;' => '&#x022d1;',
	  '&supset;' => '&#x02283;',
	  '&supseteq;' => '&#x02287;',
	  '&supseteqq;' => '&#x02ac6;',
	  '&supsetneq;' => '&#x0228b;',
	  '&supsetneqq;' => '&#x02acc;',
	  '&swarrow;' => '&#x02199;',
	  '&therefore;' => '&#x02234;',
	  '&therefore;' => '&#x02234;',
	  '&thickapprox;' => '&#x02248;',
	  '&thicksim;' => '&#x0223c;',
	  '&thinspace;' => '&#x02009;',
	  '&tilde;' => '&#x0223c;',
	  '&tildeequal;' => '&#x02243;',
	  '&tildefullequal;' => '&#x02245;',
	  '&tildetilde;' => '&#x02248;',
	  '&toea;' => '&#x02928;',
	  '&tosa;' => '&#x02929;',
	  '&triangle;' => '&#x025b5;',
	  '&triangledown;' => '&#x025bf;',
	  '&triangleleft;' => '&#x025c3;',
	  '&trianglelefteq;' => '&#x022b4;',
	  '&triangleq;' => '&#x0225c;',
	  '&triangleright;' => '&#x025b9;',
	  '&trianglerighteq;' => '&#x022b5;',
	  '&tripledot;' => '&#x020db;',
	  '&twoheadleftarrow;' => '&#x0219e;',
	  '&twoheadrightarrow;' => '&#x021a0;',
	  '&ulcorner;' => '&#x0231c;',
	  '&union;' => '&#x022c3;',
	  '&unionplus;' => '&#x0228e;',
	  '&uparrow;' => '&#x02191;',
	  '&uparrow;' => '&#x021d1;',
	  '&uparrow;' => '&#x02191;',
	  '&uparrowdownarrow;' => '&#x021c5;',
	  '&updownarrow;' => '&#x02195;',
	  '&updownarrow;' => '&#x021d5;',
	  '&updownarrow;' => '&#x02195;',
	  '&upequilibrium;' => '&#x0296e;',
	  '&upharpoonleft;' => '&#x021bf;',
	  '&upharpoonright;' => '&#x021be;',
	  '&upperleftarrow;' => '&#x02196;',
	  '&upperrightarrow;' => '&#x02197;',
	  '&upsilon;' => '&#x003c5;',
	  '&uptee;' => '&#x022a5;',
	  '&upteearrow;' => '&#x021a5;',
	  '&upuparrows;' => '&#x021c8;',
	  '&urcorner;' => '&#x0231d;',
	  '&varepsilon;' => '&#x003b5;',
	  '&varkappa;' => '&#x003f0;',
	  '&varnothing;' => '&#x02205;',
	  '&varphi;' => '&#x003c6;',
	  '&varpi;' => '&#x003d6;',
	  '&varpropto;' => '&#x0221d;',
	  '&varrho;' => '&#x003f1;',
	  '&varsigma;' => '&#x003c2;',
	  '&varsubsetneq;' => '&#x0228a;&#x0fe00;',
	  '&varsubsetneqq;' => '&#x02acb;&#x0fe00;',
	  '&varsupsetneq;' => '&#x0228b;&#x0fe00;',
	  '&varsupsetneqq;' => '&#x02acc;&#x0fe00;',
	  '&vartheta;' => '&#x003d1;',
	  '&vartriangleleft;' => '&#x022b2;',
	  '&vartriangleright;' => '&#x022b3;',
	  '&vee;' => '&#x022c1;',
	  '&vee;' => '&#x02228;',
	  '&vert;' => '&#x02016;',
	  '&vert;' => '&#x0007c;',
	  '&verticalbar;' => '&#x02223;',
	  '&verticaltilde;' => '&#x02240;',
	  '&verythinspace;' => '&#x0200a;',
	  '&wedge;' => '&#x022c0;',
	  '&wedge;' => '&#x02227;',
	  '&wp;' => '&#x02118;',
	  '&wr;' => '&#x02240;',
	  '&zeetrf;' => '&#x02128;'
	);
	
		if ($mode=='rev') {
			preg_match_all('#\&\#(.*)\;#sU',$string,$entities);
			$entities[0]=array_unique($entities[0]);
			foreach($entities[0] as $v) {
				if (in_array($v,$cr))
					$string=str_replace( $v,array_search($v,$cr),$string);
			}
			return $string;	
		}
	
		preg_match_all('#\&(.*)\;#sU',$string,$entities);
		$entities[0]=array_unique($entities[0]);
		if (is_array($entities[0]) && !empty($entities[0]))
			foreach($entities[0] as $v) {
			if (array_key_exists($v,$cr))
				$string=(str_replace( $v,$cr[$v],$string));
			}
	
	 return $string;
	}
}


//
// init
//

include_once $_AS['basedir'] . 'inc/class.articlesystem.php'; //Basisklasse
include_once $_AS['basedir'] . 'inc/class.lang.php'; //Sprachobjekt

$_AS['temp']=array();
if (empty($mvars[800]))
	$_AS['modkey']=$cms_mod['key'];
else
	$_AS['modkey']=as_cleanstring($mvars[800]);


if ($mvars['1']=='2' && $sess->name == 'sefrengo' && ($view == 'preview' || $view == 'edit') || $mvars['1']=='1' ) {
  $_AS['xml-gen-active'] = false;
} else {
  $_AS['xml-gen-active'] = true;

}
	
$_AS['snippet_object'] = false;
$_AS['snippet_replacement_found'] = 'unknown';

//CMS Webrequest erstllen
$_AS['cms_wr'] =& $GLOBALS['sf_factory']->getObject('HTTP', 'WebRequest');

//AdoDB initialtisieren
$adodb =& $GLOBALS['sf_factory']->getObject('DATABASE', 'Ado');

//Articlesystem initializieren
$_AS['artsys_obj'] = new Articlesystem;

//Collectionklasse laden
include_once $_AS['basedir'] . 'inc/class.articlecollection.php';
include_once $_AS['basedir'] . 'inc/class.elementcollection.php';

//Externe Variablen per CMS WebRequest holen
#$_AS['idarticle'] = $_AS['cms_wr']->getVal($_AS['modkey'].'idarticle');

//Einige Config-Vars direkt holen
$_AS['config']['date'] = str_replace( array('{day}', '{month}', '{year}'), array('d', 'm', 'Y'),$mvars[10]);
$_AS['config']['time'] = str_replace( array('{hour}', '{minute}'), array('%H', '%M'), $mvars[11]);
$_AS['config']['time12'] = str_replace( array('{hour}', '{minute}'), array('%I', '%M'), $mvars[11]);
$_AS['config']['time24'] = str_replace( array('{hour}', '{minute}'), array('%H', '%M'), $mvars[11]);

$_AS['config']['day'] = $mvars[10210];
$_AS['config']['month'] = $mvars[10211];
$_AS['config']['year'] = $mvars[10212];

// create category id<->name array for later use
$adodb =& $GLOBALS['sf_factory']->getObject('DATABASE', 'Ado');
$sql = "SELECT idcategory, name FROM ".$cfg_cms['db_table_prefix']."plug_".$_AS['db']."_category WHERE idclient='".$client."' AND idlang='".$lang."' ORDER BY name,hash ASC"; // AND idlang='".$idlang."'
$rs = $adodb->Execute($sql);
$_AS['temp']['categories']=array();
while (!$rs->EOF) {
    $_AS['temp']['categories'][$rs->fields[0]] = $rs->fields[1];
    $rs->MoveNext();
}
$rs->Close();
// get langs
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
$_AS['temp']['langs']=array();

while (!$rs->EOF) {
    $_AS['temp']['langs'][$rs->fields[0]] = $rs->fields[1];
    $rs->MoveNext();
}

$rs->Close();



//
// routing preperation
//

$_AS['cat_routing']['routings'] = array();		
$mvars[300]=trim($mvars[300]);
if(!empty($mvars[300]) ) {

	$_AS['cat_routing']['idcatside'] = $idcatside;
	$_AS['cat_routing']['idcat'] = $idcat;

	$_AS['cat_routing']['category_temp'] = '';
	$_AS['cat_routing']['raw'] = trim( str_replace(' ', '',$mvars[300]));

  $_AS['cat_routing']['raw_vals'] = explode("\n", $_AS['cat_routing']['raw']);

  foreach ($_AS['cat_routing']['raw_vals'] AS $v) {
  	$v=trim($v);
    $_AS['cat_routing_pieces'] = explode('>', $v);
    $_AS['cat_routing']['routings'][ $_AS['cat_routing_pieces']['0'] ] = $_AS['cat_routing_pieces']['1'];
  }
  
	//source idcatside
	if (array_key_exists('idcatside:'.$idcatside, $_AS['cat_routing']['routings'])) {
	  //idcatside to as cat
		$_AS['cat_routing']['category_temp'] = $_AS['cat_routing']['routings']['idcatside:'.$idcatside];
		if ($_AS['cat_routing']['category_temp'] > 0) 
		  $_AS['routed']['category'] = $_AS['cat_routing']['category_temp'];
	// source idcat
	} else if (array_key_exists('idcat:'.$idcat, $_AS['cat_routing']['routings'])) { 
	  //idcat to as cat
	  $_AS['cat_routing']['category_temp'] = $_AS['cat_routing']['routings']['idcat:'.$idcat];
	  if ($_AS['cat_routing']['category_temp'] > 0) 
			$_AS['routed']['category'] = $_AS['cat_routing']['category_temp'];
	}

}

//
// sorting preperation
//
$mvars[400]=trim($mvars[400]);
if(!empty($mvars[400])) {

	$_AS['sorting']['array'] = array();		

	$_AS['sorting']['raw'] = trim( str_replace(' ', '',$mvars[400]));

  $_AS['sorting']['raw_vals'] = explode("\n", $_AS['sorting']['raw']);
  foreach ($_AS['sorting']['raw_vals'] AS $v) {
    $_AS['sorting_pieces'] = explode('>', $v);
    
    if (strpos($_AS['sorting_pieces']['0'],'date')!==false || strpos($_AS['sorting_pieces']['0'],'time'))
    	$_AS['sorting_pieces']['0']='article_'.$_AS['sorting_pieces']['0'];

    if (strpos($_AS['sorting_pieces']['0'],'category')!==false)
    	$_AS['sorting_pieces']['0']='id'.$_AS['sorting_pieces']['0'];
  
    $_AS['sorting']['array'][$_AS['sorting_pieces']['0']]=$_AS['sorting_pieces']['1'];
  }

}

//
// get current category
//

if (!empty($_AS['routed']['category']) && empty($mvars[302]))
	$_AS['temp']['category']=$_AS['routed']['category'];
else {
	if (!empty($mvars[8]))
		$_AS['temp']['category']=$mvars[8];
#	$_AS['temp']['category']=implode(',',array_flip($_AS['temp']['categories']));
}

$_AS['config']['startmonth'] = date("m");

$_AS['config']['monthback'] = -1;
$_AS['config_static']['monthback']=-1;


//
// create category strings
//

$_AS['cat_str']	= '';
if (strpos($_AS['temp']['category'],',')!==false) {
	$_AS['cat_str_arr']=array();
	foreach(array_filter(explode(',',$_AS['temp']['category'])) as $v)
		$_AS['cat_str_arr'][]=$_AS['temp']['categories'][$v];

	$_AS['cat_str']=implode(', ',$_AS['cat_str_arr']);
	$_AS['cat_str_fn']=implode('-',$_AS['cat_str_arr']);
} elseif ($_AS['temp']['category']!='')	{			
  $_AS['cat_str']= $_AS['temp']['categories'][$_AS['temp']['category']];
  $_AS['cat_str_fn']=$_AS['cat_str'];
}

//
// create filename
//

$_AS['file']=str_replace( array(
																'{category_id}',
																'{category_name}',
																'{lang_id}',
																'{lang_name}'
																),
													array (
																str_replace(',','-',$_AS['temp']['category']),
																$_AS['cat_str_fn'],
																$lang,
																$_AS['temp']['langs'][$lang]
																),
													$mvars[900]);

//
// get modified time
//
if($_AS['xml-gen-active'])
	if (is_file($cfg_client['path'].$_AS['file']))
		$_AS['file_modified']=@filemtime($cfg_client['path'].$_AS['file']);

	


//
// start creation process
//
if($_AS['xml-gen-active'])
	if($_AS['file_modified']+($mvars[480]*60)<mktime() ||
	 !empty($mvars[4]) ||
	 !empty($mvars[700]) ||
	 	empty($_AS['file_modified'])){
	

	  //init
	  $_AS['collection'] = new ArticleCollection();
		$_AS['elements'] = new ArticleElements;
		
		$_AS['collection']->setLegal(mktime(date('H'),date('i'),0,date('m'),date('d'),date('Y')));
			
		// set category
		if(!empty($_AS['temp']['category'])) 
	  		$_AS['collection']->setIdcategory($_AS['temp']['category']);
	
	
	
	  //Offline geschaltete Termine anzeigen? NEIN!
	  $_AS['collection']->setHideOffline(true);
	
	
		$_AS['timestamp_rangestart']=mktime(0,0,0,0,0,1971);	
		if (empty($mvars[3])){
			$_AS['timestamp_rangeend']	=	mktime(
																						23,59,59,
																						date('m'),
																						date('d'),
																						date('Y')
																					);
		} else {
			$_AS['timestamp_rangeend']	=	mktime(
																						date('H'),date('i'),59,
																						date('m'),
																						date('d'),
																						date('Y')
																					);
		}
	
		$_AS['collection']->setDateRange($_AS['timestamp_rangestart'],$_AS['timestamp_rangeend']);
		$_AS['timestamp_rangestart']='';
		$_AS['timestamp_rangeend']='';
	
		$_AS['collection']->setSorting( $_AS['sorting']['array']);

		if (!empty($mvars['48']))
			$_AS['collection']->setLimit($mvars['48'],0);				

	  $_AS['collection']->generate();
	
		$_AS['config']['day'] = $mvars[10110];
		$_AS['config']['month'] = $mvars[10111];
		$_AS['config']['year'] = $mvars[10112];
		$_AS['config']['day2'] = $mvars[11110];
		$_AS['config']['month2'] = $mvars[11111];

		$_AS['list2']['row'] = array();
		$_AS['list1']['row'] = array();
		
		$_AS['list2']['filename'] = array();
		$_AS['list1']['filename'] = array();
		$_AS['list2']['filemodified'] = array();
		$_AS['list1']['filemodified'] = array();

    $_AS['list']['changed_list'] = '';


		if ($mvars['4000']=='true') {
			$_AS['temp']['gen_rss']=false;
			$_AS['temp']['db_changed_list'] =	as_get_val('as_rss-changed_mem-'.$_AS['file']);
		}

		
	  for($iter = $_AS['collection']->get(); $iter->valid(); $iter->next() ) {

	    //Aktuellen Eintrag als Objekt bereitstellen
	    $_AS['item'] =& $iter->current();
	
			$_AS['item_elements']=$_AS['elements']->loadById($_AS['item']->getDataByKey('idarticle'));

	    //Tpl in Tmp-Var kopieren
	    $_AS['temp']['list1_output'] = $mvars[6];
			$_AS['temp']['list2_output'] = $mvars[7];


			$_AS['temp']['data1']	=	as_element_replacement(	$_AS['item'],
																											$_AS['artsys_obj'],
																											$_AS['item_elements']['image'],
																											$_AS['item_elements']['file'],
																											$_AS['item_elements']['link'],
																											'',
																											$mvars[6],
																											'',
																											'',
																											'',
																											'',
																											$_AS['temp']['categories'],
																											$_AS['config'],
																											false);
			if (!empty($_AS['temp']['list2_output']))
				$_AS['temp']['data2']	=	as_element_replacement(	$_AS['item'],
																												$_AS['artsys_obj'],
																												$_AS['item_elements']['image'],
																												$_AS['item_elements']['file'],
																												$_AS['item_elements']['link'],
																												'',
																												$mvars[7],
																												'',
																												'',
																												'',
																												'',
																												$_AS['temp']['categories'],
																												$_AS['config'],
																												false);

	    $_AS['list']['changed_list'] .= 'id'.$_AS['item']->getDataByKey('idarticle').'_'.$_AS['item']->getDataByKey('lastedit').' ';

			if ($mvars['700']!='') {
			
				//
				// create filename
				//
	
				$_AS['temp']['filename'] = '';
				$_AS['temp']['filemodified'] = '';

				$_AS['temp']['filename']	=	$mvars[720];
					
				$_AS['temp']['filename'] =str_replace( array(
																				'{category_id}',
																				'{category_name}',
																				'{lang_id}',
																				'{lang_name}',
																				'{idarticle}',
																				'{title}'
																				),
																	array (
																				str_replace(',','-',$_AS['temp']['category']),
																				$_AS['cat_str_fn'],
																				$lang,
																				$_AS['temp']['langs'][$lang],
																				$_AS['item']->getDataByKey('idarticle'),
																				$_AS['item']->getDataByKey('title')
																				),
																	$_AS['temp']['filename']);
	
				foreach ($_AS['temp']['data'.str_replace('list','',$mvars['700'])] as $k => $v){
						$_AS['temp']['filename']=str_replace('{'.$k.'}',$v,$_AS['temp']['filename']);
				}
				
				//
				// get modified time
				//
				if (is_file($cfg_client['path'].$_AS['temp']['filename']))
					$_AS['temp']['filemodified']=@filemtime($cfg_client['path'].$_AS['temp']['filename']);
			
			
				$_AS[$mvars['700']]['filename'][]=$_AS['temp']['filename'];
				$_AS[$mvars['700']]['filemodified'][]=$_AS['temp']['filemodified'];
			
			}

		
			if (is_array($_AS['temp']['data1']) && $mvars[9]!=2) {
				foreach ($_AS['temp']['data1'] as $k => $v)
					if (empty($mvars[9]))
						$_AS['temp']['data1_conv'][$k]='<![CDATA['.$v.']]>';
					else
						$_AS['temp']['data1_conv'][$k]=asxml_safe_cr(htmlentities($v,ENT_COMPAT,'UTF-8'));
				
				$_AS['temp']['data1']=$_AS['temp']['data1_conv'];
			}
			if (is_array($_AS['temp']['data2']) && $mvars[9]!=2){
				foreach ($_AS['temp']['data2'] as $k => $v) 
					if (empty($mvars[9]))
						$_AS['temp']['data2_conv'][$k]='<![CDATA['.$v.']]>';
					else
						$_AS['temp']['data2_conv'][$k]=asxml_safe_cr(htmlentities($v,ENT_COMPAT,'UTF-8'));
						
				$_AS['temp']['data2']=$_AS['temp']['data2_conv'];	
			}		
			
			unset($_AS['temp']['result_idcatsideback']);
			
			$_AS['temp']['first_routed_side']=array_slice(array_filter(explode('|',$_AS['item']->getDataByKey('idcategory'))),0,1);

	   	$_AS['temp']['result_idcatside']=(int) str_replace('idcatside:','',array_search($_AS['temp']['first_routed_side'][0],$_AS['cat_routing']['routings']));	

			if (empty($_AS['temp']['result_idcatside']) && empty($mvars[71]))
				$_AS['temp']['result_idcatside']=$idcatside;
			elseif (!empty($_AS['temp']['result_idcatside']) && !empty($mvars[71])) {
				$_AS['temp']['result_idcatsideback']=$_AS['temp']['result_idcatside'];
				$_AS['temp']['result_idcatside']=$mvars[71];
			} elseif (!empty($mvars[71]))
				$_AS['temp']['result_idcatside']=$mvars[71];
				
			$_AS['temp']['data1']['url'] = as_url_creator( $cfg_client['htmlpath']. asxml_createLinkUrl($lang,$_AS['temp']['result_idcatside'], 1),
																						 array(	'idarticle' => $_AS['item']->getDataByKey('idarticle'),
																						 				'idcatsideback' => $_AS['temp']['result_idcatsideback']),
																						 				true 
																									);
			$_AS['temp']['data2']['url'] = $_AS['temp']['data1']['url'];

			//fill template
			foreach ($_AS['temp']['data1'] as $k => $v){

					//fill template - element dependent if-statements
					$_AS['temp']['list1_output']=str_replace('{'.$k.'}',$v,as_element_ifstatements($_AS['temp']['list1_output'],$_AS['temp']['data1'],$k,$v));

					$_AS['temp']['list1_output']=str_replace('{'.$k.'}',$v,$_AS['temp']['list1_output']);

			}
			//fill template
			foreach ($_AS['temp']['data2'] as $k => $v){

					//fill template - element dependent if-statements
					$_AS['temp']['list2_output']=str_replace('{'.$k.'}',$v,as_element_ifstatements($_AS['temp']['list2_output'],$_AS['temp']['data2'],$k,$v));

					$_AS['temp']['list2_output']=str_replace('{'.$k.'}',$v,$_AS['temp']['list2_output']);

			}
	  	// global if-statements
			$_AS['temp']['list2_output'] = as_element_sfifstatements($_AS['temp']['list2_output']);
			$_AS['temp']['list1_output'] = as_element_sfifstatements($_AS['temp']['list1_output']);

			$_AS['temp']['list1_output'] = str_replace('cms://',$cfg_client['htmlpath'].$cfg_client['contentfile'].'?',$_AS['temp']['list1_output']);
			$_AS['temp']['list2_output'] = str_replace('cms://',$cfg_client['htmlpath'].$cfg_client['contentfile'].'?',$_AS['temp']['list2_output']);

    	$_AS['temp']['list1_output'] = str_replace('{baseurl}',$cfg_client['htmlpath'],$_AS['temp']['list1_output']);
			$_AS['temp']['list2_output'] = str_replace('{baseurl}',$cfg_client['htmlpath'],$_AS['temp']['list2_output']);

			$_AS['temp']['prepurl4xml']=array();

			if (strpos($_AS['temp']['list1_output'],'{prepurl4xml}')!==false) {
				$_AS['temp']['prepurl4xml']=array();
				preg_match_all('#\{prepurl4xml\}(.*)\{/prepurl4xml\}#sU',$_AS['temp']['list1_output'],$_AS['temp']['prepurl4xml']);
				array_filter($_AS['temp']['prepurl4xml'][1]);
				foreach($_AS['temp']['prepurl4xml'][1] as $v) {
						$v_old=$v;
						$v=htmlspecialchars_decode($v,ENT_COMPAT);
						$v=htmlspecialchars($v,ENT_COMPAT,'UTF-8');
						$_AS['temp']['list1_output'] = str_replace($v_old,$v,$_AS['temp']['list1_output']);
					}
				$_AS['temp']['list1_output']=str_replace(array('{prepurl4xml}','{/prepurl4xml}'), array('',''), $_AS['temp']['list1_output']);
			}

			if (strpos($_AS['temp']['list2_output'],'{prepurl4xml}')!==false) {
				$_AS['temp']['prepurl4xml']=array();
				preg_match_all('#\{prepurl4xml\}(.*)\{/prepurl4xml\}#sU',$_AS['temp']['list2_output'],$_AS['temp']['prepurl4xml']);
				array_filter($_AS['temp']['prepurl4xml'][1]);
				foreach($_AS['temp']['prepurl4xml'][1] as $v) {
						$v_old=$v;
						$v=htmlspecialchars_decode($v,ENT_COMPAT);
						$v=htmlspecialchars($v,ENT_COMPAT,'UTF-8');
						$_AS['temp']['list2_output'] = str_replace($v_old,$v,$_AS['temp']['list2_output']);
					}
				$_AS['temp']['list2_output']=str_replace(array('{prepurl4xml}','{/prepurl4xml}'), array('',''), $_AS['temp']['list2_output']);
			}

			if (strpos($_AS['temp']['list1_output'],'{preptxt4xml_special}')!==false) {
				$_AS['temp']['preptxt4xml_special']=array();
				preg_match_all('#\{preptxt4xml_special\}(.*)\{/preptxt4xml_special\}#sU',$_AS['temp']['list1_output'],$_AS['temp']['preptxt4xml_special']);
				array_filter($_AS['temp']['preptxt4xml_special'][1]);
				foreach($_AS['temp']['preptxt4xml_special'][1] as $v) {
					$v_old=$v;
					$v=html_entity_decode($v,ENT_COMPAT,'UTF-8');
					$v=asxml_safe_cr(htmlentities($v,ENT_COMPAT,'UTF-8'));
					$_AS['temp']['list1_output'] = str_replace($v_old,$v,$_AS['temp']['list1_output']);
				}
				$_AS['temp']['list1_output']=str_replace(array('{preptxt4xml_special}','{/preptxt4xml_special}'), array('',''), $_AS['temp']['list1_output']);
			}

			if (strpos($_AS['temp']['list2_output'],'{preptxt4xml_special}')!==false) {
				$_AS['temp']['preptxt4xml_special']=array();
				preg_match_all('#\{preptxt4xml_special\}(.*)\{/preptxt4xml_special\}#sU',$_AS['temp']['list2_output'],$_AS['temp']['preptxt4xml_special']);
				array_filter($_AS['temp']['preptxt4xml_special'][1]);
				foreach($_AS['temp']['preptxt4xml_special'][1] as $v) {
					$v_old=$v;
					$v=html_entity_decode($v,ENT_COMPAT,'UTF-8');
					$v=asxml_safe_cr(htmlentities($v,ENT_COMPAT,'UTF-8'));
					$_AS['temp']['list2_output'] = str_replace($v_old,$v,$_AS['temp']['list2_output']);
				}
				$_AS['temp']['list2_output']=str_replace(array('{preptxt4xml_special}','{/preptxt4xml_special}'), array('',''), $_AS['temp']['list2_output']);
			}

			if (strpos($_AS['temp']['list1_output'],'{preptxt4xml}')!==false) {
				$_AS['temp']['preptxt4xml']=array();
				preg_match_all('#\{preptxt4xml\}(.*)\{/preptxt4xml\}#sU',$_AS['temp']['list1_output'],$_AS['temp']['preptxt4xml']);
				array_filter($_AS['temp']['preptxt4xml'][1]);
				foreach($_AS['temp']['preptxt4xml'][1] as $v) {
					$v_old=$v;
					$v=htmlspecialchars(htmlspecialchars_decode($v),ENT_NOQUOTES,'UTF-8');
					$_AS['temp']['list1_output'] = str_replace($v_old,$v,$_AS['temp']['list1_output']);
				}
				$_AS['temp']['list1_output']=str_replace(array('{preptxt4xml}','{/preptxt4xml}'), array('',''), $_AS['temp']['list1_output']);
			}

			if (strpos($_AS['temp']['list2_output'],'{preptxt4xml}')!==false) {
				$_AS['temp']['preptxt4xml']=array();
				preg_match_all('#\{preptxt4xml\}(.*)\{/preptxt4xml\}#sU',$_AS['temp']['list2_output'],$_AS['temp']['preptxt4xml']);
				array_filter($_AS['temp']['preptxt4xml'][1]);
				foreach($_AS['temp']['preptxt4xml'][1] as $v) {
					$v_old=$v;
					$v=htmlspecialchars(htmlspecialchars_decode($v),ENT_NOQUOTES,'UTF-8');
					$_AS['temp']['list2_output'] = str_replace($v_old,$v,$_AS['temp']['list2_output']);
				}
				$_AS['temp']['list2_output']=str_replace(array('{preptxt4xml}','{/preptxt4xml}'), array('',''), $_AS['temp']['list2_output']);
			}

	    $_AS['list2']['row'][] = $_AS['temp']['list2_output'];
	    $_AS['list1']['row'][] = $_AS['temp']['list1_output'];
	  }




		if(count($_AS['list1']['row']) == 1)
		  $_AS['temp']['rows1'] = $_AS['list1']['row'][0];
		elseif(count($_AS['list1']['row'])>1)
	    $_AS['temp']['rows1'] = implode("\n",$_AS['list1']['row']);
	
		if(count($_AS['list2']['row']) == 1)
		  $_AS['temp']['rows2'] = $_AS['list2']['row'][0];
		elseif(count($_AS['list2']['row'])>1)
	    $_AS['temp']['rows2'] = implode("\n",$_AS['list2']['row']);
	
		$_AS['output']['list_body']=$mvars[5];
	
		// 
		// chop
		// 
		if (strpos($_AS['temp']['rows1'],'{chop}')!==false){
			preg_match_all('#\{chop\}(.*)\{/chop\}#sU',$_AS['temp']['rows1'],$_AS['temp']['chopparts']);
			if (!empty($_AS['temp']['chopparts']))
		  	foreach ($_AS['temp']['chopparts'][1] as $k => $v)
		  		$_AS['temp']['rows1']=str_replace(	$_AS['temp']['chopparts'][0][$k],
		  																				asxml_str_chop($v, $mvars['1003'], $mvars['1004'], $mvars['1005']),
		  																				$_AS['temp']['rows1']);
		  else
		  	$_AS['temp']['rows1']=str_replace(array('{chop}','{/chop}'), array('',''), $_AS['temp']['rows1']);
		}
	
		if (strpos($_AS['temp']['rows2'],'{chop}')!==false){
			preg_match_all('#\{chop\}(.*)\{/chop\}#sU',$_AS['temp']['rows2'],$_AS['temp']['chopparts']);
			if (!empty($_AS['temp']['chopparts']))
		  	foreach ($_AS['temp']['chopparts'][1] as $k => $v)
		  		$_AS['temp']['rows2']=str_replace(	$_AS['temp']['chopparts'][0][$k],
		  																				asxml_str_chop($v, $mvars['1003'], $mvars['1004'], $mvars['1005']),
		  																				$_AS['temp']['rows2']);
		  else
		  	$_AS['temp']['rows2']=str_replace(array('{chop}','{/chop}'), array('',''), $_AS['temp']['rows2']);
		}
	

		if ( $mvars['4000']=='true' && $_AS['list']['changed_list'] != $_AS['temp']['db_changed_list'] || !is_file($cfg_client['path'].$_AS['file']) ) {
			as_set_val ('as_rss-changed_mem-'.$_AS['file'],$_AS['list']['changed_list']);
			$_AS['temp']['gen_rss']=true;	
		}
	
		if ( $mvars['4000']!='true' || ( $mvars['4000']=='true' && $_AS['temp']['gen_rss']==true ) ) {

		if (strpos($_AS['output']['list_body'],'{content1}')!==false || strpos($_AS['output']['list_body'],'{content2}')!==false){

			$_AS['output']['list_body'] = str_replace( '{content1}',$_AS['temp']['rows1'],$_AS['output']['list_body']);
		  $_AS['output']['list_body'] = str_replace( '{content2}',$_AS['temp']['rows2'],$_AS['output']['list_body']);
			
			$_AS['output']['list_body']=str_replace( array(
																			'{date}',
																			'{time}',
																			'{site_url}',
																			'{category}'
																			),
																array (
																			date($_AS['config']['date'],mktime()),
																			strftime($_AS['config']['time'],mktime()),
																			$cfg_client['htmlpath'],
																			$_AS['cat_str']
																			),
																$_AS['output']['list_body']);
		
			$_AS['handle'] = @fopen ($cfg_client['path'].$_AS['file'], 'w+');
			@fwrite ($_AS['handle'],'<'.'?xml version="1.0" encoding="utf-8"?'.'>'.asxml_snippetReplace($_AS['output']['list_body']));
			@fclose ($_AS['handle']); 
		
		}

		if ($mvars['700']!='') {
			foreach ($_AS[$mvars['700']]['row'] as $k => $v) {
				if($_AS[$mvars['700']]['filemodified'][$k]+($mvars[480]*60)<mktime() || !empty($mvars[4]) ) {
					if (strpos($_AS[$mvars['700']]['row'][$k],'{chop}')!==false){
						preg_match_all('#\{chop\}(.*)\{/chop\}#sU',$_AS[$mvars['700']]['row'][$k],$_AS['temp']['chopparts']);
						if (!empty($_AS['temp']['chopparts']))
					  	foreach ($_AS['temp']['chopparts'][1] as $k => $v)
					  		$_AS[$mvars['700']]['row'][$k]=str_replace(	$_AS['temp']['chopparts'][0][$k],
					  																				asxml_str_chop($v, $mvars['1003'], $mvars['1004'], $mvars['1005']),
					  																				$_AS[$mvars['700']]['row'][$k]);
					  else
					  	$_AS[$mvars['700']]['row'][$k]=str_replace(array('{chop}','{/chop}'), array('',''), $_AS[$mvars['700']]['row'][$k]);
					}
					$_AS['handle'] = @fopen ($cfg_client['path'].$_AS[$mvars['700']]['filename'][$k], 'w+');
					@fwrite ($_AS['handle'],'<'.'?xml version="1.0" encoding="utf-8"?'.'>'."\n".asxml_snippetReplace($_AS[$mvars['700']]['row'][$k]));
					@fclose ($_AS['handle']); 
				}
			}
		}
	 }
	}

$_AS['link']=str_replace( array(
																'{rss_file}'
																),
													array (
																$cfg_client['htmlpath'].$_AS['file'],
																
																),
													$mvars[920]);

echo stripslashes(asxml_snippetReplace($_AS['link']));

unset($adodb,$rs, $_AS, $mvars, $mod);


}
</CMSPHP>
