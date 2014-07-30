$(function(window,undefined){
var plugins=[];
plugins.add=function(plugin){
	for(var i=0;i<plugins.length;i++){
		if(plugin.id==plugins[i].id){
			plugins[i]==plugin;
		}
	}
}
plugin=function(properties){
	if(properties!=undefined){
		this.props={};
		var keys=Object.keys(properties);
		for(var i=0;i<keys.length;i++){
			this.props[keys[i]]=properties[keys[i]];
		}
	}
	return this;
}
plugin.prototype={
	name:function(name){
		if(name==undefined){
			return this.props.name;
		}
		else{
			this.props.name=name;
			return this;
		}
	},
	url:function(url){
		if(url==undefined){
			return this.props.url;
		}
		else{
			this.props.url=url;
			return this;
		}
	},
	update:function(update){
		if(name==undefined){
			return this.props.update;
		}
		else{
			this.props.update=update;
			return this;
		}
	},
	markup:function(markup){
		if(markup==undefined){
			return this.props.markup
		}
		else{
			this.props.markup=markup;
			return this;
		}
	},
	get:function(){
		$.ajax("api/get.php",
			{
				data:{plugin:this.url()},
				success:function(data){
					var markup=$("<div/>").addClass("plugin")
						.append($("<h3/>").text(data.title))
						.append(data.markup);
					markup.replaceAll(this.markup());
				},
				type:"POST",
				dataType:"json",
				context:this
			}
		);
	}
}
function getAvaliablePlugins(){
	$.post("api/list.php",
		{},
		function(data){
			for(var i=0;i<data.length;i++){
				var toAdd=new plugin({url:data[i],markup:$("<div/>").addClass("plugin")});
				$(".tiles").append(toAdd.markup());
				plugins.add(toAdd);
				toAdd.get();
			}
		},
		'json'
	);
}
getAvaliablePlugins();
});
