<?php 
$sub_query = $_GET["q"];
$user = $_GET["u"];
$query = urlencode("{$sub_query} from:{$user}");
$raw = file_get_contents("http://mobile.twitter.com/search?q={$query}");

$dom = new DOMDocument();
$dom->loadHTML($raw);

$xpath = new DOMXpath($dom);
$timestamps_xp  = $xpath->query("//td[contains(@class, 'timestamp')]");
$tweet_texts_xp = $xpath->query("//div[contains(@class, 'tweet-text')]");

$tweet_texts = array();
$date  = array();

foreach($tweet_texts_xp as $t) {
    $tweet_texts[] = $t->textContent;
}

foreach($timestamps_xp as $t) {
    $date[] = $t->nodeValue;
}

$out = array();
for ($i = 0; $i < count($tweet_texts) - 1; $i++) {
    $o['text'] = trim($tweet_texts[$i]);
    $o['date'] = trim($date[$i]);
    $out[] = $o;
}

header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
print(json_encode($out));

?>