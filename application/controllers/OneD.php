<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;

class OneD extends CI_Controller {
    private $clientId = 'b627b77d-5a2a-4be8-87dd-6a7e04fa0d17';
    private $clientSecret = 'rWe8Q~XjnimO2Ekm6evC8QnU3O6IOn5udcIxsc-y';
    private $redirectUri = 'http://localhost';
    private $client;

    public function __construct() {
        parent::__construct();
        $this->load->model('M_TokenOneD');
        $this->client = new Client();
    }

    public function getAuthorizationUrl() {
        $authorizationUrl = 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=' . $this->clientId .
            '&response_type=code&redirect_uri=' . urlencode($this->redirectUri) .
            '&response_mode=query&scope=offline_access%20files.readwrite%20openid%20profile';

        echo json_encode(['url' => $authorizationUrl]);
    }

    public function getAccessToken() {
        //$authorizationCode = $this->input->get('code');
        $authorizationCode = 'M.C511_BL2.2.U.c6dedbdf-d13e-bec7-66f9-fba31f92c225';
        $response = $this->client->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'scope' => 'offline_access files.readwrite openid profile',
                'code' => $authorizationCode,
                'redirect_uri' => $this->redirectUri,
                'grant_type' => 'authorization_code',
                'client_secret' => $this->clientSecret,
            ]
        ]);

        $tokens = json_decode($response->getBody()->getContents(), true);
        $this->session->set_userdata('access_token', $tokens['access_token']);
        $this->session->set_userdata('refresh_token', $tokens['refresh_token']);
        echo json_encode(['tokens' => $tokens]);
    }

}
?>
