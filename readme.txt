=== Easy FTP Upload ===
Contributors: pandorawombat
Donate link: http://www.bucketofwombats.com/easy-ftp-upload-for-wordpress/
Tags: upload, ftp, large files
Requires at least: 3.0
Tested up to: 3.0.5
Stable tag: 1.0

Allow end users to upload files via FTP - accommodates larger files. Ideal for printing companies or others who require large graphic files.

== Description ==

Allow end users to upload files via FTP - accommodates larger files. Ideal for printing companies or others who require large graphic files. Originally conceived to circumvent the upload file size limitations imposed on sites with shared hosting.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Either install from the Add New button in your 'Plugins' menu...
1. OR unzip `Easy-FTP-Upload.zip` and load its entire folder directly to the `/wp-content/plugins/` directory...
1. Then activate the plugin through the 'Plugins' menu in WordPress...
1. Use shortcode described below to place the plugin in posts or pages.

== Frequently Asked Questions ==

= What does it do and how do I use it? =

Please see the detailed information section below.

== Screenshots ==

1. The upload form.

== Arbitrary section ==

I didn’t start out intending to write a plugin for WordPress. But then I needed to give the clients of a print shop the ability to upload large files via the print shop’s site without having to have any knowledge of – or software for – FTP. And the print shop didn’t want to have to pay any money for this ability. “Calling all open-source fiends…”

I initially came across many plugins that would allow uploading of files. There was a catch, however – the normal upload method used is not FTP-based, and is subject to the file size limits set in the php.ini file. So if you have shared hosting which doesn’t allow you to control this limit – yet have people who need to upload large files – you are most likely SOL with these plugins.

Then I found one plugin that claimed it could optionally be used to send via FTP. I knew this is exactly what I needed, but I never could get that plugin to work right. It seemed as though the “FTP” option might have been added as an afterthought and possibly never fully debugged, because I simply could not get it to work. At first I spent a couple of hours poring through the code of that plugin, trying to figure out why it didn’t work. Then, being the impatient soul that I am, it occurred to me that it would be faster and less frustrating to write my own plugin focusing on the functionality I wanted than to try to wade through and hack a plugin that was cluttered with all sorts of other functionality I didn’t need.

Thus Easy FTP Upload was born.

This is version 1.0, and the functionality is fairly basic, although I did try to construct this in a way that would allow for future expansion, while currently allowing some easy basic customizations. The formatting attributes of the basic upload form are controlled by the plugin’s separate .css file, for instance.

In case you want to alter things on a more advanced level, the form itself is created by importing a separate .html file included with this plugin (actually it is an HTML fragment, but it can still easily be edited with most HTML-aware code editors – or even to an extent in some WYSIWYG editors). The javascript code that is used to validate the form is included in a separate .js file. Finally, I tried to write the main .php file using verbose commenting where possible.

Since this plugin will expose access to the FTP account login info that you supply to any end user who is allowed to access the page or post where you put it, for security reasons I highly recommend that you create a separate FTP login account on your server to be used exclusively with this plugin. This avoids exposing other usernames and passwords which you might not want to be exposed. It also allows you to limit uploads if necessary by assigning a quota for this specific username account. You should also make a folder on your FTP site exclusively to be used by this account, and assign this as the root directory for the account. For example, you could create a special login called “webuploads@” [domain.com] and point it to a home directory on the FTP site called “~webuploads.”

You then control the plugin’s implementation and behavior using shortcode. As of version 1.0, there are only four attributes to be set via shortcode, and all of them are required. Within the braces [ ], you need only insert the word:

[easy_ftp_upload

then follow with these arguments (each separated by a space)

server="domain.com"
the domain of your ftp server

ftp_user_name="username@domain.com"
the username for the ftp account chosen for this purpose

ftp_user_pass="password"
the password for this specific account

notify_email="notifyme@domain.com"
the email address where you would like to be notified when someone uploads something using this form.

then close the tag with ]

This plugin will find the default folder you have created for this FTP account, and will then create a folder within it named by either the sender’s Company Name (if one was specified) or Contact Name (if no separate Company Name given). Of course, whichever of these is used will first pass through a format scrubber to make sure the name is “legal” on the server (e.g. “Harry’s Special Furniture” will create a directory called “harrys_special_furniture”). If the person has uploaded files before using the exact same Contact or Company info, the file will go into the folder that already exists. At this time, the plugin handles duplicates by overwriting files with the same name, but I intend to add functionality to allow for “versioning” in a future edition, when/if I ever get time, and if there turns out to be a demand for it.

In order for the form to validate and submit, I required the Contact Name field to be filled out, but not the Company Name field. I did this because some clients of the print shop were individuals rather than companies. If you wanted to force the entry of a Company Name, you could edit the .html file for that “input” to have a class of “EFU_text_req” instead of the “EFU_text” class it uses by default (and if you really want to be thorough, you’d probably want to alter the HTML describing its label to include that red asterisk).

Anyway, If you find this plugin useful, I hope you’ll consider donating in order to help me support my evil open-source coding habit. Bug reports and suggestions for future added functionality are also appreciated. But this is my first plugin, so I beg you to be gentle with any criticism. Thank you.