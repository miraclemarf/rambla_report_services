<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';

use GuzzleHttp\Client;

class M_TokenOneD extends CI_Model {
    private $clientId = 'b627b77d-5a2a-4be8-87dd-6a7e04fa0d17';
    private $clientSecret = 'dvP8Q~r6oP4CJ3t8P.mhzlYYJ3BWuECmz2gTicy-';
    // secret id a726c91f-37c0-4816-9213-21a7a0ee9e05
    private $redirectUri = 'https://sales.rambla.id/oned/token';
    private $client;

    public function __construct() {
        parent::__construct();
        $this->client = new Client();
    }

    public function refreshToken() {
        //$refreshToken = $this->session->userdata('refresh_token');

        // Define the file path of the JSON file
        $filePath = APPPATH . 'tokens/token.json';

        // Check if the file exists
        if (!file_exists($filePath)) {
            echo "Token file does not exist.";
            return;
        }

        // Read the JSON file
        $jsonData = read_file($filePath);

        // Decode the JSON data to an associative array
        $tokenData = json_decode($jsonData, true);

        $refreshToken = $tokenData['refresh_token'];

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
        //$this->session->set_userdata('access_token', $tokens['access_token']);
        //$this->session->set_userdata('refresh_token', $tokens['refresh_token']);

        $tokenData = array(
            'access_token' => $tokens['access_token'], // Generate a random token
            'refresh_token' => $tokens['refresh_token'] // Expiration time
        );

        // Convert the array to JSON format
        $jsonData = json_encode($tokenData, JSON_PRETTY_PRINT);


        if (!write_file($filePath, $jsonData)) {
            echo "Unable to write the token to the file.";
        } else {
            echo "Token has been successfully written to the file.";
        }

        return $tokens['access_token'];
    }

    public function getAccessToken() {
        // Define the file path of the JSON file
        $filePath = APPPATH . 'tokens/token.json';

        // Check if the file exists
        if (!file_exists($filePath)) {
            echo "Token file does not exist.";
            return;
        }

        // Read the JSON file
        $jsonData = read_file($filePath);

        // Decode the JSON data to an associative array
        $tokenData = json_decode($jsonData, true);

        return $tokenData['access_token'];
    }
}
