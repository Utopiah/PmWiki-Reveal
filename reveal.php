<?php
/*
     Fabien Benetou

     Based on http://www.pmwiki.org/wiki/Cookbook/SlideShow

     Installing:
        Download reveal.js and uncompress it to pub/reveal folder in your "Farm" directory

     Using:
        Include this (reveal.php) file in your config.php.
        Create a page using html/htmlend <section> to define slides and then
        access the page with action=reveal

*/

Markup('section','fulltext','/\(:section:\)/e',"Keep('<section>')");
Markup('sectionend','fulltext','/\(:sectionend:\)/e',"Keep('</section>')");

SDV($HandleActions['reveal'],'HandleSlides');

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
                        Reveal.initialize();
                </script>
        </body>
</html>
');


function HandleSlides($pagename, $auth = 'read') {
  global $SlideShowFmt,$FmtV,$ScriptUrl,$Group,$Name;
  
  $page = RetrieveAuthPage($pagename, $auth, false, READPAGE_CURRENT);
  if (!$page) Abort("?cannot read $pagename");
  
  $FmtV['$Slide'] = MarkupToHTML($pagename, $page['text']);
  FmtPageName($SlideShowFmt, $pagename);
  PrintFmt($pagename,$SlideShowFmt);
  exit();
}

?>
