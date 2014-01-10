LightweightCMS
==============
Lightweight CMS is an Open Source Modular Application framework and Content Management System.

Currently only the admin section is working to some degree please check below for details.

The system is comprised of a JavaScript Engine to keep all Plugins within the Theme this works by allowing plugins to output 
there links at a href links and the JS will convert them to work within the admin layout.

Plguins that are shipped with this source code are the Content System this is a modular content system that allows 
you to create block's of HTML code using a basic variable replace and these block can then be added to a page
on the page they can be named so a template can load a block by name more support is to be added to the content system

There is a Plugins plugin this is due to the way that the Application framework built i thought it would be better not to hide it
to prevent confusion to show you that even the plugins manager is a plugin with the system this allows you to create new plugins
and manage existing plugins however there can be a flag inserted into the DB table to prevent removal of the plugins via the CMS
you will find this is enabled on the 2 default plugins you can still remove them from your site in the database however this would
only be useful should you only want the Application framework and not the CMS part of this application. 

Securtiy
==============
There is a Cookie and Session verification system in place on this code this prevents external session and cookie hijacking 

BETA NO SUPPORT
==============
This project is still in development and no release has been launched as such this source code is completely unsupported and without warrenty

Updates
==============
10/01/2014 Barkermn01 - Been working on changing the Content system to be modular instead of the static system that's in place this 
has been a complete rebuild of the content system and is not finished currently you can create new blocks, edit blocks, delete blocks,
there is also the following support for pages, you can create a new page, and remove a page there are still bugs with this however
the edit page system is still being create as such it will currently render a page outline but is not able to save page edits this update
will be coming soon 
