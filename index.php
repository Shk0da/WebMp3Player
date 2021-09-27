<!DOCTYPE html>
<html>
  <head>
	<title>Music Collection</title>
    <!-- Include favicon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<!-- Include font -->
	<link href="https://fonts.googleapis.com/css?family=Lato:400,400i" rel="stylesheet">
	<!-- Include Amplitude JS -->
	<script type="text/javascript" src="js/amplitude.js"></script>
	<!-- Include Style Sheet -->
	<link rel="stylesheet" type="text/css" href="css/app.css"/>
    <!-- Include viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <?php
      include __DIR__ . '/environment.php';
      include __DIR__ . '/utils.php';
      $songs = getSongs($playlistName);
      $songSize = count($songs);
    ?>
    <div id="flat-black-player-container">
      <div id="list-screen" class="slide-in-top">
        <div id="list-screen-header" class="hide-playlist">
          <img id="up-arrow" src="img/up.svg"/>
          Hide Playlist
        </div>
        
        <div id="list">
          <?php 
          for ($index = 0; $index <= $songSize - 1; $index++) {
                echo '
                <div class="song amplitude-song-container amplitude-play-pause" data-amplitude-song-index="' . $index . '">
                  <span class="song-number-now-playing">
                    <span class="number">' . $index . '</span>
                    <img class="now-playing" src="img/now-playing.svg"/>
                  </span>
                  <div class="song-meta-container">
                    <span class="song-name" data-amplitude-song-info="name" data-amplitude-song-index="' . $index . '"></span>
                    <span class="song-artist-album"><span data-amplitude-song-info="artist" data-amplitude-song-index="' . $index . '"></span> - <span data-amplitude-song-info="album" data-amplitude-song-index="' . $index . '"></span></span>
                  </div>
                  <span class="song-duration"><span>
                </div>
                '; 
            }
          ?>
      
        </div>

        <div id="list-screen-footer">
          <div id="list-screen-meta-container">
            <span data-amplitude-song-info="name" class="song-name"></span>
            <div class="song-artist-album">
              <span data-amplitude-song-info="artist"></span>
            </div>
          </div>
          <div class="list-controls">
            <div class="list-previous amplitude-prev"></div>
            <div class="list-play-pause amplitude-play-pause"></div>
            <div class="list-next amplitude-next"></div>
          </div>
        </div>
      </div>
      <div id="player-screen">
        <div class="player-header down-header">
            <img id="down" src="img/down.svg"/> Show Playlist
        </div>
        <div id="player-top">
          <img data-amplitude-song-info="cover_art_url"/>
        </div>
        <div id="player-progress-bar-container">
          <progress id="song-played-progress" class="amplitude-song-played-progress"></progress>
          <progress id="song-buffered-progress" class="amplitude-buffered-progress" value="0"></progress>
        </div>
        <div id="player-middle">
          <div id="time-container">
            <span class="amplitude-current-time time-container"></span>
            <span class="amplitude-duration-time time-container"></span>
          </div>
          <div id="meta-container">
            <span data-amplitude-song-info="name" class="song-name"></span>
            <div class="song-artist-album">
              <span data-amplitude-song-info="artist"></span>
            </div>
          </div>
        </div>
        <div id="player-bottom">
          <div id="control-container">
            <div id="shuffle-container">
              <div class="amplitude-shuffle amplitude-shuffle-off" id="shuffle"></div>
            </div>
            <div id="prev-container">
              <div class="amplitude-prev" id="previous"></div>
            </div>
            <div id="play-pause-container">
              <div class="amplitude-play-pause" id="play-pause"></div>
            </div>
            <div id="next-container">
              <div class="amplitude-next" id="next"></div>
            </div>
            <div id="repeat-container">
              <div class="amplitude-repeat" id="repeat"></div>
            </div>
          </div>

          <div id="volume-container">
            <img src="img/volume.svg"/><input type="range" class="amplitude-volume-slider" step=".1"/>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="js/functions.js"></script>
  <script>
    Amplitude.init({
      "bindings": {
        37: 'prev',
        39: 'next',
        32: 'play_pause'
      },
      "songs": [
        <?php            
            $index = 0;
            foreach ($songs as $song) {
              $info = getInfoMp3($song);
              echo '
              {
                "name": "' . $info["title"] . '",
                "artist": "' . $info["artist"] . '",
                "album": "' . $info["album"] . '",
                "url": "' . $info["url"] . '",
                "cover_art_url": "' . $info["picture"] . '",
                "time_callbacks": {
                  0: function() {
                    document.title = "' . $info["title"] . ' - ' . $info["artist"] . ' [' . $info["album"] . ']";
                  }
                }
              }
              '; 
              if ($index++ < ($songSize - 1)) {
                echo ',';
              }
            }
          ?>
      
      ]
    });
  </script>
</html>
