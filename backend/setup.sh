#!/bin/bash

# Esperar a que la base de datos esté disponible
echo "Esperando a que la base de datos esté disponible..."
while ! mysqladmin ping -h"$WORDPRESS_DB_HOST" --silent; do
    sleep 1
done

echo "Base de datos disponible, continuando..."

# Variables de entorno
WP_CLI_PATH="/var/www/html/wp-cli.phar"
WP_PATH="/var/www/html"

# Descargar WP-CLI si no existe
if [ ! -f "$WP_CLI_PATH" ]; then
    echo "Descargando WP-CLI..."
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    mv wp-cli.phar $WP_CLI_PATH
    chmod +x $WP_CLI_PATH
fi

alias wp="php $WP_CLI_PATH --path=$WP_PATH --allow-root"

# Comprobar si WordPress está instalado
if ! $(wp core is-installed); then
    echo "Instalando WordPress..."
    wp core install \
        --url="http://localhost:8000" \
        --title="Event Manager" \
        --admin_user="admin" \
        --admin_password="adminpassword" \
        --admin_email="admin@example.com" \
        --skip-email
else
    echo "WordPress ya está instalado."
fi

# Instalar y activar plugins necesarios
echo "Instalando y activando plugins..."

# Instalar WPGraphQL
if ! $(wp plugin is-installed wp-graphql); then
    wp plugin install https://github.com/wp-graphql/wp-graphql/releases/download/v1.11.5/wp-graphql.zip --activate
else
    wp plugin activate wp-graphql
fi

# Activar plugin event-manager
wp plugin activate event-manager

# Configurar enlaces permanentes
echo "Configurando enlaces permanentes..."
wp rewrite structure '/%postname%/' --hard
wp rewrite flush --hard

# Iniciar Apache en primer plano
echo "Iniciando Apache..."
apache2-foreground
