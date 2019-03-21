<VirtualHost *:80>
  ServerAdmin webmaster@localhost
  ServerName korting.ru
ServerAlias kor.ksupport.ru
ServerAlias tr-k.ksupport.ru
ServerAlias www.korting.ru
ServerAlias kerting.ru
ServerAlias www.kerting.ru
ServerAlias koerting-bt.ru
ServerAlias www.koerting-bt.ru
ServerAlias xn--c1adjgrqo.xn--p1ai
ServerAlias www.xn--c1adjgrqo.xn--p1ai
ServerAlias xn--c1ajfnnm7h.xn--p1ai
ServerAlias www.xn--c1ajfnnm7h.xn--p1ai
ServerAlias kerting.net
ServerAlias www.kerting.net
ServerAlias koerting-br.net
ServerAlias www.koerting-br.net
ServerAlias koerting-bt.com
ServerAlias www.koerting-bt.com
ServerAlias korsini.ru
ServerAlias www.korsini.ru
ServerAlias b2b.korting.ry
  DocumentRoot /var/www/html/korting.ru
 
  CustomLog /var/log/apache2/korting.ru_access.log common
  ErrorLog /var/log/apache2/korting.ru_error.log
  <Directory />
    Options FollowSymLinks
    AllowOverride None
  </Directory>
 
  <Directory /var/www/html/korting.ru>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All 
    Order allow,deny
    allow from all
  </Directory>
</VirtualHost>

