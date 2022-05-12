FROM php:8-apache

COPY matt-virtualhost.conf /etc/apache2/sites-available/matt-virtualhost.conf
COPY matt-php.conf /etc/apache2/conf-enabled/matt-php.conf
COPY wwwroot /var/www/html

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf &&\
    a2enmod rewrite &&\
    a2dissite 000-default &&\
    a2ensite matt-virtualhost

EXPOSE 80
