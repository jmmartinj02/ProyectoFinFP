<?php
require_once __DIR__ . '/../db/Database.php';
require_once __DIR__ . '/../Models/GestorModel.php';
require_once __DIR__ . '/../Vistas/View.php';

class GestionController {
    private $model;

    public function __construct() {
        $this->model = new GestorModel();
    }

    public function inicio() {
        $databases = $this->model->listarBasesDeDatos();
        View::show('homeView', ['databases' => $databases]);
    }

    public function tablas() {
        if (!isset($_GET['db'])) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos.']);
            return;
        }

        $dbName = $_GET['db'];
        $tablas = $this->model->listarTablas($dbName);
        View::show('tablasView', ['dbName' => $dbName, 'tablas' => $tablas]);
    }

    public function ver() {
        if (!isset($_GET['db']) || !isset($_GET['table'])) {
            View::show('errorView', ['mensaje' => 'Faltan parámetros.']);
            return;
        }

        $db = $_GET['db'];
        $table = $_GET['table'];
        $registros = $this->model->obtenerRegistros($db, $table);

        View::show('tablasView', [
            'dbName' => $db,
            'table' => $table,
            'registros' => $registros
        ]);
    }
        public function dashboard() {
        $resumen = $this->model->obtenerResumen();
        $graficos = $this->model->tablasPorBaseDeDatos();
        $detalle  = $this->model->detalleBasesDeDatos();

        View::show('dashboardView', [
            'resumen' => $resumen,
            'graficos' => $graficos,
            'detalle' => $detalle
        ]);
    }
    // Crear nueva base de datos
    public function crearBD() {
        View::show('crearBDView');
    }

// Crear nueva tabla
    public function formCrearTabla() {
    if (empty($_GET['db'])) {
        View::show('errorView', ['mensaje' => 'No se especificó la base de datos.']);
        return;
    }

    $db = $_GET['db'];
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
                View::show('mensajeView', [
                    'mensaje' => "La tabla <strong>$nombre</strong> se ha creado correctamente.",
                    'enlace' => "index.php?controller=GestionController&action=tablas&db=" . urlencode($db)
                ]);
            } else {
                View::show('errorView', ['mensaje' => "Error al crear la tabla: $resultado"]);
            }
        }
    }

// Modificar una tabla existente
    public function modificarTabla() {
        if (!isset($_GET['bd']) || !isset($_GET['tabla'])) {
            View::show('errorView', ['mensaje' => 'Falta la base de datos o tabla.']);
            return;
        }

        $bd = $_GET['bd'];
        $tabla = $_GET['tabla'];

        View::show('modificarTablaView', ['bd' => $bd, 'tabla' => $tabla]);
    }

// Editar datos dentro de una tabla
    public function editarDatos() {
        if (!isset($_GET['bd']) || !isset($_GET['tabla'])) {
            View::show('errorView', ['mensaje' => 'Falta la base de datos o tabla.']);
            return;
        }

        $bd = $_GET['bd'];
        $tabla = $_GET['tabla'];

        View::show('editarDatosView', ['bd' => $bd, 'tabla' => $tabla]);
    }
    public function procesarCrearBD() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['db'])) {
            $db = trim($_POST['db']);

            // Validar nombre (solo letras, números y guiones bajos)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $db)) {
                View::show('crearBDView', ['error' => 'Nombre de base de datos no válido.']);
                return;
            }

            $resultado = $this->model->crearBaseDeDatos($db);

            if ($resultado === true) {
                View::show('crearBDView', [
                    'exito' => "La base de datos <strong>$db</strong> se creó correctamente."
                ]);
            } else {
                View::show('crearBDView', [
                    'error' => "Error al crear la base de datos: " . htmlspecialchars($resultado)
                ]);
            }
        } else {
            View::show('crearBDView', ['error' => 'Debes indicar un nombre de base de datos.']);
        }
    }
// Mostrar pantalla de confirmación antes de eliminar
    public function confirmarEliminarBD() {
        if (!isset($_GET['db'])) {
            View::show('errorView', ['mensaje' => 'No se especificó la base de datos a eliminar.']);
            return;
        }

        $db = $_GET['db'];
        View::show('confirmarEliminarBDView', ['db' => $db]);
    }
    // Mostrar confirmación antes de eliminar una tabla
    public function confirmarEliminarTabla() {
        $db = $_GET['db'] ?? '';
        $tabla = $_GET['table'] ?? '';

        if (empty($db) || empty($tabla)) {
            View::show('errorView', ['mensaje' => 'No se especificó la tabla a eliminar.']);
            return;
        }

        View::show('confirmarEliminarTablaView', [
            'db' => $db,
            'tabla' => $tabla
        ]);
    }

// Una vez pulsado el boton de borrado de tabla, se obtiene la base de datos y la tabla, para luego borrarla
    public function eliminarTabla() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $_POST['db'] ?? '';
            $tabla = $_POST['tabla'] ?? '';

            if (empty($db) || empty($tabla)) {
                View::show('errorView', ['mensaje' => 'Faltan datos para eliminar la tabla.']);
                return;
            }
            // guarda en resultado db y tabla para borarla
            $resultado = $this->model->eliminarTabla($db, $tabla);

            if ($resultado === true) {
                View::show('mensajeView', [
                    'mensaje' => "La tabla <strong>$tabla</strong> se ha eliminado correctamente.",
                    'enlace' => "index.php?controller=GestionController&action=tablas&db=" . urlencode($db)
                ]);
            } else {
                View::show('errorView', ['mensaje' => "Error al eliminar la tabla: $resultado"]);
            }
        } else {
            View::show('errorView', ['mensaje' => 'Petición no válida para eliminar la tabla.']);
        }
    }


