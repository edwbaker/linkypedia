#!/usr/bin/php
<?php

//Connect to database
$db = mysqli_connect('127.0.0.1', 'edwab', 'rkcbsdwg', 'nhm_wiki');

$url_matches = array(
  'User pages' => 'wikipedia.org/wiki/User:',
  'User talk pages' => 'wikipedia.org/wiki/User_talk',
  'WikiProjects' => '/wiki/Wikipedia:WikiProject_[^/]+$',
  'WikiProjects pages' => '/wiki/Wikipedia:WikiProject_',
  'WikiProjects talk pages' => '/wiki/Wikipedia_talk:WikiProject_',
  'Wikipedia Signpost' => '/wiki/Wikipedia:Wikipedia_Signpost',
  'Village Pump' => '/wiki/Wikipedia:Village_pump',
  'Reference Desk' => '/wiki/Wikipedia:Reference_desk',
  'Graphics Lab' => '/wiki/Wikipedia:Graphics_Lab',
  'Copyright Problems' => '/wiki/Wikipedia:Copyright_problems',
  'Suspected Copyright Violtaions' => '/wiki/Wikipedia:Suspected_copyright_violations',
  'Possibly unfree files' => 'wiki/Wikipedia:Possibly_unfree_files',
  'Media copyright questions' => 'wiki/Wikipedia:Media_copyright_questions',
  'Articles for creation' => '/wiki/Wikipedia:Articles_for_creation',
  'Featured article candidates' => '/wiki/Wikipedia:Featured_article_candidates',
);

$page_matches = array(
  'Biota InfoBox' => 'class="infobox biota"',
  'Type specimen' => 'type specimen',
  'Lepidoptera' => 'Lepidoptera',
  'Stub' => 'Wikipedia:Stub',
  'Lepidoptera stub' => 'Lepidoptera.*Wikipedia:Stub',
);



if (isset($argv[1])){
	if (array_key_exists($argv[1], $url_matches)){
	  one_stat($db, 'url', $argv[1], $url_matches[$argv[1]]);
	} else
  if (array_key_exists($argv[1], $page_matches)){
	  one_stat($db, 'page', $argv[1], $page_matches[$argv[1]]);
	} else {
	  one_stat($db, 'page', $argv[1], $argv[1]);
	}
} else {
	all_stats($db, $url_matches, $page_matches);
}



function all_stats($db, $url_matches, $page_matches){
  //Summary stats
  $sql = "SELECT COUNT(id) as count FROM wiki_data";
  $result = mysqli_fetch_object(mysqli_query($db, $sql));
  print $result->count.' pages have links to the domain'."\n\n";
  //Process URL matches
  foreach ($url_matches as $title => $match){
	$sql = "SELECT COUNT(*) as count FROM wiki_data WHERE wiki_url REGEXP '$match'";
	$result = mysqli_fetch_object(mysqli_query($db, $sql));
	print $title." ".$result->count."\n";
  }
  print "\n\n";
  //Process page matches
  foreach ($page_matches as $title => $match){
	$sql = "SELECT COUNT(*) as count FROM wiki_data WHERE wiki_page REGEXP '$match'";
	$result = mysqli_fetch_object(mysqli_query($db, $sql));
	print $title." ".$result->count."\n";
  }
}

function one_stat($db, $type, $title, $match){
  switch($type){
  	case 'url':
  	  $sql = "SELECT * FROM wiki_data WHERE wiki_url REGEXP '$match'";
	  $result = (mysqli_query($db, $sql));
	  while ($row = mysqli_fetch_object($result)){
	    print $row->wiki_url."\n";
	  }
	  break;
  	case 'page':
  	 $sql = "SELECT * FROM wiki_data WHERE wiki_page REGEXP '$match'";
	  $result = (mysqli_query($db, $sql));
	  while ($row = mysqli_fetch_object($result)){
	    print $row->wiki_url."\n";
	  }
	  break;	
  }
}