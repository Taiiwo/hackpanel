$(function(){
var hackathon = [];
var log = $('.container');
$.cookie.json=true;

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
plugins.order=function(order){
	if(order!==undefined){
		$.cookie("plugin-order",order,{expires:100*365});
	}
	return $.cookie("plugin-order");
}
//custom sort for getting plugins in the correct order
plugins.sortCompare=function(a,b){
	var aLocation=plugins.order().indexOf(a.url()),
			bLocation=plugins.order().indexOf(b.url())
	return aLocation-bLocation
}
plugins.recordOrder=function(e, selected){
	var order=Array();
	var $plugins=$(selected).parent().children();
	for(var i=0;i<$plugins.length;i++){
		order[i]=$plugins.eq(i).data("plugin-name");
	}
	plugins.order(order);
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
					var markup=$("<div/>").addClass("plugin").addClass(this.url())
						.append($("<header>|||<span class='pluginName'>"+this.name()+"</span></header>"))
						.append(
							$(data.markup)
								.fadeIn(500)
								.css('display', '')
						);
					$('.' + this.url()).attr('title',this.name());
					if (data.size[0] > 1 || data.size[1] != 250){
						$('.' + this.url())
							.animate({width: data.size[0] * 290 + (data.size[0] - 1) * 15}, 200)
							.attr('data-ss-colspan', data.size[0])
							.animate({height:40 + data.size[1]}, 200)
						reloadGrid();

					}
          //resize plugin


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
      //loop through the available plugins and add them to the plugins array
			$('.container').empty();
			for(var i=0;i<data.length;i++){
				var toAdd=new plugin({url:data[i],markup:$("<div/>").addClass("plugin").addClass(data[i]).data("plugin-name",data[i])});
				plugins.add(toAdd);
				toAdd.get();
			}
			plugins.sort(plugins.sortCompare);
			for(var i=0;i<plugins.length;i++){
				toAdd=plugins[i];
				$('.container').append(toAdd.markup().fadeIn().append('<div class="spinner"></div>'));
			}
			reloadGrid();
		},
		'json'
	);
}

function reloadGrid(){
	var options = {
                minColumns: 2,
                gutterX: 15,
                gutterY: 15,
                handle: "header",
                centerWhileDragging: false,
                animationSpeed: 300
	};
	if (!is_desktop){
		options['minColumns'] = 1;
		$('.plugin').width(290)
			.attr('data-ss-colspan', 1);
	}

	$('.container').unbind('ss-rearranged',plugins.recordOrder);
	$('.container').shapeshift(options);
	$('.container').on('ss-rearranged',plugins.recordOrder);
}
//initialise all the plugins



//Controls the auto complete functionality
var search={
	options:[],
  //gets the known events from the server
	loadJSON:function(){
		$.ajax("api/hackathons.json",{
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
					$("#search").val(e.data.default);
					$("#searchBox").submit();
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

//update search terms when the search box is changed
$("#search").bind('input',function(){
	search.loadResults(this.value);
});

//load plugins when search term is submitted
$("#searchBox").submit(function(e){
  e.preventDefault();
  getAvaliablePlugins();
  var searchTerm=$(this).find("#search").val()
  var found=false;

  //is it a known hackathon
  for(var i=0;i<search.options.length;i++){
    if(search.options[i].default==searchTerm){
      found=true;
      break;
    }
  }
  if(found){
    hackathon=search.options[i];
    $('#alphaBar').fadeOut(500);
  }
  else{
    //otherwise just let it search itself
    hackathon={default:searchTerm};
  }
  for(var i=0;i<plugins.length;i++){
    plugins[i].get();
  }

  //animate tiles in and autocomplete out
  $(".container").fadeIn(500);
  $("#search").blur();
  $("#search").focus(function(){$(".search-items").slideDown()});
})

var is_desktop = true;
$( window ).resize(function (){
	if (document.body.clientWidth < 614){
		is_desktop = false;
		reloadGrid();
	}
	else if (document.body.clientWidth >= 614 && is_desktop == false){
		is_desktop = true;
		getAvaliablePlugins();
		reloadGrid();
	}
});


});
