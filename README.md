# Getting Start

- composer update
- jika menggunakan XAMPP (VHOST)
  https://www.yiiframework.com/doc/guide/2.0/en/start-installation
- edit database
  common -> config -> main-local.php


# Jika menemui error seperti ini
```
Exception (Invalid Configuration) 'yii\base\InvalidConfigException' with message 'The directory is not writable by the Web process: /opt/lampp/htdocs/sikkepo_yii2/admin/web/assets'

in /opt/lampp/htdocs/sikkepo_yii2/vendor/yiisoft/yii2/web/AssetManager.php:242
...
...
...
#26 {main}
```

Gunakan perintah 
#UBUNTU
- chmod -R 777 /opt/lampp/htdocs/sikkepo_yii2
