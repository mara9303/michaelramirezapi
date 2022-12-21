# MICHAELRAMIREZAPI

Proyecto de microframework api en PHP.

## Instalación

1. Vamos a utilizar el gestor de dependencias [composer](https://getcomposer.org/), si ya lo tiene instalado, ejecutar el comando:

```bash
composer install
```

2.Para la creación de la base de datos y sus tablas, ejecutar el comando:

```bash
php bin/execute create:tables
```

3.Configuración de variables

Se creó un archivo de respaldo para las variables de ambiente del proyecto llamado .env.example, por favor copie y pegue este archivo y lo renombra a .env; una vez hecho se deben configurar las variables

```bash
DB_NAME='Ubicación de nuestra base de datos sqlite'
API_TOKEN='Nuestro token con full acceso'
API_TOKEN_READ='Nuestro token con permisos de lectura solamente'
```
Las variables de los token pueden ser autogeneradas con el comando:

```bash
php bin/execute create:tokens
```

## Uso

El api está configurado para funcionar de la siguiente manera:

1.Tiene un archivo index en la raíz que incluye a su vez al archivo index en la carpeta pública y a su vez este crea el objeto App con patrón singleton y es donde inicia toda la configuración del api.

2.En el objeto App se cargan las rutas del api para lo cual utilicé el componente de rutas de symfony/routing y symfony/http-foundation para el manejo de los Request y Response.

3.Si la ruta existe, el objeto App manda a llamar al controlador y método configurado en la ruta.

4.En el controlador se verifica los permisos del token y si tiene acceso, por medio del modelo consulta o realiza alguna modificación en los datos, en caso de consulta el objeto se parsea a conveniencia y en el caso de las modificaciones se retorna el status de la transacción.

5.De vuelta nuevamente en el controlador, se retorna un Response con su debido status y mensaje.

6.Para poder utilizar el api y ejecutar sus peticiones, es necesario configurar en los headers una nueva llave que se llamará apiToken, este va a ser el encargado de gestionar el acceso y poder ejecutar las solicitudes, si este header no está presente retornará el error:

```json
{
    "data": [],
    "status": "error",
    "message": "An error occurred: The API_TOKEN is not present"
}
```

## Comentarios
Dentro del repositorio se encuentra la colección de postman llamada **MICHAELRAMIREZAPI.postman_collection.json**.

Espero que la lectura del código sea sencilla, cualquier retroalimentación es bien recibida.
    
## Autor

- [@mara9303](https://github.com/mara9303)