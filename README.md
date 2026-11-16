# Task Management API REST— Laravel 13

API REST desarrollada en **Laravel 13** para la gestión de tareas (`tasks`)

---

## 🚀 Cómo ejecutar el proyecto

### 1. Requisitos previos
* PHP >= 8.3
* Composer
* Gestor de base de datos - MySQL

### 2. Instalación y configuración
1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/AdolfoAnias/qa-bit-test.git
   cd qa-bit-test
   ```
2. **Instalar dependencias de PHP:**
   ```bash
   composer install
   ```
3. **Configurar variables de entorno:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *(Edita el archivo `.env` con las credenciales de tu base de datos de Mysql).*

   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=qabit_test
   DB_USERNAME=
   DB_PASSWORD=
   ```

4. **Ejecutar migraciones:**   
   ```bash
   php artisan migrate
   ```

5. **Levantar el servidor local:**
   ```bash
   php artisan serve
   ```
   La API estará disponible en `http://127.0.0.1:8000`.

### 3. Ejecutar las pruebas automatizadas (PHPUnit)
Para verificar que los 5 tests de integración (*Feature Tests*) pasan correctamente:
```bash
php artisan test
```

### 4. Endpoints principales
*(Importante: incluir el header `Accept: application/json` en clientes como Postman).*

| Método | Endpoint | Descripción | Código HTTP esperado |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/tasks` | Listar todas las tareas | `200 OK` |
| `POST` | `/api/tasks` | Crear una nueva tarea | `201 Created` / `422 Unprocessable Content` |
| `GET` | `/api/tasks/{id}` | Obtener una tarea específica | `200 OK` / `404 Not Found` |
| `PUT/PATCH` | `/api/tasks/{id}` | Actualizar una tarea existente | `200 OK` / `422 Unprocessable Content` |
| `DELETE` | `/api/tasks/{id}` | Eliminar una tarea | `200 OK` |

---


## 🧠 Decisiones técnicas tomadas

* **Arquitectura en capas (`Controller` -> `Service` -> `Model`):** Se delegó la lógica de negocio y persistencia a `TaskService`, manteniendo `TaskController` limpio y enfocado únicamente en recibir peticiones HTTP y devolver respuestas JSON.
* **Uso de API Resources (`TaskResource`):** Se estandarizó la estructura del JSON de salida.
* **Validación estricta y códigos HTTP semánticos:** Se implementaron reglas de validación para los campos `title`, `description`, `status`, `assignee` y `due_date`, asegurando respuestas `422` ante datos inválidos, `201` al crear registros y `200` al eliminar (se podria utilizar `204` tambien).
* **Implementación de pruebas con PHPUnit:** Se implementaron 5 *Feature Tests* utilizando `RefreshDatabase` y `TaskFactory` para validar de forma aislada el listado, creación, error de validación, actualización y borrado de tareas.

---

## 📚 Documentación de la API (Swagger)

Este proyecto utiliza **L5-Swagger** (OpenAPI) para documentar y probar los endpoints de forma interactiva.

### Cómo generar y visualizar la documentación

1. **Generar los archivos de Swagger:**
   Cada vez que se modifique o agregue nuevos endpoints, ejecutar el siguiente comando en la raíz del proyecto:
   ```bash
   php artisan l5-swagger:generate
   ```

2. **Iniciar el servidor local:**
   ```bash
   php artisan serve
   ```

3. **Acceder desde el navegador:**
   Abre la siguiente URL para explorar y probar la API:
   * **Interfaz Swagger UI:** [http://127.0.0.1:8000/api/documentation]
   
---

## 🔐 Autenticación y Protección de Rutas (Laravel Sanctum)

Los endpoints de tareas están protegidos mediante el middleware `auth:sanctum`, por lo que es obligatorio enviar un **Bearer Token** válido en cada petición.

### 1. Obtener un Token de acceso
Envía una petición `POST` al endpoint de inicio de sesión o registro con las credenciales del usuario:

* **Endpoint:** `POST /api/login` *(o `/api/register`)*
* **Headers:**
  ```text
  Accept: application/json
  Content-Type: application/json
  ```
* **Body (JSON):**
  ```json
  {
    "email": "usuario@ejemplo.com",
    "password": "tu_password"
  }
  ```
* **Respuesta esperada (`200 OK`):**
  ```json
  {
    "token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890"
  }
  ```

### 2. Usar el Token en Postman (o cliente HTTP)
Para consumir cualquier ruta protegida (`/api/tasks`), incluye el token obtenido en la cabecera `Authorization`:

```text
Accept: application/json
Authorization: Bearer 1|aBcDeFgHiJkLmNoPqRsTuVwXyZ1234567890
```

* Si el token no se envía, es inválido o ha expirado, la API responderá automáticamente con el código **`401 Unauthenticated`**.
