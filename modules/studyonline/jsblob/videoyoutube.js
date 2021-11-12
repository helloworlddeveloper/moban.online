var events = [];
var errors = [];
var vid = '';
var loopVideos = false;
var shuffleCount = 0;
var activePlayer = 'embed_video_youtube';
var contentType = 'load-video';
var playerContent = '9q6snB0I8oI';
var player;
var iframeCount = 1;
var playerParams = '';
var listRegex = new RegExp('\&list=([^\&]+)');
var listTypeRegex = new RegExp('\&listType=([^\&]+)');
var playlistRegex = new RegExp('\&playlist=([^\&]+)');

// Define quality options for validating form inputs
var qualityLevels = {'default': 1, 'highres': 1, 'hd1080': 1, 'hd720': 1,
    'large': 1, 'medium': 1, 'small': 1};


/**
 * The 'updateHTML' function updates the innerHTML of an element.
 * @param {string} elmId Mandatory The element to update HTML for.
 * @param {string} value Mandatory The updated HTML for the element.
 */
function updateHTML(elmId, value) {
  if (document.getElementById(elmId)) {
    document.getElementById(elmId).innerHTML = value;
  }
}

/**
 * The 'addInformation' function pushes data onto the events array, then calls
 * getVideoUrl() and getEmbedCode(), a sequence is common to several functions.
 * @param {string} opt_eventData Optional The event to log.
 */
function addInformation(opt_eventData) {
  if (opt_eventData) {
    events.push(opt_eventData);
  }
  getVideoUrl();
}

/**
 * The 'clearOutput' removes any HTML in a few page elements and resets
 * the events[] and errors[] arrays.
 */
function clearOutput() {
  updateHTML('errorCode', 'None yet.');
  updateHTML('videoUrl', '');
  updateHTML('eventhistory', 'None yet.');
  events = [];
  errors = [];
}

/**
 * The 'createYTPlayer' function embeds an <iframe> player.
 * @param {string} playerDiv Mandatory The DOM ID for the div where the
 *     <iframe> will be embedded.
 * @param {string} playerHeight Mandatory The height of the embedded player.
 * @param {string} playerWidth Mandatory The width of the embedded player.
 * @param {string} playerVideoId Mandatory The video ID to embed.
 * @param {Object} playerVars Mandatory Player parameters or {}.
 */
function createYTPlayer(playerDiv, playerHeight, playerWidth, playerVideoId,
    playerVars) {
  if ('list' in playerVars && 'listType' in playerVars) {
    var newPlayer = new YT.Player(playerDiv, {
      height: playerHeight,
      width: playerWidth,
      playerVars: playerVars,
      events: {
        'onError': onPlayerError,
        'onPlaybackQualityChange': onytplayerQualityChange,
        'onPlaybackRateChange': onytplayerPlaybackRateChange,
        'onReady': onYouTubeHTML5PlayerReady,
        'onStateChange': onytplayerStateChange
      }
    });
  } else {
    var newPlayer = new YT.Player(playerDiv, {
      height: playerHeight,
      width: playerWidth,
      videoId: playerVideoId,
      playerVars: playerVars,
      events: {
        'onError': onPlayerError,
        'onPlaybackQualityChange': onytplayerQualityChange,
        'onPlaybackRateChange': onytplayerPlaybackRateChange,
        'onReady': onYouTubeHTML5PlayerReady,
        'onStateChange': onytplayerStateChange
      }
    });
  }
}

/**
  EVENT HANDLERS
 */

/**
 * The 'onYouTubePlayerReady' function executes when the onReady event
 * fires, indicating that the player is loaded, initialized and ready
 * to receive API calls.
 * @param {Object} event Mandatory A value that identifies the player.
 */
function onYouTubeHTML5PlayerReady(event) {
  // No need to do any of this stuff if the function was called because
  // the user customized the player parameters for the embedded player.
  if (event && event.target) {
    player = event.target;

    setInterval(updateytplayerInfo, 600);
    addInformation();
    updateytplayerInfo();
  }
}

/**
 * The 'onytplayerStateChange' function executes when the onStateChange
 * event fires. It captures the new player state and updates the
 * "Player state" displayed in the "Playback statistics".
 * @param {string|Object} newState Mandatory The new player state.
 */
