#!/bin/bash
host=$1
find /media/usb/music/ -name "*.mp3" | sed 's/ /%20/g' | sed "s|/media/usb/music/|http://${host}/music/songs/|g" > playlist.m3u