<?php

	class qa_online_user_widget {

		function allow_template($template)
		{
			return true;
		}

		function allow_region($region)
		{
			return true;
		}

		function get_tag($tag,$xml)
{
	preg_match_all('/<'.$tag.'>(.*)<\/'.$tag.'>$/imU',$xml,$match);
	return $match[1];
}

function is_bot()
{
	/* This function will check whether the visitor is a search engine robot */
	
	$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
	"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
	"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
	"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
	"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
	"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
	"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
	"Butterfly","Twitturls","Me.dium","Twiceler");

	foreach($botlist as $bot)
	{
		if(strpos($_SERVER['HTTP_USER_AGENT'],$bot)!==false)
		return true;	// Is a bot
	}

	return false;	// Not a bot
}


function count_online()
{
	if($this->is_bot()) die();
	$stringIp = $_SERVER['REMOTE_ADDR'];
	$intIp = ip2long($stringIp);

	// Checking wheter the visitor is already marked as being online:
	$inDB = qa_db_query_sub('SELECT 1 FROM ^who_is_online WHERE ip='.$intIp.'');

	if(!mysql_num_rows($inDB))
	{
		// This user is not in the database, so we must fetch
		// the geoip data and insert it into the online table:
	
	
	if(!isset($_COOKIE['geoData']))
	{
		// Making an API call to Hostip:
		
		$xml = file_get_contents('http://api.hostip.info/?ip='.$stringIp);
		
		$city = $this->get_tag('gml:name',$xml);
		$city = $city[1];
		
		$countryName = $this->get_tag('countryName',$xml);
		$countryName = $countryName[0];
		
		$countryAbbrev = $this->get_tag('countryAbbrev',$xml);
		$countryAbbrev = $countryAbbrev[0];
		
		// Setting a cookie with the data, which is set to expire in a month:
		setcookie('geoData',$city.'|'.$countryName.'|'.$countryAbbrev, time()+60*60*24*30,'/');
	}
	
	//else($_COOKIE['geoData'])
	else
	{
		// A "geoData" cookie has been previously set by the script, so we will use it
		// Always escape any user input, including cookies:
		list($city,$countryName,$countryAbbrev) = explode('|',mysql_real_escape_string(strip_tags($_COOKIE['geoData'])));
	}
	
	$countryName = str_replace('(Unknown Country?)','UNKNOWN',$countryName);
	
	// In case the Hostip API fails:
		
	if (!$countryName)
	{
		$countryName='UNKNOWN';
		$countryAbbrev='XX';
		$city='(Unknown City?)';
	}
	

		qa_db_query_sub('INSERT INTO ^who_is_online (ip,city,country,country_code) VALUES(#,$,$,$)',$intIp,$city,$countryName,$countryAbbrev);

	}
	else
	{
		// If the visitor is already online, just update the dt value of the row:
		qa_db_query_sub('UPDATE qa_who_is_online SET dt=NOW() WHERE ip='.$intIp.'');
	}

	// Removing entries not updated in the last 10 minutes:
	qa_db_query_sub('DELETE FROM qa_who_is_online WHERE dt<SUBTIME(NOW(),"0 0:10:0") ');

	// Counting all the online visitors:
	list($totalOnline) = mysql_fetch_array(qa_db_query_sub('SELECT COUNT(*) FROM ^who_is_online'));

	// Outputting the number as plain text:
	echo $totalOnline;


}


function geodata()
{
	if($this->is_bot()) die();

	// Selecting the top 15 countries with the most visitors:
	$result = 
				qa_db_query_sub('	SELECT country_code,country, COUNT(*) AS total
						FROM qa_who_is_online
						GROUP BY country_code
						ORDER BY total DESC
						LIMIT 15');

	while($row=mysql_fetch_assoc($result))
	{
		echo '
		<div class="geoRow">
		<div class="flag"><img src="localhost/q2a5/qa-plugin/q2a-online/who-is-online/img/famfamfam-countryflags/'.strtolower($row['country_code']).'.gif" width="16" height="11" /></div>
		<div class="country" title="'.htmlspecialchars($row['country']).'">'.$row['country'].'</div>
		<div class="people">'.$row['total'].'</div>
		</div>
	';
	}

}

		
	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{
			
			
	?>
	<div class="onlineWidget">
	<div class="panel"><?php $this->geodata()?> </div>
	<div class="count"><?php $this->count_online()?></div>
    <div class="label">online</div>
    <div class="arrow"></div>
	</div><?php
					
	}
	};


/*
	Omit PHP closing tag to help avoid accidental output
*/