function onytplayerStateChange(newState) {
  if (typeof newState == 'object' && newState['data']) {
    newState = newState['data'];
  }
  events.push('onStateChange event: Player state changed to: "' +
      newState + '" (' + getPlayerState(newState) + ')');
  updateHTML('playerstate', newState);
}

/**
 * The 'onPlayerError' function executes when the onError event fires.
 * It captures the error and adds it to an array that is displayed in
 * the "Errors" section of the demo.
 * @param {string} errorCode Mandatory A code that explains the error.
 */
function onPlayerError(errorCode) {
  if (typeof errorCode == 'object' && errorCode['data']) {
    errorCode = errorCode['data'];
  }
  errors.push('Error: ' + errorCode);
}

/**
 * The 'onytplayerQualityChange' function executes when the
 * onPlaybackQualityChange event fires. It captures the new playback quality
 * and updates the "Quality level" displayed in the "Playback Statistics".
 * @param {string|Object} newQuality Mandatory The new playback quality.
 */
function onytplayerQualityChange(newQuality) {
  if (typeof newQuality == 'object' && newQuality['data']) {
    newQuality = newQuality['data'];
  }
  events.push('onPlaybackQualityChange event: ' +
      'Playback quality changed to "' + newQuality + '"');
}

/**
 * The 'onytplayerPlaybackRateChange' function executes when the
 * onPlaybackRateChange event fires. It captures the new playback rate
 * and updates the "Plabyack rate" displayed in the "Playback Statistics".
 * @param {string|Object} newRate Mandatory The new playback rate.
 */
function onytplayerPlaybackRateChange(newRate) {
  if (typeof newRate == 'object' && newRate['data']) {
    newRate = newRate['data'];
  }
  events.push('onPlaybackRateChange event: ' +
      'Playback rate changed to "' + newRate + '"');
}

/**
 * PLAYER FUNCTION CALLS
 * Player function calls are documented at:
 * https://developers.google.com/youtube/iframe_api_reference.html
 *
 * You can navigate directly to a description of each function by
 * appending the function name, as an anchor link, to the URL above.
 * For example, the two URLs below would be used to link to the "mute"
 * and "playVideo" functions, respectively:
 * https://developers.google.com/youtube/iframe_api_reference.html#mute
 * https://developers.google.com/youtube/iframe_api_reference.html#playVideo
 */

/**
 * The 'cueVideo' function determines whether the user is trying to
 * cue a video by its video ID or its URL and then calls the appropriate
 * function to actually cue the video. After cueing the video, this
 * function updates the video URL and embed code for the video.
 * @param {string} idOrUrl Mandatory The ID or URL for the video to cue.
 * @param {number} startSeconds Optional The time offset, measured in
 *     seconds from the beginning of the video, from which the video
 *     should start playing.
 * @param {string} quality Optional The suggested playback quality for
 *     the video. See documentation for the setPlaybackQuality function
 *     for more information.
 */
function cueVideo(idOrUrl, startSeconds, quality) {
  // XSS sanitizer -- make sure params contain valid values
  if (xssSanitizer('Video ID or URL', idOrUrl, 'videoIdOrUrl') &&
      xssSanitizer('Start at', startSeconds, 'digits') &&
      xssSanitizer('Suggested quality', quality, 'qualitylevels')) {
    var urlRegex = /https\:/;
    if (idOrUrl.match(urlRegex)) {
      player.cueVideoByUrl(idOrUrl, parseInt(startSeconds), quality);
      addInformation('cueVideoByUrl(' + idOrUrl +
          ', parseInt(' + startSeconds + '), ' + quality + ');');
    } else {
      player.cueVideoById(idOrUrl, parseInt(startSeconds), quality);
      addInformation('cueVideoById(' + idOrUrl +
          ', parseInt(' + startSeconds + '), ' + quality + ');');
    }
  }
}

/**
 * The 'loadVideo' function determines whether the user is trying to
 * load a video by its video ID or its URL and then calls the appropriate
 * function to actually load the video. After loading the video, this
 * function updates the video URL and embed code for the video.
 * @param {string} idOrUrl Mandatory The ID or URL for the video to load.
 * @param {number} startSeconds Optional The time offset, measured in
 *     seconds from the beginning of the video, from which the video
 *     should start playing.
 * @param {string} quality Optional The suggested playback quality for
 *     the video. See documentation for the setPlaybackQuality function
 *     for more information.
 */
