<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use GuzzleHttp\Client;

class OneD extends CI_Controller {
    private $clientId = 'b627b77d-5a2a-4be8-87dd-6a7e04fa0d17';
    private $clientSecret = 'dvP8Q~r6oP4CJ3t8P.mhzlYYJ3BWuECmz2gTicy-';
    private $redirectUri = 'https://sales.rambla.id/oned/token';
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
        $data['url'] = $authorizationUrl;
        $this->load->view('configuration/onedauth', $data);
    }

    public function getAccessToken() {
        $authorizationCode = $this->input->get('code');
        //$authorizationCode = 'M.C511_BL2.2.U.c6dedbdf-d13e-bec7-66f9-fba31f92c225';
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
        //$this->session->set_userdata('access_token', $tokens['access_token']);
        //$this->session->set_userdata('refresh_token', $tokens['refresh_token']);

        $tokenData = array(
            'access_token' => $tokens['access_token'], // Generate a random token
            'refresh_token' => $tokens['refresh_token'] // Expiration time
        );

        // Convert the array to JSON format
        $jsonData = json_encode($tokenData, JSON_PRETTY_PRINT);
         // Define the file path to store the JSON file
         $filePath = APPPATH . 'tokens/token.json'; // Store it in the "application/tokens" folder


        if (!write_file($filePath, $jsonData)) {
            echo "Unable to write the token to the file.";
        } else {
            echo "Token has been successfully written to the file.";
        }

        echo json_encode(['tokens' => $tokens]);
    }

}
?>
