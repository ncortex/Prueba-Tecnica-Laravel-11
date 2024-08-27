# Readme
Prueba técnica para la empresa **** escrita en PHP8.2 usando Laravel 11 como framework.
He intentado centrarme extrictamente en los requerimientos de la prueba, por lo que no he añadido ninguna funcionalidad extra, sino que he preferido pulir la implementación de la funcionalidad requerida, sin reinventar la rueda.

[link_pdf_prueba]

## instalación

1. Clonar el repositorio
   ```
   git clone git@github.com:ncortex/Prueba-Tecnica-Laravel-11.git
   cd Prueba-Tecnica-Laravel-11
   ```
2. Instalar las dependencias
   ```
    composer install
   ```
3. Crear un archivo `.env` en la raíz del proyecto y copiar el contenido del archivo `.env.example` en él.
   ```
    cp .env.example .env
   ```
4. Crear una base de datos y rellenarla con los datos de la API externa. Puede tardar unos minutos
   
   ```
   php artisan migrate --seed
    ```
5. Ejecutar el servidor en local
   ```
   php artisan serve
   ```
## demo online
[link_demo]
## decisiones clave 
- Uso del ingles como idioma de desarrollo.
- Uso de sanctum como sistema de autenticación. Es el sistema recomendado por Laravel para casos como este, y se ocupa de todo lo relativo a la autenticación de usuarios, incluida la generación de tokens para acceso vía API.
- Uso del sistema de paginación por defecto de Laravel.
- Inicialización de los datos de prueba en la base de datos mediante seeders. De esta forma los datos de la API se copian en nuestra BD local al ejecutar `php artisan migrate --seed`. Esto hace que las consultas a nuestra API sean significativamente más rápidas que si se hicieran las consultas a la API externa cada vez que un usuario hace una petición, pues de esta forma solo se hacen consultas asíncronas a la API externa.
- Si se ejecuta el seeder una segunda vez se comprueba si el personaje ya existe en la BD y, si es así, lo actualiza. Si no, lo crea. 
- Separación de las llamadas a la API externa en un seeder. De esta forma, si en el futuro se decide cambiar la API externa por otra, solo habría que modificar el seeder y no el controlador.
- En vez de crear una tabla de favoritos, los favoritos se almacenan en forma de array en una columna de la tabla `users`. De esta manera se pueden recuperar todos los favoritos de un usuario en una sola lectura de la BD. Por contra, haría mas ineficiente,por ejemplo, una consulta que cuente el numero de favoritos que tiene un personaje. Esto se podría solucionar añadiendo un contador en la tabla de personajes y actualizandolo cuando un usuario lo añada o elimine de sus favoritos.
- He eliminado el campo nombre en el modelo de usuario, ya que no es necesario. He eliminado también los métodos a autenticación que no se usan en la API.
- Validar TODAS las peticiones usando el validador de Laravel por defecto.
## posibles mejoras
- mantener un id propio para cada personaje. En este proyecto he usado el mismo id que proporciona la API, pero podría haber usado uno que asigne yo (aunque coincida). De esta forma, si la API externa cambia el id de un personaje, o si se cambia de API externa, no afectaría a nuestra BD.
- hacer una interfaz web para la API. En este proyecto he hecho solo la API, pero se podría hacer una interfaz web para que los usuarios puedan interactuar con la API de forma más visual.
## endpoints

### POST /api/register
parámetros: 

`email` with valid email format

`password` with at least 6 characters

en caso de éxito devuelve un token de autenticación. Si no, devuelve un mensaje de error.

```
curl -X POST http://localhost:8000/favorites \
-H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{"character_id": 1}'
```

### POST /api/login
parámetros: 

`email` with valid email format

`password` with at least 6 characters

en caso de éxito devuelve un token de autenticación. Si no, devuelve un mensaje de error.
### GET /api/characters
parámetros: 

`page` (opcional, por defecto muestra la primera página)

`per_page` (opcional, por defecto muestra 20 personajes por página)

`name` (opcional, busca personajes cuyo nombre contenga la cadena de texto)

`status` (opcional, busca personajes cuyo status sea el indicado)


### GET /api/characters/{id}