function loadVideo(idOrUrl, startSeconds, quality) {
  // XSS sanitizer -- make sure params contain valid values
  if (xssSanitizer('Video ID or URL', idOrUrl, 'videoIdOrUrl') &&
      xssSanitizer('Start at', startSeconds, 'digits') &&
      xssSanitizer('Suggested quality', quality, 'qualitylevels')) {
    var urlRegex = /https\:/;
    if (idOrUrl.match(urlRegex)) {
      player.loadVideoByUrl(idOrUrl, parseInt(startSeconds), quality);
      addInformation('loadVideoByUrl(' + idOrUrl +
          ', parseInt(' + startSeconds + '), ' + quality + ');');
    } else {
      //player.loadVideoById(idOrUrl, parseInt(startSeconds), quality);
      player.loadVideoById({'videoId': idOrUrl,
                            'startSeconds': parseInt(startSeconds),
                            'suggestedQuality': quality});
      addInformation('loadVideoById(' + idOrUrl +
          ', parseInt(' + startSeconds + '), ' + quality + ');');
    }
  }
}

/**
 * The 'cueListArray' function determines whether the user is trying to
 * cue a video by its video ID or its URL and then calls the appropriate
 * function to actually cue the video. After cueing the video, this
 * function updates the video URL and embed code for the video.
 * @param {string} videoList Mandatory List of video IDs to load/cue.
 * @param {string} startIndex Mandatory First video in set to play.
 * @param {number} startSeconds Optional The time offset, measured in
 *     seconds from the beginning of the video, from which the video
 *     should start playing.
 * @param {string} quality Optional The suggested playback quality for
 *     the video. See documentation for the setPlaybackQuality function
 *     for more information.
 */
function cueListArray(videoList, startIndex, startSeconds, quality) {
  // XSS sanitizer -- make sure params contain valid values
  if (xssSanitizer('Start index', startIndex, 'digits') &&
      xssSanitizer('Start at', startSeconds, 'digits') &&
      xssSanitizer('Suggested quality', quality, 'qualitylevels')) {
    player.cuePlaylist(videoList, parseInt(startIndex),
        parseInt(startSeconds), quality);
    addInformation('cuePlaylist([\'' + videoList.join('\',\'') + '\'], ' +
        startIndex + ', parseInt(' + startSeconds + '), ' + quality + ');');
  }
}

/**
 * The 'loadListArray' function loads a list of videos specified by
 * their video ID, calling the loadPlaylist function and using that
 * function's argument syntax.
 * @param {string} videoList Mandatory Array of video IDs.
 * @param {number} startIndex Optional First video to play in array.
 * @param {number} startSeconds Optional See loadVideo function.
 * @param {string} quality Optional See loadVideo function.
 */
function loadListArray(videoList, startIndex, startSeconds, quality) {
  // XSS sanitizer -- make sure params contain valid values
  if (xssSanitizer('Start index', startIndex, 'digits') &&
      xssSanitizer('Start at', startSeconds, 'digits') &&
      xssSanitizer('Suggested quality', quality, 'qualitylevels')) {
    player.loadPlaylist(videoList, parseInt(startIndex),
        parseInt(startSeconds), quality);
    addInformation('loadPlaylist([\'' + videoList.join('\',\'') + '\'], ' +
        startIndex + ', parseInt(' + startSeconds + '), ' + quality + ');');
  }
}

/**
 * The 'cueList' function loads a list of videos, which could be a
 * playlist, list of user uploads, list of user favorites, or set of
 * search results. It calls the cuePlaylist function and uses that
 * function's object syntax.
 * @param {string} listType Mandatory Type of list to cue.
 * @param {string} list Mandatory Combines with listType to identify list.
 * @param {number} startIndex Optional First video to play in array.
 * @param {number} startSeconds Optional See loadVideo function.
 * @param {string} quality Optional See loadVideo function.
 */
