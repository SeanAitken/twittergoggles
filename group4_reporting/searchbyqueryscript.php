<?php

//Total Number of Tweets for All Specific Jobs

echo "<p><h1>Total Number of Tweets for All Specific Jobs</h1></p>";

//Establishes connection to database
$link= mysql_connect('sociotechnical.ischool.drexel.edu', 'info154', 'info154');
/*
//Added to allow all browsers time to load query results
ini_set('max_execution_time', 300);
*/

$result_all= ($_POST['result_name']);
$result_array=  explode(",", $result_all);
foreach ($result_array as &$value){
    $value = 'q='.$value;
}
$result_string=str_replace(array('#','@'),array('%23', '%40'),implode("' or query='", $result_array));

$query = "select last_count, query, job_id from twitterinblack46.job where query='".$result_string."' order by last_count desc;";

$result= mysql_query($query);

$CSVarray = array();

while($row = mysql_fetch_array($result))
{
  $CSVarray[] = array ( 'job_id' => $row['job_id'], 'last_count' => $row['last_count'], 'query' => $row['query'] );
}

if($result === FALSE) {
    die(mysql_error()); // TODO: better error handling
}
else{
//Sets up table
echo "<p><table border='1'
<tr>
<th>Job ID</th>
<th>Last Count</th>
<th>Result</th>
</tr></p>";

//Populates table
mysql_data_seek($result,0);
while($row = mysql_fetch_array($result)){
	echo "<p>";
    echo"<tr>";
    echo "<td>" . $row["job_id"] . "</td>";
    echo "<td>" . ltrim($row["last_count"],'0') . "</td>";
    echo "<td>" . str_replace(array('%23', '%40', '%20', 'q='), array('#','@',' ',''), $row['query']) . "</td>";
    echo "<tr>";
}
echo "</table>";
echo "</p>";
}


outputCSV($CSVarray);

function outputCSV($data) {
    $file = fopen("data.csv", "w+");
    function __outputCSV($vals, $key, $filehandler) {
        fputcsv($filehandler, $vals); // add parameters if you want
    }
    array_walk($data, "__outputCSV", $file);
    echo "<p>CSV file successfully created</p>";
    fclose($file);
}


mysql_close($link);


?>