<?php

require_once __DIR__ . '/../Models/VistasModel.php';
require_once __DIR__ . '/../Vistas/View.php';

class VistasController {
    //he metido algo de private, son buenas practicas enseñadas por Carlos
    //meter algo de seguridad y complicarme un poco sin poner los datos en public
    private $model;

    public function __construct() {
        $this->model = new VistasModel();
    }
//funcion que lista con la informaciion del model y funcion "listar"
    public function index() {
        $vistas = $this->model->listar();
        View::show("vistasIndexView", ["vistas" => $vistas]);
    }
//controller del formulario, que tras ejecutar el post
//mediante al model y "guardar" para llevarnos a Vistas
    public function nueva() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nombre = $_POST["nombre"];
            $desc = $_POST["descripcion"];
            $sql = $_POST["sql"];
            $db = $_POST["db"];

            $this->model->guardar($nombre, $desc, $sql, $db);

            header("Location: index.php?controller=VistasController&action=index");
            exit;
        }

        View::show("vistasNuevaView", [
            "bases" => $this->model->getBases()
        ]);
    }
    // ejecuta una vista guardada.
    // obtiene los datos desde la vista, 
    // ejecuta su SQL mediante el modelo "ejecutarvista"
    // y muestra los resultados en una tabla.
    public function ejecutar() {
    $id = $_GET["id"] ?? null;
    if (!$id) { header("Location: index.php?controller=VistasController&action=index"); exit; }

    $vista = $this->model->getVista($id);
    if (!$vista) { header("Location: index.php?controller=VistasController&action=index"); exit; }

    $resultado = $this->model->ejecutarVista($vista);

    View::show("vistasEjecutarView", [
        "vista" => $vista,
        "resultado" => $resultado
    ]);
}
// muestra el formulario de edición de una vista.
//si se ejecuta el POST, actualiza los datos
//y redirige a vistas.
public function editar() {
    $id = $_GET["id"] ?? null;
    if (!$id) { header("Location: index.php?controller=VistasController&action=index"); exit; }

    $vista = $this->model->getVista($id);
    if (!$vista) { header("Location: index.php?controller=VistasController&action=index"); exit; }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $this->model->actualizarVista(
            $id,
            $_POST["nombre"],
            $_POST["descripcion"],
            $_POST["sql"],
            $_POST["db"]
        );

        header("Location: index.php?controller=VistasController&action=index");
        exit;
    }

    View::show("vistasEditarView", [
        "vista" => $vista,
        "bases" => $this->model->getBases()
    ]);
}
// simplemente elimina la vista tirando de su ID
//luego te lleva directamente a Vistas
public function eliminar() {
    $id = $_GET["id"] ?? null;
    if ($id) {
        $this->model->eliminarVista($id);
    }

    header("Location: index.php?controller=VistasController&action=index");
    exit;
}

}
