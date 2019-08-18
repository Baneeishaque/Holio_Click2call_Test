<?php
/* Hoiio developer credentials */
$hoiioAppId = "2ps6Cgo8IegFZyCW";
$hoiioAccessToken = "ZEbJnaUN5STiqOQQ";
$voiceCallURL = "https://secure.hoiio.com/open/voice/call";
/* My Number */
$callMyNumber = "+6511111111";
/* print HTML for page headers */
echo <<<HEADER
<html>
    <head>
        <title>Click-to-Call Example</title>
    </head>
    <body>
HEADER;
        
if($_POST == null) {
    // no form submission, show click-to-call page
    show_click_to_call_page();
} else {
    if(isset($_POST['call'])) {
        // connect me to this number
        $mobileNumber = $_POST['mobileNumber'];     
        
        // prepare HTTP POST variables
        $fields = array(
                'app_id' => urlencode($hoiioAppId),
                'access_token' => urlencode($hoiioAccessToken),
                'dest1' => urlencode($callMyNumber),    // connect "dest1" and "dest2" with a call
                'dest2' => urlencode($mobileNumber)     
            );
        // form up variables in the correct format for HTTP POST
        $fields_string = "";
        foreach($fields as $key => $value) 
            $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string,'&');
        /* initialize cURL */
        $ch = curl_init();
        
        /* set options for cURL */
        curl_setopt($ch, CURLOPT_URL, $voiceCallURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);       
        
        /* execute HTTP POST request */
        $raw_result = curl_exec($ch);
        $result = json_decode($raw_result);     // parse JSON formatted result
        
        /* close connection */
        curl_close($ch);
        /* parse the result of the API request */
        if($result->status == 'success_ok') {
            echo 'Success making call';
        } else {
            echo $result->status;
            show_click_to_call_page();
        }
    } 
}
/* print HTML for page footers */
echo <<<FOOTER
    </body>
</html>
FOOTER;
/* function to print HTML for click-to-call form */
function show_click_to_call_page() {
    echo <<<CLICK2CALL
<h2>Click-2-Call example</h2>
<form id="login" action="" method="post">
    <table>
        <tr>
            <td>Mobile Number</td>
            <td><input type="text" name="mobileNumber" value=""/></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Call" name="call"/></td>
        </tr>
    </table>
</form>
CLICK2CALL;
}
?>