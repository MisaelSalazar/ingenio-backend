# 🏭 Ingenio Backend

Este es el backend del sistema desarrollado para el **Ingenio Plan de Ayala**, como parte de un proyecto académico y profesional enfocado en la gestión interna de información. El sistema fue construido con una arquitectura modular en PHP, sin frameworks externos, con endpoints RESTful organizados por entidad (`users` y `flayers`) y configuraciones centralizadas.

---

## 📌 Características principales

- CRUD completo para usuarios (`create`, `delete`, `update`, `get_all`)
- Autenticación básica con login y validación de roles
- CRUD de flayers (anuncios o materiales informativos)
- Modularización por carpetas (`users`, `flayers`, `config`)
- Middleware de autenticación (`auth.php`)
- Control de CORS (`cors.php`)
- Conexión a base de datos con PDO (`database.php`)
- Script adicional para hashear contraseñas

---

## 🧰 Tecnologías utilizadas

- PHP 8+
- MySQL / MariaDB
- JSON como formato de intercambio
- JWT o sesiones (dependiendo si lo implementas más adelante)
- HTML (en el cliente, si lo conectas)

---
