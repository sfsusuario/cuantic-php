# CUANTIC - PHP CONTAINER
Pasos para la instalación de un sitio web básico con php 7.4 y nginx.

### Prerequisitos
Para ejecutar correctamente este proyecto necesitarás las siguientes herramientas instaladas en tu equipo:
1. Última versión de docker instalada
2. GIT version control

### Paso 1: Descargar repositorio
Usando la herramienta git, te ubicarás en la ruta deseada de la instalación del proyecto y ejecutarás el siguiente comando:

`
git clone https://github.com/sfsusuario/cuantic-php.git
`

Una vez terminada la descarga de tu repositorio, ingresas al directorio principal del proyecto ejecutando el siguiente comando:

`
cd cuantic-php
`

### Paso 2: Construir contenedores y ejecutarlos
Para construir las imágenes y los contenedores junto con sus dependencias ejecutamos el siguiente comando:

`
docker compose create
docker compose start
`

Una vez terminado podemos verificar con el siguiente comando, que efectivamente nuestros contenedores se están ejecutando correctamente:

`
docker ps -a
`

Se listarán los contenedor y el estado actual
![image](https://user-images.githubusercontent.com/126966209/226182030-f26427ce-befa-4b4e-bfb7-6b2c4b7e162f.png)

### Paso 3: Probar nuestro sitio web desde nginx
Una vez ejecutados los contenedores podemos verificar que están ejecutando correctamente, podemos ingresar al contendor y consultar nuestro sitio web de prueba con:

`
curl localhost:8080
`

Podemos visualizar que se ejecuta nuestro sitio web php visualizando la hora actual desde la respuesta de nginx:

![image](https://user-images.githubusercontent.com/126966209/226182098-2a2d673b-97cb-4973-bd94-19b5c272a97e.png)

**Nota:** Este comando mostró resultados directamente del servidor nginx, consultando el servicio desde el puerto 8080 e imprimiendo los resultados de esas solicitud.

### Paso 3: Probar nuestro script de PHP directamente con el comando php.
También podemos ejecutar el script de php directamente con el comando PHP:

`
docker exec -it php php index.php
`

Se imprimirá los siguientes resultados:

![image](https://user-images.githubusercontent.com/126966209/226182311-ead6c62a-ea3a-4f19-a862-891996906179.png)

**Nota:** Este comando ejecutó directamente el script de index.php sin necesidad de utilizar nginx como intermediario. Solamente usamos el comando php para realizar la ejecución. Para ello accedimos con "docker exec -it php" al contenedor y ejecutamos dentro de éste, el comando "php index.php"
