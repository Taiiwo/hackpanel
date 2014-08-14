$(function(){
var hackathon = [];
var log = $('.tiles');

//creates the drag and drop grid
var pluginsGrid = $(".tiles").gridster({
       	widget_base_dimensions: [290, 290],
       	widget_margins: [15, 15],
       	autogrow_cols: true,
	min_cols: 1,
	max_cols: 3,
	draggable: {
		handle: 'header'
	},
       	resize: {
       		enabled: true
       	}
}).data('gridster');
$(".tiles").fadeOut(10);

//this is where the plugins are stored
var plugins = [];
plugins.add=function(plugin){
	for(var i=0;i<plugins.length;i++){
		if(plugin.url()==plugins[i].url()){
			plugins[i]=plugin;
			return;
		}
	}
	plugins.push(plugin);
}

//The plugin object
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
  //set or retrieve properties
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

  //the update function of the plugin
	get:function(){
    //fetch the latest plugin info from the server
		$.ajax("api/get.php",
			{
				data:{
					plugin:this.url(),
					searchTerm:hackathon.default
				},
				success:function(data){
					this.name(data.title);
          //create the plugin box and populate
					var markup=$("<ln/>").addClass("plugin").addClass(this.url())
						.append($("<header>|||<span class='pluginName'>"+this.name()+"</span></header>"))
						.append(data.markup);

          //place plugin
					for ( var ind = 0; ind < pluginsGrid.$widgets.length; ind++){
						if ( pluginsGrid.$widgets.eq(ind).attr('class').split(' ')[1] == this.url()){
							/* If 'move_widget' was a thing that gridster would let you do,
								this code would fix plugins sometimes not resizing, but
								it's obviously completely untested.
							if ((pluginsGrid.$widgets.eq(ind).attr('data-col') - 1) + data.size[0] > 3{
								pluginsGrid.move_widget(pluginsGrid.$widgets.eq(ind), -(3 - ((pluginsGrid.$widgets.eq(ind).attr('data-col') - 1) + data.size[0]));
							}*/
							pluginsGrid.resize_widget(pluginsGrid.$widgets.eq(ind), data.size[0], data.size[1]);
						}
					};

					$('.' + this.url()).attr('title',this.name());

          //insert plugin box into the page markup
					this.markup().empty();
					this.markup().append(markup.contents());
					if ( data.style != 'null' ) {
						$("head").append("<style type='text/css'>"+data.style+"</style>");
					}
          
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


tiles = {};
function getAvaliablePlugins(){
	$.post("api/list.php",
		{},
		function(data){
			for(var i=0;i<data.length;i++){
				var toAdd=new plugin({url:data[i],markup:$("<ln/>").addClass("plugin").addClass(data[i])});
				console.log(pluginsGrid);
				tiles[data[i]] = pluginsGrid.add_widget(toAdd.markup());
				plugins.add(toAdd);
				toAdd.get();
			}
		},
		'json'
	);
}
getAvaliablePlugins();



//Controls the auto complete functionality
var search={
	options:[],
  //gets the known events from the server
	loadJSON:function(){
		$.ajax("api/searches.json",{
			dataType:'json',
			success:function(data){
				search.options=data;
				search.loadResults();
			}
		})
	},

  //processes and sort the results
	loadResults:function(term){
		if(term==undefined)var term="";
		var resultsMarkup=$("<div/>").addClass("search-items");

    //loop through events and check if search term is found
    //in any of the fields
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
        //since it's a match add the result to the list
				var result=search.options[i];
				var individualResult=$("<div/>").addClass("searchResult").append(
						$("<div><div/></div>").find('div').append(
							$("<h2/>").text(result.default)
							).append(
								$("<span/>").addClass("dates").text(result.startDate+"-"+result.endDate)
							)
						);
        //when it's clicked auto fill the field
				individualResult.click(result,function(e){
					hackathon=e.data;
					$("#search").val(e.data.default);
					$("#search").blur();
					$(".tiles").fadeIn(500);
					$("#search").focus(function(){$(".search-items").slideDown()});
					for(var i=0;i<plugins.length;i++){
						plugins[i].get();
					}
				})
				resultsMarkup.append(individualResult);
			}
		}

    //show and hide the suggestions depending on search field focus
    if($("#search:focus").length==0){
      resultsMarkup.hide();
    }
    $("#search").focus(resultsMarkup,function(e){
      e.data.slideDown();
    })
    $("#search").blur(resultsMarkup,function(e){
      e.data.slideUp();
    })
		resultsMarkup.replaceAll($(".search-items"));
	}
}

search.loadJSON();
$("#search").bind('input',function(){
	search.loadResults(this.value);
});
});