function cueList(listType, list, startIndex, startSeconds, quality) {
  // XSS sanitizer -- make sure params contain valid values
  if (xssSanitizer('Start index', startIndex, 'digits') &&
      xssSanitizer('Start at', startSeconds, 'digits') &&
      xssSanitizer('Suggested quality', quality, 'qualitylevels')) {
    listType = listType.replace('cue-', '');
    player.cuePlaylist({'listType': listType, 'list': list,
                        'index': startIndex,
                        'startSeconds': parseInt(startSeconds),
                        'suggestedQuality': quality});
    addInformation('cuePlaylist({\'listType\': \'' + listType + '\', \'' +
        '\'list\': \'' + list + '\',\'index\': \'' + startIndex + '\',' +
        '\'startSeconds\': \'' + startSeconds + '\',' +
        '\'suggestedQuality\': \'' + quality + '\'});');
  }
}

/**
 * The 'loadList' function loads a list of videos, which could be a
 * playlist, list of user uploads, list of user favorites, or set of
 * search results. It calls the loadPlaylist function and uses that
 * function's object syntax.
 * @param {string} listType Mandatory Type of list to load.
 * @param {string} list Mandatory Combines with listType to identify list.
 * @param {number} startIndex Optional First video to play in array.
 * @param {number} startSeconds Optional See loadVideo function.
 * @param {string} quality Optional See loadVideo function.
 */
function loadList(listType, list, startIndex, startSeconds, quality) {
  // XSS sanitizer -- make sure params contain valid values
  if (xssSanitizer('Start index', startIndex, 'digits') &&
      xssSanitizer('Start at', startSeconds, 'digits') &&
      xssSanitizer('Suggested quality', quality, 'qualitylevels')) {
    listType = listType.replace('load-', '');
    player.loadPlaylist({'listType': listType, 'list': list,
                        'index': startIndex,
                        'startSeconds': parseInt(startSeconds),
                        'suggestedQuality': quality});
    addInformation('loadPlaylist({\'listType\': \'' + listType + '\', \'' +
        '\'list\': \'' + list + '\',\'index\': \'' + startIndex + '\',' +
        '\'startSeconds\': \'' + startSeconds + '\',' +
        '\'suggestedQuality\': \'' + quality + '\'});');
  }
}

// Playback controls and player settings
/**
 * The 'play' function plays the currently cued/loaded video. It calls
 * player.playVideo().
 */
function play() {
  events.push('playVideo();');
  player.playVideo();
}

/**
 * The 'pause' function pauses the currently cued/loaded video. It calls
 * player.pauseVideo().
 */
function pause() {
  events.push('pauseVideo();');
  player.pauseVideo();
}

/**
 * The 'stop' function stops the currently cued/loaded video. It also
 * closes the NetStream object and cancels loading of the video. It calls
 * player.stopVideo().
 */
function stop() {
  events.push('stopVideo();');
  if( player != undefined)
  {
    player.stopVideo();
  }
}

/**
 * The 'seekTo' function seeks to the specified time of the video. The
 * time is specified as an offest, measured in seconds from the beginning
 * of the video. The function causes the player to find the closest
 * keyframe before the specified value.
 * @param {number} seconds Mandatory The time offset to skip to.
 * @param {boolean} allowSeekAhead Mandatory A flag that indicates if
 *     the player will make a new request to the server if the
 *     specified time is beyond the currently loaded video data.
 */
function seekTo(seconds, allowSeekAhead) {
  // XSS sanitizer -- make sure param contains a valid value
  if (xssSanitizer('Seek to', seconds, 'digits')) {
    events.push('seekTo(' + seconds + ', ' + allowSeekAhead + ');');
    player.seekTo(seconds, allowSeekAhead);
    document.getElementById('embedded-player-start').value = seconds;
  }
}

// Playing a video in a playlist

/**
 * The 'nextVideo' function plays the next video in a playlist.
 * It calls player.nextVideo().
 */
function nextVideo() {
  events.push('nextVideo();');
  player.nextVideo();
}

/**
 * The 'previousVideo' function plays the previous video in a playlist.
 * It calls player.previousVideo().
 */
function previousVideo() {
  events.push('previousVideo();');
  player.previousVideo();
}

/**
 * The 'playVideoAt' function seeks to a video at the specified playlist index.
 * @param {number} index Mandatory The playlist index of the video.
 */
