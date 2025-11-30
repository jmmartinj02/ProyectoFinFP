<?php
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Models/GestorModel.php';
require_once __DIR__ . '/../Models/LogModel.php';
require_once __DIR__ . '/../Vistas/View.php';
class GestionController {
    private $model;
    private $log;

    public function __construct() {
        $this->model = new GestorModel();
        $this->log = new LogModel();
        
    }

    /** =======================================================
     *  MÉTODO AUXILIAR PARA REGISTRAR LOGS
     *  ======================================================= */
    private function registrarLog($accion, $descripcion) {
        if (isset($this->log)) {
            $usuario = $_SESSION['usuario']['nombre'] ?? 'sistema';
            $this->log->registrar($usuario, $accion, $descripcion);
        }
    }

    /** =======================================================
     *  INICIO / HOME
     *  ======================================================= */
    public function inicio() {
        if (empty($_SESSION['log_db'])) {
            header("Location: index.php?controller=LogsController&action=configurar");
            exit;
        }

        $databases = $this->model->listarBasesDeDatos();
        $this->registrarLog('ACCESO', 'Ingreso al panel principal');
        View::show('homeView', ['databases' => $databases]);
    }

    /** =======================================================
     *  LISTADO DE TABLAS
     *  ======================================================= */
    public function tablas() {
        if (empty($_GET['db'])) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos.']);
            return;
        }

        $dbName = $_GET['db'];
        $tablas = $this->model->listarTablas($dbName);

        $this->registrarLog('VER_TABLAS', "Visualización de tablas en '$dbName'");

