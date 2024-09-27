# wp-react-java-ch1
# Project estructure:

event-manager-challenge/
├── backend/            # WordPress and configuration
│   ├── wp-content/
│   │   └── plugins/
│   │       └── event-manager/
│   │           └── event-manager.php
│   └── Dockerfile
├── frontend/           # App React
│   ├── src/
│   ├── Dockerfile
│   └── .dockerignore
├── docker-compose.yml  # Orquestación de los servicios
└── README.md           # Doc


# Event Manager Challenge

## Descripción

Este proyecto es una aplicación web que integra un backend de WordPress con un frontend en React utilizando GraphQL. El backend utiliza un tipo de publicación personalizado llamado `Event`, y el frontend muestra una lista de eventos consumiendo la API GraphQL de WordPress.

## Tecnologías Utilizadas

- **Docker & Docker Compose**: Para orquestar los servicios.
- **WordPress**: Como backend y CMS.
- **WPGraphQL**: Para exponer la API GraphQL.
- **React**: Para el frontend.
- **Apollo Client**: Para consumir la API GraphQL en React.
- **Nginx**: Para servir la aplicación React.

## Requisitos Previos

- Tener **Docker** y **Docker Compose** instalados en tu máquina.

## Instrucciones de Instalación y Ejecución

1. **Clonar el repositorio:**

   ```bash
   git https://github.com/camiloreynaga/wp-react-java-ch1.git
   cd wp-react-java-ch1 

2. **Ejecutar con docker:**
   docker-compose up -d --build
3. ** acceder con las credenciales **

URL: http://localhost:8080/
Admin panel: http://localhost:8080/wp-admin
- Usuario: admin
- Contraseña: adminpassword