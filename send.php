<?php
//send messages
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
include_once('settings.php');

$argvs = json_encode($_POST);
$exchange = 'hello-exchange';

$conn = new AMQPStreamConnection(HOST, PORT, USER, PASS);
	
$channel = $conn->channel();
$severity = 'email';
$channel->exchange_declare($exchange,
					'direct',
					false,
					true,
					false);

$msg = new AMQPMessage($argvs);

$channel->basic_publish($msg, $exchange);

header('Location: form.php?sent=true');
?>