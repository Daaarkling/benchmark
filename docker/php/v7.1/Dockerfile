FROM php:7.1-cli

# Install requirements
RUN apt-get update && apt-get install -y --no-install-recommends \ 
	openssh-client \ 
	curl \ 
	ca-certificates \ 
	wget \ 
	git \
	zip \
	unzip


# Install composer 
RUN curl -sS https://getcomposer.org/installer | php 
RUN mv composer.phar /usr/local/bin/composer     
  
  
# Install pecl ext
RUN pecl install msgpack-2.0.2 \
    && pecl install jsond-1.4.0RC1 \
    && docker-php-ext-enable msgpack jsond


# Install serggp/php-protobuf
RUN mkdir -p /tmp/protobuf \
	&& git clone -b php7 https://github.com/serggp/php-protobuf.git /tmp/protobuf \
    && docker-php-ext-configure /tmp/protobuf \
    && docker-php-ext-install /tmp/protobuf \
    && rm -r /tmp/protobuf


# Prepare benchmark
WORKDIR /opt
RUN git clone https://github.com/Daaarkling/benchmark.git
WORKDIR benchmark/benchmark-php
RUN composer install
RUN mkdir output
CMD php init.php b:r -r $repetition -f $format -o csv -d ./output
