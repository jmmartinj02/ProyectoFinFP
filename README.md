# GestorRemotoBD
**Proyecto Fin de Ciclo – Administración de Sistemas Informáticos en Red (ASIR)**  
**Autor:** José Manuel Martín Jaén  
**Fecha:** Octubre 2025  

Aplicación web desarrollada en **PHP (Modelo–Vista–Controlador)** para la administración remota de **bases de datos MySQL/MariaDB** alojadas en servidores o instancias en la nube (por ejemplo, AWS).  

El sistema está diseñado para ser accesible desde un entorno web, permitiendo la gestión completa de las bases de datos sin necesidad de herramientas externas como phpMyAdmin o conexiones SSH.

---

##  Funcionalidades Implementadas

### 🔧 Estructura base del proyecto
- Entorno PHP en arquitectura **Modelo–Vista–Controlador (MVC)**.  
- Directorios organizados: `controllers/`, `Models/`, `Vistas/`, `includes/`, `db/`.  
- Sistema de rutas basado en:  
  `index.php?controller=NombreController&action=metodo`  
- Header y footer globales reutilizables.  
- Integración con **Bootstrap 5**, sin uso de JavaScript externo (salvo recursos visuales menores).  

---

### 🗄️ Módulo principal – Gestión de bases de datos
- Listado de bases de datos disponibles.  
- Acceso a tablas de cada base.  
- Creación de nuevas bases de datos.  
- Eliminación de bases de datos con confirmación previa.  
- Interfaz limpia y coherente, con uso de `mensajeView` para confirmaciones de borrado.

---

### Módulo de gestión de tablas
- Listado de tablas de cada base de datos.  
- Visualización del contenido de cada tabla (registros).  
- Creación de tablas con nombre y definición de columnas.  
- Eliminación de tablas con confirmación visual.
- Verificar la correcta ejecución de los métodos `obtenerColumnas()` y `actualizarEstructuraTabla()`.(FUNCIONA)
- Edición de estructura:  
  - Eliminar columnas seleccionadas.  
  - Añadir nuevas columnas.  
  - Guardar cambios mediante `ALTER TABLE`.
  - Añadido nueva funcionalidad(31/10/2025):
    -Botón de edición de registros, con formulario para modificación(TESTEANDO)
---

### Funcionalidades adicionales implementadas
- Ejecución de consultas SQL manuales desde la interfaz web.  
- Monitorización básica de rendimiento y tamaño de tablas.  
- Sistema de autenticación simple (login de acceso) utilizando las credenciales del sistema.  
- Caducidad de sesión por inactividad.  

---

##  En curso
- Integrar el botón **“Editar estructura”** en la vista `tablasView.php`.   
- Añadir, creación y almacenamiento de Vistas.
- Funcionalidad de almacenamiento de LOGS, creacion de la base de datos LOGS y su tabla, automatizado, falta, modificar TODOS los controllers/models, para almacenar información en dicha BBDD/tabla.

## Mejoras Pendientes o Posibles Optimizaciones
 1. Sistema de Logs de Actividad (alta prioridad)
  - Objetivo: registrar todas las operaciones realizadas dentro del sistema para llevar trazabilidad y control. Detalles:
  - Registrar usuario, acción (INSERT, UPDATE, DELETE, SELECT, etc.), fecha/hora, base de datos y tabla afectadas.
  - Guardar los logs en una base de datos interna (por ejemplo gestor_logs).
  - Crear vista dedicada para visualizarlos desde el panel de administración.
  - Posibilidad futura de filtrarlos por usuario o tipo de acción.

💾 2. Copias de Seguridad y Restauración
  Objetivo: permitir crear y restaurar backups de las bases de datos gestionadas.
    Detalles:
  - Botón para generar copia de seguridad (mysqldump o mediante consultas SHOW CREATE TABLE + SELECT).
  - Descarga automática del archivo .sql.
  - Opción para restaurar una copia desde un archivo local validado.

     3. Sistema de Vistas o Consultas Guardadas
  - Objetivo: ofrecer una forma de guardar y reutilizar consultas SQL frecuentes o informes personalizados.
  Detalles:
  - Crear tabla vistas_guardadas con campos (id, nombre, descripcion, sql, db).
  - Desde el módulo de consultas, poder guardar una consulta actual como vista.(Así, aprovecho vistas)
  - Listar y ejecutar vistas guardadas con un clic.

     4. Sistema de Relaciones entre Tablas (mejora pendiente de creación de tablas)
  - Objetivo: permitir definir claves foráneas y relaciones entre tablas directamente desde la interfaz.
    Detalles:
  - Al crear una nueva tabla, detectar si otras tablas tienen campos compatibles (por ejemplo, id o user_id).
  - Permitir al usuario marcar campos como foráneos y seleccionar la tabla/campo de referencia.
  - Generar automáticamente la restricción FOREIGN KEY (...) REFERENCES ....
  - Mostrar visualmente las relaciones en la vista de tablas (opcionalmente con un gráfico o diagrama ER sencillo).

    5. Módulo de Gestión de Estructura Avanzada
  - Objetivo: mejorar la edición de tablas ya existentes.
  - Permitir renombrar columnas y cambiar sus tipos desde el interfaz.
  - Añadir índices o claves únicas.
  - Opción para duplicar una tabla (estructura + datos).
  - Validación previa antes de aplicar cambios con ALTER TABLE.

### A nivel técnico
- Permitir que otros compañeros instalen la aplicación y administren su propia base de datos desde su entorno.  
- Mejorar validaciones en formularios (tipos SQL, campos vacíos, mantener datos tras errores).  
- Integrar logs de operaciones (registro de acciones para futuras funcionalidades).  

### A nivel visual
- Ajustar detalles de estilo (espaciado, botones, tipografía).  
- Añadir navegación lateral o barra superior con secciones.  
- Incluir información adicional para el administrador (uso, tiempo de sesión, actividad reciente).  

---

## 🌱 Futuras Mejoras
- Autenticación por roles (administrador, técnico, invitado). *(Pendiente de evaluación)*  
- Modificar la obtención de bases de datos para ocultar las internas del sistema y evitar borrados críticos. *(Parcialmente implementado, AÚN FALTA MODIFICAR EN EL DASHBOARD)*  
- Historial de operaciones (registro de modificaciones por usuario).  
- Tema oscuro / claro configurable por el usuario. *(Idea opcional inspirada en Severino)*  

---

## 📌 Notas
Este README sirve como **documento vivo** del proyecto.  
A medida que se implementen o modifiquen funcionalidades, se irá actualizando para reflejar el progreso y los cambios del sistema.

---