// Funcion que se dedica a borrar una base de datos,
    public function eliminarBD() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['db'])) {
            $db = trim($_POST['db']);
            $resultado = $this->model->eliminarBaseDeDatos($db);

            if ($resultado === true) {
                View::show('crearBDView', [
                    'exito' => "La base de datos <strong>$db</strong> ha sido eliminada correctamente."
                ]);
            } else {
                View::show('crearBDView', [
                    'error' => "No se pudo eliminar la base de datos: " . htmlspecialchars($resultado)
                ]);
            }
        } else {
            View::show('errorView', ['mensaje' => 'Petición no válida para eliminar la base de datos.']);
        }
    }
    // formulario para modificar la tabla
    public function formEditarTabla() {
        $db = $_GET['db'] ?? '';
        $tabla = $_GET['table'] ?? '';

        if (empty($db) || empty($tabla)) {
            View::show('errorView', ['mensaje' => 'No se especificó la tabla a modificar.']);
            return;
        }

        // saca la estructura de la tabla
        $columnas = $this->model->obtenerColumnas($db, $tabla);

        View::show('editarTablaView', [
            'db' => $db,
            'tabla' => $tabla,
            'columnas' => $columnas
        ]);
    }

    // Procesa añaadir columna, si faltase información, lelva a error, si no, continua.
    public function guardarEstructuraTabla() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $_POST['db'] ?? '';
            $tabla = $_POST['tabla'] ?? '';
            $nuevas = $_POST['nuevas'] ?? [];
            $eliminar = $_POST['eliminar'] ?? [];

            if (empty($db) || empty($tabla)) {
                View::show('errorView', ['mensaje' => 'Faltan datos para modificar la estructura.']);
                return;
            }
            //se envía la información
            $resultado = $this->model->actualizarEstructuraTabla($db, $tabla, $nuevas, $eliminar);
            // si $resultado tiene información quiere decir que la consulta se ha realizado con exito
            if ($resultado === true) {
                View::show('mensajeView', [
                    'mensaje' => "La estructura de la tabla <strong>$tabla</strong> se ha actualizado correctamente.",
                    'enlace' => "index.php?controller=GestionController&action=ver&db=" . urlencode($db) . "&table=" . urlencode($tabla)
                ]);
            } else {
                View::show('errorView', ['mensaje' => "Error al actualizar la estructura: $resultado"]);
            }
        }
    }
    // Parte relacionada con las consultas SQL.
    // Mostrar formulario de consultas
public function consultas() {
    // Obtener las bases de datos disponibles
    $bases = $this->model->obtenerBasesDeDatos();

    // Si ya hay una base seleccionada en sesión, la usamos
    $dbActual = $_SESSION['conexion']['db'] ?? '';

    View::show('consultasView', [
        'bases' => $bases,
        'dbActual' => $dbActual
    ]);
}

// los datos del formulario se pasan a la base de datos
public function ejecutarConsulta() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        View::show('errorView', ['mensaje' => 'Petición no válida.']);
        return;
    }

    // Guardar base seleccionada (si la hay)
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

    // Ejecutar la consulta
    $resultado = $this->model->ejecutarConsultaSQL($sql, $limit);

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
    //Lo utilizaremos para que el formulario tome forma, según los campos de una tabla
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
        if (strpos($col['Extra'], 'auto_increment') !== false) continue; // ignorar autoincrement
        $campo = $col['Field'];
        $campos[] = "`$campo`";
        $valores[":$campo"] = $_POST[$campo] ?? null;
    }

    $sql = "INSERT INTO `$table` (" . implode(',', $campos) . ") VALUES (" . implode(',', array_keys($valores)) . ")";
    $stmt = $db->prepare($sql);
    $stmt->execute($valores);

    header("Location: index.php?controller=GestionController&action=ver&db=$dbName&table=$table");
    exit;
}
public function editarRegistro() {
    if (!isset($_GET['db'], $_GET['table'], $_GET['id'])) {
        View::show('errorView', ['mensaje' => 'Faltan parámetros para editar.']);
        return;
    }

    $db = $_GET['db'];
    $table = $_GET['table'];
    $id = $_GET['id'];

    $registro = $this->model->obtenerRegistroPorId($db, $table, $id);
    $columnas = $this->model->obtenerColumnas($db, $table);

    View::show('editarRegistroView', [
        'db' => $db,
        'table' => $table,
        'registro' => $registro,
        'columnas' => $columnas
    ]);
}

public function actualizarRegistro() {
    if (!isset($_POST['db'], $_POST['table'], $_POST['id'])) {
        View::show('errorView', ['mensaje' => 'Datos incompletos para la actualización.']);
        return;
    }

    $db = $_POST['db'];
    $table = $_POST['table'];
    $id = $_POST['id'];
    $datos = $_POST;
    unset($datos['db'], $datos['table'], $datos['id']);

    $resultado = $this->model->actualizarRegistro($db, $table, $id, $datos);

    if ($resultado) {
        View::show('mensajeView', [
            'mensaje' => "Registro actualizado correctamente.",
            'volver' => "index.php?controller=GestionController&action=ver&db=$db&table=$table"
        ]);
    } else {
        View::show('errorView', ['mensaje' => 'Error al actualizar el registro.']);
    }
}


}
