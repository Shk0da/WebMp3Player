<?php 

$path = "songs";
$webPath = "music/songs";
$playlistName = "playlist.m3u";
$allowedFormats = array("mp3");

$scheme = "http";
$host = "localhost";

if (isset($_SERVER['REQUEST_URI'])) {
    $url =  parse_url($_SERVER['REQUEST_URI']);
    $scheme = isset($url["scheme"]) ? $url["scheme"] : $scheme;
    $host = isset($url["host"]) ? $url["host"] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $host);
}
