<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use GuzzleHttp\Client;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

function create_news($data)
{
    $client = new Client(['base_uri' => 'http://localhost:8000/news']);

    $response = $client->request('POST',  [
        json_decode($data), 
        'headers' => ['Content-Type' => 'application/json',]
    ]);

    return $response;

}

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    $respose = create_news($msg->body);
    echo " [x] $response\n";
    $msg->ack();
  };
  
$channel->basic_qos(null, 1, null);
$channel->basic_consume('hello', '', false, false, false, false, $callback);
  
while ($channel->is_open()) {
    $channel->wait();
}


$channel->close();
$connection->close();

?>