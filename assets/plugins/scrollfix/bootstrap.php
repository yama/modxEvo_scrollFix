<?php

/** @var DocumentParser $modx */
if ( $modx->event->name !== 'OnManagerMainFrameHeaderHTMLBlock' ) {
    return;
}

if (empty($scrollFix) || $scrollFix === 'Disabled') {
    $scrollFix = false;
}
if (empty($fixTabHeader) || $fixTabHeader === 'Disabled') {
    $fixTabHeader = false;
}
if (!isset($cookieLifetime)) {
    $cookieLifetime = 120;
}
if (empty($addToTopButton) || $addToTopButton === 'Disabled') {
    $addToTopButton = false;
}
if (!isset($addToBottomButton)) {
    $addToBottomButton = 'Disabled';
}
if (!isset($addToTopCustom)) {
    $addToTopCustom = 'bottom:55px|right:40px|width:40px|height:40px|line-height:40px|font-size:20px|background-color:#89AD4A|color:#fff|border:1px solid #658F1A|border-radius:5px';
}
if (!isset($addToBottomCustom)) {
    $addToBottomCustom = 'bottom:15px|right:40px|width:40px|height:40px|line-height:40px|font-size:20px|background-color:#89AD4A|color:#fff|border:1px solid #658F1A|border-radius:5px';
}
if (!isset($addToTopLabel)) {
    $addToTopLabel = '&#x25B2;';
}
if (!isset($addToBottomLabel)) {
    $addToBottomLabel = '&#x25BC;';
}
if (!isset($extSaveButtons)) {
    $extSaveButtons = 'Disabled';
}
if (empty($alwaysStay) || $alwaysStay === 'Disabled') {
    $alwaysStay = false;
}
if (empty($jQueryCdn) || $jQueryCdn === 'Disabled') {
    $jQueryCdn = false;
}

$jDocReady = array();
$jWinLoad = array();
$jWinUnLoad = array();
$jWinResize = array();

// DETERMINE MODX v1.1
$version = $modx->getVersionData();
$modx11 = substr($version['version'],0,3) === '1.1';

// PREPARE HTML-OUTPUT
$html = '<!-- ScrollFix Start -->';

// TAKE CARE OF JQUERY
if( $scrollFix || $alwaysStay || $addToTopButton || $fixTabHeader) {
    $html .= sprintf(
        "<script>!window.jQuery && document.write(unescape('%s'))</script>",
        urlencode(
            sprintf(
                '<script src="%s"></script>',
                !$jQueryCdn ? 'https://code.jquery.com/jquery.min.js' : MODX_BASE_URL . 'assets/js/jquery.min.js'
            )
        )
    );

    // ADD ERROR-MSG & NO-CONFLICT
    $html .= '
        <script>!window.jQuery && alert(\'ScrollFix: jQuery not found! Enable "Load jQuery from CDN" in Plugin-Configuration\');
            if( window.jQuery ) window.$j = jQuery.noConflict();
        </script>';
};

// PREPARE SCROLLFIX
$scrollFixFunctions = '';
if( $scrollFix ) {
    // PREPARE GET- / SET-SCROLL FUNCTIONS
    if ($fixTabHeader) {
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
                if( $j(".tab-pane").length > 0 ) {
                    var t = $j(".tab-pane").find(".tab-page:visible:first");
                    t.scrollLeft(x);
                    t.scrollTop(y);
                } else {
                    window.scrollTo(x, y);
                };
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

    if (!$modx11 || $fixTabHeader) {
        $scrollFixFunctions = $getSetScrollFunctions . '

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
                createCookie(pageref+"x",s[0],' . $cookieLifetime . ');
                createCookie(pageref+"y",s[1],' . $cookieLifetime . ');
            }

            // GENERATE pageref AS COOKIE-ID
            var params={};
            window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(str,key,value){params[key] = value;});
            var pageref = "ScrollFix_a"+params["a"];
            if(params["id"] != undefined) { pageref += "_id"+params["id"]; }
        ';

        $jWinLoad[] = 'loadP(pageref);';
        $jWinUnLoad[] = 'unloadP(pageref);';
    };
};

// PREPARE ALWAYS-STAY
if( $alwaysStay && $extSaveButtons === 'Disabled' ) {
    $jDocReady[] = 'if($j("#stay")) { if(!$j("#stay").val()) { $j("#stay").val(2); }};';
};

