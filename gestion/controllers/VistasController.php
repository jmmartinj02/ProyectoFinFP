<?php

require_once __DIR__ . '/../Models/VistasModel.php';
require_once __DIR__ . '/../Vistas/View.php';

class VistasController {

    private $model;

    public function __construct() {
        $this->model = new VistasModel();
    }

    public function index() {
        $vistas = $this->model->listar();
        View::show("vistasIndexView", ["vistas" => $vistas]);
    }

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

public function eliminar() {
    $id = $_GET["id"] ?? null;
    if ($id) {
        $this->model->eliminarVista($id);
    }

    header("Location: index.php?controller=VistasController&action=index");
    exit;
}

}
