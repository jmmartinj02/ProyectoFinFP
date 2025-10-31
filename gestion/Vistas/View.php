<?php
class View {
    public static function show($viewName, $data = []) {
        extract($data);
        require __DIR__ . "/$viewName.php";
    }
}