// PREPARE FIX TAB-HEADER AND DISABLE FOR SPECIFIC ACTIONS
$tabHeightFunctions = '';
if( $fixTabHeader ) {
    $tabHeightFunctions = '
            function setTabPageHeight() {
                if ($j(".tab-page").length > 0) {
                    $j(".tab-page").css("box-sizing","content-box");
                    $j(".sectionBody > p:first").html("");
                    var winHeight = $j(window).height();
                    var tabsHeight = $j("h2.tab:first").height();
                    var tabOffset = $j(".tab-pane:first").offset();
                    if (typeof tabOffset.top != "undefined") { var tabOffsetTop = tabOffset.top * 2; } else { var tabOffsetTop = 0; };
                    var height = winHeight - tabsHeight - tabOffsetTop;
                    $j(".tab-page").css("overflow","auto").css("max-height",height+"px");
                };
            };
        ';
    $jDocReady[] = '$j(".sectionBody").css("margin-bottom","0");';
    $jDocReady[] = 'setTabPageHeight();';
    $jDocReady[] = 'setTimeout(setTabPageHeight, 100);';
    $jWinResize[] = 'setTabPageHeight();';
};

// PREPARE ScrollTo-BUTTONS
$buttonDefaultStyle = 'position:fixed;z-index:99999;display:block;text-align:center;cursor:pointer;text-decoration:none;';
if( $addToTopButton ) {
    $toTopFunc = ( $modx11 && !$fixTabHeader ) ? 'window.scrollTo(0,0)' : 'setScrollXY(0,0)';
    $addToTopCustom = str_replace('|',';',$addToTopCustom);
    $jWinLoad[] = '$j("body").append("<a onclick=\"'. $toTopFunc .'\" style=\"'. $buttonDefaultStyle . $addToTopCustom .'\">'. $addToTopLabel .'</a>");';
};
if( $addToBottomButton !== 'Disabled' ) {
    if ($modx11 && !$fixTabHeader) {
        $toBottomFunc = 'window.scrollTo(0,document.body.scrollHeight)';
    } else {
        $toBottomFunc = 'setScrollXY(0,1e10)';
    }
    $addToBottomCustom = str_replace('|',';',$addToBottomCustom);
    $jWinLoad[] = '$j("body").append("<a onclick=\"'. $toBottomFunc .'\" style=\"'. $buttonDefaultStyle . $addToBottomCustom .'\">'. $addToBottomLabel .'</a>");';
};

// PREPARE EXTENDED SAVE-BUTTON AND DISABLE FOR SPECIFIC ACTIONS
if( $extSaveButtons !== 'Disabled' ) {
    global $_style, $_lang;
    switch( $_GET['a'] ) {
        case 11:
        case 12:
        case 87:
        case 88:
            $jsFunc = 'document.userform.save.click();';
            break;
        default:
            $jsFunc = "document.mutate.save.click();saveWait('mutate');";
    };
    $jDocReady[] =
        'if( $j("#stay").length > 0 ) {
                    $j("#stay").hide();
                    $j("#stay").val(2);
                    $j("#Button1 a").attr("title","'. $_lang["save"] .' + '. $_lang["stay"] .'");
                    $j("#Button1").append("<a style=\"cursor: pointer;padding-left:1em;padding-right:1em;border-top-right-radius:0px;border-bottom-right-radius:0px;\" onclick=\"$j(\'#stay\').val(\'1\'); documentDirty=false; '. $jsFunc .'\" title=\"'. $_lang["save"] .' + '. $_lang["stay_new"] .'\"><img src=\"'. $_style["icons_new_document"] .'\" /></a>");
                    $j("#Button1").append("<a style=\"cursor: pointer;padding-left:1em;padding-right:1em;margin-right:1em;margin-left:-1px;border-top-left-radius:0px;border-bottom-left-radius:0px;\" onclick=\"$j(\'#stay\').val(\'\'); documentDirty=false; '. $jsFunc .'\" title=\"'. $_lang["save"] .' + '. $_lang["close"] .'\"><img src=\"'. $_style["icons_cancel"] .'\" /></a>");
                };';
};

// FIX JUMP TO TOP
if( $scrollFix || $modx11 ) {
    $jWinLoad[] = '$j("#Button1 > a").removeAttr("href").css("cursor","pointer");';
};

$html .= '
        <script>
            '. $scrollFixFunctions . $tabHeightFunctions;
if (!empty($jDocReady)) {
    $html .= '
            $j(document).ready(function() {
                ' . implode("\n                ", $jDocReady) . '
            });';
}
if (!empty($jWinLoad)) {
    $html .= '
            $j(window).load(function() {
                ' . implode("\n                ", $jWinLoad) . '
            });';
}
if (!empty($jWinUnLoad)) {
    $html .= '
            $j(window).unload(function() {
                ' . implode("\n                ", $jWinUnLoad) . '
            });';
}
if (!empty($jWinResize)) {
    $html .= '
            $j(window).resize(function() {
                ' . implode("\n                ", $jWinResize) . '
            });';
}
$html .= '
        </script>';

$html .= '
    <!-- ScrollFix End -->
';

$modx->event->output($html);
