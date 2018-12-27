<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Helper\Zoho\ZohoCrmConnect;

class Webhook extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function zoho_crm_hook()
	{
        $owner_id = $this->input->post('ownerId');
        $owner_name = $this->input->post('ownerName');
        $callback_url = $this->input->post('callback_url');
        $text = 'Owner ID: '. $owner_id . ' - Name: '. $owner_name;
        
        if ($owner_id) {
            $options = [
                'http_errors' => true,
                'payload' => ['text' => $text],
                'headers' => [
                    'Content-Type' => 'application/json'
                  ]
              ];
    
            $mmc_hook_client = new \GuzzleHttp\Client([
                'base_uri' => $callback_url,
                'timeout'  => 0,
            ]);
    
            $response = $mmc_hook_client->request('POST', '/zoho_crm_callback', $options);
            header ('Content-Type:application/json');
            echo $response->getBody();
        }
    }
    
    public function zoho_crm_callback() 
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        header ('Content-Type:application/json');
        $data = json_encode(['data' => $request]);
        error_log($data . "\n", 3, './hook.log');
        echo $data;
    }
}
