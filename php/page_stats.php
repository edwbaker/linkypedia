#!/usr/bin/php
<?php
/**
This script populates the standrad linkypedia web_wikipediapage table
with view counts from Wikipedia (using http://stats.grok.se).

use:

./page_stats.php PAGE [YEAR [MONTH]]

PAGE : the name of the wikipedia page to get stats for
       setting this to 'all' will get page views for all
       pages in linkypedia's database

YEAR : Get records from a specific year only. Not setting this
       value will reurn all results from December 2007

MONTH: If YEAR and MONTH are specified returns results for a
       given month only (Jan = 01, Dec = 12)

*/

//Connect to database
$db = mysqli_connect('localhost', 'linkypedia', 'password', 'linkypedia');
$pages = array('url' => '', 'page' => $argv[1]);

//TODO: Get $lang from URL
$lang = 'en';

//If getting all pages from linkypedia populate $pages array
//from database
if ($argv[1] == 'all') {
  $sql = 'SELECT url FROM web_wikipediapage';
  $results = mysqli_query($db, $sql);
  $pages = array();
  while ($result = mysqli_fetch_object($results)){
    $page_name = substr($result->url, strrpos($result->url, 'wiki/')+5);
    $pages[] = array('url' => $result->url, 'page' => $page_name);
  }
}

/*---------------------
 * We only have stats since December 2007 up to
 * the current month.
 *---------------------
 */

//First stats available
$start_year       = isset($argv[2]) ? $argv[2] : 2007;
if ($start_year == 2007) {
  $start_year_month = isset($argv[3]) ? $argv[3] : 12;
} else {
  $start_year_month = isset($argv[3]) ? $argv[3] : 1;
}
//Can't go beyond current month
$end_year         = isset($argv[2]) ? $argv[2] : date("Y");
$end_year_month   = isset($argv[3]) ? $argv[3] : date("m");

//We iterate the $year and $month variables: initialise!
$year = $start_year;
$month= $start_year_month;

//ERROR CONDITIONS
if (!isset($pages)){
  //TODO: Give guidance on how to use this script insetad of being cryptic
  print "You must specify a page\n";
  exit;
}

//Iterate over the pages to collect view stats
foreach ($pages as $page) {
  $total_views = 0;
  //Iterate over years
  for ($year = $start_year; $year <= $end_year; $year++){
    $min_month = ($year == $start_year) ? $start_year_month : 1;
    $max_month = ($year == $end_year)   ? $end_year_month   : 12;
    //Iterate over months
    for ($month = $min_month; $month <= $max_month; $month++) {
      $views = get_monthly_stats($lang, $page['page'], $year, $month);
      $total_views += $views;
      print $year.$month.': '.$views. "($total_views)\n";
      //TODO: Create a table and store these values for finer grain stats
    }
    //TODO: Check if the url exists(?)
    $sql = "UPDATE web_wikipediapage SET views = '$total_views' WHERE url = '".$page['url']."';";
    mysqli_query($db, $sql);
  }
}

/**
 * Function to get page view for a given month for a specified
 * wikipedia page from http://stats.grok.se
 */
function get_monthly_stats($lang, $page, $year, $month) {
  $grok = "http://stats.grok.se/$lang/$year".sprintf("%02d", $month)."/$page";

  $page = file_get_contents($grok);

  //Cut out monthly user stats
  $start_match = ' has been viewed ';
  $start = strpos($page, $start_match) + strlen($start_match);
  $page = substr($page, $start);
  $end_match = ' times in ';
  $end = strpos($page, $end_match);
  $page = substr($page, 0, $end);

  return trim($page);
}
