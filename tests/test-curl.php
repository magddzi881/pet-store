<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://petstore.swagger.io/v2/pet/findByStatus?status=available");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CAINFO, "C:/php/extras/ssl/cacert.pem");
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    echo 'OK';
}
curl_close($ch);
