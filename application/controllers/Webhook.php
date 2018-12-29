<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Helper\Zoho\ZohoCrmConnect;
use function GuzzleHttp\json_encode;

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
        $callback_endpoint = $this->input->post('endpoint');

        $text = ((is_array($owner_name) ? implode(' ', $owner_name) : $owner_name)) . ' vừa thêm một liên lạc khách hàng!';
        
        if ($owner_id) {
            $options = [
                'http_errors' => true,
                'json' => ['text' => $text]
              ];
    
            $mmc_hook_client = new \GuzzleHttp\Client([
                'base_uri' => $callback_url,
                'timeout'  => 0,
            ]);
    
            $response = $mmc_hook_client->request('POST', $callback_endpoint, $options);
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
            header ('Content-Type:application/json');
            $this->response($response->getBody());
        }
    }
    
    public function zoho_crm_callback() 
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);

        $data = json_encode($request, JSON_UNESCAPED_UNICODE);
        error_log($data . "\n", 3, './hook.log');
        $this->response($data);
    }

    public function zoho_report()
    {
        $data = '[{"label":"Doanh thu phát sinh","datums":[{"x":"Jan","y":3360930764},{"x":"Feb","y":3091189401},{"x":"March","y":2521072000},{"x":"April","y":2232707000},{"x":"May","y":2251784600},{"x":"June","y":2253470200},{"x":"July","y":2372347490},{"x":"August","y":2584937540},{"x":"Sep","y":1930233900},{"x":"Oct","y":2828655400},{"x":"Nov","y":2780409400}]},{"label":"Doanh thu ký mới","datums":[{"x":"Jan","y":97000000},{"x":"Feb","y":913105400},{"x":"March","y":5530316000},{"x":"April","y":3038260000},{"x":"May","y":4670147700},{"x":"June","y":520300000},{"x":"July","y":221000000},{"x":"August","y":6691749509},{"x":"Sep","y":4902757400},{"x":"Oct","y":365000000},{"x":"Nov","y":4567613000}]},{"label":"Doanh thu ký mới luỹ kế","datums":[{"x":"Jan","y":97000000},{"x":"Feb","y":1010105400},{"x":"March","y":6540421400},{"x":"April","y":9578681400},{"x":"May","y":14248829100},{"x":"June","y":14769129100},{"x":"July","y":14990129100},{"x":"August","y":21681878609},{"x":"Sep","y":26584636009},{"x":"Oct","y":26949636009},{"x":"Nov","y":31517249009}]},{"label":"Doanh thu lũy kế ","datums":[{"x":"Jan","y":3360930764},{"x":"Feb","y":6452120165},{"x":"March","y":8973192165},{"x":"April","y":11205899165},{"x":"May","y":13457683765},{"x":"June","y":15711153965},{"x":"July","y":18083501455},{"x":"August","y":20668438995},{"x":"Sep","y":22598672895},{"x":"Oct","y":25427328295},{"x":"Nov","y":28207737695}]}]';
        $this->response($data);
    }

    public function put_json()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        if ($stream_clean) {
            $fh = fopen('./db.json', 'w');
            fwrite($fh, $stream_clean);
            fclose($fh);
            $this->response(json_encode(['result' => 'success']));
        }
        else {
            $this->response(json_encode(['result' => 'fail', 'msg' => 'no data']));
        }

    } 

    public function get_json()
    {
        $fh = fopen('./db.json', 'r');
        $content = fgets($fh);
        fclose($fh);
        $this->response($content);
    }

    public function get_lead()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $zoho = new ZohoCrmConnect();
        if ($stream_clean) {
            $request = json_decode($stream_clean);
            $field = $request->field;
            $value = $request->value;
            $data = $zoho->search('Leads', '', '', '('.$field.':equals:'.$value.')');
        }
        else {
            $data = $zoho->getAllRecords('Leads');
        }
        $this->response(json_encode($data));
    }

    protected function response ($data) 
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header ('Content-Type:application/json');
        echo $data;
        exit;
    }
}
