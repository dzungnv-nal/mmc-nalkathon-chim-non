<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Helper\Zoho\ZohoCrmConnect;

class Zoho extends CI_Controller {

	//Get all records in Leads module
	public function getAllRecords()
	{
		$zoho = new ZohoCrmConnect();
		$leads = $zoho->getAllRecords('Leads');
		var_dump($leads);
	}

	//Get a record in Leads module by record's ID
	public function getRecord()
	{
		$record_id = '2672639000000206003';
		$zoho = new ZohoCrmConnect();
		$lead = $zoho->getRecordById('Leads', $record_id);
		var_dump($lead->id);
		var_dump($lead->Email);
	}	

	//Insert a new record to Leads module
	public function insertLead () {
		$record = new \stdClass;
		$lead = new \stdClass;
		$lead->Last_Name = 'Test 1';
		$lead->First_Name = 'User';
		$lead->Company = 'i3 Viet Nam';
		$lead->Email = 'test1@i3vietnam.com';
		$lead->Phone = '0988765453';
		$lead->Lead_Source = 'Online Store';
		$lead->api_name = 'Leads';
		$record->data = [$lead];
		$record->trigger = ["approval"];

		$zoho = new ZohoCrmConnect();
		$inserted = $zoho->insertRecord('Leads', $record);
		var_dump($inserted);
	}
}
