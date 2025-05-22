# Используем официальный образ PHP с Apache
FROM php:8.2-apache

# Устанавливаем необходимые расширения PHP для Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath xml

# Включаем модуль Apache rewrite
RUN a2enmod rewrite

# Копируем проект в корень веб-сервера
COPY . /var/www/html/

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Настраиваем DocumentRoot на public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Открываем порт 80
EXPOSE 80

# Запускаем Apache в форграунд режиме
CMD ["apache2-foreground"]
