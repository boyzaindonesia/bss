<?php

/* Based on code from David Walsh – http://davidwalsh.name/bitly-php */
function make_bitly_url($url,$format = 'xml',$version = '2.0.1') {
    //Set up account info 
    
    // $bitly_login = 'dewacode'; //dewacode@gmail.com
    // $bitly_api   = 'R_e9fdfa73cfa2435cb7d5ec73c7a25384';
    
    $bitly_login = 'febripixelnine'; //febri@pixelnine.id
    $bitly_api   = 'R_36ee9fd8b01143b3a0ab98a7090c4dd9';

    //create the URL
    $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$bitly_login.'&apiKey='.$bitly_api.'&format='.$format;
    $response = file_get_contents($bitly);
    if(strtolower($format) == 'json') {
        $json = @json_decode($response,true);
        return $json['results'][$url]['shortUrl'];
    } else {
        $xml = simplexml_load_string($response);
        return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
    }
}