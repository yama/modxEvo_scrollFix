# modxEvo_scrollFix
Offers several features to optimize workflow within Modx Evolution Manager:

- "ScrollFix": Remembers last scroll-position after pressing Save-button, or leaving a page (for Modx 1.0)
- "Fix Tab-Header": Moves Scrollbar from Window to Tab-Pane so Tab-Headers are always within viewport and accessible. Also remembers last Scroll-Position if ScrollFix is enabled! 
- "Add ToTop-Button": Scrolls to window-top, or tab-header top if fixed. Can be styled via custom CSS
- "Extend Save-Button": Splits Save-Button + Dropdown into 3 separate Save-Buttons
- "Load jQuery from CDN": In case jQuery can not be found, this option acts as fallback to load jQuery from code.jquery.com

#### Tested with
- Modx Evolution 1.0.5 - 1.1
- ManagerManager 0.6.2 (mm_createTab, mm_moveFieldsToTab)
- MultiTV 2.0

#### Version History
##### v0.4 - *2016-01-15*
- New Feature "Extend Save-Button"
- New Feature "ToTop-Button Custom": Custom CSS and label to change default styling
- Compatible with new Modx 1.1 internal ScrollFix-Feature: Plugin´s ScrollFix without "Fix Tab-Header" enabled gets automatically disabled in Modx 1.1 for now

##### v0.3 - *2016-01-10*
- New Feature "Fix Tab-Header"
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