uhq-radio :: readme
v0.09 release

Requires cURL to be installed.

##==[ Credits ]==

Module created and maintained by Ian A. Underwood (iunderwood).

Admin EXM/Oxygen Icons from the Crystal Project (http://www.everaldo.com/crystal/).

##==[ Description ]==

The purpose of this module is to provide an interface for an Internet radio station within a XOOPS environment.

This version has been built and tested against XOOPS 2.3.3 and XOOPS 2.4.5.

This module parses XML statistic files only, and thus you will need to get the appropriate passwords from your stream host.  This module supports retrieving stats from both Shoutcast and Icecast 2.x servers, and will support a single failover mount on Icecast if it is configured.

See changelog.txt for details, and readme.txt for configuration information.

##==[ License ]==

All source code for this module is licensed under the Creative Commons GNU General Public license.  All code is copyright 2008-2009 by Ian Underwood.  For more information, visit the following URL:

http://creativecommons.org/licenses/GPL/2.0/

The full text of the GPL is included in license.txt.

##==[ The Story ]==

In the summer of 2006, shortly before my son was born, a friend and I decided to start up our own Internet radio station specializing in game OSTs during the off-time, and as a place where we could share our music with other folks online.  A previous station that both of us used to DJ at used XOOPS for its website so we thought we'd do the same.

My first foray into module building was with "FragRadio", which was an attempt to make an interface for radio use that could read stats and information from Shoutcast or Icecast so I could conveniently display who was on the air and what was playing that moment.  FragRadio eventually morphed into a relatively bastardized system which could interact with SAM Broadcaster for some limited means and stuff.  The module was extremely rough and had no pretty administrative interface or features, meaning that any changes usually required writing and executing my own SQL queries directly.

The station shut down after 6 months of operation because I needed to attend to the needs of my rapidly growing infant (now toddler) and there wasn't sufficient time to code or maintain the site.  In the period that followed, I managed to lose the source code to what was a rather functional module.

During the summer of 2008, I was presented with an opportunity to develop some code for another radio station another friend was hoping to bring online.  I was unfortunately unable to find any existing modules which met my needs and this was the catalyst which brought forth the rebirth of the radio module.  Instead of revisiting the FragRadio name, I decided to put it under the UHQ banner where I do most of my other development.  This time, the focus has not been so strongly on output features, but rather a more cautious and determined approach to make administration a not-so-difficult affair.

Please glance over this readme.txt in full, because the module had a fair number of options.

##==[ Features ]==

This module provides the following features to Internet broadcasters:

* What's on now
  Radio Status and On-Air blocks are capable of showing the viewer what is currently playing, and what DJ is currently on the air.

* Airstaff Management
  There's an admin interface used to define DJs within the system with an identifier for easy extraction.  The on-air block uses this information to show who is currently on-air.  The DJ List block puts up a list of all configured airstaff, along with links to a basic DJ profile.

* SAM Broadcaster Handoff Automation
  Synchronized handoff control can now happen between two DJs, or a DJ and automated system with stations that use SpacialAudio's SAM Broadcaster product.  Additionally, a series of controls is available to send limited control requests to an automated player.

  Information about integrating with SAM Broadcaster can be found in the extras directory of this module.

##==[ Installation & Updates ]==

Installation is as simple and straightforward as most of the other XOOPS modules available today.  Extract the uhq_radio folder into your modules directory and use the module installer to load the module into your site.

Your PHP installation must allow for "allow_url_fopen" to be enabled.

If you are updating from a previous version, please review the notes in the event that there are any additional steps or items to note.  You will likely need to clear your template cache.

##==[ Streaming Metadata Requirements ]==

If you intend to use show names and DJ IDs as part of your station information, the module will attempt to extract this information from either the <server_description> tag under an IceCast mountpoint, or in the <SERVERTITLE> on ShoutCast.

This solution may present some difficulty for stations that rely on the server description or title for information in their directory or YP listings.  If this is the case, it is recommended to set up a separate IceCast mountpoint or a Shoutcast instance where text information can be gleaned.  However, this second instance would then be configured to use the absolute lowest bitrate supported codec that your broadcast software is capable of.  This can be as low as 8kbps for MP3 and 14kbps for Ogg Vorbis.

##==[ Admin Configuration Items ]==

Before the power contained within this module can be harnessed, it must be configured appropriately from the administrative side.

I will describe each configuration section as it's presented by the interface on the module.

#--[ Module Preferences ]--

There's only one module-wide preference to set for now:

* XML Cache Time (10)
  This feature controls the amount of time each XML file retrieved from a streaming server is to stay fresh.
  
  This is to limit the number of queries made against a streamhost at any given time, which is important on a busy site, but also beneficial when pulling statistics from multiple IceCast mountpoints which have the same source.

* Use External Cache (No)
  This feature controls cache updates.  If yes, then cache updates need to be called from an external trigger via ecu.php discussed in a later section of this document.  The cache update will process all stations and mountpoints for a given mountpoint type.

* External Cache Update PW (changeme)
  This is a simple password which is checked for all updates.  Because this a publicly released module, this extra ounce of prevention was necessary.

* Enable SAM Broadcaster
  Currently, this enables features specific to the support of SAM Broadcaster to be visible to DJs and other parts of the module.

#--[ Index ]--

The index is a generic page which simply gives the administrator a set of statistics for the module.  There may be some more status information and such at a future date.

#--[ Airstaff ]--

This section is where you can optionally define DJ IDs, and then associate that with a website account.

The DJ ID is designed to be extracted from a stream description, either within the <server_description> tag on an IceCast mount or the <SERVERTITLE> tag on a Shoutcast stream.

While not required for station operation, you will need this in order to use DJ profiles and allow for future integration with DJ playlists and other advanced features.

A DJID can be up to 5 characters in length and can be any combination of letters and numbers.  The ID is case sensitive, so be sure to keep that a consideration.

#--[ Mount Points ]--

A mount point is simply a location where an audio stream can be connected to.  At a minimum, your station needs one mountpoint in order to determine text information and listener count.

Configuration options:

* Server IP/FQDN
  This is the IP address or the fully-qualified domain name of your streaming server.
  
* Server Port
  This is the TCP port which the server runs on and will get the XML file from.
  
* Server Type
  Icecast and Shoutcast are the only server types currently supported.  StreamerP2P does not provide any title information, so this mountpoint can only be used for listener counts.
  
* Icecast Mount
  This is the mountpoint on Icecast servers where the information is located.

* Icecast Fallback
  If the main mountpoint is not found, this mountpoint will be used as an information source instead.
  
* Statistics username
  This is the username which is used to access the XML statistic information.  Shoutcast mountpoints will always use "admin" as the username.  StreamerP2P does not use this field.

* Statistics password
  This is the password which is used to access the XML statistic information.
  
* Codec
  This allows you to select the codec that you're using.  Currently cosmetic.

* Bitrate
  This allows you to define the bitrate of the stream.  Currently cosmetic.
  
* Maximum Listeners
  This allows you to set a maximum listener count for the mount point.  Currently unused.
  
* Listener Variance
  This allows you to deduct a number from the listener count.  For example, if your main mountpoint has two relays which hang off of it, the variance would be two.

* Reliable text?
  This is set to suitable if the module can reliably pull in all text-based information with this mountpoint, including artist, title, DJ ID, show name, etc.

* Reliable count?
  This is set to suitable if the module should use the mount point as part of a counter series.

#--[ Channels ]--

A channel is a single program.  A channel can support multiple bitrates and codecs.

It is possible for an Internet radio station to have a couple of channels.  For instance, one channel can be the main stream which is open to the public, and another can be set aside for staff use ... such as training, DJ tryouts, and whatnot.

A channel will have an associated mount point which contains suitable text information, and any number of other mount points which are then used for listener count information.

Configuration Options:

* Channel Name
  This is simply the name of the channel.
  
* Channel Tagline
  This is the channel's tagline.

* Channel Info
  Typically, this is the station's URL.  This will be renamed in a future release.

* Channel Description
  This is a freeform text block for the station description.  Currently unused.

* Text Source Mountpoint
  This is a list of all mount points which have suitable text flagged.  This is where the particular channel will get all its text information.
  
  DJ Info:
  
* Parse DJ Info
  This option allows you to process DJ IDs or not.
  
* SOL/Start Delimiter
  DJ Information will begin either at the start of the line, or after a defined starting delimiter.

* Start Delimiter Text
  The DJ information will start after the text information provided.

* EOL/End Delimiter
  DJ information will end either at the end of the line, or before defined ending delimiter.

* End Delimiter Text
  The DJ information will end before the text information provided.

  Show Info:

* Parse Show Info
  This option allows you to process show names or not.

* SOL/Start Delimiter
  DJ Information will begin either at the start of the line, or after a defined starting delimiter.

* Start Delimiter Text
  The DJ information will start after the text information provided.

* EOL/End Delimiter
  DJ information will end either at the end of the line, or before defined ending delimiter.

* End Delimiter Text
  The DJ information will end before the text information provided.

Examples:

If you had a server description with a DJID of "AUTO", a show name of "Automagic!", you could encode the description like this:

[AUTO] {Automagic!}

The options would then be this:

Parse DJ Info: Extract from description
SOL/Start Delimiter: Delimiter
Start Delimiter Text: [
EOL/End Delimiter: Delimiter
End Delimiter Text: ]
Parse Show Info: Extract from description
SOL/Start Delimiter: Delimiter
Start Delimiter Text: {
EOL/End Delimiter: Delimiter
End Delimiter Text: }

However, you could encode it differently:

AUTO :: Automagic!

Then the options would be:

Parse DJ Info: Extract from description
SOL/Start Delimiter: SOL
Start Delimiter Text: (blank)
EOL/End Delimiter: Delimiter
End Delimiter Text: " :: " (Don't include the quotes, but include the spaces.)
Parse Show Info: Extract from description
SOL/Start Delimiter: Delimiter
Start Delimiter Text: " :: " (Don't include the quotes, but include the spaces.)
EOL/End Delimiter: EOL
End Delimiter Text: (blank)

Do take the time to find out what form will work for you.

#--[ Playlists ]--

A playlist is simply a list of mountpoints that a listener can use to latch onto a stream.

This functionality has not yet been implemented.

##==[ Blocks and Block Configuration ]==

#--[ Radio Status ]--

This block shows what's playing, how many listeners are tuned in, and what the show name is, if used.

This block is cloneable.

Block Options:

* Channel
  This is a drop-down menu that will let you select a channel to assocaite with this block.

* Display Tune-In Link?
  Select Yes to show the tune-in link.

* Link URL
  This is the web page which instructs your listeners to tune-in.  This can be a PLS or M3U file, but that's not really recommended.

* Target
  This determines where the page opens.  You can select the current window, a new window, or a pop-up window.

* Pop-Up:
  These two fields define the width and height of the pop-up window.  Adjust these numbers to best fit your tune-in window.

* Show Listener Count?
  Select Yes to show the current listener count.  This will get the full listener count for the station across all mount points.

* Show Offline Errors?
  Select Yes to display why the block has determined offline status.  Usually good to set to "no" in a production site.  Errors confuse listeners.
  
* Use Tune-In Graphic?
  Select Yes to display a graphic link instead of a text "Tune in: Click Here" link.

* Use AJAX?
  Select Yes to have this block dynamically refresh with the latest information once per minute.
  
#--[ Radio Control ]--

Starting in version 0.04, the option was added to give remote start/stop and skip track capability to a station autoplayer or jukebox.  This is done by using calling out a URL to a remote system, presumably your autoplayer.  A command is deemed successful if there is an HTTP response from the autoplayer.  No further checks are performed at this time.

The key is to properly configure the start/stop block for the autoplayer's URLs.  This is configured in the block options.  The following are the block defaults:

Start URL: http://autoplayer.url/event/AP-Start
Stop URL: http://autoplayer.url/event/AP-Stop
Skip URL: http://autoplayer.url/event/AP-Skip
Stop Now URL: http://autoplayer.url/event/AP-StopNOW
Rewind URL: http://autoplayer.url/event/AP-Rewind

Autoplayer permission is enforced based upon who can see the control block.  The methods of the block don't specify which block the actual request came from at this time, hence the block is not cloneable at this time.

Please refer to the extras directory for information about integrating this with SpacialAudio's SAM Broadcaster.

#--[ Handoff Control ]--

This block provides an interface where DJs may coordinate handoff between themselves and the autoplayer at any given time.  In the event that there is no handoff taken, the autoplayer start event will be sent.

Block Options:

* Remote Handoff Port
  This is the port where the website will attempt to send its events.  Currently, all DJs who anticipate working with the handoff system must forward this port to the actual control port of their broadcasting application.

* Allow Unverified Handoff Requests?
  If this option is "yes", then handoff automation will not make any automated checks to verify the remote player is ready.  In most cases, this should be left set to "no".

* Test Event
  This is the trailing part of the URL that will be attempted when a handoff is requested.

* Start Event
  This is the trailing part of the URL that will signal the remote player to start.

Methods:

When a DJ requests a handoff, the website will try and execute a URL consisting of the requesting IP, handoff port, and the test event.  This check only verifies that the player software is operating.

The next step is for the DJ to verify their player is all set.  This can be accomplished by the player software sending off the following URL:

http://your.xoops.site/modules/uhq_radio/handoff.php?op=verify

It is recommended that any remote player utilize a script to automatically verify when the test event is sent, but that is not a strict requirement.

A verification command will only be processed if the requesting IP matches the original handoff request.

A currently-broadcasting system needs to send the following URL after disconnecting from the stream host:

http://your.xoops.site/modules/uhq_radio/handoff.php?op=go

Handoff permission is enforced based upon who can see the handoff block and is not cloneable.

Please refer to the extras directory for information about integrating this with SpacialAudio's SAM Broadcaster.

#--[ DJ Panel ]--

This block will appear to a user who has a configured DJ ID in the Airstaff section.  It simply serves as a reminder to the DJ what their DJ ID is if their request flag is turned on.

There are no options for this block.

#--[ DJ List ]--

This block will list all the DJs associated with the website, and provide a hyperlink to their DJ Profile.

Block options:

* # of columns
  Depending on the placement of the block, a number of columns can be beneficial.  This allows you to set that number.
  
* Font Size
  The size of the font will be largely determined by the theme your site uses.  However, this field can override the theme setting to make the font bigger or smaller, depending on your desires.
  
#--[ On-Air ]--

This block presents the photo of the DJ profile on air for a given station, along with the DJ name and show name.

Block options:

* Select a channel
  Choose the channel which will be associated with this block.
  
##==[ Module Pages ]==

There are a couple of other pages which support module functions:

#--[ djprofile.php ]--

This page will be contextually linked from either the DJ List or the On-Air module, and presents a DJ profile.

A DJ can edit their own information, but they cannot change their DJ ID.

#--[ ecu.php ]--

This page triggers external cache updates, if enabled.  This function was originally written with mind to keep the website from continuously refreshing information from a streaming server.  If ECU is enabled, the cached information is never updated, except when explicitly requested.  ECU is a simple script and takes two arguments: update and updatepw.  THe update PW is the password specified in the module option.  The update type is either "pop" or "txt" which simply updates all caches that pertain to either listener counts, or song information.

For example, to update the listener count on a per-minute basis, a Cron or a SAM PAL Script can call ECU every minute:

http://your.xoops.site/modules/uhq_radio/ecu.php?update=pop&updatepw=changeme

Additionally, text information can also be refreshed every time a song changes.  This needs to be triggered from whatever streaming application you use.

http://your.xoops.site/modules/uhq_radio/ecu.php?update=txt&updatepw=changeme

ECU does not take any station-specific identifiers into account and will update caches for every unique server configured in the module.

#--[ history.php ]--

This script will process the XML cache and make a DB entry for song history or listener history data.  This option isn't turned on or off per-se in the module because things like historical recording are managed by triggers only.

If ECU is enabled, it is recommended that history be run shortly after the caches have been updated.

This page requires three parameters, chid, htype, and updatepw.

#--[ xmlstatus.php ]--

This page will return an XML document with all the information which comprises the Radio Status block.  It does require the channel ID to be passed on the parameter line such as:

xmlstatus.php?chid=1

This will return an XML in the following format:

<uhqradio>
  <channel>
    <name>PowerFrag.FM</name>
    <tag>Have fun!</tag>
    <web>http://www.powerfrag.fm</web>
    <status>1</status>
  </channel>
  <onair>
    <djid>HAP</djid>
    <showname>Automagically Random</showname>
    <artist>Richard Dekkard</artist>
    <title>Brief Memory</title>
    <listeners>0</listeners>
  </onair>
</uhqradio>

The information returned is as close to real time as the request is made.

It is possible for an AJAX enabled web page to request this information and dynamically update its content on a regular interval with this information.  An example can be found in ajax_updates.txt in he extras folder.

#==[ Requests / Bug Reports ]==

You may drop me an email here: xoops@underwood-hq.org.

If there is a specific feature or bug you are experiencing, the more information I have the better.

++I;