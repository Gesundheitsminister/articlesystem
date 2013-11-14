Changelog
================================================================================================


Articlesystem main module v1.7.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.07.01<br/>
Release: 14.11.2013

* NOTE: No changes on module, just updated modules version number



Articlesystem main module v1.6.10
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.10<br/>
Release: 17.08.2011

* FIXED: html-entities output of free def. field filters selectbox values



Articlesystem main module v1.6.9
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.10<br/>
Release: 23.05.2011

* FIXED: wrong/missing first selectbox entry config-option of free def. field filters
* ADDED: new template-global elements {idcatside_url} {idcatside_page_url} (not available in detail-view of the separate output mode)



Articlesystem main module v1.6.8
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.08<br/>
Release: 14.04.2011

* No changes on module



Articlesystem main module v1.6.7
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.07<br/>
Release: 24.12.2010

* FIXED: month switch in calendar view
* FIXED: {hide_on_last_item}{/hide_on_last_item} doesn't work in some cases
* NOTE: several other fixes and/or changes



Articlesystem main module v1.6.6
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.07<br/>
Release: 02.10.2010

* CHANGED: improved multiple articlesystem database recognition 
* FIXED: problem with category-side-routing in article's list view



Articlesystem main module v1.6.5
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.05<br/>
Release: 15.09.2010

* No changes on module



Articlesystem main module v1.6.4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.04<br/>
Release: 12.09.2010

* No changes on module



Articlesystem main module v1.6.3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.03<br/>
Release: 03.09.2010

* ADDED: new fully template based page navigation {page_nav_adv}
* ADDED: new article list elements {items_count:all} (or {items_count}), {items_count:page}, {items_count:oddeven} 
* CHANGED: element templates pane splittet into 2 panes



Articlesystem main module v1.6.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.02<br/>
Release: 30.08.2010

* FIXED: {custom_data:x} for text-fields (nl2br removed)



Articlesystem main module v1.6.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.01<br/>
Release: 25.08.2010

* No changes on module



Articlesystem main module v1.6.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.06.00<br/>
Release: 16.08.2010

* ADDED: additional date-/time-format options/elements
* ADDED: new display mode 'Calendar' -> use custom-fields (date/datetime) to define articles as events and use a calendar-view as filter
* ADDED: multiple articlesystem databases / multiple articlesystems feature 
* NOTE: many many other fixes and/or changes



Articlesystem main module v1.4.0-beta2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.03.09 (1.4 beta 2)<br/>
Release: 17.03.2009

* ADDED: {label} element (free def. fields title) for custom-filters label setting 
* ADDED: special option for custom filters: selectable entries depending on the selection of other filters
* NOTE: several other fixes and/or changes



Articlesystem main module v1.4.0-beta1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.03.08 (1.4 beta 1)<br/>
Release: 01.03.2009

* ADDED: free def. field types "Checkbox", "Radio", "Image", "Link", "File", "Info"
* ADDED: value||title input/output for free def. field types "select values",  "enter and select values" and 'Checkbox/Radio'-values input/output
* ADDED: image link option added
* ADDED: new article dates element (like images, files and links)
* ADDED: element alias option for free def. fields (e.g. {custom:1} = {alias})
* ADDED: frontend/backend if-statements
* ADDED: new display mode 'Teaser-Mode' -> shows articles by internal category/side input or selection via free def. field.
* ADDED: new really specific free def. fields sf-cat/page-tree elements * REMOVED: maybe useful in association with the new 'Teaser-Mode'
* ADDED: additional sql-where-clause option 
* NOTE: plugin & several other minor fixes and changes



Articlesystem main module v1.2.4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.04<br/>
Release: ?

* CHANGED: some changes of the category db-storing of {set_catgory_form}



Articlesystem main module v1.2.3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.03<br/>
Release: ?

* ADDED: new element {set_category_form} * REMOVED: same as {category_form} but only available in backend view to set up a single category for listing per SF-side
* NOTE: portions of base module code / base functions extracted in separate include files



Articlesystem main module v1.2.2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.02<br/>
Release: 23.09.2008

