<?php
namespace Helper\Zoho;

if (!defined('ZOHO_APP_REFRESH_CODE')) define('ZOHO_APP_REFRESH_CODE', '1000.ab7f6b70ff072bc8067c1dfd71f4cdc1.55ab61859e069620551a63f92af6178d');
if (!defined('ZOHO_APP_CLIENT_ID')) define('ZOHO_APP_CLIENT_ID', '1000.03E56M4HRH9U7334278W6LL074RLQU');
if (!defined('ZOHO_APP_CLIENT_SECRET')) define('ZOHO_APP_CLIENT_SECRET', '3235e680e76668acda457e6a2cd3455dbfe86118c0');


class ZohoCrmConnect {
  protected $zoho_account_client;
  protected $zoho_crm_client;

  const ZOHO_ACCOUNT_BASE_URL = 'https://accounts.zoho.com';
  const ZOHO_CRM_BASE_URL = 'https://www.zohoapis.com';

  public function __construct () {
    $this->connect();
  }

  private function connect(){
    $this->zoho_account_client = new \GuzzleHttp\Client([
        'base_uri' => self::ZOHO_ACCOUNT_BASE_URL,
        'timeout'  => 2.0,
    ]);

    $this->zoho_crm_client = new \GuzzleHttp\Client([
      'base_uri' => self::ZOHO_CRM_BASE_URL,
      'timeout'  => 2.0,
    ]);
  }

  public function getAccessToken () {
    $options = [
      'http_errors' => true,
      'query' => [
        'refresh_token' => ZOHO_APP_REFRESH_CODE,
        'client_id' => ZOHO_APP_CLIENT_ID,
        'client_secret' => ZOHO_APP_CLIENT_SECRET,
        'grant_type' => 'refresh_token',
      ]
    ];

    $response = $this->zoho_account_client->request('POST','/oauth/v2/token',$options);
    if ($response->getStatusCode() == 200) {
      $data = json_decode($response->getBody());
      return $data;
    }
    else {
      return false;
    }
  }

  public function getAllRecords($module) {
    $records = [];
    if ($module !== '') {
      $uri = '/crm/v2/'.$module;
      $access_token = $this->getAccessToken();

      $options = [
        'http_errors' => true,
        'headers' => [
          'Authorization' => 'Zoho-oauthtoken '. $access_token->access_token
        ]
      ];

      $response = $this->zoho_crm_client->request('GET', $uri, $options);
      if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody());
        $records = $data->data;
        return $records;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }
  }

  public function getRecordById($module, $id) {
    $record = [];
    if ($module !== '' && $id !== '') {
      $uri = '/crm/v2/'.$module.'/'.$id;
      $access_token = $this->getAccessToken();

      $options = [
        'http_errors' => true,
        'headers' => [
          'Authorization' => 'Zoho-oauthtoken '. $access_token->access_token
        ]
      ];

      $response = $this->zoho_crm_client->request('GET', $uri, $options);
      if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getBody());
        $record = $data->data[0];
        return $record;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }    
  }

  public function search($module, $field = '', $value = '', $criteria = '')
    {
        $page_limit = 0;
        try {
            $records = [];
            if ($module !== '') {
                $uri = '/crm/v2/' . $module . '/search?page=%d&per_page=%d';
                $uri .= $criteria != '' ? '&criteria=' . $criteria : '';
                $uri .= $field != '' ? '&' . $field . '=' . $value : '';

                $access_token = $this->getAccessToken();
                if ($access_token == false || !is_object($access_token) || !property_exists($access_token, 'access_token')) {
                    return false;
                }

                $options = [
                    'http_errors' => true,
                    'headers' => [
                        'Authorization' => 'Zoho-oauthtoken ' . $access_token->access_token,
                        'Content-Type' => 'application/json',
                    ],
                ];

                $rec_per_page = 200;
                $page = 1;
                $record_count = $rec_per_page;

                while ($record_count <= $rec_per_page && $record_count > 0) {

                    $endpoint = sprintf($uri, $page, $rec_per_page);
                    $response = $this->zoho_crm_client->request('GET', $endpoint, $options);

                    if ($response->getStatusCode() == 200) {
                        $data = json_decode($response->getBody());
                        $record_count = isset($data->data) ? count($data->data) : 0;
                        $records = $record_count > 0 ? array_merge($records, $data->data) : $records;
                        usleep(5000);
                    } else if ($response->getStatusCode() == 204) {
                        break;
                    } else {
                        return false;
                    }

                    $page++;
                    if ($page_limit > 0 && $page >= $page_limit) {
                        break;
                    }
                }
                return $records;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

  public function searchRecordByEmail($module, $email) {
    return $this->search($module,'email', $email);   
  }

  public function searchRecordByPhone($module, $phone) {
    return $this->search($module,'phone', $phone);   
  }

  public function insertRecord($module, $data) {
    if ($module !== '' && $data !== null) {
      $uri = '/crm/v2/'.$module;
      $access_token = $this->getAccessToken();

      $options = [
        'http_errors' => true,
        'json' => $data,
        'headers' => [
          'Authorization' => 'Zoho-oauthtoken '. $access_token->access_token
        ]
      ];
        $response = $this->zoho_crm_client->request('POST', $uri, $options);
        if ($response->getStatusCode() == 201) {
          $data = json_decode($response->getBody());
          $record = $data->data[0];
          return $record;
        }
        else {
          return false;
        }
    }
    else {
      return false;
    }  
  }

}
?>
