# ProyectoFinFP
Gestor Web de base de datos
# GestorRemotoBD

Aplicación web desarrollada en **PHP (Modelo–Vista–Controlador)** para la administración remota de **bases de datos MySQL/MariaDB** alojadas en servidores o instancias en la nube (por ejemplo, AWS).  
El proyecto está diseñado para ser accesible desde un entorno web, permitiendo la gestión completa de las bases de datos sin necesidad de herramientas externas.

---

## 🚀 Funcionalidades Implementadas
  - Ejecución de consultas SQL manuales desde la interfaz web.  
  - Monitorización básica de rendimiento y tamaño de tablas.
    ### 🔧 Estructura base del proyecto
  - Entorno PHP en arquitectura **Modelo–Vista–Controlador (MVC)**.  
  - Directorios organizados: `controllers/`, `Models/`, `Vistas/`, `includes/`, `db/`.  
  - Sistema de rutas basado en: `index.php?controller=NombreController&action=metodo`  
  - Header y footer globales reutilizables.  
  - Integración con **Bootstrap 5**, sin uso de JavaScript externo (a excepción de algún recurso visual).
  - Listado de tablas de cada base de datos.  
  - Visualización del contenido de cada tabla (registros).  
  - Creación de tablas con nombre y definición de columnas.  
  - Eliminación de tablas con confirmación visual.
   ### 🗄️ Módulo principal – Gestión de bases de datos
  - Listado de bases de datos disponibles.  
  - Acceso a tablas de cada base.  
  - Creación de nuevas bases de datos.  
  - Eliminación de bases de datos con confirmación previa.  
  - Interfaz limpia y coherente, con uso de `mensajeView` para confirmaciones de borrado.
    ### FUNCIONALIDADES SECUNDARIAS IMPLEMENTADAS:
     - Implementar un sistema de autenticación simple (login de acceso) utilizando las credenciales del sistema.  (EXISTE)
     - Caducidad de sesión por inactividad.


---

#### En curso:
- Integrar el botón **“Editar estructura”** en la vista `tablasView.php`.  
- Verificar la correcta ejecución de los métodos `obtenerColumnas()` y `actualizarEstructuraTabla()`.
  ### 📋 Módulo de gestión de tablas
  - Edición de estructura:
  - Eliminar columnas seleccionadas.  
  - Añadir nuevas columnas.  
  - Guardar cambios mediante `ALTER TABLE`.   

---

## 🧠 Mejoras Pendientes o Posibles Optimizaciones

### A nivel técnico
  - Idea: permitir que otros compañeros instalen la aplicación en su entorno y puedan administrar su propia base de datos.  
- Mejorar validaciones en formularios (tipos SQL, campos vacíos, mantener la información al recargar).  (¡¡¡¡¡¡IMPORTANTÍSIMO!!!!!!!)
- Integrar logs de operaciones (registro de acciones, para futuras funcionalidades).  
  

### A nivel visual
- Ajustar detalles de estilo (espaciado, botones, tipografía).  
- Añadir navegación lateral o barra superior con secciones.  
- Incluir información adicional para el administrador (uso, tiempo de sesión, actividad reciente).  

---

## 🌱 Futuras Mejoras

- Autenticación por roles (administrador, técnico, invitado). *(Pendiente de evaluación)*  
- Modificar la obtención de bases de datos para no mostrar información del propio sistema gestor y evitar borrados críticos. (Hecho a medias) 
- Historial de operaciones (registro de modificaciones por usuario).    
- Tema oscuro y claro configurable por el usuario. *(Idea opcional inspirada de Severino)*  

---

## 📌 Notas

Este README sirve como documento vivo del proyecto.  
A medida que se implementen o modifiquen funcionalidades, se irá actualizando para reflejar el progreso y los cambios del sistema.

---

