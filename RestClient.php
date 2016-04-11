<?php

class RestClient
{
	private $base_url = "https://www.streak.com/api/v1";
	private $api_key ="";
		
	public function GetPipelines()
	{
		$curl = $this->SetUpcURL("pipelines");
		$curl_response = curl_exec($curl);		
		$this->ProcessResponse($curl_response, $curl);
	}
	
	public function AddUser($pipeline, $details)
	{
		
	}
	
	function __construct()
	{
		$this->api_key = parse_ini_file("config.ini")["API_KEY"];
	}
	
	private function SetUpcURL($endpoint)
	{
		$curl = curl_init(sprintf("%s/%s", $this->base_url, $endpoint));

		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERPWD, $this->api_key );  
		
		return $curl;
	}
	
	private function ProcessResponse($response, $curl)
	{
		if ($response === false)
		{
			$info = curl_error ($curl);
			curl_close($curl);
			die(sprintf("Error Occured during curl exec. Additional Info: %s ", $info));
		}
		curl_close($curl);
		
		$decoded = json_decode($response);
		
		if (array_key_exists("success", $decoded) && !$decoded->success)
		{
			die(sprintf("Error Message Resturned: %s", $decoded->error));
		}
		echo "Resquest Sucessful";
		echo '<pre> ' ;
		print_r($decoded) ;
		'</pre>';
	}
	
}




