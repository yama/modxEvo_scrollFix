//<?php
/**
 * ScrollFix 
 * Remembers last scroll-position after pressing save-button, combined with "AlwaysStay"
 * tested only with Evo 1.0.15
 *
 * ScrollFix:  http://forums.modx.com/thread/92462/remember-position-of-resource-page-after-saving-in-evo
 * AlwaysStay: https://github.com/extras-evolution/AlwaysStay/blob/master/install/assets/plugins/AlwaysStay.tpl
 *
 * @category    plugin
 * @version     0.1
 * @author		dh@fuseit.de
 * @internal    @configuration:
 *              &alwaysStay=AlwaysStay;list;Enabled,Disabled;Enabled &scrollFix=ScrollFix;list;Enabled,Disabled;Enabled
 * @internal    @events:
 *				OnDocFormRender,OnTempFormRender,OnChunkFormRender,OnSnipFormRender,OnPluginFormRender
 */

$alwaysStay = isset( $alwaysStay ) ? $alwaysStay : 'Disabled';
$scrollFix = isset( $scrollFix ) ? $scrollFix : 'Disabled';

$e = & $modx->Event;
if ($e->name == "OnDocFormRender" ||
    $e->name == "OnTempFormRender" ||
    $e->name == "OnChunkFormRender" ||
    $e->name == "OnSnipFormRender" ||
    $e->name == "OnPluginFormRender"
   ) {
	$html = '';
	if( $scrollFix != 'Disabled' ) {
    	$html .= '
		<script>!window.jQuery && document.write(unescape(\'%3Cscript src="/assets/js/jquery.min.js"%3E%3C/script%3E\'))</script>
        <script type="text/javascript">
			window.$j = jQuery.noConflict();
            ////////////////////////////////
			// fixscroll.js:
			// call loadP and unloadP when body loads/unloads and scroll position will not move
			function getScrollXY() {
				var x = 0, y = 0;
				if( typeof( window.pageYOffset ) == "number" ) {
					// Netscape
					x = window.pageXOffset;
					y = window.pageYOffset;
				} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
					// DOM
					x = document.body.scrollLeft;
					y = document.body.scrollTop;
				} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
					// IE6 standards compliant mode
					x = document.documentElement.scrollLeft;
					y = document.documentElement.scrollTop;
				}
				return [x, y];
			}
					   
			function setScrollXY(x, y) {
				window.scrollTo(x, y);
			}
			function createCookie(name,value,days) {
				if (days) {
					var date = new Date();
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = "; expires="+date.toGMTString();
				}
				else var expires = "";
				document.cookie = name+"="+value+expires+"; path=/";
			}
			
			function readCookie(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(";");
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==" ") c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
				return null;
			}
			function loadP(pageref){
				x=readCookie(pageref+"x");
				y=readCookie(pageref+"y");
				setScrollXY(x,y)
			}
			function unloadP(pageref){
				s=getScrollXY()
				createCookie(pageref+"x",s[0],0.1);
				createCookie(pageref+"y",s[1],0.1);
			}
			
			$j("#Button1 > a").removeAttr("href").css("cursor","pointer");	// REMOVE # FROM HREF TO AVOID SCROLL-TO-TOP
			$j( window ).unload(function() {
				unloadP("UniquePageNameHereScroll");
			});
			$j(document).ready(function() {
				loadP("UniquePageNameHereScroll");
			});
        </script>';
	};
	
	if( $alwaysStay != 'Disabled' ) {
		$html .= '
		<script>
			if(!$("stay").value) $("stay").value=2;	// ALWAYS STAY
		</script>';
	};
	
    $e->output($html);
}