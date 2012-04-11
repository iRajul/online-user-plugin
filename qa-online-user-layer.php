<?php

/*
	Question2Answer (c) Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-plugin/mouseover-layer/qa-mouseover-layer.php
	Version: See define()s at top of qa-include/qa-base.php
	Description: Theme layer class for mouseover layer plugin


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	class qa_html_theme_layer extends qa_html_theme_base {
		
		
		function head_custom() {
		
		if(qa_opt('online_user_active_on')){
		?>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

		
		<?php
		
			
//	$this->output('<script type="text/javascript" SRC="'.qa_html(QA_HTML_THEME_LAYER_URLTOROOT.'who-is-online/widget.js').'"  ></script>');

	$this->output('<link rel="stylesheet" type="text/css" href="'.qa_html(QA_HTML_THEME_LAYER_URLTOROOT.'who-is-online/styles.css').'" media="screen" />');


		?>

<script language="javascript">
$(document).ready(function(){
	// This function is executed once the document is loaded
	
	// Caching the jQuery selectors:
	var count = $('.onlineWidget .count');
	var panel = $('.onlineWidget .panel');
	var timeout;
	
	// Loading the number of users online into the count div:
	//count.load('<?php qa_html(QA_HTML_THEME_LAYER_URLTOROOT.'who-is-online/online.php')?> ');
	//count.load('http://localhost/question2answer/qa-plugin/q2a-online/who-is-online/online.php');
	$('.onlineWidget').hover(
		function(){
			// Setting a custom 'open' event on the sliding panel:
			
			clearTimeout(timeout);
			timeout = setTimeout(function(){panel.trigger('open');},500);
		},
		function(){
			// Custom 'close' event:
			
			clearTimeout(timeout);
			timeout = setTimeout(function(){panel.trigger('close');},500);
		}
	);
	
	var loaded=false;	// A flag which prevents multiple ajax calls to geodata.php;
	
	// Binding functions to custom events:
	
	panel.bind('open',function(){
		panel.slideDown(function(){
			if(!loaded)
			{
				// Loading the countries and the flags once the sliding panel is shown:
				//panel.load('<?php qa_html(QA_HTML_THEME_LAYER_URLTOROOT.'who-is-online/geodata.php')?> ');
				//panel.load('http://localhost/question2answer/qa-plugin/q2a-online/who-is-online/geodata.php');
				loaded=true;
			}
		});
	}).bind('close',function(){
		panel.slideUp();
	});
	
});
</script>
		
		<?php

}

qa_html_theme_base::head_custom();

		}
				
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/