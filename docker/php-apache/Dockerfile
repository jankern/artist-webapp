FROM php:7.2-apache

# copy custom configs
COPY php.ini /usr/local/etc/php/
COPY apache.conf /etc/apache2/sites-available/000-default.conf
COPY ssmtp.conf /etc/ssmtp/

# generate SSL cert for testing purposes
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/CN=localhost"

# enable apache modules
RUN a2enmod expires headers rewrite ssl
RUN a2ensite default-ssl

# install extensions
RUN apt-get update -q && apt-get install -qy \
       libfreetype6-dev \
       libjpeg62-turbo-dev \
       libmcrypt-dev \
       libpng-dev \
       ssmtp \
       unzip \
       dos2unix \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip

# decide which REDAXO version to use
#ENV REDAXO_VERSION=5.0.0 REDAXO_SHA=d205cbd6783332a212c5ae92d73c77178c2d2f28
#ENV REDAXO_VERSION=5.1.0 REDAXO_SHA=d9cd7583eb61fd00e3bc91148648a4126245d040
#ENV REDAXO_VERSION=5.2.0 REDAXO_SHA=fa141b1b7116c5a1349c63193c230d517c112b8e
#ENV REDAXO_VERSION=5.3.0 REDAXO_SHA=fd20820ab4c1faa905dabe7bb526c3aec8afe24b
#ENV REDAXO_VERSION=5.4.0 REDAXO_SHA=bdf19a343d91bb23ff5f8a7644af54c643bebd2b
#ENV REDAXO_VERSION=5.5.0 REDAXO_SHA=a90e5329c7588d467b95c3785a49682069760a77
#ENV REDAXO_VERSION=5.5.1 REDAXO_SHA=050aa51821531238ffbb084936649cde83026ca4
#ENV REDAXO_VERSION=5.6.0 REDAXO_SHA=7cfc858d74f1840cfc3f7a073503157be8bdd47b
#ENV REDAXO_VERSION=5.6.1 REDAXO_SHA=841dbf6ed747ae779ec205a3b077bb38e54bc405
#ENV REDAXO_VERSION=5.6.2 REDAXO_SHA=3dde376fefb9825f64a33c012efb7da96cfa1098
#ENV REDAXO_VERSION=5.6.3 REDAXO_SHA=9dca2068ac02c3ab5618b827dbd97aec0a2cf8e2
ENV REDAXO_VERSION=5.6.4 REDAXO_SHA=6cd0be12f7bcfba7221304f37cfd63709541625d

# fetch REDAXO, validate checksum and extract to tmp folder
RUN set -e; \
    mkdir /tmp/redaxo; \
    curl -Ls -o /tmp/redaxo/redaxo_${REDAXO_VERSION}.zip https://github.com/redaxo/redaxo/releases/download/${REDAXO_VERSION}/redaxo_${REDAXO_VERSION}.zip; \
    echo "${REDAXO_SHA} */tmp/redaxo/redaxo_${REDAXO_VERSION}.zip" | shasum -c -a 256; \
    unzip -oq /tmp/redaxo/redaxo_${REDAXO_VERSION}.zip -d /tmp/redaxo/src; \
    rm -f /tmp/redaxo/redaxo_${REDAXO_VERSION}.zip;

# copy REDAXO configs and helpers
COPY default.config.yml demos.yml docker-redaxo.php /tmp/redaxo/

# copy REDAXO setup script and run setup
COPY docker-entrypoint.sh /usr/local/bin/
RUN dos2unix /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# start apache
CMD ["apache2-foreground"]
