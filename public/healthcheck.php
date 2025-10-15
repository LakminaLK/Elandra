<?php
// Simple health check for Railway
http_response_code(200);
header('Content-Type: text/plain');
echo 'OK';
exit;