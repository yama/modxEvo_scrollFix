# modxEvo_scrollFix
Remembers last scroll-position after pressing Save-button, or leaving a page in Modx Evolution Manager  

#### Tested with
- Modx Evolution 1.0.9 - 1.0.15
- ManagerManager 0.6.2
- MultiTV 2.0

#### Version History
##### v0.3 - *2016-01-10*
- New Feature "Fix Tab-Header": Moves Scrollbar from Window to Tab-Pane so Tab-Headers are always within viewport and accessible. Also remembers last Scroll-Position if ScrollFix is enabled! 
- Added: Alert-Message if jQuery is not loaded
- Added: Optionally load jQuery from code.jquery.com
- Optimized Code

##### v0.21 - *2016-01-04*
- Bugfix: Remembering position in Chunks, Snippets, Plugins after pressing "Save"-Button...
- Bugfix: Typing-Error in default configuration-string
- Changed: Cookie-Liftetime Days to Minutes
  
##### v0.2 - *2015-12-31*
- Changed: Events to single-event "OnManagerMainFrameHeaderHTMLBlock"  
- Changed: Cookie-ID depends now on GET-Params "a" and "id" (enables position-fix on every Action/Id/Subpage separately, except Modules)
- Added: Optional Cookie-Lifetime setting
- Added: Optional "Scroll to Top"-Button
  
##### v0.1 - *2015-11-21*
- Initial release