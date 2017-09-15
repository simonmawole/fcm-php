<?php
    $output = '';
    function sendNotification($data){
        $headers = array(
            "Authorization: Key=", //After Key= add your fcm server key eg. Key=AAAAMTW_nL8:APA9....
            "Content-Type: application/json"
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($curl);

        if($response === false){
            die("SEND ERROR: " . curl_error($response));
        }

        curl_close($curl);
        return $response;
    }
    if(isset($_POST['topic'])){
        $title = $_POST['title'];
        $message = $_POST['message'];
        $to = "/topics/" . $_POST['to'];
        $data = array(
            "to" => $to,
            "data" => array(
                "title" => $title,
                "message" => $message
            )
        );
        $output = sendNotification($data);
    } 
    if(isset($_POST['single'])){
        $title = $_POST['title'];
        $message = $_POST['message'];
        $to = $_POST['to'];
        $data = array(
            "to" => $to,
            "data" => array(
                "title" => $title,
                "message" => $message
            )
        );
        $output = sendNotification($data);
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FCM</title>
</head>
<body>
    <form action="index.php" method="POST">
        <label>Topic / Single User:</label><br/>
        <input type="text" name="to"/><br/><br/>

        <label>Title:</label><br/>
        <input type="text" name="title"/><br/><br/>

        <label>Message:</label><br/>
        <input type="text" name="message"/><br/><br/>

        <input type="submit" name="topic" value="Send To Topic"/> | <input type="submit" name="single" value="Send To Single User"/>
    </form>

    <p><b>Response: </b><br/>
        <?php
            echo $output;
        ?>
    </p>
</body>
</html>
