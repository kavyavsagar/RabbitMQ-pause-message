<?php
// Receive messages and assign it to different queue. Also pause SMS time for a particular time period
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
include_once('settings.php');

$exchange = 'hello-exchange';
$consumer_tag_em = 'consumerEmail';
$consumer_tag_sm = 'consumerSMS';

$conn = new AMQPStreamConnection(HOST, PORT, USER, PASS);
$channel = $conn->channel();

$channel->exchange_declare($exchange,
         'direct',
         false,
         true,
         false);
// declare email queue
$channel->queue_declare($queue1= 'email');
$channel->queue_bind($queue1, $exchange);
// declare sms queue
$channel->queue_declare($queue2= 'sms');
$channel->queue_bind($queue2, $exchange);

  //SEND EMAIL
$consumerEmail = function($msg){
    $data = json_decode( $msg->body, true);
  
    $msg_type = $data['msg_type'];
    $from_email = $data['from'];
    $to_email = $data['receiver'];
    $subject = "Email from JRD"; 
    $message = $data['message'];

    if($msg_type == 'email'){
        echo "Sender : ". $from_email, "\n";
        echo "Receiver : ". $to_email, "\n";
        echo "Message : ". $message, "\n";
        $transporter = Swift_SmtpTransport::newInstance('smtp.mailtrap.io', 25)
                      ->setUsername('485773766db22dc12')
                      ->setPassword('511398e1db949e');

        $mailer = Swift_Mailer::newInstance($transporter);  

        $message = Swift_Message::newInstance($transporter)
            ->setSubject($subject)
            ->setFrom(array($from_email))
            ->setTo(array($to_email))
            ->setBody($message, 'text/html');
        // Add alternative parts with addPart()
        $message->addPart($message, 'text/plain');

        $mailer->send($message);

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

        if($data == 'quit'){
            $msg->delivery_info['channel']->basic_cancel($msg->delivery_info['consumer_tag']);
        }else{
            echo '####### EMAIL #######',  "\n";// print_r($data);
        }
    }
};

 // SEND SMS
$consumerSMS = function($msg){
    $data = json_decode( $msg->body, true);

    $msg_type = $data['msg_type'];
    $from_email = $data['from'];
    $to_email = $data['receiver'];  
    $message = $data['message'];
    
    if($msg_type == 'sms'){
        // Pause queue for a specified time period
        pauseSMS();
       
        echo "Sender : ". $from_email, "\n";
        echo "Receiver : ". $to_email, "\n";
        echo "Message : ". $message, "\n";

        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    
        if($data == 'quit'){
            $msg->delivery_info['channel']->basic_cancel($msg->delivery_info['consumer_tag']);
        }else{
            echo '####### SMS #######',  "\n"; //print_r($data);
        }
    }
};

$channel->basic_consume($queue1,
  $consumer_tag_em,
  false,
  false,
  false,
  false,
  $consumerEmail);

$channel->basic_consume($queue2,
  $consumer_tag_sm,
  false,
  false,
  false,
  false,
  $consumerSMS);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$conn->close();

//Pause queue for a specified time period
function pauseSMS(){

    $atime = explode("-", PAUSE_PERIOD);

    $now = new DateTime(null, new DateTimeZone('Asia/Dubai'));
    $current = $now->format('Y-m-d H:i:s');

    $start = new DateTime($atime[0]);
    $start = $start->format('Y-m-d H:i:s'); 

    $end = new DateTime($atime[1]);    
    $end =  $end->format('Y-m-d H:i:s'); 

    if($end < $start) {

      $stop_date = new DateTime($end);
      $stop_date->modify('+1 day');
      $stop = $stop_date->format('Y-m-d H:i:s'); 
    }

    if($current >= $start && $current <= $stop){
        //PAUSE PERIOD
        echo "paused";
        time_sleep_until(strtotime($stop));
    }
   
}
?>