function playVideoAt(index) {
  // XSS sanitizer -- make sure param contains a valid value
  if (xssSanitizer('Playlist index number', index, 'digits')) {
    events.push('playVideoAt(' + index + ');');
    player.playVideoAt(index);
  }
}

// Setting playback behavior for playlists
/**
 * The 'setLoop' function indicates whether videos should play in a loop.
 */
function setLoop() {
  loopVideos = loopVideos ? false : true;
  events.push('setLoop(' + loopVideos + ');');
  // Update UI to reflect correct looping status.
  document.getElementById('player-loop-status').innerHTML =
    loopVideos ? 'on' : 'off';
  document.getElementById('player-loop-link').innerHTML =
    loopVideos ? 'off' : 'on';
  document.getElementById('embedded-player-loop').checked =
    loopVideos ? true : false;
  player.setLoop(loopVideos);
}

/**
 * The 'setShuffle' function indicates whether videos should be shuffled.
 * If videos are already shuffled and parameter is true, videos will be
 * reshuffled. If parameter is false, videos return to original order.
 * @param {boolean} shuffleVideos Mandatory Set to true to shuffle videos.
 */
function setShuffle(shuffleVideos) {
  if (shuffleVideos) {
    shuffleCount += 1;
    document.getElementById('player-shuffle-text').style.display = '';
    document.getElementById('player-unshuffle-link').style.display = '';
  } else {
    shuffleCount = 0;
    document.getElementById('player-shuffle-text').style.display = 'none';
    document.getElementById('player-unshuffle-link').style.display =
        'none';
  }
  events.push('setShuffle(' + shuffleVideos + ');');
  player.setShuffle(shuffleVideos);
}

// Retrieving playlist information
/**
 * The 'getPlaylist' function returns a list of videos in a playlist.
 */
function getPlaylist() {
  var playlist = player.getPlaylist();
  if (playlist) {
    playlistVideosNode = document.getElementById('playlistvideos');
    if (playlistVideosNode) {
      while (playlistVideosNode.hasChildNodes()) {
        playlistVideosNode.removeChild(playlistVideosNode.firstChild);
      }
    }
    var listOfVideos = document.createElement('textarea');
    listOfVideos.id = 'playlist-videos';
    listOfVideos.cols = 12;
    listOfVideos.rows = Math.ceil(getPlaylistCount()) + 1;
    listOfVideos.innerHTML = playlist.join(',\n');
    playlistVideosNode.appendChild(listOfVideos);
  }
}

/**
 * The 'getPlaylistIndex' function returns the playlist index position
 * of the currently playing video based on the current playlist order.
 * It calls player.getPlaylistIndex().
 * @return {number} The playlist index of the currently playing video.
 */
function getPlaylistIndex() {
  var index = player.getPlaylistIndex();
  if (!index && index != 0) {
    return '';
  }
  return index;
}

/**
 * The 'getPlaylistCount' function returns the number of videos in a
 * playlist by calling player.getPlaylist() and returning the length
 * of the array returned by that function.
 * @return {number} The number of videos in the playlist.
 */
function getPlaylistCount() {
  var playlist = player.getPlaylist();
  if (playlist) {
    return playlist.length;
  }
}

// Changing the player volume

/**
 * The 'mute' function mutes the player. It calls player.mute().
 */
function mute() {
  events.push('mute();');
  player.mute();
}

/**
 * The 'unMute' function unmutes the player. It calls player.unMute().
 */
function unMute() {
  events.push('unMute();');
  player.unMute();
}

/**
 * The 'isMuted' function determines whether the player is muted.
 * @return {string} Returns 'on' if volume is on and 'off' if volume is muted.
 */
function isMuted() {
  if (!player.isMuted()) {
    return 'on';
  }
  return 'off';
}

/**
 * The 'getVolume' function returns the player volume. The volume is
 * returned as an integer on a scale of 0 to 100. This function will
 * not necessarily return 0 if the player is muted. Instead, it will
 * return the volume level that the player would be at if unmuted.
 * It calls player.getVolume().
 * @return {number} A number between 0 and 100 that specifies current volume.
 */
function getVolume() {
  if (player) {
    return player.getVolume();
  }
}

