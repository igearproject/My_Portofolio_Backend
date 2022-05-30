<!DOCTYPE html>
<html>
    <head>
        <title>Email To Me</title>
    </head>
<body>
 
    <h1> {{$mailData['subject']}}</h1>
    <p>From: {{$mailData['name']}}</p>
    <p>Email: {{$mailData['email']}}</p>
    <p>Status: 
    @if ($mailData['status'])
        Terverifikasi
    @else
        Tidak terverifikasi
    @endif
    </p>
    <br/>
    <p>{{$mailData['message']}}</p>
    <br/>
    <br/>
    <p>
        Best regards,
    </p>
    <p>
        By Gede Arya Web
    </p>
 
</body>
</html> 