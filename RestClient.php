<?php

class RestClient
{
	private $base_url = "https://www.streak.com/api/v1";
	private $api_key ="";
		
	public function GetPipelines()
	{
		$pipelines = $this->ProcessGETEcURL("pipelines");
	
		//TODO:Cache this details
		
		$final_piplines = array();
		foreach ($pipelines as $pipeline)
		{
			$final_piplines[$pipeline->name] = $pipeline->pipelineKey;
		}

		return $final_piplines;

	}
	
	public function GetUsers($pipeline=null)
	{
		$all_boxes = $this->ProcessGETEcURL("boxes");
		
		if (!$pipeline)
		{
			return $all_boxes;
		}
		
		$pipelines = $this->GetPipelines();
		
		$final_users = array();
		
		foreach($all_boxes as $box)
		{
			//TODO: Dont use the field id
			if ($box->pipelineKey == $pipelines[$pipeline])
			{
			
				$final_users[$box->fields->{1004}] = $box->fields;
			}
		}
		
		return $final_users;

				
	}
	
	public function AddUser($pipeline, $details)
	{
		
	}
	
	function __construct()
	{
		$this->api_key = parse_ini_file("config.ini")["API_KEY"];
	}
	
	private function ProcessGETEcURL($endpoint)
	{
		$curl = curl_init(sprintf("%s/%s", $this->base_url, $endpoint));

		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_USERPWD, $this->api_key );  
		
		$curl_response = curl_exec($curl);		
		return $this->ProcessResponse($curl_response, $curl);
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

		return $decoded ;

	}
	
}