/**
 * The 'setVolume' function sets the player volume.
 * @param {number} newVolume Mandatory The new player volume. The value
 *     must be an integer between 0 and 100. It calls player.setVolume(volume).
 */
function setVolume(newVolume) {
  // XSS sanitizer -- make sure volume is just numbers.
  if (xssSanitizer('Volume', newVolume, 'digits')) {
    events.push('setVolume(' + newVolume + ');');
    player.setVolume(newVolume);
  }
}


// Playback status
/**
 * The 'getBytesLoaded' function returns the number of bytes loaded for
 * the current video. It calls player.getVideoBytesLoaded().
 * @return {number} The number of bytes loaded for the current video.
 */
function getBytesLoaded() {
  return player.getVideoBytesLoaded();
}

/**
 * The 'getBytesTotal' function returns the size in bytes of the currently
 * loaded/cued video. It calls player.getVideoBytesTotal().
 * @return {number} The total number of bytes in the video.
 */
function getBytesTotal() {
  return player.getVideoBytesTotal();
}

/**
 * The 'getVideoLoadedFraction' function returns the size in bytes of the currently
 * loaded/cued video. It calls player.getVideoLoadedFraction().
 * @return {number} The total number of bytes in the video.
 */
function getVideoLoadedFraction() {
  return player.getVideoLoadedFraction();
}

/**
 * The 'getStartBytes' function returns the number of bytes from which the
 * currently loaded video started loading. It calls player.getVideoStartBytes().
 * @return {number} The number of bytes into the video when the player
 *     began playing the video.
 */
function getStartBytes() {
  return player.getVideoStartBytes();
}

/**
 * The 'getPlayerState' function returns the status of the player.
 * @return {string} The current player's state -- e.g. 'playing', 'paused', etc.
 */
function getPlayerState() {
  if (player) {
    var playerState = player.getPlayerState();
    switch (playerState) {
      case 5:
        return 'video cued';
      case 3:
        return 'buffering';
      case 2:
        return 'paused';
      case 1:
        return 'playing';
      case 0:
        return 'ended';
      case -1:
        return 'unstarted';
      default:
        return 'Status uncertain';
    }
  }
}

/**
 * The 'getCurrentTime' function returns the elapsed time in seconds from
 * the beginning of the video. It calls player.getCurrentTime().
 * @return {number} The elapsed time, in seconds, of the playing video.
 */
function getCurrentTime() {
  var currentTime = player.getCurrentTime();
  return roundNumber(currentTime, 3);
}

// Playback quality
/**
 * The 'getQuality' function returns the actual playback quality of the
 * video shown in the player.
 * @return {string} The quality level of the currently playing video.
 */
function getQuality() {
  var quality = player.getPlaybackQuality();
  if (!quality) {
    return '';
  }
  return quality;
}

/**
 * The 'setQuality' function sets the suggested playback quality for the
 * video. It calls player.setPlaybackQuality(suggestedQuality:String).
 * @param {string} newQuality Mandatory The suggested playback quality.
 */
function setQuality(newQuality) {
  events.push('setPlaybackQuality(' + newQuality + ');');
  player.setPlaybackQuality(newQuality);
}

/**
 * The 'getQualityLevels' function retrieves the set of quality formats
 * in which the current video is available. It calls
 * player.getAvailableQualityLevels().
 * @return {string} A string (comma-separated values) of available quality
 *                  levels for the currently playing video.
 */
function getQualityLevels() {
  return player.getAvailableQualityLevels();
}

// Playback rate
/**
 * The 'getPlaybackRate' function returns the current playback rate of the
 * video shown in the player.
 * @return {string} The playback rate of the currently playing video.
 */
function getPlaybackRate() {
  return player.getPlaybackRate() || '';
}

/**
 * The 'setPlaybackRate' function sets the playback rate for the video.
 * It calls player.setPlaybackRate(playbackRate:String).
 * @param {string} playbackRate Mandatory The desired playback rate.
 */
function setPlaybackRate(playbackRate) {
  if (xssSanitizer('Playback rate', playbackRate, 'decimal')) {
    events.push('setPlaybackRate(' + playbackRate + ');');
    player.setPlaybackRate(playbackRate);
  }
}

