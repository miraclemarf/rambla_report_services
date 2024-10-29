<?php
if (!function_exists('api_response')) {
    function api_response($status = "success", $message = "", $data = null, $errors = null, $code = 200)
    {
        $CI = &get_instance();
        $CI->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode([
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'errors' => $errors
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
    }
}
