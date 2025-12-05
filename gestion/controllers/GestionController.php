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

    //Carga el model del sistema y el de logs, para autodetectar *_logs
    private function registrarLog($accion, $descripcion) {
        if (isset($this->log)) {
            $usuario = $_SESSION['usuario']['nombre'] ?? 'sistema';
            $this->log->registrar($usuario, $accion, $descripcion);
        }
    }

     //pagina principal del panel, si no existe la base de datos de logs, obliga a crearla
     //muestra un listado de bases de datos.

    public function inicio() {
        if (empty($_SESSION['log_db'])) {
            header("Location: index.php?controller=LogsController&action=configurar");
            exit;
        }

        $databases = $this->model->listarBasesDeDatos();
        $this->registrarLog('ACCESO', 'Ingreso al panel principal');
        View::show('homeView', ['databases' => $databases]);
    }

    ///////////listado tablas///////////////
    //requeire un get con el dato especifico, para listar las tablas de dicho parametro(db)
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

    ////////////////ver registro de tabla////////////////
    //necesita del parametro db y table para poder ver los registros de la tabla de dicha base de datos
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

    //////////////dashboard///////////////
    //lo que puede verse en el dashboard
    //resumen general, graficos, detalles de bases, copias y los logs rec.
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
        require_once __DIR__ . '/../Models/LogModel.php';
        $logsModel = new LogModel();
        $ultimosLogs = $logsModel->getLastLogs(10);


        View::show('dashboardView', [
            'resumen' => $resumen,
            'graficos' => $graficos,
            'detalle' => $detalle,
            'lastBackup' => $lastBackup,
            'backupStats' => $backupStats,
            "ultimosLogs" => $ultimosLogs

        ]);
    }
    /////////////base de datos/////////////////////
    //metodo de acceso a la vista del formulario de creacion de BD
    public function crearBD() {
        $this->registrarLog('ACCESO_CREAR_BD', 'Acceso al formulario de creación de base de datos');
        View::show('crearBDView');
    }
///////////////procesamiento de creacion base de datos//////////////
    //valido nombre, y ejecuta la creacion llamando a la funcion crearBaseDeDatos del gestionmodel
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
    //confirmacion de eliminacion de BD
    public function confirmarEliminarBD() {
        if (empty($_GET['db'])) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos a eliminar.']);
            return;
        }
        View::show('confirmarEliminarBDView', ['db' => $_GET['db']]);
    }
    //elimina definitivamente la base de datos
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

