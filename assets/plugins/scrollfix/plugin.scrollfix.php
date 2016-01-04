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
 * @version     0.21
 * @date		04.01.2016
 * @author		dh@fuseit.de
 * @internal    @configuration:
 *              &alwaysStay=AlwaysStay;list;Enabled,Disabled;Disabled &scrollFix=ScrollFix;list;Enabled,Disabled;Disabled &cookieLifetime=ScrollFix Cookie-Lifetime (Minutes);int;120 &addToTopButton=Add ToTop-Button;list;Enabled,Disabled;Disabled
 * @internal    @events:
 *				OnManagerMainFrameHeaderHTMLBlock
 */

$alwaysStay 	= isset( $alwaysStay ) ? $alwaysStay : 'Disabled';
$scrollFix 		= isset( $scrollFix ) ? $scrollFix : 'Disabled';
$cookieLifetime = isset( $cookieLifetime ) ? $cookieLifetime : 120;
$addToTopButton = isset( $addToTopButton ) ? $addToTopButton : 'Disabled';

$e = & $modx->Event;

if ( $e->name == "OnManagerMainFrameHeaderHTMLBlock" ) {

	$html = '<!-- ScrollFix Start -->';

	// PREPARE TOTOP-BUTTON
	$addToTopButtonCode = $addToTopButton != 'Disabled'
		? '$j("body").append("<a onclick=\"setScrollXY(0,0)\" style=\"position:fixed;z-index:999999;bottom:10px;right:10px;display:block;width:50px;height:50px;line-height:50px;font-size:30px;text-align:center;background-color:#89AD4A;color:#fff;border:1px solid #658F1A;border-radius:5px;cursor:pointer;text-decoration:none;\">&#x25B2;</a>");'
		: '';

	// PREPARE SCROLLFIX
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

			function createCookie(name,value,minutes) {
				if (minutes) {
					var date = new Date();
					date.setTime(date.getTime()+(minutes*60*1000));
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
				setScrollXY(x,y);
			}

			function unloadP(pageref){
				s=getScrollXY()
				createCookie(pageref+"x",s[0],'. $cookieLifetime .');
				createCookie(pageref+"y",s[1],'. $cookieLifetime .');
			}

			// DETERMINE GET-PARAMS
			var params={};
			window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(str,key,value){params[key] = value;});

			// GENERATE pageref AS COOKIE-ID
			var pageref = "ScrollFix_a"+params["a"];
			if(params["id"] != undefined) { pageref += "_id"+params["id"]; }

			$j( window ).unload(function() {
				unloadP(pageref);
			});
			$j( window ).load(function() {
				$j("#Button1 > a").removeAttr("href").css("cursor","pointer");	// REMOVE # FROM HREF TO AVOID SCROLL-TO-TOP
				loadP(pageref);
				'. $addToTopButtonCode .'
			});
        </script>';
	};

	// PREPARE ALWAYS-STAY
	if( $alwaysStay != 'Disabled' ) {
		$html .= '
		<script>
			$j(document).ready(function() {
				if($("stay")) { if(!$("stay").value) { $("stay").value=2; }}
			});
		</script>';
	};

	$html .= '
	<!-- ScrollFix End -->';

	$e->output($html);
}