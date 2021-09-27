<?php

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function getSongs($playlistName) {
    $songs = array();
    if (!file_exists($playlistName)) {
        return $songs;
    }

    $handle = fopen($playlistName, "r");
    if ($handle) {
        $latch = 1;
        $counter = 0;
        while (($song = fgets($handle)) !== false) {
          $row = preg_replace('~[\r\n]+~', '', $song);

          if ("#EXTM3U" == $row || '' == $row) continue;

          if (startsWith($row, '#')) {
            $info = preg_split("/[,]+/", str_replace('#EXTINF:', '', $row));
            $songs[$counter][0] = $info[1];
          } else {
            $songs[$counter][1] = $row;
          }

          if ($latch-- == 0) {
            $latch = 1;
            $counter++;
          }
        }
        fclose($handle);
    } else {
        echo '<script>console.log("Fail read ' . $playlistName .'")</script>';
    }
    return $songs;
}

function getInfoMp3($song) {
    global $scheme, $host, $webPath, $path;

    $mp3Url = $song[1];
    $mp3Info = $song[0];

    $info = preg_split("/[-]+/", $mp3Info);
    $artist = trim($info[0]);

    preg_match('/\|(.*?)\|/', $info[1], $match);
    $title = (isset($match[0])) ? trim(str_replace($match[0], '', $info[1])) : "";
    $album = (isset($match[1])) ? trim($match[1]) : "";
              
    $picture = "img/cover.jpg";
    $coverUrl = str_replace($scheme . '://' . $host . '/' . $webPath, $path, $mp3Url) . ".jpg";
    if (file_exists(str_replace('%20', ' ', $coverUrl))){
        $picture = $coverUrl;
    }

    return array(
        "url" => $mp3Url,
        "artist" => $artist,
        "title" => $title,
        "album" => $album,
        "picture" => $picture
    );
}

?>