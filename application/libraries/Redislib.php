<?php

class Redislib
{

    private $client;

    public function __construct()
    {
        // Create Redis client with timeout settings and no persistence
        $this->client = new Predis\Client([
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'timeout'  => 600,               // 10 seconds timeout
            'persistent' => false,          // Disable persistent connections
            'read_write_timeout' => 600,     // Read/write timeout
        ]);
    }

    public function set($key, $value)
    {
        return $this->client->set($key, $value);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }
    // Get array (list) from Redis by key
    public function getArray($key)
    {
        return $this->client->lrange($key, 0, -1);  // Get all elements from list stored in Redis
    }

    // Set array (list) in Redis under a key
    public function setArray($key, $values)
    {
        return $this->client->lpush($key, $values);
    }

    public function hset($hash, $field, $value)
    {
        return $this->client->hSet($hash, $field, $value);
    }

    public function hget($hash, $field)
    {
        return $this->client->hGet($hash, $field);
    }

    public function del($key)
    {
        return $this->client->del([$key]);
    }

    public function lPush($key, $value)
    {
        return $this->client->lpush($key, $value);
    }

    public function lRange($key, $start, $end)
    {
        return $this->client->lrange($key, $start, $end);
    }

    public function exists($key)
    {
        return $this->client->exists($key);
    }

    // Function to flush the Redis database
    public function flush_db()
    {
        try {
            return $this->client->flushdb();
        } catch (Exception $e) {
            log_message('error', 'Error flushing Redis DB: ' . $e->getMessage());
            return false;
        }
    }
}
