<?php
/*
     Fabien Benetou

     Based on http://www.pmwiki.org/wiki/Cookbook/SlideShow

     Installing:
        Download reveal.js and uncompress it to pub/reveal folder in your "Farm" directory

     Using:
        Include this (reveal.php) file in your config.php.
        Create a page using section to define slides and then
        access the page with action=reveal
	
	If you defined a master/client keypair configure them further in the multiplex parameters

*/

Markup('section','fulltext','/\(:section:\)/e',"Keep('<section>')");
Markup('sectionend','fulltext','/\(:sectionend:\)/e',"Keep('</section>')");
Markup('sectionextended','fulltext','/\(:sectionextended (.*?):\)/e',"Keep('<section $1>')");

SDV($HandleActions['reveal'],'HandleRevealSlides');

SDV($SlideShowFmt, '<!doctype html>
<html lang="en">
        <head>
                <meta charset="utf-8">
                <title>reveal.js - Barebones</title>
                <link rel="stylesheet" href="$FarmPubDirUrl/reveal.js-master/css/reveal.css" type="text/css" />
        </head>
        <body>
                <div class="reveal">
                        <div class="slides">
$Slide
                        </div>
                </div>
                <script src="$FarmPubDirUrl/reveal.js-master/js/reveal.js"></script>
                <script>
Reveal.initialize({
	    // other options...

	    multiplex: {
		            // Example values. To generate your own, see the socket.io server instructions.
		            secret: null, // Obtained from the socket.io server. Gives this (the master) control of the presentation
		            id: "", // Obtained from socket.io server
				            url: "https://reveal-multiplex.glitch.me/" // Location of socket.io server
						        },

	    // Don"t forget to add the dependencies
	    dependencies: [
		            { src: "//cdn.socket.io/socket.io-1.3.5.js", async: true },
			            { src: "/pub/reveal.js-master/plugin/multiplex/client.js", async: true },

	            // other dependencies...
	        ]
});

                </script>
        </body>
</html>
');


function HandleRevealSlides($pagename, $auth = 'read') {
  global $SlideShowFmt,$FmtV,$ScriptUrl,$Group,$Name;
  
  $page = RetrieveAuthPage($pagename, $auth, false, READPAGE_CURRENT);
  if (!$page) Abort("?cannot read $pagename");
  
  $FmtV['$Slide'] = MarkupToHTML($pagename, $page['text']);
  FmtPageName($SlideShowFmt, $pagename);
  PrintFmt($pagename,$SlideShowFmt);
  exit();
}

/*  For master control of slides (ALL presentations!)
{"secret":"14731768290817665483","socketId":"1ff0f582dc396a71"}

Example of working control
http://fabien.benetou.fr/pub/home/testingrevealremote/
*/

SDV($HandleActions['revealcontrol'],'ControlSlides');

SDV($SlideShowFmtControl, '<!doctype html>
<html lang="en">
        <head>
                <meta charset="utf-8">
                <title>reveal.js - Barebones</title>
                <link rel="stylesheet" href="$FarmPubDirUrl/reveal.js-master/css/reveal.css" type="text/css" />
        </head>
        <body>
                <div class="reveal">
                        <div class="slides">
$Slide
                        </div>
                </div>
                <script src="$FarmPubDirUrl/reveal.js-master/js/reveal.js"></script>
                <script>
Reveal.initialize({
	    // other options...

	    multiplex: {
		            // Example values. To generate your own, see the socket.io server instructions.
		            secret: "", // Obtained from the socket.io server. Gives this (the master) control of the presentation
		            id: "", // Obtained from socket.io server
				            url: "https://reveal-multiplex.glitch.me/" // Location of socket.io server
						        },

	    // Don"t forget to add the dependencies
	    dependencies: [
		            { src: "//cdn.socket.io/socket.io-1.3.5.js", async: true },
			            { src: "/pub/reveal.js-master/plugin/multiplex/master.js", async: true },

	            // other dependencies...
	        ]
});

                </script>
        </body>
</html>
');

function ControlSlides($pagename, $auth = 'read') {
  global $SlideShowFmtControl,$FmtV,$ScriptUrl,$Group,$Name;
  
  $page = RetrieveAuthPage($pagename, $auth, false, READPAGE_CURRENT);
  if (!$page) Abort("?cannot read $pagename");
  
  $FmtV['$Slide'] = MarkupToHTML($pagename, $page['text']);
  FmtPageName($SlideShowFmtControl, $pagename);
  PrintFmt($pagename,$SlideShowFmtControl);
  exit();
}

?>
