var playListId, channelId;

$(function() {
    $(".formkaraoke").on("submit", function(e) {
       e.preventDefault();
       // prepare the request
       var request = gapi.client.youtube.search.list({
            part: "snippet",
            type: "video",
            q: encodeURIComponent('Karaoke '+$("#search").val()).replace(/%20/g, "+"),
            maxResults: 3,
            order: "viewCount",
            publishedAfter: "2015-01-01T00:00:00Z"
       }); 
       // execute the request
       request.execute(function(response) {
          var results = response.result;
          var item = '';
          $("#results").html("");
          $.each(results.items, function(index, item) {
              htmlItem  = '<div class="item">';
              htmlItem += '<h2 class="btn-primary">'+item.snippet.title+'</h2>';
              htmlItem += '<div align="center">';
              htmlItem += '<iframe class="video w100" width="640" height="360" src="//www.youtube.com/embed/'+item.id.videoId+'" frameborder="0" allowfullscreen></iframe>';
              htmlItem += '<button class="btn btn-primary" onclick="addVideoToPlaylist(\''+item.id.videoId+'\')">addPlayList</button>';
              htmlItem += '</div>';
              htmlItem += '</div>';
              $("#results").append(htmlItem);
           
          });
          resetVideoHeight();
       });
    });
    
    $(window).on("resize", resetVideoHeight);
});

function resetVideoHeight() {
    $(".video").css("height", $("#results").width() * 9/16);
}

function init() {
    gapi.client.setApiKey("AIzaSyAAlWToOBS1ysvNuCKFBdCVtYER3TmowU0");
    gapi.client.load("youtube", "v3", function() {
        // yt api is ready
    });
}



/**********CREAR LISTA DE REPRODUCCION ************/
function getList(listId){
  $.get(
    "https://www.googleapis.com/youtube/v3/playlists",{
      part : 'id',
      id : listId,
      key : 'AIzaSyAAlWToOBS1ysvNuCKFBdCVtYER3TmowU0'
    },
    function(data){
      $.each(data.items, function(i, item){
        console.log(item);
      })
    }
  );
}

function crearPlayList(){
  var auth = getDatosAuth();
  $.post(
    "https://www.googleapis.com/youtube/v3/playlists",{
        part: 'snippet,status',
        resource: {
          snippet: {
            title: 'Test Playlist',
            description: 'Myplaylist created with the YouTube API'
          },
          status: {
            privacyStatus: 'private'
          }
        },
        authorization : auth
      })
      .done(function(response){
          console.log('crearPlayList: ');
          console.log(response);
          console.log(response.id);
          console.log(response.snippet.title);
          console.log(response.snippet.description);
          playListId = response.id;
      })
      .fail(function(xhr, textStatus, error){
        var errorCode = xhr.status;
        console.log('Error Code: '+errorCode);
        if (errorCode==401){
          var newWindow = window.open("","",'height=500,width=800');
          var newURL = getUrlAuth();
          newWindow.location.href = newURL;
          newWindow.focus();
        }
      });
}

function addToPlaylist(id, startPos, endPos) {
  var auth = getDatosAuth();
  var details = {
    videoId: id,
    kind: 'youtube#video'
  }
  if (startPos != undefined) {
    details['startAt'] = startPos;
  }
  if (endPos != undefined) {
    details['endAt'] = endPos;
  }
  $.post(
    "https://www.googleapis.com/youtube/v3/playlists",{
    part: 'snippet',
    resource: {
      snippet: {
        playlistId: playListId,
        resourceId: details
      }
    },
    authorization : auth
  },
  function(response){
    $('#status').html('<pre>' + JSON.stringify(response.result) + '</pre>');
  }
  );
}
//add video a playlist
function addVideoToPlaylist(videoId) {
  loginYoutube();
  /*
  getUrlAuth();
  auth2youtube();
  handleClientLoad();
  addToPlaylist(videoId);
  getList(playListId); 
  */
}

function loginYoutube(){
  var newWindow = window.open("","",'height=500,width=800');
  var newURL = getUrlAuth();
  newWindow.location.href = newURL;
  newWindow.focus();
}


var client_id     = '';
var client_secret = '';
var redirect_uri  = '';
var urlAuth       = '';
var urlToken       = '';

var response_type = 'code';
var scope         = 'https://www.googleapis.com/auth/youtubepartner https://www.googleapis.com/auth/youtube';
var login_hint    = 'icompsoluciones@gmail.com';
var access_type   = 'offline';
var grant_type_aut= 'authorization_code';
var grant_type_ref= 'refresh_token';

var urlAuth2      = 'https://www.googleapis.com/oauth2/v4/token';
var code          = '4/AAB1tUJMTx-LbUgVpNqGWGwTNVahcLM9mKtkbvv2MTqmKEaUeXDvHlgH1UH7l8QUg4vC2SZLH1bb5AuAF2CTsyk';

function getUrlAuth(){
    urlAuth   += '?' + 'client_id='+client_id+'&redirect_uri='+redirect_uri; 
    urlAuth   += '&response_type='+response_type+'&scope='+scope+'&login_hint='+login_hint; 
    return urlAuth;
}

function updateTokenAuth(){
    var auth = getDatosAuth();
    var token = auth.split(" ");

    $.post('https://accounts.google.com/o/oauth2/token',{
      client_id     : client_id,
      client_secret : client_secret,
      refresh_token : token[1],
      grant_type    : grant_type_ref
    })
    .done(function(response){
      console.log('Done! '+response);
    })
    .fail(function(xhr, textStatus, error){
      console.log('Error:'+error);
    });
}

function getDatosAuth(){
  $.getJSON("token_auth.json", function(data) {
    var token = data.token_type + " " + data.access_token;
    return token;
  });
}

function getTokenInfo(){
    $.post('https://www.googleapis.com/oauth2/v1/tokeninfo',{
      access_token     : code,
    })
    .done(function(response){
      console.log('Done! '+response);
    })
    .fail(function(xhr, textStatus, error){
      console.log('Error:'+error);
  });
}
function getDatosClient(){

  $.getJSON("client_id.json", function(data) {
      var c = {
          client_id : data.web.client_id,
          auth_uri  : data.web.auth_uri,
          token_uri : data.web.token_uri,
          client_secret : data.web.client_secret,
          redirect_uris : data.web.redirect_uris
      };

      client_id     = c.client_id;
      redirect_uri  = c.redirect_uris[0];
      client_secret = c.client_secret;
      urlAuth       = c.auth_uri;
      urlToken      = c.token_uri;

      console.log(client);

      return c;
  });
}

/*
function getTokenAuth(){
    $.post('https://accounts.google.com/o/oauth2/token',{
    client_id     : '816045738066-pcdaprans3ktgkhucbishsui9qucehn1.apps.googleusercontent.com',
    client_secret : 'xRXy4u1_Nh6G5WwjuvpHhrm0',
    redirect_uri  : 'http://127.0.0.1:8000/oauth2youtube',
    grant_type    : 'authorization_code'
  })
  .done(function(response){
    console.log('Done! '+response);
  })  
  .fail(function(xhr, textStatus, error){
    console.log('errror:'+error);
  });
}*/