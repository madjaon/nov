<html>
  <head>
    <meta charset="utf-8">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>jwplayer test</title>
    <style>
      .jwplayer .jw-rightclick.jw-open {
        display: none !important;
      }
    </style>
    <script src="//content.jwplatform.com/libraries/xSqZrvkA.js"></script>
  </head>
  <body>
    <div id="myDiv">This text will be replaced with a player.</div>
    <script>
      jwplayer("myDiv").setup({
        "playlist": [{
          "title":"One Playlist Item With Multiple Qualities",
          "description":"Two Qualities - One Playlist Item",
          "sources": [
            // {
            //   "file": "",
            //   "type": "video/mp4",
            //   "label": "720p"
            // },
            // {
            //   "file": "",
            //   "type": "video/mp4",
            //   "label": "480p"
            // },
            {
              "file": "https://r1---sn-nx57yn7d.c.drive.google.com/videoplayback?id\u003d357358d013fd8002\u0026itag\u003d18\u0026source\u003dwebdrive\u0026requiressl\u003dyes\u0026ttl\u003dtransient\u0026mm\u003d30\u0026mn\u003dsn-nx57yn7d\u0026ms\u003dnxu\u0026mv\u003du\u0026pl\u003d64\u0026ei\u003dAyhRWcb9L4frqQXRpL0I\u0026driveid\u003d0B3xRgRF6EUCdRG9Dcl9RWkdkS3c\u0026mime\u003dvideo/mp4\u0026lmt\u003d1498485535694168\u0026mt\u003d1498490610\u0026ip\u003d2400:8901::f03c:91ff:fe7a:522d\u0026ipbits\u003d0\u0026expire\u003d1498505283\u0026cp\u003dQVJOWkZfVFJWRFhNOkM5X2xWbXF1UmVv\u0026sparams\u003dip,ipbits,expire,id,itag,source,requiressl,ttl,mm,mn,ms,mv,pl,ei,driveid,mime,lmt,cp\u0026signature\u003d5A40A8A3297D9F0417CAE1A233BAD553DB246337.32E95CE23DFBAA4C7D49D47DFF877F9AA052606E\u0026key\u003dck2",
              "type": "video/mp4",
              "label": "360p"
            }
          ]
        }],
        "width": "960",
        "height": "540"
      });
    </script>
  </body>
</html>