///////////////////////tablas/////////////////////////
    //formulario de creacion de tabla, le pasa el valor db, para hacer el stmtn
    public function formCrearTabla() {
        $db = $_GET['db'] ?? '';
        if (empty($db)) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos.']);
            return;
        }
        View::show('crearTablaView', ['db' => $db]);
    }
    //funcion que usa los datos del formulario y los pasa al modelo crearTabla
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
    //simplemente confirmacion de borrado de tabla
    public function confirmarEliminarTabla() {
        $db = $_GET['db'] ?? '';
        $tabla = $_GET['table'] ?? '';
        if (empty($db) || empty($tabla)) {
            View::show('errorView', ['mensaje' => 'No se especificó la tabla a eliminar.']);
            return;
        }

        View::show('confirmarEliminarTablaView', ['db' => $db, 'tabla' => $tabla]);
    }
    //usa el model eliminartabla para eliminar definitivamente la tabla con los datos pasados(db,tabla)
    public function eliminarTabla() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $_POST['db'] ?? '';
            $tabla = $_POST['tabla'] ?? '';
            //si está vacío, da un error de falta de datos
            if (empty($db) || empty($tabla)) {
                View::show('errorView', ['mensaje' => 'Faltan datos.']);
                return;
            }

            $resultado = $this->model->eliminarTabla($db, $tabla);
            //si ha devuelto un true, se guarda en el log la accion y da un mensaje de confiramcion
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
/////////////////////registros////////////////////////////
    //formulario de inserccion de registros, necesita de db y table para que funcione
    public function formInsertarRegistro() {
        $dbName = $_GET['db'] ?? '';
        $table = $_GET['table'] ?? '';

        $db = new Database($dbName);
        $columnas = $db->obtenerColumnasForm($table);

        include __DIR__ . '/../Vistas/formInsertarRegistro.php';
    }
    //funcion que introduce un registro en una tabla, usa db y table
    //usa la funcion obtenercolumnas para poder hacer un for
    //
    public function insertarRegistro() {
        $dbName = $_GET['db'] ?? '';
        $table = $_GET['table'] ?? '';

        $db = new Database($dbName);
        $columnas = $db->obtenerColumnasForm($table);

        $campos = [];
        $valores = [];
////////////////////////
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
    //psra el formulario de edicion de registro
    //utiliza el id del registro, 
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
    //aquí usa los datos del formulario para llamar al model actualizarRegistrosEdit
    //y hacer el cambio en la base  de datos
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
        //si ha ido bien, actualiza y mensaje, junto con el registro de logs
        if ($resultado) {
            $this->registrarLog('ACTUALIZAR_REGISTRO', "Registro #$id actualizado en '$table'");
            View::show('mensajeView', [
                'mensaje' => "Registro actualizado correctamente.",
                'volver' => "index.php?controller=GestionController&action=ver&db=$db&table=$table"
            ]);
            //si no... error
        } else {
            View::show('errorView', ['mensaje' => 'Error al actualizar el registro.']);
        }
    }
        //elimina un registro usando el ID, necesita de db, table e id(identifica la linea)
    public function eliminarRegistro() {
        if (!isset($_GET['db'], $_GET['table'], $_GET['id'])) {
            View::show('errorView', ['mensaje' => 'Faltan parámetros.']);
            return;
        }

        $db = $_GET['db'];
        $table = $_GET['table'];
        $id = $_GET['id'];

        $resultado = $this->model->eliminarRegistroPorId($db, $table, $id);
        //si va bien actualiza log y muestra mensaje
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
/////////////////////consultas sql//////////////////////////
    //invoca la vista de consultas SQL
    public function consultas() {
        $bases = $this->model->obtenerBasesDeDatos();
        $dbActual = $_SESSION['conexion']['db'] ?? '';

        View::show('consultasView', ['bases' => $bases, 'dbActual' => $dbActual]);
    }
    //esta funcion se encarga de ejecutar la consultaSQL
    public function ejecutarConsulta() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::show('errorView', ['mensaje' => 'Petición no válida.']);
            return;
        }
        /////////////////7
        if (!empty($_POST['base'])) {
            $_SESSION['conexion']['db'] = $_POST['base'];
        }

        $sql = trim($_POST['sql'] ?? '');
        $limit = intval($_POST['limit'] ?? 100);
        //si no hay nada en $sql mostrará error
        if ($sql === '') {
            View::show('consultasView', [
                'error' => 'Introduce una consulta SQL.',
                'bases' => $this->model->obtenerBasesDeDatos(),
                'dbActual' => $_SESSION['conexion']['db'] ?? ''
            ]);
            return;
        }
        //si todo va bien, sql contiene algo, y usando limite, envía a la vista de consultas
        //toda la informacion obtenida
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
    /////////priemra version de vistas, inservible, pero me da cosa borrarla
    //public function seleccionarBD() {
    //    $bases = $this->model->listarBasesDeDatos();

        // Si no hay ninguna base creada, redirigimos directamente al formulario de creación
    //    if (empty($bases)) {
    //        header("Location: index.php?controller=GestionController&action=crearBD");
    //        exit;
    //    }

        // Si hay bases, mostramos la vista para seleccionar
    //    View::show('seleccionarBDView', ['bases' => $bases]);
    //}
}  