/**
 * The 'getAvailablePlaybackRates' function retrieves the supported playback
 * rates for the currently playing video. It calls
 * player.getAvailablePlaybackRates().
 * @return {string} A string (comma-separated values) of available playback
 *                  rates for the currently playing video.
 */
function getAvailablePlaybackRates() {
  return player.getAvailablePlaybackRates();
}

// Retrieving video information

/**
 * The 'getDuration' function retrieves the length of the video. It calls
 * player.getDuration() function.
 * @return {number} The length of the video in seconds.
 */
function getDuration() {
  return player.getDuration();
}

/**
 * The 'getVideoUrl' function returns the YouTube.com URL for the
 * currently loaded/playing video. It calls player.getVideoUrl().
 */
function getVideoUrl() {
  var videoUrl = player.getVideoUrl();
  updateHTML('videoUrl', videoUrl);
}


// Player size ... setPlayerHeight and setPlayerSize

/**
 * The 'setPlayerHeight' function calculates the height of the player
 * for the given aspect ratio and width, which are specified in the demo.
 * This ensures that the player dimensions are a legitimate aspect ratio,
 * which should make videos look nicer.
 * @param {string} aspectRatio Mandatory The aspect ratio of the player.
 *     Valid values are 'standard' (4x3) and 'widescreen' (16x9).
 * @param {number} playerWidth Mandatory The pixel-width of the player.
 */
function setPlayerHeight(aspectRatio, playerWidth) {
  // XSS sanitizer -- make sure player width is just numbers.
  if (xssSanitizer('Width', playerWidth, 'digits')) {
    if (aspectRatio == 'widescreen') {
      updateHTML('playerHeight', ((playerWidth * 9) / 16));
    } else if (aspectRatio == 'standard') {
      updateHTML('playerHeight', ((playerWidth * 3) / 4));
    }
  }
}

/**
 * The 'setPlayerSize' function adjusts the size of the video and of the
 * DOM element to match the width and height set in the demo.
 * @param {number} playerWidth Mandatory The desired player width.
 * @param {number} playerHeight Mandatory The desired player width.
 */
function setPlayerSize(playerWidth, playerHeight) {
  if (xssSanitizer('Width', playerWidth, 'digits')) {
    events.push('setSize(' + playerWidth + ', ' + playerHeight + ');');
    player.setSize(playerWidth, playerHeight);
    document.getElementById(activePlayer).width = playerWidth;
    document.getElementById(activePlayer).height = playerHeight;
  }
}

// Retrieving video information and playback status

/**
 * The 'updateytplayerInfo' function updates the volume and
 * "Playback statistics" displayed  on the page. (It doesn't actually
 * update the player itself.) The onYouTubePlayerReady uses the
 * setInterval() function to indicate that this function should run
 * every 600 milliseconds.
 */
function updateytplayerInfo() {
  if (player) {
    updateHTML('volume', Math.round(getVolume()));

    updateHTML('videoduration', getDuration());
    updateHTML('videotime', getCurrentTime());
    updateHTML('playerstate', getPlayerState());

    updateHTML('bytestotal', getBytesTotal());
    updateHTML('startbytes', getStartBytes());
    var fraction = getVideoLoadedFraction();
    if (fraction) {
      updateHTML('percentloaded', Number(fraction.toFixed(4)));
    }
    updateHTML('playbackrate', getPlaybackRate());
    updateHTML('availableplaybackrates', getAvailablePlaybackRates());
    updateHTML('bytesloaded', getBytesLoaded());

    updateHTML('playbackquality', getQuality());
    updateHTML('availablelevels', getQualityLevels());
    updateHTML('ismuted', isMuted());

    // TODO: Move calls to getPlaylistCount() and getPlaylist()
    // elsewhere since these only change when player content changes.
    if (contentType != 'video' && contentType != 'videolist') {
      updateHTML('playlistcount', getPlaylistCount());
      updateHTML('currentplaylistvideo', getPlaylistIndex());
      getPlaylist();
    }
  }
  if (events.length > 0) {
    updateHTML('eventhistory', '<ol><li>' + events.join('<li>') + '</ol>');
  }
  if (errors.length > 0) {
    updateHTML('errorCode', '<ol><li>' + errors.join('<li>') + '</ol>');
  }
}

