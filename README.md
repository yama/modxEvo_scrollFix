# modxEvo_scrollFix
Remembers last scroll-position after pressing Save-button, or leaving a page in Modx Evolution Manager

#### Version History
0.1 Initial release
0.2 Changed: Events to single-event "OnManagerMainFrameHeaderHTMLBlock"
    Changed: Cookie-ID depends now on GET-Params "a" and "id" (enables position-fix on every Action/Id/Subpage separately)
    Added: Optional Cookie-Lifetime setting
    Added: Optional "Scroll to Top"-Button