# Advanced Message Queuing Protocol (AMQP) in RabbitMQ

A messaging service that reads incoming messages from a RabbitMQ queue, and sends out SMS and Emails, depending on the message type. System want to pause sending SMS messages during a specified time period, like 6pm to 6am. This time period should be configurable from a settings.php file. Created such a system in PHP, using required technologies/libraries.

Constraints are:
  
a: Messages must be read from a RabbitMQ queue.
  
b: Only one RabbitMQ queue is to be used for incoming messages. Create queues for our own needs, but the initial incoming messages must be received on one queue only, as there are other systems that send those messages and they can not be changed
  
c: The emails should continue being sent all day long
  
d: Only SMS messages need to be paused in the configured time window


The message formats on the input queue are:

Email:

{
  ';type';: ';email';,
  ';from';: ';no-reply@company.com';,
  ';recipients';: [';test1@test.com';, ';test2@test.com';],
  ';html';: ';This is a test email. It can <i>also</i> contain HTML code';
  ';text';: ';This is a test email. It is text only';
}

SMS:

{
  ';type';: ';sms';,
  ';from';: ';JRD';,
  ';recipients';: [';+971501478902';],
  ';body';: ';Test SMS message body';
}

Here i am using https://mailtrap.io/ as email SMTP server and use Swiftmailer email library for it. To simulate the sending of an SMS, just print it out to the console.


Install and Download RabbitMQ Server

http://www.rabbitmq.com/download.html


Tutorials of each process

http://www.rabbitmq.com/getstarted.html



