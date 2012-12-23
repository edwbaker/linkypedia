#!/usr/bin/php
<?php
$page = $argv[1];
$lang = 'en';

//First stats available
$start_year       = 2007;
$start_year_month = 12;
//Can't go beyond current month
$end_year         = date("Y");
$end_year_month   = date("m");

$year = $start_year;
$month= $start_year_month;

if (!isset($page)){
  print "You must specify a page\n";
  exit;
}


for ($year = $start_year; $year <= $end_year; $year++){
  $min_month = ($year == $start_year) ? $start_year_month : 1;
  $max_month = ($year == $end_year)   ? $end_year_month   : 12;
  for ($month = $min_month; $month <= $max_month; $month++) {
    print $year.$month.': '.get_monthly_stats($lang, $page, $year, $month);
  }
}


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

  return $page;
}
