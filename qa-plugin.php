<?php

/*
	Plugin Name: Online Users
	Plugin URI: http://#
	Plugin Description: Online User PLugin for Q2A
	Plugin Version: 1.0
	Plugin Date: 2012-04-06
	Plugin Author: Rajul
	Plugin License: 
	Plugin Minimum Question2Answer Version: 1.4
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('widget', 'qa-online-user-widget.php', 'qa_online_user_widget', 'Online User Widget');
	qa_register_plugin_module('module', 'qa-online-user-admin.php', 'qa_online_user_widget_admin', 'Online User Widget');
	qa_register_plugin_layer('qa-online-user-layer.php', 'Online User Layer');	

/*
	Omit PHP closing tag to help avoid accidental output
*/
