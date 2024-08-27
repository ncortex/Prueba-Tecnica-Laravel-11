# Readme
Prueba técnica para la empresa **** escrita en PHP8.2 usando Laravel 11 como framework.
He intentado centrarme extrictamente en los requerimientos de la prueba, por lo que no he añadido ninguna funcionalidad extra, sino que he preferido pulir la implementación de la funcionalidad requerida, sin reinventar la rueda.

[link_pdf_prueba]

## Instalación

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
6. (Opcional) Programar una tarea regular en cron para que la base de datos local se actualice regularmente
    ```
    0 0 * * * cd /path-to-your-project && php artisan migrate --seed >> /dev/null 2>&1
    ```
Para copiar y pegar:
```
git clone git@github.com:ncortex/Prueba-Tecnica-Laravel-11.git
cd Prueba-Tecnica-Laravel-11
composer install
cp .env.example .env
echo "Actualizando base de datos desde la API externa. Puede tardar unos minutos."
php artisan migrate --seed
php artisan serve
```
En otra terminal:
```
#Register
curl -X POST http://localhost:8000/api/register \
-H "Content-Type: application/json" \
-d '{"email": "jerry_smith@msn.com",
"password": "123456"}'
#Login
YOUR_ACCESS_TOKEN=$(curl -X POST http://localhost:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email": "jerry_smith@msn.com",
     "password": "123456"}')
#Añadir a rick y a morty a favoritos
curl -X POST http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{"id": 1}'
curl -X POST http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{"id": 2}'
#Eliminar a morty de favoritos
curl -X DELETE http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{"id": 2}'
#Listar favoritos (Debería mostrar solo a rick)
curl -X GET http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" 
```
## Decisiones clave 
- Uso del ingles como idioma de desarrollo.
- Uso de sanctum como sistema de autenticación. Es el sistema recomendado por Laravel para casos como este, y se ocupa de todo lo relativo a la autenticación de usuarios, incluida la generación de tokens para acceso vía API.
- Uso del sistema de paginación por defecto de Laravel.
- Inicialización de los datos de prueba en la base de datos mediante seeders. De esta forma los datos de la API se copian en nuestra BD local al ejecutar `php artisan migrate --seed`. Esto hace que las consultas a nuestra API sean significativamente más rápidas que si se hicieran las consultas a la API externa cada vez que un usuario hace una petición, pues de esta forma solo se hacen consultas asíncronas a la API externa.
- Si se ejecuta el seeder una segunda vez se comprueba si el personaje ya existe en la BD y, si es así, lo actualiza. Si no, lo crea. 
- Separación de las llamadas a la API externa en un seeder. De esta forma, si en el futuro se decide cambiar la API externa por otra, solo habría que modificar el seeder y no el controlador.
- En vez de crear una tabla de favoritos, los favoritos se almacenan en forma de array en una columna de la tabla `users`. De esta manera se pueden recuperar todos los favoritos de un usuario en una sola lectura de la BD. Por contra, haría mas ineficiente,por ejemplo, una consulta que cuente el numero de favoritos que tiene un personaje. Esto se podría solucionar añadiendo un contador en la tabla de personajes y actualizandolo cuando un usuario lo añada o elimine de sus favoritos.
- He eliminado el campo nombre en el modelo de usuario, ya que no es necesario. He eliminado también los métodos a autenticación que no se usan en la API.
- Validar TODAS las peticiones usando el validador de Laravel por defecto.

## Posibles mejoras
- Añadir tests. En este proyecto no he añadido tests, pero sería una mejora importante para asegurar que todo funciona correctamente y que se pudiese ampliar el proyecto sin miedo a romper la funcionalidad.
- Mantener un id propio para cada personaje. En este proyecto he usado el mismo id que proporciona la API, pero podría haber usado uno que asigne yo (aunque coincida). De esta forma, si la API externa cambia el id de un personaje, o si se cambia de API externa, no afectaría a nuestra BD.
- Hacer una interfaz web para la API. En este proyecto he hecho solo la API, pero se podría hacer una interfaz web para que los usuarios puedan interactuar con la API de forma más visual.

## Endpoints

### POST /api/register

`email` with valid email format

`password` with at least 6 characters

En caso de éxito devuelve un token de autenticación. Si no, devuelve un mensaje de error.

```
curl -X POST http://localhost:8000/api/register \
-H "Content-Type: application/json" \
-d '{"email": "jerry_smith@msn.com",
     "password": "123456"}'
```

### POST /api/login

`email` with valid email format

`password` with at least 6 characters

En caso de éxito devuelve un token de autenticación. Si no, devuelve un mensaje de error.
```
YOUR_ACCESS_TOKEN=$(curl -X POST http://localhost:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email": "jerry_smith@msn.com",
     "password": "123456"}')
```

### GET /api/characters

`page` (opcional, por defecto muestra la primera página)

`per_page` (opcional, por defecto muestra 20 personajes por página)

`name` (opcional, busca personajes cuyo nombre contenga la cadena de texto)

`status` (opcional, busca personajes por status (Alive,Dead,unknown))

`species` (opcional, busca personajes por especie)

`gender` (opcional, busca personajes por género (Female,Male,Genderless,unknown))

Devuelve una lista paginada y filtrada de personajes.

```
curl -X GET http://localhost:8000/api/characters
curl -X GET http://localhost:8000/api/characters?name=rick&status=Dead&species=Human&gender=Male&page=4&per_page=10
```
### GET /api/characters/{id}
Devuelve la información de un personaje.

```
curl -X GET http://localhost:8000/api/characters/15
```
### GET /api/favorites
Devuelve la lista de personajes favoritos de usuario autenticado.
```
curl -X GET http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" 
```
### POST /api/favorites

`id` (obligatorio, id del personaje que se quiere añadir a favoritos)

Añade un personaje a la lista de favoritos del usuario autenticado.

```
curl -X POST http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{"id": 15}'
```
### DELETE /api/favorites

`id` (obligatorio, id del personaje que se quiere eliminar de favoritos)

Elimina un personaje de la lista de favoritos del usuario autenticado.

```
curl -X DELETE http://localhost:8000/api/favorites \
-H "Authorization: Bearer $YOUR_ACCESS_TOKEN" \
-H "Content-Type: application/json" \
-d '{"id": 15}'
```
