### Sample demo eshop app

##### Virtual host configs

```
<VirtualHost *:80>
  ServerName eshop                                                              
  DocumentRoot /var/www/eshop

  <Directory /var/www/eshop>
    Options Indexes FollowSymLinks
    Order allow,deny
    Allow from all
    AllowOverride All
  </Directory>
 </VirtualHost>
```

##### Modify /etc/hosts
```
127.0.0.1 eshop
```

##### Enable mod_rewrite module, under apache2

```
sudo a2enmod rewrite
```

##### Modify libs/geopay.php to your needs
##### Example:

```
define('GEOPAY_URL', 'http://localhost:3009/');
define('RETURN_URL', 'http://localhost/simple/return.php');
define('ABANDON_URL', 'http://localhost/simple/return.php');
define('GEOPAY_ID_TOKEN', '4LAHEH0K6G15D83G33ED');
define('SECRET_KEY', '5vIIiB0wEu6VnPPi/Pk8IpkkOeUR2a/snU85YfGK');
define('LOCALE', 'en');
define('DESCRIPTION', 'My Shopping Cart');
define('TAX', 2);
define('SHIPPING', 3);
```