        View::show('tablasView', ['dbName' => $dbName, 'tablas' => $tablas]);
    }

    /** =======================================================
     *  VER REGISTROS DE UNA TABLA
     *  ======================================================= */
    public function ver() {
        if (!isset($_GET['db'], $_GET['table'])) {
            View::show('errorView', ['mensaje' => 'Faltan parámetros.']);
            return;
        }

        $db = $_GET['db'];
        $table = $_GET['table'];
        $registros = $this->model->obtenerRegistros($db, $table);

        $this->registrarLog('VER_REGISTROS', "Visualización de registros en '$table' (BD: $db)");

        View::show('tablasView', [
            'dbName' => $db,
            'table' => $table,
            'registros' => $registros
        ]);
    }

    /** =======================================================
     *  DASHBOARD
     *  ======================================================= */
    public function dashboard() {
        $resumen = $this->model->obtenerResumen();
        $graficos = $this->model->tablasPorBaseDeDatos();
        $detalle  = $this->model->detalleBasesDeDatos();
      
        $this->registrarLog('ACCESO_DASHBOARD', 'Visualización del dashboard general');
        //para introrucir en el dashboard el aviso de la copia de seguridad
        require_once __DIR__ . '/../Models/BackupModel.php';
        $backupModel = new BackupModel();
        $lastBackup = $backupModel->getLastBackupDate();
        $backupStats = $backupModel->estadisticas();


        View::show('dashboardView', [
            'resumen' => $resumen,
            'graficos' => $graficos,
            'detalle' => $detalle,
            'lastBackup' => $lastBackup,
            'backupStats' => $backupStats

        ]);
    }

    /** =======================================================
     *  BASES DE DATOS
     *  ======================================================= */
    public function crearBD() {
        $this->registrarLog('ACCESO_CREAR_BD', 'Acceso al formulario de creación de base de datos');
        View::show('crearBDView');
    }

    public function procesarCrearBD() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['db'])) {
            $db = trim($_POST['db']);
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $db)) {
                View::show('crearBDView', ['error' => 'Nombre de base de datos no válido.']);
                return;
            }

            $resultado = $this->model->crearBaseDeDatos($db);

            if ($resultado === true) {
                $this->registrarLog('CREAR_BD', "Base de datos '$db' creada correctamente");
                View::show('crearBDView', ['exito' => "La base de datos <strong>$db</strong> se creó correctamente."]);
            } else {
                View::show('crearBDView', ['error' => "Error al crear la base de datos: " . htmlspecialchars($resultado)]);
            }
        } else {
            View::show('crearBDView', ['error' => 'Debes indicar un nombre de base de datos.']);
        }
    }

    public function confirmarEliminarBD() {
        if (empty($_GET['db'])) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos a eliminar.']);
            return;
        }
        View::show('confirmarEliminarBDView', ['db' => $_GET['db']]);
    }

    public function eliminarBD() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['db'])) {
            $db = trim($_POST['db']);
            $resultado = $this->model->eliminarBaseDeDatos($db);

            if ($resultado === true) {
                $this->registrarLog('ELIMINAR_BD', "Base de datos '$db' eliminada");
                View::show('crearBDView', ['exito' => "Base de datos <strong>$db</strong> eliminada correctamente."]);
            } else {
                View::show('crearBDView', ['error' => "Error al eliminar la base de datos: " . htmlspecialchars($resultado)]);
            }
        } else {
            View::show('errorView', ['mensaje' => 'Petición no válida.']);
        }
    }

    /** =======================================================
     *  TABLAS
     *  ======================================================= */
    public function formCrearTabla() {
        $db = $_GET['db'] ?? '';
        if (empty($db)) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos.']);
            return;
        }
        View::show('crearTablaView', ['db' => $db]);
    }

    public function crearTabla() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $_POST['db'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $columnas = $_POST['columnas'] ?? [];

            if (empty($db) || empty($nombre) || empty($columnas)) {
                View::show('errorView', ['mensaje' => 'Faltan datos para crear la tabla.']);
                return;
            }

            $resultado = $this->model->crearTabla($db, $nombre, $columnas);
            if ($resultado === true) {
                $this->registrarLog('CREAR_TABLA', "Tabla '$nombre' creada en '$db'");
                View::show('mensajeView', [
                    'mensaje' => "La tabla <strong>$nombre</strong> se creó correctamente.",
                    'enlace' => "index.php?controller=GestionController&action=tablas&db=" . urlencode($db)
                ]);
            } else {
                View::show('errorView', ['mensaje' => "Error al crear la tabla: $resultado"]);
            }
        }
    }

    public function confirmarEliminarTabla() {
        $db = $_GET['db'] ?? '';
        $tabla = $_GET['table'] ?? '';
        if (empty($db) || empty($tabla)) {
            View::show('errorView', ['mensaje' => 'No se especificó la tabla a eliminar.']);
            return;
        }

        View::show('confirmarEliminarTablaView', ['db' => $db, 'tabla' => $tabla]);
    }

    public function eliminarTabla() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $_POST['db'] ?? '';
            $tabla = $_POST['tabla'] ?? '';
            if (empty($db) || empty($tabla)) {
                View::show('errorView', ['mensaje' => 'Faltan datos.']);
                return;
            }

            $resultado = $this->model->eliminarTabla($db, $tabla);
            if ($resultado === true) {
                $this->registrarLog('ELIMINAR_TABLA', "Tabla '$tabla' eliminada en '$db'");
                View::show('mensajeView', [
                    'mensaje' => "Tabla eliminada correctamente.",
                    'enlace' => "index.php?controller=GestionController&action=tablas&db=" . urlencode($db)
                ]);
            } else {
                View::show('errorView', ['mensaje' => "Error al eliminar la tabla: $resultado"]);
            }
        }
    }

    /** =======================================================
     *  REGISTROS
     *  ======================================================= */
    public function formInsertarRegistro() {
        $dbName = $_GET['db'] ?? '';
        $table = $_GET['table'] ?? '';

        $db = new Database($dbName);
        $columnas = $db->obtenerColumnasForm($table);

        include __DIR__ . '/../Vistas/formInsertarRegistro.php';
    }

    public function insertarRegistro() {
        $dbName = $_GET['db'] ?? '';
        $table = $_GET['table'] ?? '';

        $db = new Database($dbName);
        $columnas = $db->obtenerColumnasForm($table);

        $campos = [];
        $valores = [];

        foreach ($columnas as $col) {
            if (strpos($col['Extra'], 'auto_increment') !== false) continue;
            $campo = $col['Field'];
            $campos[] = "`$campo`";
            $valores[":$campo"] = $_POST[$campo] ?? null;
        }

        $sql = "INSERT INTO `$table` (" . implode(',', $campos) . ") VALUES (" . implode(',', array_keys($valores)) . ")";
        $stmt = $db->prepare($sql);
        $stmt->execute($valores);

        $this->registrarLog('INSERTAR_REGISTRO', "Nuevo registro insertado en '$table' (BD: $dbName)");

        header("Location: index.php?controller=GestionController&action=ver&db=$dbName&table=$table");
        exit;
    }

    public function editarRegistroEdit() {
        if (!isset($_GET['db'], $_GET['table'], $_GET['id'])) {
            View::show('errorView', ['mensaje' => 'Faltan parámetros.']);
            return;
        }

        $db = $_GET['db'];
        $table = $_GET['table'];
        $id = $_GET['id'];

        $registro = $this->model->obtenerRegistroPorId($db, $table, $id);
        $columnas = $this->model->obtenerColumnas($db, $table);

        $this->registrarLog('EDITAR_REGISTRO', "Acceso a edición del registro #$id en '$table'");

        View::show('editarRegistroView', [
            'db' => $db,
            'table' => $table,
            'registro' => $registro,
            'columnas' => $columnas
        ]);
    }

    public function actualizarRegistroEdit() {
        if (!isset($_POST['db'], $_POST['table'], $_POST['id'])) {
            View::show('errorView', ['mensaje' => 'Datos incompletos.']);
            return;
        }

        $db = $_POST['db'];
        $table = $_POST['table'];
        $id = $_POST['id'];
        $datos = $_POST;
        unset($datos['db'], $datos['table'], $datos['id']);

        $resultado = $this->model->actualizarRegistroEdit($db, $table, $id, $datos);

        if ($resultado) {
            $this->registrarLog('ACTUALIZAR_REGISTRO', "Registro #$id actualizado en '$table'");
            View::show('mensajeView', [
                'mensaje' => "Registro actualizado correctamente.",
                'volver' => "index.php?controller=GestionController&action=ver&db=$db&table=$table"
            ]);
        } else {
            View::show('errorView', ['mensaje' => 'Error al actualizar el registro.']);
        }
    }

    public function eliminarRegistro() {
        if (!isset($_GET['db'], $_GET['table'], $_GET['id'])) {
            View::show('errorView', ['mensaje' => 'Faltan parámetros.']);
            return;
        }

        $db = $_GET['db'];
        $table = $_GET['table'];
        $id = $_GET['id'];

        $resultado = $this->model->eliminarRegistroPorId($db, $table, $id);
        if ($resultado) {
            $this->registrarLog('ELIMINAR_REGISTRO', "Registro #$id eliminado de '$table' (BD: $db)");
            View::show('mensajeView', [
                'mensaje' => "Registro eliminado correctamente.",
                'volver' => "index.php?controller=GestionController&action=ver&db=$db&table=$table"
            ]);
        } else {
            View::show('errorView', ['mensaje' => 'Error al eliminar el registro.']);
        }
    }

    /** =======================================================
     *  CONSULTAS SQL MANUALES
     *  ======================================================= */
    public function consultas() {
        $bases = $this->model->obtenerBasesDeDatos();
        $dbActual = $_SESSION['conexion']['db'] ?? '';

        View::show('consultasView', ['bases' => $bases, 'dbActual' => $dbActual]);
    }

    public function ejecutarConsulta() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::show('errorView', ['mensaje' => 'Petición no válida.']);
            return;
        }

        if (!empty($_POST['base'])) {
            $_SESSION['conexion']['db'] = $_POST['base'];
        }

        $sql = trim($_POST['sql'] ?? '');
        $limit = intval($_POST['limit'] ?? 100);

        if ($sql === '') {
            View::show('consultasView', [
                'error' => 'Introduce una consulta SQL.',
                'bases' => $this->model->obtenerBasesDeDatos(),
                'dbActual' => $_SESSION['conexion']['db'] ?? ''
            ]);
            return;
        }

        $resultado = $this->model->ejecutarConsultaSQL($sql, $limit);
        $this->registrarLog('CONSULTA_SQL', "Ejecución de consulta manual: $sql");

        View::show('consultasView', [
            'sql' => $sql,
            'bases' => $this->model->obtenerBasesDeDatos(),
            'dbActual' => $_SESSION['conexion']['db'] ?? '',
            'type' => $resultado['type'],
            'data' => $resultado['data'],
            'affected' => $resultado['affected'],
            'error' => $resultado['error']
        ]);
    }

    public function seleccionarBD() {
        $bases = $this->model->listarBasesDeDatos();

        // Si no hay ninguna base creada, redirigimos directamente al formulario de creación
        if (empty($bases)) {
            header("Location: index.php?controller=GestionController&action=crearBD");
            exit;
        }

        // Si hay bases, mostramos la vista para seleccionar
        View::show('seleccionarBDView', ['bases' => $bases]);
    }
    //segunda versión, el sistema daba fallo al no tener una base de datos donde hacer el post
    //con lo cual, primero recuperamos con get, si está vacio, que lo busque en la sesion
    //si aun asi sigue vacío, nos vamos a seleccionar base de datos para la vista,
    //y guardamos siempre en la sesión la base de datos.
