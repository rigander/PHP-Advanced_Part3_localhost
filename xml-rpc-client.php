<?php
header('Content-Type: text/html;charset=utf-8');
/* Сюда приходят данные с сервера */
$output = [];
/* Основная функция */
function make_request($xml, &$output) {
    /* НАЧАЛО ЗАПРОСА */
    $options = [ 'http'=>[ 'method' => "POST",
        'header' => "User-Agent: PHPRPC/1.0\r\n" .
            "Content-Type: text/xml\r\n" . "Content-length: "
            . strlen($xml) . "\r
\n",
    'content' => "$xml"
    ]
    ];
    $context = stream_context_create($options);
    $retval = file_get_contents('http://mysite.local/xmlrpc/xml-rpc-server.php',
        false, $context);
    /* КОНЕЦ ЗАПРОСА */
    $data = xmlrpc_decode($retval);
    if (is_array($data) && xmlrpc_is_fault($data))
    {
        $output = $data;
    }else{
        $output = unserialize(base64_decode($data));
    }
}
/* Идентификатор статьи */
$id = 1;
$request_xml = xmlrpc_encode_request('getNewsById', array($id));
make_request($request_xml, $output);
/* Вывод результата */
var_dump($output);
?>