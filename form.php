<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Advanced Messaging System</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style type="text/css">
      body{
        margin: 30px;
      }
      #result{
        margin-bottom: 20px;
        font-size: 14px;    
        color: #15B72A; 
      }
      header{margin: 0 0 30px 0;}
      section{width: 50%;}
    </style>
  </head>
  <header><h1>Advanced Messaging System</h1></header>
  <section>  
    <?php
        if(!empty($_GET['sent'])){
     ?>
        <div id="result">
            Your message was sent!
        </div>
    <?php
    }
    ?>
<form action="send.php" method="POST">
    <div class="form-group">
        <label for="msg_type">Type</label>
        <select name="msg_type" id="msg_type" class="form-control">
            <option value="email">Email</option>
            <option value="sms">SMS</option>
        </select>
    </div>
    <div class="form-group">
        <label for="from">From</label>
        <input type="text" name="from" id="from" class="form-control">       
    </div>
    <div class="form-group">
        <label for="receiver">Reciever</label>
        <input type="text" name="receiver" id="receiver" class="form-control">           
    </div>
    <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>   
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default">Send</button>
    </div>
</form></section>
  </body>
</html>