FROM php:8.1.1-apache
WORKDIR /var/www/laravel9-posgre-line-app

# PHPのモジュールなどをインストール ※決まり文句
RUN apt-get update \
  && apt-get install -y zlib1g-dev \
  && apt-get install -y libpng-dev \
  && apt-get install -y libpq-dev \
  && apt-get install -y zip unzip \
  && apt-get install -y libzip-dev \
  # postgresql関係
  #&& docker-php-ext-install pdo \
  && docker-php-ext-install pdo_pgsql \
  && docker-php-ext-install pgsql \
  # ローカル上で.htaccessを有効する。これが無いとmod_rewriteが有効化されない、例えばLaravel側でルーティングを設定しても404となる
  && a2enmod rewrite \
  #xdebug install
  && pecl install xdebug \
  && docker-php-ext-enable xdebug

# composerのインストール ※決まり文句
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && chmod +x /usr/local/bin/composer

# composer周りの環境変数
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
# タイムゾーン設定
ENV TZ=Asia/Tokyo

# 自分が作成したphp.iniファイルを適用する
COPY ./php.ini /usr/local/etc/php/php.ini