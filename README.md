youtube-dl-mp3-php
==================

Php Script to download youtube video with youtube-dl, convert FFmpeg and encoding with with lame. And create a mp3 file to download directly

###Requirements:

* FFmpeg: http://www.ffmpeg.org
* lame: http://downloads.sourceforge.net/lame/
* youtube-dl: https://github.com/rg3/youtube-dl

###Server side
Set permissions to youtube-dl:

    chmod a+x youtube-dl
    
Edit download.php file, add your domain url where you put the script, in $base:

    $base = "http://yoursite.com/mp3/";

go to

http://yoursite.com/mp3/download.php?id={code}&nome={name.mp3}

'code' is the code of video, the code is after
https://www.youtube.com/watch?v=

'name.mp3' is the name of mp3 saved

return data will be a json object with a assolute url of mp3

### Client Side

Make  JSONP request, if you use jQuery:

    $.ajax({
        url: "http://yoursite.com/mp3/download.php",
     
        jsonp: "callback",
     
        dataType: "jsonp",
     
        data: {
            id: "34s_cIuHWB4",
            name: "mmysong.mp3"
        },
     
        // work with the response
        success: function( data ) {
            if(data.link) alert(data.link);
            if(data.errori) console.log(data.errori);
        }
    });

Now you can redirect the page to  "data.link", set:

    window.location.href = data.link
    
oer create a link:

    "<a href='"+data.link+"'>download</a>"
