# modxEvo_scrollFix
Remembers last scroll-position after pressing Save-button, or leaving a page in Modx Evolution Manager
  
#### Version History
##### v0.21 - *2016-01-04*
- Bugfix: Remembering position in Chunks, Snippets, Plugins after pressing "Save"-Button...
- Bugfix: Typing-Error in default configuration-string
- Changed: Cookie-Liftetime Days to Minutes
  
##### v0.2 - *2015-12-31*
- Changed: Events to single-event "OnManagerMainFrameHeaderHTMLBlock"  
- Changed: Cookie-ID depends now on GET-Params "a" and "id" (enables position-fix on every Action/Id/Subpage separately)
- Added: Optional Cookie-Lifetime setting
- Added: Optional "Scroll to Top"-Button
  
##### v0.1 - *2015-11-21*
- Initial release