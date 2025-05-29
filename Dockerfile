# Usa una imagen base de PHP con Composer y servidor embebido
FROM php:8.2-cli

# Instala dependencias necesarias para Composer
RUN apt-get update && apt-get install -y unzip git curl \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Establece el directorio de trabajo
WORKDIR /app

# Copia todo el código al contenedor
COPY . .

# Instala dependencias PHP (excepto las de desarrollo)
RUN composer install --no-dev

# Expone el puerto
EXPOSE 10000

# Comando para ejecutar el servidor PHP embebido
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
