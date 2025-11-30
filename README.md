# 🚀 GestorRemotoBD  
**Proyecto de Fin de Ciclo – ASIR (Administración de Sistemas Informáticos en Red)**  
**Autor:** José Manuel Martín Jaén · 2025  

Aplicación web desarrollada en **PHP (MVC)** que permite administrar de forma remota bases de datos **MySQL/MariaDB**, ofreciendo una alternativa ligera a phpMyAdmin con un panel limpio, y adaptado para entornos educativos o cloud (AWS, VPS, contenedores, etc.).

---

# 📚 Características Principales

## ✔️ Gestión completa de bases de datos
- Listado dinámico de bases existentes.
- Creación y eliminación de bases de datos.
- Detección automática de bases críticas del sistema.
- Visualización de tablas, tamaños y estadísticas.

---

## ✔️ Módulo de Tablas
- Listado completo de tablas por base.
- Vista de registros en formato tabla.
- Creación de tablas con definición de columnas.
- Eliminación confirmada de tablas.
- Edición de estructura:
  - Añadir columnas  
  - Eliminar columnas  
  - Modificar tipos *(en desarrollo)*  
- Edición de registros *(31/10/2025 — en pruebas)*.

---

## ✔️ Módulo SQL (Consultas manuales)
- Ejecución de consultas SQL personalizadas.
- Resultados formateados.
- Gestión de errores SQL vía PDO.
- Integración futura con consultas guardadas.

---

## ✔️ Sistema de Logs (gestor_logs)
Detecta si en el sistema hay una base de datos de logs, si no existe, hace al usuario crear una.
Registra automáticamente:

- Usuario autenticado  
- Acción realizada  
- Fecha y hora  
- Base y tabla afectada    

Incluye panel para visualizar logs.  

---

## ✔️ Sistema de Copias de Seguridad (Backups)
*(Implementación activa — finalizando detalles)*

### Tipos de copia:
- **Full (completa)**  
- **Incremental** (En desarrollo) (Dudo de si es rentable, he de jugar con muchas variables, ademas de tener en cuenta cuales han sido los ultimso cambios para guardar la informacion nueva.
- **Diferencial**  (En desarrollo) (Mas de lo mismo, un juego interminable tengo unos controllers y unos models, que parecen que van a hacer explotar a mi ordenador)

### Funciones actuales:
- Generación de backups `.sql` guardados en:
  - `/backups/full/`
  - `/backups/incremental/`
  - `/backups/diferencial/`
- Descarga directa mediante controlador.
- Eliminación de copias.
- Registro de fecha del último backup.(No tenía ni idea de como implementar las copias, he tenido que buscar información en otros lugares.)
- **Aviso automático en Dashboard si han pasado más de 24h sin copia.**

---

## ✔️ Dashboard avanzado
Incluye:

- Tarjetas de resumen:
  - Numero de copias, tipo, etc..
  - Tamaño de copias
  - Última copia realizada
  - Número de bases
  - Tablas totales
  - Registros estimados
  - Tamaño total
  - Tabla detallada con tamaño y registros.
  - Botón de acceso rápido al módulo de bases.
  - Estadísticas y tamaño total de backups.

---

# 🛠️ Arquitectura del Proyecto (MVC)
- Rutas basadas en:
  - index.php?controller=NombreController&action=metodo

---

# 🔄 En desarrollo actualmente
- Finalización del módulo completo de restauración.
- Ajustes finales del módulo de Vistas SQL guardadas.(Sigo atascado)
- Optimización de detección de BDs internas. (No va mal, pero... creo que podría mejorarlo, no se como, pero tengo que pensarlo en profundidad)
- Finalizar integración de edición avanzada de estructuras. (Considero que los formularios que tengo hechos... quizá no sean todo lo completos y funcionales que podrían ser, no se)
- Actualización pendiente del Dashboard (bases internas). (Filtrar bases internas de mariadb)

---

# 🧠 Roadmap (Mejoras previstas)

## 1️⃣ Sistema de Vistas / Consultas Guardadas
Guardar consultas frecuentes en una tabla propia.

## 2️⃣ Detección de Relaciones entre Tablas (Esto es una locura y creo que no lo voy a implementar)
Mini-diagrama ER integrado.

## 3️⃣ Editor avanzado de estructura (Un segundo formulario, pero, creo que perderían muchas de las tablas toda la logica para la que se usan, podría traer mas problemas que soluciones)
- Renombrado de columnas  
- Cambios de tipo  
- Añadir índices  
- Duplicar tablas  

## 4️⃣ Backups avanzados (
- Compresión ZIP  ( No es mala idea, si fuese completamente funcional, ahorrar espacio cuando haya bases de datos enormes sería todo un logro)
- Programación automática  (Tengo pensado hacer siempre que se inicie el sistema gestor, que realice una copia de los logs)


## 5️⃣ Tema oscuro / claro (opcional)

---

# 🚫 Funcionalidades descartadas
- Sistema de roles → Solo administrador.
- Multiusuario avanzado → Fuera del alcance del proyecto. (Considero que este programa puede instalarse en cualquier backend y acceder a él, necesita de credenciales para hacer la conexion a la base de datos, además de permisos privilegiados, con lo que multiusuarios sería un sin sentido)

---

# 📝 Notas finales
Este README funciona como **documento vivo**, actualizado conforme evoluciona el proyecto y sus módulos internos.


