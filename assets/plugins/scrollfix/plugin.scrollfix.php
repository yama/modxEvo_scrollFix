//<?php
/**
 * ScrollFix
 * Offers several features to optimize workflow within Modx Evolution Manager
 *
 * Latest version on Github: https://github.com/Deesen/modxEvo_scrollFix/
 *
 * @category    plugin
 * @version     0.42
 * @date        29.01.2016
 * @author      dh@fuseit.de
 * @internal    @properties &scrollFix=ScrollFix;list;Enabled,Disabled;Disabled &fixTabHeader=Fix Tab-Header;list;Enabled,Disabled;Disabled &cookieLifetime=ScrollFix Cookie-Lifetime (Minutes);int;120 &addToTopButton=Add ToTop-Button;list;Enabled,Disabled;Disabled &addToTopCustom=ToTop-Button Custom-CSS (replace semicolon by |);textarea;bottom:55px|right:40px|width:40px|height:40px|line-height:40px|font-size:20px|background-color:#89AD4A|color:#fff|border:1px solid #658F1A|border-radius:5px|border-bottom-left-radius:0px|border-bottom-right-radius:0px &addToTopLabel=ToTop-Button Custom-Label;text &addToBottomButton=Add ToBottom-Button;list;Enabled,Disabled;Disabled &addToBottomCustom=ToBottom-Button Custom-CSS (replace semicolon by |);textarea;bottom:15px|right:40px|width:40px|height:40px|line-height:40px|font-size:20px|background-color:#89AD4A|color:#fff|border:1px solid #658F1A|border-radius:5px|border-top-left-radius:0px|border-top-right-radius:0px &addToBottomLabel=ToBottom-Button Custom-Label;text &extSaveButtons=Extend Save-Button;list;Enabled,Disabled;Disabled &alwaysStay=AlwaysStay;list;Enabled,Disabled;Disabled &jQueryCdn=Load jQuery from CDN (code.jquery.com);list;Enabled,Disabled;Disabled
 * @internal    @events OnManagerMainFrameHeaderHTMLBlock
 */

include MODX_BASE_PATH . 'assets/plugins/scrollfix/bootstrap.php';
