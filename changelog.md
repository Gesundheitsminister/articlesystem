Changelog
================================================================================================


Articlesystem plugin v1.7.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.07.01<br/>
Release: 15.11.2013

* FIXED: Wrong jQuery inclusion since Sefrengo v1.5.0
* CHANGED: Updated jQuery.datePicker to latest version
* CHANGED: Updated button style to backend skin from Sefrengo v1.5.0



Articlesystem plugin v1.6.10
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.10<br/>
Release: 17.08.2011

* ADDED: possibilty to show the initial editor of an article in article's list view 
* CHANGED: in article's list view, a column containing the free def. field "image" shows the 
		  image-thumbnail now (instead of path * ADDED: image name)
* FIXED: facebook export: fixed and rewritten (you have to create a facebook-app now,
          look into the Docs-folder for help)
* ADDED: facebook export: possibility to setup the receiver and sender of a posting
          (possibilty to write to facebook pages walls without changing the current user)
* CHANGED: facebook export: export-data saving removed but it's now possible (and useful) to
          setup a free def. field for the post-id 
* FIXED: module: html-entities output of free def. field filters selectbox values



Articlesystem plugin v1.6.9
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.09<br/>
Release: 23.05.2011

* FIXED: problems with free def. fields type "select/select2" on first selection/saving
* FIXED: htmlentities on title/description of free def. fields "file/link/image""



Articlesystem plugin v1.6.8
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.08<br/>
Release: 14.04.2011

* FIXED: twitter-export short-url url-parameter problem
* FIXED: twitter-export slashes problem
* NOTE: minor fixes and/or changes



Articlesystem plugin v1.6.7
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.07<br/>
Release: 24.12.2010

* ADDED: memorisation of list view's sorting state on article actions (like editing) 
* FIXED: missing language string in settings-section buttons
* NOTE: several other fixes and/or changes



Articlesystem plugin v1.6.6
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.06<br/>
Release: 02.10.2010

* ADDED: new option in article elements settings to set item limit for 
          file-/image-element's selectboxes 
* CHANGED: internal changes of images/files-selectboxes creation
* FIXED: select images/files via Resource-Browser



Articlesystem plugin v1.6.5
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.05<br/>
Release: 15.09.2010

* ADDED: ui-language translated (poorly) to english and implemented
* CHANGED: handling of deleting image, file, link, date elements on article editing
* FIXED: deleting image, file, link, date elements in new duplicated articles removes the elements in the source article
* FIXED: adding image, file, link, date elements removes last changes in article fields



Articlesystem plugin v1.6.4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.04<br/>
Release: 12.09.2010

* CHANGED: twitter-feature now uses OAuth-authentication (with PIN)
* ADDED: default-value-on-duplication option for all free def. fields with default value option
* FIXED: check-/radio-box default-values problems



Articlesystem plugin v1.6.3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.03<br/>
Release: 03.09.2010

* No changes on plugin



Articlesystem plugin v1.6.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.02<br/>
Release: 30.08.2010

* FIXED: plugin update-errors
* FIXED: module: {custom_data:x} for text-fields (nl2br removed)
* ADDED: php-notices reporting deactivation (facebook-/twitter-publishing)



Articlesystem plugin v1.6.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.01<br/>
Release: 25.08.2010

* ADDED: twitter-publishing with automatic short-url creation (used service: is.gd)
* ADDED: manual facebook publishing confirmation 
* CHANGED: improved wording in "special settings" section
* NOTE: several other fixes and/or changes



Articlesystem plugin v1.6.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.00<br/>
Release: 16.08.2010

* ADDED: facebook-/twitter-publishing feature (JSON-PHP-extension and PHP 5.x required!!!, in this version, only publishing to facebook userpages)
* ADDED: add images nor files via wysiwyg-editor with configurable resourcebrowser pathes, file-types etc.
* REMOVED: separately for every wysiwyg custom field and the standard text field
* ADDED: hidden feature: define readonly custom fields (text, textarea, datetime) * REMOVED: simply add "[readonly]" in the label name
* ADDED: backend list view sortable (sort direction) via column-head-links
* ADDED: column's width in backend list configurable
* ADDED: multiple articlesystem databases / multiple articlesystems feature 
* NOTE: many many other fixes and/or changes



Articlesystem plugin v1.4.0-beta2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.03.09 (1.4 beta 2)<br/>
Release: 17.03.2009

* ADDED: assign article categories to specific sf-usergroups (only users of choosen sf-usergroups can view, add and edit articles in specific categories)
* ADDED: popup calendar for free def. field type "date"
* ADDED: base documentation (phpdoc) for important classes and functions
* NOTE: several other fixes and/or changes



Articlesystem plugin v1.4.0-beta1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.03.08 (1.4 beta 1)<br/>
Release: 01.03.2009

* ADDED: free def. field types "Checkbox", "Radio", "Image", "Link", "File", "Info"
* ADDED: value||title input/output for free def. field types "select values",  "enter and select values" and 'Checkbox/Radio'-values input/output
* ADDED: image link option added
* ADDED: new article dates element (like images, files and links)
* ADDED: element alias option for free def. fields (e.g. {custom:1} = {alias})
* ADDED: file upload for free def. field types "Image" and "File"
* ADDED: customizable validation  of free def. fields via regular expressions and free definable error messages on article input
* ADDED: element title option for standard article fields
* ADDED: separate config-UI for article element's detail configuration
* ADDED: more configurable article list view (article fields to display, on/off-switches for
          search-box, custom-filters, time-range and so on ...)



