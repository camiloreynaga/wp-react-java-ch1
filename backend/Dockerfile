FROM wordpress:latest

# Instalar extensiones PHP necesarias (opcional)
RUN docker-php-ext-install mysqli

# Copiar plugins y temas personalizados
COPY wp-content /var/www/html/wp-content

# Copiar script de inicio para configurar WordPress
COPY setup.sh /setup.sh
RUN chmod +x /setup.sh

# Ejecutar el script de inicio al iniciar el contenedor
ENTRYPOINT ["bash", "/setup.sh"]
