<?php

// Import library dependencies
jimport('joomla.event.plugin');

/**
* Plugin that uses JQuery to creates a Map based on 
* content in a Joomla Category.
* Syntax {catmap <image_file>, <image_width>, <image_height>, <category_id>}
*/
class plgContentCatMap extends JPlugin
{
	/**
	* Constructor
	*/
	function plgContentCatMap ( &$subject, $config ) {
		parent::__construct( $subject, $config );
	}

	/**
	* Plugin method with the same name as the event will be called automatically.
	*/
	function onPrepareContent( &$row, &$params ) {
		// simple performance check to determine whether bot should process further
		if ( JString::strpos( $row->text, 'catmap' ) === false ) {
			return true;
		}
		global $mainframe, $Itemid;
		$document =& JFactory::getDocument();
		
		// 	Parameters
		//$map_image_url = $this->params->get( 'map_image_url' , '/use_plugin_params_to_add_a_map_image.jpg');
		//$section_id = $this->params->get( 'section_id', '0' );
		//$category_id = $this->params->get( 'category_id', '0' );

		//	Add CSS		
		$document->addStyleSheet( JURI::base() . 'plugins/content/catmap/catmap.css' );

		//	Add Javascript
		$document->addScript( 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		$document->addScript( JURI::base() .'plugins/content/catmap/catmap.js');
	
		$firsttime = true;
			
 		// Find all Maps
 		$working = "";
 		$replacethis = "";
 		$withthis = "";
 		$regex = '/{catmap\s*.*?}/i';

 		// find all instances of plugin and put in $matches
		preg_match_all( $regex, $row->text, $matches );
		
	 	$count = count( $matches[0] );
	 	for ( $i=0; $i < $count; $i++ ) {
 		
			$replacethis = $matches[0][$i];
			$working = $replacethis;
			$working = str_replace( '{catmap', '', $working );
			$working = str_replace( '}', '', $working );
 			$working = trim($working);

			$withthis = '';
			if ($firsttime == true) {
				$withthis = '<div id="map" class="catmap"></div>' . "\n";
			}
			
			$plugin_array = explode(",",$working);
			$image_file = "no_image_file_in_plugin.jpg";
			if (count($plugin_array) > 0) {
				$image_file = $plugin_array[0];
			}
			$image_width = 100;
			if (count($plugin_array) > 1) {
				$image_width = intval($plugin_array[1]);
			}
			$image_height = 100;
			if (count($plugin_array) > 2) {
				$image_height = intval($plugin_array[2]);
			}
			$category_id = 0;
			if (count($plugin_array) > 3) {
				$category_id = intval($plugin_array[3]);
			}
			$icon_file = "no_icon_file_in_plugin.gif";
			if (count($plugin_array) > 4) {
				$icon_file = $plugin_array[4];
			}

			if ($firsttime == true) {
				$js = "$(window).load(function() { initializeMap('$image_file', $image_width, $image_height, '$icon_file'); });";
				$document->addScriptDeclaration( $js );
			}

			// $withthis .= '<img src=' . $image_file . ' />';

			//
			// Work from section_id and content_id to create a list of 
			// content items to be loaded onto the map.
			//
			$db =& JFactory::getDBO();
			$query = 'SELECT * ' .
				' FROM #__content' .
				' WHERE catid = ' . intval($category_id);
			$db->setQuery($query);
			$content = $db->loadObjectList();

			$withthis .= '<ul id="catmaplist">';
			foreach ($content as $content_item) {
				$params = new JParameter($content_item->attribs);
				$mapx = $params->get('mapx');
				$mapy = $params->get('mapy');
				$class = $params->get('mapclass');

				$withthis .= '<li style="clear: right"><a href="' 
					. '#' 
					. '" title="' 
					. $content_item->title 
					. '" class="bullet ' . $class
					. '" id="item' 
					. $content_item->id 
					. '" rel="' 
					. $mapx 
					. '-' 
					. $mapy 
					. '">' 
					. $content_item->title 
					. '</a>' 
					. '<div class="box" id="item'
					. $content_item->id
					. '-box">' 
					.  $content_item->introtext 
					. '</div>' 
					. '</li>'
					. "\n"
					;
			}
			$withthis .= '</ul>';

			$row->text = str_replace( $replacethis, $withthis, $row->text );			
			$firsttime = false;
 		}

		return true;
	}
}
