
function initializeMap(image_file, image_width, image_height, icon_file){
	$('ul#catmaplist').fadeOut();
		$('#map').fadeOut(500, function(){
			$('#map').empty()
			.css({
				width: '0px',
				height: '0px',
				backgroundImage: 'url(' + image_file + ')',
				position: 'relative'
			})
			.click(function(){
				hideMapHighlight();
			});
		if ($('a.bullet').length > 0) { 
			var animateObject = new Object();
			animateObject.width = image_width + 'px';
			animateObject.height = image_height + 'px';
			$('#map').animate(animateObject, 600); 
		}
		loadBullets();
		// $('#map a.bullet').css('background-image', 'url(' + icon_file + ')');
		//loadClosePane(image_file, image_width, image_height, icon_file);
	});
	$('<div class="popup">The Popup</div>').appendTo('#map');
}

function loadClosePane(image_file, image_width, image_height, icon_file){
	$('<a id="mapclose" href="javascript:void(0)"><span>Close Map</span></a>')
		.appendTo('#map')
		.click(function(){ 
			$('#map')
				.empty()
				.animate({
					width: '100px',
					height: '30px'
				}, 1000)
				.html('<a id="mapopen" href="javascript:void(0)"><span>Open Map</span></a>')
				.fadeIn();
			$('#mapopen')
				.click(function(){
					initializeMap(image_file, image_width, image_height, icon_file);
				});
		});
}

function loadBullets(){
	$('a.bullet').each(function(){
		var coords = $(this).attr('rel').split('-');
		var nodeid = $(this).attr('id');
		var popupid = nodeid + '-box';
		$(this).clone()
			.attr('title', $(this).html())
			.html($(popupid).html())
			.attr('id', 'map-' + nodeid)
			.appendTo('#map')
			.css({left: coords[0] + 'px', top: coords[1] + 'px'})
			.hide()
			.fadeIn()
			.hover(
				function(){showMapHighlight(nodeid);},
				function(){}
			)
			;
	});
}

function showNodeName(id){
	var nodeid = '#' + id;
	$(nodeid).css({color: 'blue'}).parent().css({color: 'blue'});
	showMapHighlight(id);
}

function hideNodeName(id){
	var nodeid = '#' + id;
	$(nodeid).css({color: '#333333'}).parent().css({color: '#333333'});
	hideMapHighlight();
}

function showMapHighlight(id){
	var mapnodeid = '#map-' + id;
	$('#map a.bullet').removeClass('catmaphighlight');
	$(mapnodeid).addClass('catmaphighlight');
	showPopup(id);
}

function hideMapHighlight(){
	//var mapnodeid = '#map-' + id;
	//$(mapnodeid).removeClass('catmaphighlight');
	$('#map a.bullet').removeClass('catmaphighlight');
	hidePopup();
}

function showPopup(id){
	var nodeid = '#' + id;
	var nodeboxid = '#' + id + '-box';
	var popuphtml = '<h3>' + $(nodeid).text() + '</h3><div class="popupcontent"><p>' + $(nodeboxid).html() + '</p></div>';
	$('#map div.popup')
		.fadeOut()
		.html(popuphtml)
		.fadeIn();
}

function hidePopup(){
	$('#map div.popup').fadeOut(); 
}

