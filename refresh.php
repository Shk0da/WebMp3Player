<?php 

include __DIR__ . '/environment.php';
include __DIR__ . '/lib/getid3/getid3.php';

function getDirContents($dir, &$results = array()) {
    global $allowedFormats;
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir . '/' . $value;
        if (!is_dir($path)) {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (isset($ext) && in_array($ext, $allowedFormats)) {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
        }
    }
    return $results;
}

if (file_exists($playlistName)) {
    unlink($playlistName);
}

file_put_contents($playlistName, "#EXTM3U" . "\n", FILE_APPEND | LOCK_EX);
$songs = getDirContents($path);
foreach ($songs as $mp3) {
    $fileName = pathinfo($mp3, PATHINFO_FILENAME);
    $title = $fileName;
    $artist = "";
    $album = "";
    $time = 60;
            
    $audio = new getID3();
    $audio->encoding = 'UTF-8';
    $audio->Analyze($mp3);

    if (isset($audio->info) && isset($audio->info['tags']) && $audio->info['tags'] != null && isset($audio->info['tags']['id3v2']) && $audio->info['tags']['id3v2'] != null) {
        $idv3v2 = $audio->info['tags']['id3v2'];
        $title = isset($idv3v2['title']) ? $idv3v2['title'][0] : $fileName;
        $artist = isset($idv3v2['artist']) ? $idv3v2['artist'][0] : "";
        $album = isset($idv3v2['album']) ? $idv3v2['album'][0] : "";

        if (isset($audio->info['id3v2']) & isset($audio->info['id3v2']['APIC'])) {
            $cover = $mp3 . '.jpg';
            $data = $audio->info['id3v2']['APIC'][0]['data'];
            file_put_contents($cover, $data);
        }
    }

    if (isset($audio->info) && isset($audio->info["playtime_seconds"]) && $audio->info['playtime_seconds'] != null) {
        $time = (int) $audio->info['playtime_seconds'];
    }

    $title = str_replace('"', '\"', $title);
    $artist = str_replace('"', '\"', $artist);
    $album = str_replace('"', '\"', $album);

    $info = "#EXTINF:$time,$artist - $title [$album]\n";
    file_put_contents($playlistName, $info, FILE_APPEND | LOCK_EX);

    $song = str_replace(' ', '%20', $mp3);
    $song = str_replace($path, $scheme . "://${host}/" . $webPath, $song);
    $song = $song  . "\n";
    file_put_contents($playlistName, $song, FILE_APPEND | LOCK_EX);
}

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $scheme .'://' . $host;
header('Refresh: 0; URL=' . $redirect);
?>