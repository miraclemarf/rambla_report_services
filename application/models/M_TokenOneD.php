<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class M_TokenOneD extends CI_Model {
    private $clientId = 'b627b77d-5a2a-4be8-87dd-6a7e04fa0d17';
    private $clientSecret = 'rWe8Q~XjnimO2Ekm6evC8QnU3O6IOn5udcIxsc-y';
    private $redirectUri = 'https://sales.rambla.id/oned/token';
    private $client;

    public function __construct() {
        parent::__construct();
        $this->client = new Client();
    }

    public function refreshToken() {
        $refreshToken = $this->session->userdata('refresh_token');
        $response = $this->client->post('https://login.microsoftonline.com/common/oauth2/v2.0/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'scope' => 'offline_access files.readwrite openid profile',
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
                'redirect_uri' => $this->redirectUri,
                'client_secret' => $this->clientSecret,
            ]
        ]);

        $tokens = json_decode($response->getBody()->getContents(), true);
        $this->session->set_userdata('access_token', $tokens['access_token']);
        $this->session->set_userdata('refresh_token', $tokens['refresh_token']);
        return $tokens['access_token'];
    }

    public function getAccessToken() {
        return $this->session->userdata('access_token');
    }
}