function roundNumber(number, decimalPlaces) {
  decimalPlaces = (!decimalPlaces ? 2 : decimalPlaces);
  return Math.round(number * Math.pow(10, decimalPlaces)) /
      Math.pow(10, decimalPlaces);
}

/**
 * The 'xssSanitizer' function tries to make sure that the user isn't being
 * directed to something that would exploit an XSS vulnerability by verifying
 * that the input value matches a particular rule. If the provided value is
 * invalid, the page will display an error indicating that either the value
 * is invalid or that it doesn't have XSS vulnerabilities to exploit.
 * @param {string} field Mandatory A name that identifies the field being
 *     validated. This will appear in the error list if the value is bad.
 * @param {string} value Mandatory The value to be validated.
 * @param {string} rulesOfSanitation Mandatory A string that identifies
 *     the accepted format of the value -- e.g. alphanumeric, digits,
 *     videoId, etc.
 * @param {boolean} skipEvent Optional A flag that indicates that the
 *     error should not be printed. This is used to avoid inadvertently
 *     displaying an error when a field could include, say, a videoId or
 *     a videoUrl.
 * @return {boolean} Returns true if the value is valid and false if not.
 */
function xssSanitizer(field, value, rulesOfSanitation, skipEvent) {
  var regex = /[\"\<\>]/;
  if (value.match(regex)) {
    errors.push('These aren\'t the XSS vulnerabilities you\'re looking for.');
    return false;
  } else if (rulesOfSanitation) {
    if (rulesOfSanitation == 'alphanumeric') {
      var regex = /[\W]/;
      if (value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be an alphanumeric string.');
        return false;
      }
    } else if (rulesOfSanitation == 'digits') {
      var regex = /[\D]/;
      if (value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be an integer.');
        return false;
      }
    } else if (rulesOfSanitation == 'decimal') {
      var regex = /[0-9\.]+/;
      if (!value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be an integer or decimal value.');
        return false;
      }
    } else if (rulesOfSanitation == 'hl') {
      var regex = /[a-zA-Z\-\_\.]+/;
      if (!value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'Set the value to an ISO 639-1 two-letter language code or ' +
            'a fully specified locale, such as <code>fr</code> or ' +
            '<code>fr-ca</code>.');
        return false;
      }
    } else if (rulesOfSanitation == 'playlist') {
      var regex = /^[\w\-]{11}(,[\w\-]{11})*$/;
      if (!value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be a comma-delimited ' +
            'list of 11-character YouTube video IDs.');
        return false;
      }
    } else if (rulesOfSanitation == 'playlistId') {
      var regex = /^([A-Z][A-Z])([\w\-]+)$/;
      if (!value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be a valid YouTube playlist ID.');
        return false;
      }
    } else if (rulesOfSanitation == 'username') {
      var regex = /[\W]/;
      if (value.match(regex)) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be an alphanumeric string.');
        return false;
      }
    } else if (rulesOfSanitation == 'qualitylevels') {
      if (!qualityLevels[value]) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be a supported quality level.');
      }
    } else if (rulesOfSanitation == 'videoIdOrUrl') {
      if (!xssSanitizer(field, value, 'videoId', true)) {
        if (!xssSanitizer(field, value, 'videoUrl', true)) {
          errors.push(field + ' &ndash; This value is not supported. ' +
              'The value must be an 11-character YouTube video ID or ' +
              'a YouTube watch page URL in the format ' +
              '\'https://www.youtube.com/embed/VIDEO_ID\'.');
          return false;
        }
      }
    } else if (rulesOfSanitation == 'videoId') {
      var regex = /^[\w\-]{11}$/;
      if (value.match(regex)) {
        return true;
      }
      if (!skipEvent) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be an 11-character YouTube video ID.');
      }
      return false;
    } else if (rulesOfSanitation == 'videoUrl') {
      var regex = /^https?\:\/\/www.youtube.com\/embed\/([\w\-]){11}$/;
      if (value.match(regex)) {
        return true;
      }
      if (!skipEvent) {
        errors.push(field + ' &ndash; This value is not supported. ' +
            'The value must be a YouTube watch page URL in the ' +
            'format \'https://www.youtube.com/embed/VIDEO_ID\'.');
      }
      return false;
    } else if (rulesOfSanitation == 'search') {
      return true;
    }
  }
  return true;
}