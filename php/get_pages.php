#!/usr/bin/php
<?php

//Connect to database
$db = mysqli_connect('localhost', 'linkypedia', 'password', 'linkypedia');

//The outout file from linkypedia passed as 1st argument
$file = fopen($argv[1], 'r');

//Define some variables
$count = 0;
$fetch_urls = array();
$errors = '';

//Get the wikipedia URLS from the file
while (($data = fgetcsv($file, 0, "\t")) !== FALSE){
	$fetch_urls[] = $data[0];
	$count++;
}

//Remove duplicates
$fetch_urls = array_unique($fetch_urls);
print $count.' links found. '.sizeof($fetch_urls).' are unique.'."\n";

//Reset counter
$count = 0;

//Get all of the wikipedia pages
foreach ($fetch_urls as $wiki_url) {
	print $count.': Fetching: '.$wiki_url."\n";
	$wiki_page = mysqli_real_escape_string($db, get_wikipedia_page($wiki_url));
	$sql = "INSERT INTO wiki_data(wiki_url, wiki_page) VALUES ('$wiki_url', '$wiki_page')";
	mysqli_query($db, $sql);
	$errors .= mysqli_error($db)."\n\n";
	//sleep(1);
	$count++;
}


//Tidy up after oursleves
mysql_close($db);
print_r($errors);

function get_wikipedia_page($url){
$opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: en\r\n" .
                  "User-Agent: Mozilla/5.0 (X11; U; Linux x86_64; es-AR; rv:1.9.2.23) Gecko/20110921 Ubuntu/10.10 (maverick) Firefox/3.6.23"
      )
    );

    $context = stream_context_create($opts);

    // Open the file using the HTTP headers set above
    return file_get_contents($url, false, $context);
}
