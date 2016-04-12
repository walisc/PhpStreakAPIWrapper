<?php

include_once("RestClient.php");

$client = new RestClient();

echo '<pre> ' ;
		print_r($client->GetUsers("Jubilee Students Mailing list"));
echo '</pre>';
