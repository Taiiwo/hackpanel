$(function(window,undefined){
plugins=[];
plugins.add=function(plugin){
	for(var i=0;i<plugins.length;i++){
		if(plugin.id==plugins[i].id){
			plugins[i]==plugin;
		}
	}
}
plugin=function(properties){
	if(properties!=undefined){
		var keys=Object.keys(properties);
		for(var i=0;i<keys.length;i++){
			this[keys[i]]=prooerties[keys[i]];
		}
	}
	return this;
}
plugin.prototype={
	name:function(name){
		if(name==undefined){
			return this.name;
		}
		else{
			this.name=name;
			return this;
		}
	}
	url:function(url){
		if(url==undefined){
			return this.url;
		}
		else{
			this.url=url;
			return this;
		}
	}
	update:function(update){
		if(name==undefined){
			return this.update;
		}
		else{
			this.update=update;
			return this;
		}
	}
	markup:function(markup){
		if(markup==undefined){
			return this.markup
		}
		else{
			this.markup=markup;
			return this;
		}
	}
}
function appendPluginData(name){
	$.post("api/get.php",
		{plugin:name},
		function(data){
			var markup=$("<div/>").addClass("plugin")
				.append($("<h3/>").text(data.title))
				.append(data.markup);
			$(".container").append(markup);
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
