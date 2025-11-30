-- FULL BACKUP

DROP TABLE IF EXISTS `evento_participante`;
CREATE TABLE `evento_participante` (
  `evento_id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `fecha_inscripcion` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','confirmado','cancelado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','3','2025-04-29 19:14:14','confirmado');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','4','2025-04-29 19:14:14','confirmado');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','5','2025-04-29 19:14:14','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('2','6','2025-05-30 18:07:45','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('2','2','2025-05-30 18:10:18','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','2','2025-05-30 20:40:27','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','1','2025-05-30 20:47:50','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','7','2025-05-31 18:00:59','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('2','8','2025-06-02 06:43:24','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('1','10','2025-06-02 08:31:18','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('4','2','2025-06-06 07:32:16','pendiente');
INSERT INTO `evento_participante` (`evento_id`,`participante_id`,`fecha_inscripcion`,`estado`) VALUES ('4','5','2025-06-07 15:21:04','pendiente');

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `lugar` varchar(200) NOT NULL,
  `max_participantes` int(11) DEFAULT NULL,
  `dificultad` enum('facil','media','dificil','extrema') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `eventos` (`id`,`nombre`,`descripcion`,`fecha_inicio`,`fecha_fin`,`lugar`,`max_participantes`,`dificultad`) VALUES ('1','Escalada en Peñón de Ifach','Ruta guiada para principiantes','2023-11-15 09:00:00','2023-11-15 18:00:00','Calpe',NULL,'facil');
INSERT INTO `eventos` (`id`,`nombre`,`descripcion`,`fecha_inicio`,`fecha_fin`,`lugar`,`max_participantes`,`dificultad`) VALUES ('2','Competición avanzada','Para escaladores experimentados','2023-12-02 10:00:00','2023-12-03 19:00:00','Montanejos',NULL,'extrema');
INSERT INTO `eventos` (`id`,`nombre`,`descripcion`,`fecha_inicio`,`fecha_fin`,`lugar`,`max_participantes`,`dificultad`) VALUES ('3','Vías Iniciación','Vías de iniciación de dificultad 4º y a la sombra.','2025-07-18 09:00:00','2025-07-18 20:30:00','Alange','6','facil');
INSERT INTO `eventos` (`id`,`nombre`,`descripcion`,`fecha_inicio`,`fecha_fin`,`lugar`,`max_participantes`,`dificultad`) VALUES ('4','Vía Ferrata','Vía simple, bajo nivel de escalada, ruta caminable, con seguridad.','2025-06-10 13:43:00','2025-07-10 17:43:00','Alange','5','facil');
INSERT INTO `eventos` (`id`,`nombre`,`descripcion`,`fecha_inicio`,`fecha_fin`,`lugar`,`max_participantes`,`dificultad`) VALUES ('5','Rocódromo Puente Nuevo','Escalada de alta intensidad, con desplome, alta dificultad.','2025-06-08 09:34:00','2025-06-09 09:35:00','Badajoz','10','extrema');
INSERT INTO `eventos` (`id`,`nombre`,`descripcion`,`fecha_inicio`,`fecha_fin`,`lugar`,`max_participantes`,`dificultad`) VALUES ('7','prueba','prueba','2023-11-15 09:00:00','2023-11-15 18:00:00','merida','10','facil');

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `nivel_escalada` enum('principiante','intermedio','avanzado','experto') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `fecha_registro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('1','Admin','Administrador','admin@escalada.com','+34000000000','avanzado','asd123','admin','2025-04-23 22:40:40');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('2','Jose','Martín','jose@escalada.com',NULL,NULL,'asd123','usuario','2025-04-29 18:51:49');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('3','Juan','Pérez López','juan@escalada.com',NULL,'intermedio','temp_3','usuario','2025-05-28 09:57:22');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('4','María','Gómez Ñúñez','maria@escalada.com','924310974','principiante','asd123','usuario','2025-05-28 09:57:22');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('5','Carlos','Sánchez Martínez','carlos@escalada.com',NULL,'avanzado','asd123','usuario','2025-05-28 09:57:22');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('6','prueba','prueba','prueba@escalada.com','665725288','experto','asd123','usuario','2025-05-30 15:08:22');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('7','pepita','palotes','pepitapalotes@meloinvento.com','666666666','principiante','meloinvento','usuario','2025-05-31 18:00:29');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('8','Manolito','moro','olA@gmail.com','123456789','principiante','ola','usuario','2025-06-02 06:43:03');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('9','Carlos','Gonzalez','carlosg@escalada.com','665474849','principiante','asd123','usuario','2025-06-02 07:00:42');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('10','nico','barba','nico@escalada.com','665414243','intermedio','asd123','usuario','2025-06-02 08:31:03');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('11','Pedro','Piqueras','pedrito@escalada.com','66487952','principiante','asd123','usuario','2025-06-04 11:55:03');
INSERT INTO `usuarios` (`id`,`nombre`,`apellidos`,`email`,`telefono`,`nivel_escalada`,`password`,`rol`,`fecha_registro`) VALUES ('12','cecilia','martin','cecilia@escalada.com','924310753','principiante','asd123','usuario','2025-06-04 11:57:30');

