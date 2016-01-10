//<?php
/**
 * ScrollFix
 * Remembers last scroll-position after pressing save-button, combined with "AlwaysStay"
 *
 * ScrollFix:  http://forums.modx.com/thread/92462/remember-position-of-resource-page-after-saving-in-evo
 * AlwaysStay: https://github.com/extras-evolution/AlwaysStay/blob/master/install/assets/plugins/AlwaysStay.tpl
 *
 * @category    plugin
 * @version     0.3
 * @date        10.01.2016
 * @author      dh@fuseit.de
 * @internal    @configuration:
 *              &jQueryCdn=Load jQuery from CDN (code.jquery.com);list;Enabled,Disabled;Disabled &alwaysStay=AlwaysStay;list;Enabled,Disabled;Disabled &scrollFix=ScrollFix;list;Enabled,Disabled;Disabled &fixTabHeader=Fix Tab-Header;list;Enabled,Disabled;Disabled &cookieLifetime=ScrollFix Cookie-Lifetime (Minutes);int;120 &addToTopButton=Add ToTop-Button;list;Enabled,Disabled;Disabled
 * @internal    @events:
 *              OnManagerMainFrameHeaderHTMLBlock
 */

$jQueryCdn      = isset( $jQueryCdn ) ? $jQueryCdn : 'Disabled';
$alwaysStay     = isset( $alwaysStay ) ? $alwaysStay : 'Disabled';
$scrollFix      = isset( $scrollFix ) ? $scrollFix : 'Disabled';
$cookieLifetime = isset( $cookieLifetime ) ? $cookieLifetime : 120;
$addToTopButton = isset( $addToTopButton ) ? $addToTopButton : 'Disabled';
$fixTabHeader	= isset( $fixTabHeader ) ? $fixTabHeader : 'Disabled';

$e = & $modx->Event;

if ( $e->name == "OnManagerMainFrameHeaderHTMLBlock" ) {

	$jDocReady = array();
	$jWinLoad = array();
	$jWinUnLoad = array();
	$jWinResize = array();

	// PREPARE HTML-OUTPUT
	$html = '<!-- ScrollFix Start -->';

	// TAKE CARE OF JQUERY
	if( $scrollFix != 'Disabled' || $alwaysStay != 'Disabled' || $addToTopButton != 'Disabled' || $fixTabHeader != 'Disabled') {
		// ADD JQUERY FROM LOCAL
		$html .= '
		<script>!window.jQuery && document.write(unescape(\'%3Cscript src="/assets/js/jquery.min.js"%3E%3C/script%3E\'))</script>';

		// ADD JQUERY FROM CDN
		if( $jQueryCdn != 'Disabled' ) {
		    $html .= '
		<script>!window.jQuery && document.write(unescape(\'%3Cscript src="https://code.jquery.com/jquery.min.js"%3E%3C/script%3E\'))</script>';
		};

		// ADD ERROR-MSG & NO-CONFLICT
		$html .= '
		<script>!window.jQuery && alert(\'ScrollFix: jQuery not found! Enable "Load jQuery from CDN" in Plugin-Configuration\');
				if( window.jQuery ) window.$j = jQuery.noConflict();
		</script>';
	};

	// PREPARE SCROLLFIX
	$scrollFixFunctions = '';
	if( $scrollFix != 'Disabled' ) {

		// PREPARE GET- / SET-SCROLL FUNCTIONS
		if( $fixTabHeader != 'Disabled' ) {

			// GET POSITION OF TAB-CONTAINER
			$getSetScrollFunctions = '
			function getScrollXY() {
				var x = 0, y = 0;
				var t = $j(".tab-pane").find(".tab-page:visible:first");
				x = t.scrollLeft();
				y = t.scrollTop();
				return [x, y];
			}

			function setScrollXY(x, y) {
				var t = $j(".tab-pane").find(".tab-page:visible:first");
				t.scrollLeft(x);
				t.scrollTop(y);
			}';

		} else {

			// GET POSITION OF WINDOW
			$getSetScrollFunctions = '
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
			}';
		};

		$scrollFixFunctions = $getSetScrollFunctions .'

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

			// GENERATE pageref AS COOKIE-ID
			var params={};
			window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(str,key,value){params[key] = value;});
			var pageref = "ScrollFix_a"+params["a"];
			if(params["id"] != undefined) { pageref += "_id"+params["id"]; }
		';

		$jWinLoad[] = '$j("#Button1 > a").removeAttr("href").css("cursor","pointer");';
		$jWinLoad[] = 'loadP(pageref);';
		$jWinUnLoad[] = 'unloadP(pageref);';

	};

	// PREPARE ALWAYS-STAY
	if( $alwaysStay != 'Disabled' ) {
		$jDocReady[] = 'if($j("#stay")) { if(!$j("#stay").val()) { $j("#stay").val(2); }};';
	};

	// PREPARE FIX TAB-HEADER $tabHeight
	$tabHeightFunctions = '';
	if( $fixTabHeader != 'Disabled' ) {
		$tabHeightFunctions = '
			function setTabPageHeight() {
				$j(".tab-page").css("box-sizing","content-box");
				var winHeight = $j(window).height();
				var tabsHeight = $j("h2.tab:first").height();
				var tabOffset = $j(".tab-pane:first").offset();
				var height = winHeight - tabsHeight - tabOffset.top * 2;
				$j(".tab-page").css("overflow","auto").css("height",height+"px");
			};
		';
		$jDocReady[] = '$j(".sectionBody").css("margin-bottom","0");';
		$jDocReady[] = 'setTabPageHeight();';
		$jDocReady[] = 'setTimeout(setTabPageHeight, 100);';
		$jWinResize[] = 'setTabPageHeight();';
	};

	// PREPARE ScrollToTop-BUTTON
	if( $addToTopButton != 'Disabled' ) {
		$jWinLoad[] = '$j("body").append("<a onclick=\"setScrollXY(0,0)\" style=\"position:fixed;z-index:999999;bottom:10px;right:35px;display:block;width:50px;height:50px;line-height:50px;font-size:30px;text-align:center;background-color:#89AD4A;color:#fff;border:1px solid #658F1A;border-radius:5px;cursor:pointer;text-decoration:none;\">&#x25B2;</a>");';
	};

	$html .= '
		<script>
			'. $scrollFixFunctions . $tabHeightFunctions .'
			$j(document).ready(function() {
				'. implode("\n				", $jDocReady ) .'
			});
			$j(window).load(function() {
				'. implode("\n				", $jWinLoad ) .'
			});
			$j(window).unload(function() {
				'. implode("\n				", $jWinUnLoad ) .'
			});
			$j(window).resize(function() {
				'. implode("\n				", $jWinResize ) .'
			});

		</script>';

	$html .= '
	<!-- ScrollFix End -->
';

	$e->output($html);
}