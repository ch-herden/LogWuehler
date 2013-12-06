LogWuehler
==========

LogWuehler is an open source software to show different types of log files in a web browser.

### Supported log files
+   Apache Error Log
+   Apache Access Log
+   Nginx Error Log
+   Nginx Access Log

### System requirements
+   PHP 5.4 or later
+   Apache HTTP Server with mod_rewrite or nginx

### Installation
#### Apache HTTP Server
+ Download LogWuehler and extract it to the folder you would like to keep it in
+ Set the DocumentRoot to the public folder
+ Make "Application" folder writable
+ Make the log folders readable and executable
+ Optional: Modify htaccess for http authentication

#### Nginx
+ Download LogWuehler and extract it to the folder you would like to keep it in
+ Set the DocumentRoot to the public folder
+ Make "Application" folder writable
+ Make the log folders readable and executable
+ Add rewrite entry in vhost file
+ Optional: Modify vhost file for http authentication

Nginx vhost example width https and http authentication

    server {
        server_name [servername];
        listen 80;
        listen 443 ssl;

        if ($ssl_protocol = "") {
            rewrite ^ https://$server_name$request_uri? permanent;
        }

        ssl_certificate /etc/nginx/ssl/server.crt;
        ssl_certificate_key /etc/nginx/ssl/server.key;

        access_log [path to access log file];
        error_log [path to error log file];

        root [path to public folder];

        auth_basic "Restricted";
        auth_basic_user_file [path to htpasswd];

        index index.html;
        try_files $uri @virtual;

        location @virtual {
            index index.php;
            include fastcgi_params;
            fastcgi_param   SCRIPT_FILENAME         $document_root/index.php;
            fastcgi_pass unix:/var/run/php5-fpm.sock;
            fastcgi_index index.php;
        }
    }

### Supported languages
+ German

### Feedback
Any feedback, suggestions, criticism, doesn't matter if good or not?
Add an issue or send me an email (contact@chris-herden.de) to improve the software!
Thank you!