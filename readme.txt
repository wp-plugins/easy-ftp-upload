=== Easy FTP Upload ===
Contributors: pandorawombat
Donate link: http://www.bucketofwombats.com/easy-ftp-upload-for-wordpress/
Tags: upload, ftp, large files
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 2.6

Allow end users to upload files via FTP - accommodates larger files. Ideal for printing companies or others who require large graphic files.

== Description ==

Allow end users to upload files via FTP - accommodates larger files. Ideal for printing companies or others who require large graphic files. Originally conceived to circumvent the upload file size limitations imposed on sites with shared hosting.

NOTE: If you are upgrading from a 1.x version of this plugin, you will need to make a slight change to the implementation. There is an admin menu in the Settings area, where you will specify the server and email info. The shortcode should then only say: [easy_ftp_upload] (The other parameters will no longer be needed.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Either install from the Add New button in your 'Plugins' menu...
1. OR unzip 'Easy-FTP-Upload.zip' and load its entire folder directly to the '/wp-content/plugins/' directory...
1. Then activate the plugin through the 'Plugins' menu in WordPress...
1. Use shortcode described below to place the plugin in posts or pages.

== Frequently Asked Questions ==

= What does it do and how do I use it? =

This is version 2.1, and the functionality is fairly simple, although I did try to construct this in a way that would allow for future expansion, while currently allowing some easy basic customizations. The formatting attributes of the basic upload form are controlled by the plugin's separate .css file, for instance.

In case you want to alter things on a more advanced level, the form itself is created by importing a separate .html file included with this plugin (actually it is an HTML fragment, but it can still easily be edited with most HTML-aware code editors - or even to an extent in some WYSIWYG editors). The javascript code that is used to validate the form is included in a separate .js file. Finally, I tried to write the main .php file using verbose commenting where possible.

Since this plugin may allow access to the FTP account login info that you supply to any end user who is allowed to access the page or post where you put it, for security reasons I highly recommend that you create a separate FTP login account on your server to be used exclusively with this plugin. However, with version 2.x, I have added an admin panel to specify this information rather than requiring it to be supplied in shortcode - this makes it much less likely that someone could "harvest" this login info. Using a login exclusively designed for this plugin would allows you to limit uploads if necessary by assigning a quota for this specific username account. You should also make a folder on your FTP site exclusively to be used by this account, and assign this as the root directory for the account. For example, you could create a special login called "webuploads@domain.com" and point it to a home directory on the FTP site called "~webuploads."

You then control the plugin's implementation and behavior using the admin menu and shortcode. As of version 2.0, there are no longer any attributes to be set via shortcode, since the account settings info has been moved to an admin menu.

Shortcode to use:
[easy_ftp_upload]

This plugin will find the default folder you have created for this FTP account, and will then create a folder within it named by either the sender's Company Name (if one was specified) or Contact Name (if no separate Company Name given). Of course, whichever of these is used will first pass through a format scrubber to make sure the name is "legal" on the server (e.g. "Harry's Special Furniture" will create a directory called "harrys_special_furniture"). If the person has uploaded files before using the exact same Contact or Company info, the file will go into the folder that already exists. At this time, the plugin handles duplicates by overwriting files with the same name, but I intend to add functionality to allow for ìversioningî in a future edition, when/if I ever get time, and if there turns out to be a demand for it.

In order for the form to validate and submit, I required the Contact Name field to be filled out, but not the Company Name field. I did this because some clients of the print shop were individuals rather than companies. If you wanted to force the entry of a Company Name, you could edit the .html file for that "input" to have a class of "EFU_text_req" instead of the "EFU_text" class it uses by default (and if you really want to be thorough, you'd probably want to alter the HTML describing its label to include that red asterisk).

Anyway, If you find this plugin useful, I hope you'll consider donating in order to help me support my evil open-source coding habit. Bug reports and suggestions for future added functionality are also appreciated. But this is my first plugin, so I beg you to be gentle with any criticism. Thank you.

== Screenshots ==

1. The upload form.

== Changelog ==

= 2.6 =
* Moved the passive mode command earlier in the routine to fix some permissions issues on certain servers.

= 2.5 =
* Changed the php connect function to explicitly choose port 21, even though port 21 is the default. This may solve problems on some systems that do not recognize the default.
* Added code to attempt connecting with several different formats of whatever settings you provide it, since different FTP servers require attributes passed in different ways.

= 2.0 =
* Added an admin panel to store the FTP and email data - this makes it much more secure than exposing this information in a shortcode. Also fixed an anomaly with the notification email header - previously, it was accidentally hard-coded with a specific value related to the original users of the code. Also made the confirmation message more prominent and red in color - and this can also be changed to suit your needs by editing the p.EFU_notify class in the .css file.

= 1.3 =
* Fixed problems with the load order of the .css file. Fixed improperly formed .css tags. Resolved a missing path issue related to case-sensitive naming. Updated the readme file to allow copying and pasting of a shortcode example all on one line - trying to edit the multiline one included with previous versions was producing shortcode that didn't work due to an encoding problem. 

= 1.2 =
* Corrected a code typo.

= 1.1 =
* Corrected some code instructions.


== About ==

I didn't start out intending to write a plugin for WordPress. But then I needed to give the clients of a print shop the ability to upload large files via the print shop's site without having to have any knowledge of - or software for - FTP. And the print shop didn't want to have to pay any money for this ability. "Calling all open-source fiends"

I initially came across many plugins that would allow uploading of files. There was a catch, however - the normal upload method used is not FTP-based, and is subject to the file size limits set in the php.ini file. So if you have shared hosting which doesn't allow you to control this limit - yet have people who need to upload large files - you are most likely SOL with these plugins.

Then I found one plugin that claimed it could optionally be used to send via FTP. I knew this is exactly what I needed, but I never could get that plugin to work right. It seemed as though the "FTP" option might have been added as an afterthought and possibly never fully debugged, because I simply could not get it to work. At first I spent a couple of hours poring through the code of that plugin, trying to figure out why it didn't work. Then, being the impatient soul that I am, it occurred to me that it would be faster and less frustrating to write my own plugin focusing on the functionality I wanted than to try to wade through and hack a plugin that was cluttered with all sorts of other functionality I didn't need.

Thus Easy FTP Upload was born.