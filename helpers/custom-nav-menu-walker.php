<?php
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
	  function start_lvl(&$output, $depth) {
	    $indent = str_repeat("\t", $depth);
	    $output .= "\n$indent<ul id=\"dropdown-menu\" class=\"dropdown-menu\">\n";
	  }

	  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0){
	  	$item_html= '';
	  	parent::start_el($item_html, $item, $depth, $args);

	  	if ($item->is_dropdown && ($depth === 0)){
	  		$item_html = str_replace ('<a', '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#"', $item_html);
	  		$item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html);
	  	}

	  	$output .= $item_html;
	  }

	  function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output){
	  	$element->is_dropdown = !empty( $children_elements[$element->ID]);

	  	if ($element->is_dropdown){
	  		if ($depth === 0){
	  			$element->classes[] = 'dropdown';
	  		}else if ($depth > 0){
	  			$element->classes[] = 'dropdown-submenu';
	  		}
	  	}

	  	parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	  }
}


?>