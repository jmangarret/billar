//Buscar videos
$(".formkaraoke").on("submit", function(e) {
	e.preventDefault();
	$.ajax({
		url : '/searchresults',
		data: $(this).serialize(),
		success: function(response){
			$('#results').html(response);
		}
	});
});
//Add video
var addvideo=function(videoid){
    $.ajax({
      url : "/karaokeaddvideo",
      data : {"id" : videoid },
      success : function(response){
        $('#contenido').html(response);                            
      }
    });
};
//List videos
$(document).ready(function(){
    $.ajax({
      url : "/karaokelist",
      success : function(response){
        $('#contenido').html(response);                            
      }
    });
});