Articlesystem plugin v1.2.4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.04<br/>
Release: ?

* No changes on plugin



Articlesystem plugin v1.2.3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.03<br/>
Release: ?

* No changes on plugin



Articlesystem plugin v1.2.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.02<br/>
Release: 23.09.2008

* FIXED: textareas width on disabled wysiwyg
* FIXED: problems with quotes & entities in article fields and category names
* NOTE: several other minor fixes and changes



Articlesystem plugin v1.2.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.01<br/>
Release: 12.09.2008

* REMOVED: unnecessary development directory



Articlesystem plugin v1.2.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.00<br/>
Release: 12.09.2008

* ADDED: improved article elements db-storing (element's language & client will be stored too)
* ADDED: possibility to use SnippetReplacement for free definable field types "select values" and "enter and select values"
* CHANGED: performance improvements * REMOVED: internal counting of article list items 
* CHANGED: improved install-/uninstall-scripts (now client dependet * REMOVED: but only for new or re-edited articles)



Articlesystem plugin v1.2.0-RC5
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.04 (1.2 RC5)<br/>
Release: 08.06.2008

* CHANGED: TinyMCE updated to version 3.0.9
* FIXED: new settings not available after 1.0.x update



Articlesystem plugin v1.2.0-RC4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.03 (1.2 RC4)<br/>
Release: 07.06.2008

* ADDED: posibility to copy article's contents from another language to the current 
	  language
* CHANGED: new created article's online state in other languages (always "offline" now)
* FIXED: label for "time range" in article's list view removed if time range is set to "all"
* FIXED: article's list view page switch previous button doesn't disappears on page 1 
* FIXED: module: &amp;-problem on generating url's
* FIXED: module: offline article's custom field select values no longer available



Articlesystem plugin v1.2.0-RC3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.02 (1.2 RC3)<br/>
Release: 14.05.2008

* ADDED: free definable field types date & time



Articlesystem plugin v1.2.0-RC2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.01 (1.2 RC2)<br/>
Release: 12.05.2008

* ADDED: element's sortindex takes affect in the settings screen too
* ADDED: free definable field's label is now visible as article element name in the settings
* FIXED: "enter and select values"-field type: '--* REMOVED: CHOOSE ---' not visible sometimes
* CHANGED: layout of the "enter and select values"-field type on editing an article



Articlesystem plugin v1.2.0-RC1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.00 (1.2 RC1)<br/>
Release: 28.04.2008

* ADDED: posibility to archive/dearchive articles / sepeate article archive main view
* ADDED: switch articles on-/offline simultaneously 
* ADDED: new custom field type: "enter and select values" (you can enter a custom field value 
	  in the article mask that let the items grow in the selectbox for later selection in
	  other articles)
* ADDED: main area for category management (integrated into SF rights management)
* ADDED: if a SF project language is added or deleted the language version for exisiting 
          categories will be added or deleted too
* CHANGED: icon order changed in article list
* CHANGED: settings areas splited into single views
* CHANGED: TinyMCE updated to version 3.0.7 (gzip-loading-compression added)
* FIXED: online/offline switch no longer changes the modified (lastedit) date of an article



Articlesystem plugin v1.0.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.00.01<br/>
Release: 22.02.2008

* FIXED: "online"-state for date-only dependent articles



Articlesystem plugin v1.0.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.00.00<br/>
Release: 21.02.2008

* ADDED: new "online"-icons for time dependent articles
* FIXED: file-/link-titles layout problem
* FIXED: article's title input validation
* ADDED: module: page number var for article detail view / (back to) list view links
* CHANGED: module: old vars renamed



Articlesystem plugin v1.0.0-RC1
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.12 (1.0 RC 1) <br/>
Release: 28.01.2008

* ADDED: manual link input recognizes more 'protocols' now (e.g. mailto:) 
* ADDED: TinyMCE's (wysiwyg-editor) vertical scale feature and inline popups activated
* FIXED: ui corrections on IE(7)
* FIXED: plugin uninstallation * REMOVED: all article system related should be removed from db now
* CHANGED: image-buttons for invert selection and delete selection in list view



Articlesystem plugin v1.0.0-beta3
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.11 (1.0 beta 3)<br/>
Release: 23.01.2008

* ADDED: confirmations for taking over titles from the resource browser
* CHANGED: wysiwyg editor height
* FIXED: custom fields validation
* FIXED: output of quotation marks in form fields
* FIXED: some gui css stuff on IE7 (nevertheless not perfect atm)
* FIXED: no longer creation of an unnecessary SF user-right on installation



Articlesystem plugin v1.0.0-beta2
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.10 (1.0 beta 2)<br/>
Release: 21.01.2008

* FIXED: free definable fields 6-10 not editable
* FIXED: free definable field type "selectable values" * REMOVED: values not selectable
* FIXED: several other small fixes



Articlesystem plugin v1.0.0-beta1
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.09 (1.0 beta 1)<br/>
Release: 19.01.2008

* first offical release -- test it!!!