* ADDED: new element {idlang} and idlang if-/if_not-statement in main-template
* ADDED: new date & time elements and config options
* FIXED: unique id's of help-text divs
* NOTE: several other minor fixes and changes



Articlesystem main module v1.2.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.02.00<br/>
Release: 12.09.2008

* ADDED: if-/if_not-statement with value comparison (e.g. {if_text:1=ABC}...{/if_text:1=ABC} )
* CHANGED: new main template elements: {page_nav_next} {page_nav_prev} {page_nav_first} {page_nav_last} {pages_current} {pages_total}
* CHANGED: template's help texts
* ADDED: possibility to use SnippetReplacement for free definable field types "select values" and "enter and select values"
* CHANGED: performance improvements * REMOVED: internal counting of article list items



Articlesystem main module v1.2.0-RC5
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.04 (1.2 RC5)<br/>
Release: 08.06.2008

* No changes on module



Articlesystem main module v1.2.0-RC4
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.03 (1.2 RC4)<br/>
Release: 07.06.2008

* FIXED: &amp;-problem on generating url's
* FIXED: offline article's custom field select values no longer available



Articlesystem main module v1.2.0-RC3
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.02 (1.2 RC3)<br/>
Release: 14.05.2008

* No changes on module



Articlesystem main module v1.2.0-RC2
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.01 (1.2 RC2)<br/>
Release: 12.05.2008

* ADDED: optional attributes setting for page navigation links
* FIXED: problem with page navigation and custom filters
* CHANGED: page navigation uses paginator-php-class now



Articlesystem main module v1.2.0-RC1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.01.00 (1.2 RC1)<br/>
Release: 28.04.2008

* ADDED: sorting link elements in the body template for the article fields title, teaser, text, custom1-10,startdate,endatedate (see the module default config or template help text for more info)
* ADDED: new {customfilter_form:x} element and functionality (now you can use the custom field data in custom field type "select values" to filter the articles list view - like the frontend categories form element)
* CHANGED: page switch's number of items configuration (it's now an input field)
* CHANGED: default templates changed to show some of the new features
* ADDED: list view and detail view at the same time
* FIXED: search-functionality didn't work correctly
* FIXED: wrong time range end date in some cases
* FIXED: problem with time dependent display of articles with an end date but no end time (you have to edit and save old articles once that this fix takes affect)



Articlesystem main module v1.0.1
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.00.01<br/>
Release: 22.02.2008

* CHANGED: old vars renamed (really!)


Articlesystem main module v1.0.0
------------------------------------------------------------------------------------------------
Internal versionnumber: 01.00.00<br/>
Release: 21.02.2008

* ADDED: page number var for article detail view / (back to) list view links
* CHANGED: old vars renamed



Articlesystem main module v1.0.0-RC1
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.12 (1.0 RC 1) <br/>
Release: 28.01.2008

* ADDED: depending on some settings some options will be hidden/shown now
* FIXED: wrong page navigation calculation on time dependent / no longer visible articles
* CHANGED: url creation now xhtml-valid (&amp;)
* CHANGED: page navigation in detail view no longer visible
* NOTE: module * ADDED: plugin: a lot of small changes and improvements



Articlesystem main module v1.0.0-beta3
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.11 (1.0 beta 3)<br/>
Release: 23.01.2008

* ADDED: target idcatside option in category-, month* REMOVED: & search-form
* ADDED: article dependent year_select
* CHANGED: article dependent month_select
* CHANGED: code optimization * REMOVED: 20% less code
* FIXED: label<>form-field assignment (no id's were defined in form-fields)
* FIXED: if-/if_not-statement output problems
* FIXED: article elements output problems



Articlesystem main module v1.0.0-beta2
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.10 (1.0 beta 2)<br/>
Release: 21.01.2008

* ADDED: {category_links}-element & -template (to create something like a category-navigation)
* ADDED: module identifier string
* FIXED: {images} {files} {links} in list view
* FIXED: several other small fixes



Articlesystem main module v1.0.0-beta1
------------------------------------------------------------------------------------------------
Internal versionnumber: 00.09.09 (1.0 beta 1)<br/>
Release: 19.01.2008

* first offical release -- test it!!!


