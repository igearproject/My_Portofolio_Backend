<!DOCTYPE html>
<html>
    <head>
        <title>Verification Email By Gede Arya</title>
    </head>
<body>
 
    <h1>Verify Your Email</h1>
    <p>Hi {{$mailData['name']}},</p>
    <br/>
    <p>Our website (geearya.sipintek.com) needs to confirm that it was you who sent this message:</p>
    <br/>
    <table width="100%">
        <tr>
            <th>Name</th>
            <td>{{$mailData['name']}}</td>
        </tr>
        <tr>
            <th>Subject</th>
            <td>{{$mailData['subject']}}</td>
        </tr>
        <tr>
            <th>Message</th>
            <td>{{$mailData['message']}}</td>
        </tr>
    </table>

    <p>Please confirm this email by opening the following verification link:</p>
    <br/>
    <a href="https://gedearya.sipintek.com/verification-message/{{$mailData['id']}}/{{$mailData['token']}}/{{$mailData['messageId']}}?name={{$mailData['name']}}">
        <button>Click to Confirm</button>
    </a>
    <br/>
    <p>After confirmation, your message will be sent directly to my email.</p>
    <br/>
    <br/>
    <p>
        Best regards,
    </p>
    <p>
        By Gede Arya
    </p>
 
</body>
</html> 