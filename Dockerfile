FROM dunglas/frankenphp

# Instalar el driver de PostgreSQL que le falta a PHP
RUN install-php-extensions pdo_pgsql pgsql

# Copiar el backend PHP al servidor
COPY . /app/public/
