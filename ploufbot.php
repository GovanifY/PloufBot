<?


function file_get_contents_curl($url) {
$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
curl_setopt($ch, CURLOPT_URL, $url);

$data = curl_exec($ch);
curl_close($ch);

return $data;
}

require __DIR__ . '/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
$items=[];
$url = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&forUsername=ChroniquesMrPlouf&key=[INPUT_KEY_HERE]";
$content = file_get_contents_curl($url);
$json = json_decode($content, true);

foreach($json['items'] as $item) {
  $pid = $item['contentDetails']['relatedPlaylists']['uploads'];
  $url2 = "https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=" . $pid . '&key=[INPUT_KEY_HERE]';
  $content2 = file_get_contents_curl($url2);
  $json2 = json_decode($content2, true);
  foreach($json2['items'] as $item2) {
    array_push($items, $item2['contentDetails']['videoId']);
}
}
$rand_key = array_rand($items, 1);

//echo 'bash ' . __DIR__ . '/get_frame.sh http://www.youtube.com/watch?v=' . $items[$rand_key];
shell_exec('bash ' . __DIR__ . '/get_frame.sh http://www.youtube.com/watch?v=' . $items[$rand_key]);

$consumer_key="[INPUT_KEY_HERE]";
$consumer_secret="[INPUT_KEY_HERE]";
$access_token="[INPUT_KEY_HERE]";
$access_token_secret="[INPUT_KEY_HERE]";
# Create the connection
$twitter = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

$file_path = __DIR__ . '/frame.jpg';
$result = $this->twitter->upload('media/upload', array('media' => $file_path));
$this->assertEquals(200, $this->twitter->getLastHttpCode());
$this->assertObjectHasAttribute('media_id_string', $result);
$parameters = array('status' => '', 'media_ids' => $result->media_id_string);
$result = $this->twitter->post('statuses/update', $parameters);


?>
