# Aplicación de Búsqueda de Recursos de Idiomas

Aplicación de consola en PHP para buscar clases y exámenes de idiomas.

## Requisitos

- PHP 8.0 o superior
- MySQL 5.7+ / MariaDB 10.2+ / SQLite 3.28+
- Composer 2.0+
- Extensión PDO y mbstring

## Instalación

```bash
git clone  https://github.com/ablancogiraldo3012/idiomas-app.git
cd idiomas-app
composer install
cp .env.example .env
```

## Configuración

1. Configurar las variables de entorno en `.env`:
   ```env
   DB_HOST=localhost
   DB_NAME=idiomas_db
   DB_USER=usuario
   DB_PASS=contraseña
   ```

2. Ejecutar migraciones:
   ```bash
   mysql -u usuario -p idiomas_db < database/migrations.sql
   ```

## Uso

```bash
# Buscar recursos
php public/main.php search <término>

# Mostrar ayuda
php public/main.php --help

# Ejecutar tests
composer test
```

## Estructura del Proyecto

```
idiomas-app/
├── config/          # Configuración
├── database/        # Migraciones y esquemas
├── public/          # Punto de entrada
├── src/             # Código fuente
├── tests/           # Pruebas
└── vendor/          # Dependencias
```

## Licencia

MIT License