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

## ⏳ Qué haría con más tiempo

Dado que la API ya cuenta con autenticación por tokens (**Sanctum**), documentación interactiva (**Swagger**) y auditoría automática de cambios (**Laravel Auditing**), los siguientes pasos para llevar el sistema a producción a gran escala serían:

* **Roles y permisos granulares (RBAC):** Implementar un sistema de roles (por ejemplo, `Admin` vs `Member` usando *Policies* o *Spatie Permission*) para definir quién puede ver el historial completo de auditoría (`/audits`), reasignar tareas a otros usuarios o eliminar registros de terceros.
* **Borrado lógico (Soft Deletes) para preservar auditorías:** Añadir `SoftDeletes` al modelo `Task` para que, al eliminar una tarea (`DELETE`), no se pierda su referencia en la base de datos y los administradores puedan consultar su historial de auditoría o restaurarla en caso de error (Esto solo si fuera pedido en los requerimientos del proyecto).
* **Paginación, filtros avanzados y búsqueda:** Incorporar paginación (`paginate()`)  en `GET /api/tasks/{id}/audits`, junto con filtros para mantener tiempos de respuesta bajos cuando crezca el volumen de datos.
* **Notificaciones y colas asíncronas (Queues):** Disparar eventos y *Jobs* en segundo plano para enviar alertas por correo o webhook cuando a un usuario se le asigne una tarea o esté próxima a vencer (`due_date`).
* **Rate Limiting y ciclo de vida de tokens:** Configurar límites de peticiones por minuto (*throttling*) para proteger el endpoint de login contra fuerza bruta y definir expiración y refresco automático de tokens en Sanctum.
* **Pipeline de CI/CD con GitHub Actions:** Automatizar en cada *push* o *pull request* la ejecución de la suite de pruebas en PHPUnit, el formateo de código con **Laravel Pint** y el análisis estático con **PHPStan / Larastan**.

---

## 🤖 Uso de Inteligencia Artificial en el desarrollo

Durante el desarrollo del proyecto utilice un asistente de IA como herramienta de consulta técnica, depuración y aceleración de pruebas, manteniendo criterio propio para validar e integrar cada solución en la arquitectura del proyecto:

### 1. Qué le pedí
* **Auditoría de cambios:** Guía de instalación y configuración del paquete `owen-it/laravel-auditing` compatible con el guard de `sanctum`.
* **Pruebas automatizadas (PHPUnit):** Generación de la estructura base de `TaskFactory` y la suite de *Feature Tests* cubriendo CRUD, validaciones y autenticación por tokens con Sanctum.
* **Documentación:** Estructuración del `README.md` y documentación de los endpoints protegidos.

### 2. Qué acepté
* La integración de `owen-it/laravel-auditing` vinculada al guard `sanctum` en `config/audit.php`.
* El uso de `RefreshDatabase` y `Sanctum::actingAs()` en los tests de integración para simular peticiones autenticadas de forma aislada.

### 3. Qué corregí y adapté
* **Framework de testing:** La IA sugirió inicialmente los tests en sintaxis *Pest* (estándar por defecto en Laravel 13); adapté la suite completa a clases tradicionales de **PHPUnit** (`TestCase`).
* **Reglas de dominio y estados válidos:** Ajusté los valores generados en `TaskFactory` y en los tests para que coincidieran con las 5 columnas exactas de mi migración (`title`, `description`, `status`, `assignee`, `due_date`) y con los estados permitidos por mis reglas de validación (`status`).
* **Implementación del contrato `Auditable`:** Corregí el error de tipo en `Task.php` asegurando tanto la importación de la interfaz `OwenIt\Auditing\Contracts\Auditable` (`implements Auditable`) como el uso del trait `\OwenIt\Auditing\Auditable`.
* **Autorización y propiedad de recursos en los tests:** Al integrar Sanctum y las reglas de autorización (`403 Forbidden`), adapté los tests de actualización y eliminación para asignar la tarea creada al mismo usuario autenticado (`'assignee' => $user->id`), garantizando el cumplimiento de las políticas de acceso.
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

---

## 🔐 Implementación de historial de cambios (Laravel Audit)

Las operaciones de Crear, actualizar y eliminar tareas estan configuradas con un historial de cambios implementado con Laravel Auditing 