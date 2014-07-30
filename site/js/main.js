$(function(window,undefined){
function appendPluginData(name){
	$.post("api/get.php",
		{plugin:name},
		function(data){
			var markup=$("<div/>").addClass("plugin")
				.append($("<h3/>").text(data.title))
				.append(data.markup);
			$(".tiles").append(markup);
		},
		'json'
	);
}
function getAvaliablePlugins(){
	$.post("api/list.php",
		{},
		function(data){
			for(var i=0;i<data.length;i++){
				appendPluginData(data[i]);
			}
		},
		'json'
	);
}
getAvaliablePlugins();
});
