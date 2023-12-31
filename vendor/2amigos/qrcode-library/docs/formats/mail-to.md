MailToFormat
------------

To encode an e-mail address like sean@example.com, one could simply encode hola@2amigos.us. However to ensure it is 
recognized as an e-mail address, it is advisable to create a proper mailto: URI from the address: 
`mailto:hola@2amigos.us`.

This class helps to enforce the above rule. 

Usage
-----

```php 

use Da\QrCode\QrCode;
use Da\QrCode\Format\MailtoFormat; 

$format = new MailToFormat(['email' => 'hola@2amigos.us']);

$qrCode = new QrCode($format);

header('Content-Type: ' . $qrCode->getContentType());
echo $qrCode->writeString();

```

© [2amigos](https://2am.tech/) 2013-2023
