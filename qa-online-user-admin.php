<?php
	class qa_online_user_widget_admin {

		function allow_template($template)
		{
			return ($template!='admin');
		}	   
			
		function admin_form(&$qa_content)
		{					   
							
		// Process form input
			
			$ok = null;
			
			if (qa_clicked('qa_online_user_save')) {
			
				qa_opt('online_user_active_on',(int)qa_post_text('online_user_check'));
				qa_db_query_sub(
						'CREATE TABLE IF NOT EXISTS ^who_is_online ('.
							'id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,'.
							'ip INT(11) UNSIGNED DEFAULT 0 NOT NULL,'.
							'country VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\','.
							'country_code VARCHAR(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\','.
							'city VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\','.
							'dt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,'.
							'PRIMARY KEY (id),'.
							'UNIQUE KEY (ip),'.
							'KEY (country_code)'.							
							') ENGINE=MyISAM DEFAULT CHARSET=utf8'
					);
				
				
				
				
				$ok = qa_lang_html('admin/options_saved');
			}
			
			else if(qa_clicked('qa_online_user_reset'))
			{
			qa_opt('online_user_active_on',(int)qa_post_text('online_user_check'));
			qa_db_query_sub(
						'DROP TABLE IF EXISTS ^who_is_online'
					);
			qa_db_query_sub(
						'CREATE TABLE IF NOT EXISTS ^who_is_online ('.
							'id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,'.
							'ip INT(11) UNSIGNED DEFAULT 0 NOT NULL,'.
							'country VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\','.
							'country_code VARCHAR(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\','.
							'city VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\','.
							'dt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,'.
							'PRIMARY KEY (id),'.
							'UNIQUE KEY (ip),'.
							'KEY (country_code)'.							
							') ENGINE=MyISAM DEFAULT CHARSET=utf8'
					);
					$ok = qa_lang_html('admin/options_saved');
			}
			
					
			// Create the form for display
			
			
			
			$fields = array();

			$fields[] = array(
				'label' => 'Online User',
				'tags' => 'NAME="online_user_check"',
				'value' => qa_opt('online_user_active_on'),
				'type' => 'checkbox',
			);
			
					
			
			
			return array(		   
				'ok' => ($ok && !isset($error)) ? $ok : null,
					
				'fields' => $fields,
			 
				'buttons' => array(
					array(
						'label' => 'Save',
						'tags' => 'NAME="qa_online_user_save"',
					),
					array(
						'label' => 'Reset- Empty Table',
						'tags' => 'NAME="qa_online_user_reset"',
					),
				),
				
			);
		}
	}

