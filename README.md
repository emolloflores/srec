# Sistema de Reservas para Espacios Comunitarios SREC

Este proyecto es una aplicación web para la gestión y reserva de espacios comunitarios (salones, auditorios, canchas). Fue desarrollado como el trabajo final para el curso de Arquitectura de Software, aplicando conceptos avanzados como la **Arquitectura Hexagonal (Puertos y Adaptadores)**.

## ✨ Características Principales

-   **Usuarios:** Pueden registrarse, ver la disponibilidad de espacios y reservar por día y hora.
-   **Administradores:** Tienen la capacidad de ver todas las reservas, filtrarlas por fecha y tipo de espacio.
-   **Notificaciones:** Confirmación de reservas a través de notificaciones (actualmente implementado con logs).
-   **API RESTful:** Todos los endpoints siguen las convenciones REST para una fácil integración.
-   **Autenticación:** Sistema de login/logout seguro basado en tokens mediante Laravel Sanctum.

## 🏛️ Arquitectura del Software

El proyecto sigue estrictamente los principios de la **Arquitectura Hexagonal** para lograr un alto desacoplamiento entre la lógica de negocio y la infraestructura.

### Estructura de Módulos

La aplicación está organizada en **módulos de negocio** autocontenidos, ubicados en `app/Modules`. Cada módulo representa un subdominio de la aplicación:

-   **/Auth**: Maneja la autenticación (login, logout).
-   **/Users**: Gestiona el CRUD y la lógica de los usuarios.
-   **/Spaces**: Se encarga del CRUD de los espacios disponibles para reservar.
-   **/Reservations**: Contiene la lógica central para crear y gestionar reservas.
-   **/Notifications**: Módulo de soporte para el envío de notificaciones.
-   **/Shared**: Contiene elementos compartidos, como excepciones de dominio personalizadas.

### Capas de la Arquitectura Hexagonal

Dentro de cada módulo, la estructura refleja las capas de la arquitectura:

1.  **`Domain` (El Núcleo Interno)**
    -   Contiene las **Entidades de Dominio** (ej. `User.php`, `Space.php`). Son clases PHP puras (POPOs) que representan los conceptos del negocio y no tienen dependencias externas.
    -   También incluye **Excepciones de Dominio** (ej. `SpaceNotAvailableException.php`) que representan fallos en las reglas de negocio.

2.  **`Application` (La Capa de Aplicación)**
    -   **`Ports`**: Define las interfaces (contratos) que el núcleo necesita para comunicarse con el exterior (ej. `UserRepositoryPort.php`). El núcleo depende de estas abstracciones, no de las implementaciones.
    -   **`UseCases`**: Orquestan la lógica de negocio. Cada caso de uso (ej. `CreateSpaceUseCase.php`) representa una acción que el sistema puede realizar. Dependen únicamente de los Puertos.

3.  **`Infrastructure` (El Exterior - Adaptadores)**
    -   **`Adapters`**: Contiene las implementaciones concretas de los Puertos. Por ejemplo, `EloquentUserRepository.php` implementa `UserRepositoryPort` usando el ORM de Laravel. Aquí es donde reside la dependencia tecnológica.
    -   **`Http/Controllers`**: Son los adaptadores de entrada (driving adapters). Reciben las peticiones HTTP, validan los datos y delegan la ejecución a los Casos de Uso.
    -   **`Persistence/Eloquent`**: Contiene los modelos de Eloquent, que son un detalle de implementación para interactuar con la base de datos.
    -   **`routes.php`**: Define las rutas API específicas para cada módulo.

Este diseño garantiza que el **Núcleo (Domain + Application) es intercambiable y testeable** de forma aislada, ya que no depende de Laravel, la base de datos ni ningún otro servicio externo.

## 🚀 Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto en tu entorno local.

### Prerrequisitos

-   PHP >= 8.2
-   Composer
-   Una base de datos (MySQL, PostgreSQL, etc.)

### Pasos

1.  **Clona el repositorio:**
    ```bash
    git clone https://github.com/emolloflores/srec.git
    cd srec
    ```

2.  **Instala las dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Crea el archivo de entorno:**
    Copia el archivo de ejemplo `.env.example` a `.env`.
    ```bash
    cp .env.example .env
    ```

4.  **Configura tus variables de entorno en el archivo `.env`:**
    Asegúrate de configurar correctamente los datos de conexión a tu base de datos.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=reservas_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Genera la clave de la aplicación:**
    ```bash
    php artisan key:generate
    ```

6.  **Ejecuta las migraciones y los seeders:**
    Esto creará la estructura de la base de datos y la poblará con datos de prueba (un usuario y varios espacios).
    ```bash
    php artisan migrate:fresh --seed
    ```

7.  **Inicia el servidor de desarrollo:**
    ```bash
    php artisan serve
    ```

¡La aplicación estará disponible en `http://127.0.0.1:8000`!

## 🧪 Pruebas

El proyecto cuenta con una suite de pruebas unitarias y de integración para garantizar la calidad del código.

-   **Pruebas Unitarias:** Se centran en los Casos de Uso, "mockeando" los puertos para probar la lógica de negocio en aislamiento.
-   **Pruebas de Integración (Feature Tests):** Prueban los endpoints de la API de principio a fin, interactuando con una base de datos de prueba en memoria (SQLite).

Para ejecutar todas las pruebas:
```bash
php artisan test
```

## 🕹️ Endpoints de la API

A continuación se listan los principales endpoints disponibles. Para las rutas que requieren autenticación, se debe enviar un token `Bearer` en la cabecera `Authorization`.

| Método | Endpoint                | Descripción                                 | Requiere Auth |
| :----- | :---------------------- | :------------------------------------------ | :------------ |
| `POST` | `/api/login`            | Inicia sesión y obtiene un token de acceso. | No            |
| `POST` | `/api/logout`           | Cierra la sesión y revoca el token.         | Sí            |
| `GET`  | `/api/users`            | Lista todos los usuarios.                   | Sí            |
| `POST` | `/api/users`            | Crea un nuevo usuario.                      | Sí            |
| `GET`  | `/api/users/{id}`       | Obtiene un usuario específico.              | Sí            |
| `PUT`  | `/api/users/{id}`       | Actualiza un usuario.                       | Sí            |
| `GET`  | `/api/spaces`           | Lista todos los espacios.                   | No            |
| `POST` | `/api/spaces`           | Crea un nuevo espacio.                      | Sí            |
| `GET`  | `/api/spaces/{id}`      | Obtiene un espacio específico.              | Sí            |
| `PUT`  | `/api/spaces/{id}`      | Actualiza un espacio.                       | Sí            |
| `POST` | `/api/reservations`     | Crea una nueva reserva.                     | Sí            |

<p align="center">
  <a href="videos/gilmerHuanca.mp4">
    <img src="https://img.icons8.com/color/96/youtube-play.png" alt="Video Gilmer Huanca">
    <br>
    Explicacion Gilmer Huanca
  </a>
</p>
<p align="center">
  <a href="videos/edgarMollo.mp4">
    <img src="https://img.icons8.com/color/96/youtube-play.png" alt="Video Edgar Mollo">
    <br>
    Explicacion Edgar Mollo Flores
  </a>
</p>

## Explicacion Gilmer Huanca
🎥 [Ver Explicacion en video](videos/gilmerHuanca.mp4)

## Explicacion Edgar Mollo
🎥 [Ver Explicacion en video](videos/edgarMollo.mp4)



---