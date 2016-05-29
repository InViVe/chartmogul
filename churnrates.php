<?php 
$ch = curl_init();

$baseurl='https://api.chartmogul.com/v1/metrics/mrr-churn-rate';
$start_date='2014-08-01';
$end_date='2015-07-31';
$url=$baseurl.'?start-date='.$start_date.'&end-date='.$end_date;

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$token = '5b79e651dd7c6e3f79d66e93bc31e937';
$password = '8d7a15e9b84b0497397716f8ba7ab927';
curl_setopt($ch, CURLOPT_USERPWD, "$token:$password");

curl_setopt ($ch, CURLOPT_CAINFO, "C:/curl/cacert.pem");

$out = curl_exec($ch);

$json = json_decode($out, true);

for ($x = 0; $x < sizeof($json['entries']); $x++) {
	$churns[$x][0] = $json['entries'][$x]['mrr-churn-rate']; 
	$churns[$x][1] = $json['entries'][$x]['date']; 

} 

usort($churns, function($a, $b) {
    return   $b[0] - $a[0];
});

print  "<br /><br />" . "The top three months with the biggest churn rates (in decreasing order) from " .$start_date ." to " .$end_date . " are: " ;
$top_how_many = 3;
for ($y = 0; $y < $top_how_many; $y++) {
$month = getdate(strtotime($churns[$y][1]));
if ($y == $top_how_many-1)
print $month['month'] ." (". $churns[$y][0] . "%). ";
else
print $month['month'] ." (". $churns[$y][0] . "%), ";

}

curl_close($ch);

?>