# Hyplast Inventario - Sistema de Consultas de Inventario

## Descripción
Sistema para consultar aplicaciones de inventario desde Softland, movimientos de artículos, existencias por bodega y trazabilidad.

## Características Principales
- 📦 Consulta de existencias
- 🔄 Movimientos de inventario
- 🏢 Bodegas múltiples
- 📊 Transacciones de inventario
- 📝 Auditoría de movimientos
- 🔍 Búsqueda de artículos
- 📈 Reportes de stock

## Modelos Principales
- **Articulo**: Artículos del inventario
- **TransaccionInv**: Transacciones de inventario
- **AuditTransInv**: Auditoría de transacciones
- **Bodega**: Bodegas
- **UsuarioBodega**: Acceso por bodega

## API Endpoints
```
GET    /api/inventory/articles           # Listar artículos
GET    /api/inventory/articles/{code}    # Ver artículo
GET    /api/inventory/transactions       # Transacciones
GET    /api/inventory/stock              # Consultar stock
GET    /api/inventory/warehouses         # Listar bodegas
```

## Tipos de Documentos
- Entradas de inventario
- Salidas de inventario
- Transferencias
- Ajustes
- Devoluciones

## Requisitos
- PHP >= 8.1
- Laravel >= 10.x
- Conexión a Softland

## Instalación
```bash
composer install
php artisan migrate
```

## Autor y Propietario
**Néstor Serrano**  
Desarrollador Full Stack  
GitHub: [@nestorserrano](https://github.com/nestorserrano)

## Licencia
Propietario - © 2026 Néstor Serrano. Todos los derechos reservados.
