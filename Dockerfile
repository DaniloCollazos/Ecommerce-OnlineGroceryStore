FROM php:8.2-apache

# Instalar dependencias del sistema + extensiones PHP para PostgreSQL
RUN apt-get update && apt-get install -y \
        libpq-dev \
        && docker-php-ext-install pdo pdo_pgsql pgsql \
        && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite (necesario para URLs limpias y SEO)
RUN a2enmod rewrite

# ✅ CRÍTICO para Render: configurar DocumentRoot al directorio /public
# Evita exponer Config/, Models/, etc. al navegador
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Permitir que .htaccess funcione dentro de /public
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

COPY . /var/www/html/

# ✅ Nunca exponer el .env al navegador — moverlo fuera del public
# (tu estructura ya lo tiene en la raíz, bien)

EXPOSE 80