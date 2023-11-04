FROM php:apache

#RUN a2enmod ssl

#COPY lamp.test.crt /etc/ssl/certs/lamp.test.crt
##COPY lamp.test.key /etc/ssl/private/lamp.test.key
#COPY lamp.test-ssl.conf /etc/apache2/sites-available/lamp.test-ssl.conf
#RUN a2ensite lamp.test-ssl

RUN docker-php-ext-install pdo pdo_mysql