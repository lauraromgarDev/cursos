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

# Probando con Postman

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


