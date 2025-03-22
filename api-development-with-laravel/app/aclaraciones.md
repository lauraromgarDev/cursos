# API REST con Laravel

## Rutas de API

En Laravel, las rutas para la API suelen definirse en el archivo `routes/api.php`. A continuación se describen las rutas que utilizamos para el recurso `books`:

```php
// En routes/api.php

// Ruta para gestionar todos los métodos de un recurso, excepto 'create' y 'edit' (que son para formularios en frontend)
Route::resource('books', BookController::class)->except(['create', 'edit']);

// Rutas explícitas (opcional si ya usas resource)
Route::get('books', [BookController::class, 'index']);       // Obtener todos los libros
Route::post('books', [BookController::class, 'store']);      // Crear un nuevo libro
Route::get('books/{id}', [BookController::class, 'show']);   // Obtener un libro específico
Route::put('books/{id}', [BookController::class, 'update']); // Actualizar un libro existente
Route::delete('books/{id}', [BookController::class, 'destroy']); // Eliminar un libro
```
## Probando con Postman

## 1. Obtener todos los libros
- **Método**: GET
- **URL**: `http://mybookstore.local/api/books`
- **Descripción**: Esta solicitud obtiene todos los libros registrados en la base de datos.

## 2. Crear un nuevo libro
- **Método**: POST
- **URL**: `http://mybookstore.local/api/books`
- **Body (raw, JSON)**:
    ```json
    {
        "id": 4,
        "title": "Cicatriz",
        "author": "Juan Gómez-Jurado",
        "isbn": "9788466657990",
        "price": "15.10"
    }
    ```
- **Descripción**: Esta solicitud crea un nuevo libro con los datos proporcionados. Se espera que el JSON enviado contenga todos los campos del modelo `Book`.

## 3. Obtener un libro por ID
- **Método**: GET
- **URL**: `http://mybookstore.local/api/books/{id}`
- **Descripción**: Obtiene los detalles de un libro específico usando su ID. Cambia `{id}` por el ID real del libro.

## 4. Actualizar un libro
- **Método**: PUT o PATCH
- **URL**: `http://mybookstore.local/api/books/{id}`
- **Body (raw, JSON)**:
    ```json
    {
        "id": 4,
        "title": "Cicatriz",
        "author": "Juan Gómez-Jurado",
        "isbn": "9788466657990",
        "price": "13.10"
    }
    ```
- **Descripción**: Esta solicitud actualiza los datos de un libro existente. El ID del libro debe estar en la URL, y el cuerpo del mensaje debe incluir los nuevos datos del libro.

## 5. Eliminar un libro
- **Método**: DELETE
- **URL**: `http://mybookstore.local/api/books/{id}`
- **Descripción**: Elimina un libro específico usando su ID.


## 6. Instalación de Sanctum

Sanctum es un paquete de autenticación de API para Laravel. Se utiliza para manejar la autenticación de los usuarios en aplicaciones SPA (Single Page Application) y en APIs móviles, de una manera sencilla y ligera. Se enfoca en proporcionar una forma fácil y segura de autenticar usuarios utilizando tokens de acceso.


## Pasos para instalar Sanctum

### 1. **Instalar Sanctum usando Composer**

   ``` bash
        composer require laravel/sanctum
    ```

## 2. **Publicar los archivos de configuración**:

   Si deseas personalizar la configuración de Sanctum, puedes publicar el archivo de configuración ejecutando el siguiente comando:

 ```bash
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    ```

## 3. Agregamos una columna "role" a la tabla users
    ```bash
    php artisan make: migration add:role_column_to_users_table
    ```

## 4. **Ejecutar las migraciones**:

Sanctum requiere una tabla para almacenar los tokens de autenticación. Para crear la tabla correspondiente, ejecuta las migraciones de Laravel:

```bash
    php artisan migrate
```

## Usar la autenticación

Para habilitar las rutas de autenticación en tu API, puedes agruparlas bajo el prefijo `auth` en el archivo `routes/api.php`. Esto incluirá las rutas para el registro, inicio de sesión y cierre de sesión.

Añadimos las siguientes rutas:

```php
Route::prefix('auth')->group(function () {
    // Ruta para el registro de un nuevo usuario
    Route::post('register', [AuthController::class, 'register']);
    
    // Ruta para el inicio de sesión de un usuario
    Route::post('login', [AuthController::class, 'login']);
    
    // Ruta para cerrar sesión, protegida por el middleware de autenticación
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

## 1. Register

En la API pongo esta ruta en POST:
`http://mybookstore.local/api/auth/register`

Y en el JSON los datos para un usuario normal:

```json
{
    "name": "Laura",
    "email": "laura@email.com",
    "password": "password123",
    "role": "user"
}
```

Y la respuesta que manda es la siguiente:

```json
{
    "token": "11|GNUSxV8GIw8k0JQ9EFjMjjvJIBGx0O7LZJFoD1m851a89301",
    "message": "User created successfully",
    "user": {
        "name": "Laura",
        "email": "laura@email.com",
        "role": "user",
        "updated_at": "2025-03-22T17:53:13.000000Z",
        "created_at": "2025-03-22T17:53:13.000000Z",
        "id": 3
    }
}
```

## 2. Login

En la API pongo esta ruta en POST:
`http://mybookstore.local/api/auth/login`

Y en el JSON los datos para un usuario normal:

```json
{
    "email": "laura@email.com",
    "password": "password123"
}
```

Y la respuesta que manda es la siguiente:

```json
{
    "token": "12|t7mOGiKZFQOrgfV7HWkU680bvSw8NvVvdvVnbOfFa514ccc7",
    "message": "User logged in successfully",
    "user": {
        "id": 3,
        "name": "Laura",
        "email": "laura@email.com",
        "email_verified_at": null,
        "created_at": "2025-03-22T17:53:13.000000Z",
        "updated_at": "2025-03-22T17:53:13.000000Z",
        "role": "user"
    }
}
```
Ahora, ese token generado en la respuesta es el que vamos a copiar y pegar en postman en la parte de authoritation
## 3. Rutas protegidas

En esta parte necesitamos **autorización** para acceder a las rutas protegidas, lo que significa que debemos haber generado un **token de autenticación**. Esto se logra utilizando **Sanctum**, el cual nos permitirá autenticar al usuario y verificar su rol.

Además, vamos a crear un middleware llamado **CheckRole**, el cual revisará si el usuario tiene el rol adecuado para acceder a las rutas restringidas (en este caso, el rol de `admin`).

### Paso 1: Crear el middleware CheckRole

Primero, creamos el middleware `CheckRole`, que se encargará de verificar el rol del usuario autenticado. Este middleware se añadirá al kernel para que esté disponible en las rutas que lo necesiten.

### Paso 2: Configuración de rutas

Una vez que tenemos el middleware listo, ahora configuramos las rutas en api.php para que solo los usuarios autenticados puedan acceder a ellas. En este caso, usaremos el middleware auth:sanctum para proteger las rutas y el middleware CheckRole para verificar si el usuario tiene el rol de admin.

``` bash
// Rutas protegidas con autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas solo accesibles para administradores
    Route::middleware('CheckRole:admin')->group(function () {
        // Rutas para libros, solo accesibles por administradores (por ejemplo, POST, PUT, DELETE)
        Route::resource('books', BookController::class)->except(['create', 'edit']);
    });
});
```

### Paso 3: Pruebas

- **Prueba 1**: Si accedemos a la URL `http://mybookstore.local/api/user` estando logeados, no se generará ningún error y se nos devolverá la información del usuario autenticado.

- **Prueba 2**:Prueba 2: Si accedemos a la URL `http://mybookstore.local/api/books` con un usuario normal (que no tiene el rol de admin), veremos una lista de libros, ya que esta ruta está configurada para ser accesible por todos los usuarios (independientemente de su rol).

- **Prueba 3**: Si intentamos acceder a las rutas que permiten modificar libros (como agregar, editar o eliminar) con un usuario que no tenga el rol de admin, obtendremos un mensaje de acceso denegado `(403 Forbidden)`. Estas rutas están protegidas por el middleware CheckRole:admin, lo que garantiza que solo los usuarios con el rol de admin puedan acceder a ellas.

Esto asegura que solo los administradores puedan realizar operaciones como crear, editar o eliminar libros.