public function vistas() {

    // Primero miramos la DB enviada por GET
    if (!empty($_GET['db'])) {
        $_SESSION['current_db'] = $_GET['db'];
    }

    // Si no viene por GET, usamos la de sesión
    $db = $_SESSION['current_db'] ?? null;

    // Si sigue sin haber DB → ir a seleccionarBD
    if (!$db) {
        header("Location: index.php?controller=GestionController&action=seleccionarBD");
        exit;
    }

    // Listar vistas
    $vistas = $this->model->listarVistas($db);

    View::show('vistasView', [
        'db' => $db,
        'vistas' => $vistas
    ]);
}



    public function verVista() {
        $db = $_GET['db'] ?? null;
        $vista = $_GET['vista'] ?? null;

        if (!$db || !$vista) {
            View::show('errorView', ['mensaje' => 'Faltan parámetros']);
            return;
        }

        $detalles = $this->model->obtenerVista($db, $vista);

        View::show('vistaDetalleView', [
            'db' => $db,
            'vista' => $vista,
            'detalles' => $detalles
        ]);
    }

    public function crearVista() {
    // obtener DB desde POST o sesión
    $db = $_POST['db'] ?? ($_SESSION['current_db'] ?? null);

    if (!$db) {
        View::show('errorView', ['mensaje' => 'No hay base de datos seleccionada.']);
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        // aceptar tanto 'consulta' como 'sql' por si hay inconsistencia
        $consulta = trim($_POST['consulta'] ?? $_POST['sql'] ?? '');

        // validaciones básicas
        if ($nombre === '' || $consulta === '') {
            View::show('crearVistaView', [
                'db' => $db,
                'error' => 'Debes indicar nombre y la consulta SQL (SELECT).',
                'nombre' => $nombre,
                'sql' => $consulta
            ]);
            return;
        }

        // nombre válido (solo letras, números y guion bajo)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre)) {
            View::show('crearVistaView', [
                'db' => $db,
                'error' => 'Nombre de vista no válido (usa solo letras, números y _).',
                'nombre' => $nombre,
                'sql' => $consulta
            ]);
            return;
        }

        // llamar al modelo (se delega detección de CREATE/SELECT allí)
        $resultado = $this->model->crearVista($db, $nombre, $consulta);

        if ($resultado === true) {
            $this->registrarLog('CREAR_VISTA', "Vista '$nombre' creada en '$db'");
            header("Location: index.php?controller=GestionController&action=vistas&db=" . urlencode($db));
            exit;
        } else {
            // $resultado puede contener message de error o string devuelto por el model
            View::show('crearVistaView', [
                'db' => $db,
                'error' => $resultado,
                'nombre' => $nombre,
                'sql' => $consulta
            ]);
            return;
        }
    }

    // GET -> mostrar formulario
    View::show('crearVistaView', ['db' => $db]);
}



    public function eliminarVista() {
        $db = $_GET['db'] ?? null;
        $vista = $_GET['vista'] ?? null;

        if ($db && $vista) {
            $this->model->eliminarVista($db, $vista);
        }

        header("Location: index.php?controller=GestionController&action=vistas&db=$db");
        exit;
    }

    

}  

