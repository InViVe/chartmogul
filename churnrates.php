<?php 
$ch = curl_init();

$baseurl='https://api.chartmogul.com/v1/metrics/mrr-churn-rate';

//Start date of period to find the top three months with the highest churn rate ordered from high to low
$start_date='2014-08-01';
//End date of period to find the top three months with the highest churn rate ordered from high to low
$end_date='2015-07-31';
$url=$baseurl.'?start-date='.$start_date.'&end-date='.$end_date;

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Your personal Account Token
$token = '5b79e651dd7c6e3f79d66e93bc31e937';
//Your personal (private) Secret Key 
$password = '8d7a15e9b84b0497397716f8ba7ab927';
//The ChartMogul API uses HTTP Basic Authentication using your Account Token and Secret Key as basic auth credentials.
//You can find more info here: https://dev.chartmogul.com/docs/authentication and your personal credentials (after you sign in) here: https://app.chartmogul.com/#admin/api
curl_setopt($ch, CURLOPT_USERPWD, "$token:$password");

curl_setopt ($ch, CURLOPT_CAINFO, "C:/curl/cacert.pem");

$out = curl_exec($ch);

//Decode the Json result
$json = json_decode($out, true);


//Create a two-dimensional array containing the pairs of mrr churn rates and the respective dates they refer to
for ($x = 0; $x < sizeof($json['entries']); $x++) {
	$churns[$x][0] = $json['entries'][$x]['mrr-churn-rate']; 
	$churns[$x][1] = $json['entries'][$x]['date']; 

} 

//Sort the array based on the mrr churn rates
usort($churns, function($a, $b) {
    return   $b[0] - $a[0];
});

print  "<br /><br />" . "The top three months with the biggest churn rates (in decreasing order) from " .$start_date ." to " .$end_date . " are: " ;
//Set the number of highest churn rates months that you would like the program to fetch
$top_how_many = 3;
for ($y = 0; $y < $top_how_many; $y++) {
//convert the string containg the date into the date format so that we can only get the relative month later on
$month = getdate(strtotime($churns[$y][1]));
if ($y == $top_how_many-1)
//only return the month (and not the whole date)
print $month['month'] ." (". $churns[$y][0] . "%). ";
else
//if this is the last month to print, don't print a comma but a full stop 
print $month['month'] ." (". $churns[$y][0] . "%), ";

}

curl_close($ch);

?>