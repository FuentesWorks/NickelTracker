<VirtualHost *:80>
ServerName      triatlonafondo.com
Redirect permanent / http://www.triatlonafondo.com/
</VirtualHost>
