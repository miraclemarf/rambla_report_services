<?php

class Redislib {

    private $client;

    public function __construct() {
        // Create Redis client with timeout settings and no persistence
        $this->client = new Predis\Client([
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'timeout'  => 10,               // 10 seconds timeout
            'persistent' => false,          // Disable persistent connections
            'read_write_timeout' => 10,     // Read/write timeout
        ]);
    }

    public function set($key, $value) {
        return $this->client->set($key, $value);
    }

    public function get($key) {
        return $this->client->get($key);
    }

    public function del($key) {
        return $this->client->del([$key]);
    }

    public function exists($key) {
        return $this->client->exists($key);
    }
}
