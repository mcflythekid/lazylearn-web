<?php
	function open_con(){
		global $MYSQL_SERVER;
		global $MYSQL_USER;
		global $MYSQL_PASS;
		global $MYSQL_DB;
		$con = mysqli_connect($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);
		if (!$con) {
			die("Connection failed: " . mysqli_connect_error());
		}
		mysqli_set_charset($con,"utf8");
		return $con;
	}
	function fuck($msg){
		error_log ($msg . '<br>', 3, 'C:/Users/John/Desktop/fuck.html');
	}
	function noHTML($input, $encoding = 'UTF-8'){
		return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
	}
	function escapeJavaScriptText($string) { 
		return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\"))); 
	} 
	function cardSide($input){
		return escapeJavaScriptText(strip_tags($input, "<b></br>"));
	}
	function timeAgo($time_ago){
		global $lang;
		
		$cur_time 	= time();
		$time_elapsed 	= $cur_time - $time_ago;
		$seconds 	= $time_elapsed ;
		$minutes 	= round($time_elapsed / 60 );
		$hours 		= round($time_elapsed / 3600);
		$days 		= round($time_elapsed / 86400 );
		$weeks 		= round($time_elapsed / 604800);
		$months 	= round($time_elapsed / 2600640 );
		$years 		= round($time_elapsed / 31207680 );
		
		/* Seconds */
		if($seconds < 0){
			$seconds *= -1;
			echo "$seconds " . $lang['timeago']['next_sec'];
		}
		else if($seconds <= 60){
			echo "$seconds " . $lang['timeago']['secs_ago'];
		}
		/*Minutes */
		else if($minutes <=60){
			if($minutes==1){
				echo $lang['timeago']['min_ago'];
			}
			else{
				echo "$minutes " . $lang['timeago']['mins_ago'];
			}
		}
		/*Hours */
		else if($hours <=24){
			if($hours==1){
				echo $lang['timeago']['hour_ago'];
			}else{
				echo "$hours " . $lang['timeago']['hours_ago'];
			}
		}
		/*Days */
		else if($days <= 7){
			if($days==1){
				echo $lang['timeago']['yesterday'];
			}else{
				echo "$days " . $lang['timeago']['days_ago'];
			}
		}
		/*Weeks */
		else if($weeks <= 4.3){
			if($weeks==1){
				echo $lang['timeago']['week_ago'];
			}else{
				echo "$weeks " . $lang['timeago']['weeks_ago'];
			}
		}
		/*Months */
		else if($months <=12){
			if($months==1){
				echo $lang['timeago']['month_ago'];
			}else{
				echo "$months " . $lang['timeago']['months_ago'];
			}
		}
		/*Years */
		else{
			if($years==1){
				echo $lang['timeago']['year_ago'];
			}else{
				echo "$years " . $lang['timeago']['years_ago'];
			}
		}
	}
	function is_valid_username($username){
		$list = file(__DIR__ . "/_admin_names.txt", FILE_IGNORE_NEW_LINES + FILE_SKIP_EMPTY_LINES);
		
		
		return !in_array(strtolower($username), $list);
	}
?>