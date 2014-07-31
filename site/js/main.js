$(function(window,undefined){
var hackathon={};//options for the selected hackathon
var plugins=[];
$(".tiles").fadeOut(10);
plugins.add=function(plugin){
	for(var i=0;i<plugins.length;i++){
		if(plugin.url()==plugins[i].url()){
			plugins[i]=plugin;
			return;
		}
	}
	plugins.push(plugin);
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
				data:{
					plugin:this.url(),
					searchTerm:hackathon.default
				},
				success:function(data){
					var markup=$("<div/>").addClass("plugin").addClass(this.url())
						.append($("<h3/>").text(data.title))
						.append(data.markup);
					this.markup().empty()
					this.markup().append(markup.contents());
					//If data.scripts is a non-null array
					if(typeof(data.scripts)==typeof([])&data.scripts!=null){
						//loop through each script
						for(var i=0;i<data.scripts.length;i++){
							//if /*Injected*/ is at the start of this array element
							if(data.scripts[i].match(/\/\*Injected\*\//) != null){
								//Add the script to the HTML with script tags
								$("head").append("<script type='text/javscript'>"+data.scripts[i]+"</script>");
							}
							else{
								if($("head>script[src='"+data.scripts[i]+"']").length<0){
 									//only add if if doen't exist
									$("head").append($("<script type='text/javascript'>").attr("src",data.scripts[i]));
 								}
							}
						}
					}
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
				var toAdd=new plugin({url:data[i],markup:$("<div/>").addClass("plugin").addClass(data[i])});
				$(".tiles").append(toAdd.markup());
				plugins.add(toAdd);
				toAdd.get();
			}
		},
		'json'
	);
}
getAvaliablePlugins();




search={
	options:[],
	loadJSON:function(){
		$.ajax("api/searches.json",{
			dataType:'json',
			success:function(data){
				search.options=data;
				search.loadResults();
			}
		})
	},
	loadResults:function(term){
		if(term==undefined)var term="";
		var resultsMarkup=$("<div/>").addClass("search-items");
		for(var i=0;i<search.options.length;i++){
			var found=false;
			var keys=Object.keys(search.options[i]);
			for(var ii=0;ii<keys.length;ii++){
				if(typeof(search.options[i][keys[ii]])==typeof("")){
					if(search.options[i][keys[ii]].search(term)!=-1){
						found=true;
					}
				}
				if(typeof(search.options[i][keys[ii]])==typeof([])){
					for(var iii=0;iii<search.options[i][keys[ii]].length;iii++){
						if(search.options[i][keys[ii]][iii].search(term)!=-1){
							found=true;
						}
					}
				}
			}
			if(found==true){
				var result=search.options[i];
				var individualResult=$("<div/>").addClass("searchResult").append(
						$("<div><div/></div>").find('div').append(
							$("<h2/>").text(result.default)
							).append(
								$("<span/>").addClass("dates").text(result.startDate+"-"+result.endDate)
							).append(
								$("<span/>").addClass("description").text(result.description)
							)
						);
				individualResult.click(result,function(e){
					hackathon=e.data;
					$("#search").val(e.data.default);
					$(".search-items").slideUp();
					$(".tiles").fadeIn(500);
					$("#search").focus(function(){$(".search-items").slideDown()});
					for(var i=0;i<plugins.length;i++){
						plugins[i].get();
					}
				})
				resultsMarkup.append(individualResult);
			}
		}
		resultsMarkup.replaceAll($(".search-items"));
	}
}
search.loadJSON();
$("#search").bind('input',function(){
	search.loadResults(this.value);
});

});
