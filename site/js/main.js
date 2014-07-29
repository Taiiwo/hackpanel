$(function(window,undefined){
	$.post("api/get.php",
		{plugin:"pluginTemplate"},
		function(data){
			$(".container").append(data.markup);
		});
});