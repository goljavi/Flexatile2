# Flexatile

Flexible CMS & Ecommerce Platform made in PHP & Codeigniter HMVC

Demo: Flower selling website. 
- You can see it at:
- www.primerapropuesta.com.ar/floreria
- www.primerapropuesta.com.ar/floreria/admin
- User: demo
- Password: demo123

Websites that use this system:
- http://www.floresargentina.com.ar
- http://floresparavos.com.ar/
- http://famago.com.ar/
- http://www.hrc-consultora.com/
- http://alquileresenvaleria.com.ar/
- http://minutillo.com.ar/
- http://primerapropuesta.com.ar/faisanuevo
- http://stancat.net/sanor

All my code is written at application>modules
The following modules were coded:

- 'templates' => Template manager
- 'blog' => Blog CRUD with image upload system
- 'cms' => Content Management System for "Quienes somos" & "¿Como comprar?" pages
- 'store_items' => Product CRUD & MercadoPago API Connection
- 'item_images' => Product image CRUD
- 'store_categories' => Product category CRUD
- 'item_images' => Slider image CRUD
- 'shipping' => Shipping CRUD, price is added to the purchase total.
- 'contact' => Contact form Module with PHPMailer dependency
- 'site_security' => Auth & Session Module

All modules have an Admin UI & Public UI

How do i make it work?

- Generate the MySQL database with the SQL file in root directory. Go to application>config>database.php to make the connection. Add hostname, username, password and database.

- Go to application>config>config.php and fill $config['base_url'] with the site url, an example is localhost.

- Go to application>libraries>Mp.php and fill $this->client_id = ''; & $this->client_secret = ''; with your own MercadoPago API Keys

- The platform is made to work on the server's root directory if you can't make it work on the root directory open .htaccess file on root and fill "RewriteBase /" like this: "RewriteBase /path/to/website". Also go to application>modules>templates>views>sort_this_code.php and make the changes that are written there.
