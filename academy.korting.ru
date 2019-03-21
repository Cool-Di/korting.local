<VirtualHost *:80>
  ServerName academy.korting.ru
  ServerAlias tr-k.ksupport.ru
  ServerAlias www.academy.korting.ru
  ServerAdmin webmaster@localhost
  DocumentRoot /var/www/html/academy.korting.ru
  CustomLog /var/log/apache2/academy.korting.ru_access.log common
  ErrorLog /var/log/apache2/academy.korting.ru_error.log
  <Directory />
    Options FollowSymLinks
    AllowOverride All
  </Directory>
 
  <Directory /var/www/html/academy.korting.ru>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All 
    Order allow,deny
    allow from all
  </Directory>
</VirtualHost>

