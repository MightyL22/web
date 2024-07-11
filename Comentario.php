<?php
    include 'BDconexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $celular = $_POST['celular'];
        $sexo = isset($_POST['sexo']) ? $_POST['sexo'] : null;
        $juegoFavorito = $_POST['juegoFavorito'];
        $tipoJuego = $_POST['tipoJuego'];
        $comentario = $_POST['comentario'];

        // Validar si todos los campos necesarios están presentes
        if (!empty($nombre) && !empty($apellido) && !empty($email)) {
            $sql = "INSERT INTO comentarios (nombre, apellido, email, celular, sexo, juegoFavorito, tipoJuego, comentario) 
                VALUES (:nombre, :apellido, :email, :celular, :sexo, :juegoFavorito, :tipoJuego, :comentario)";

            try {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':apellido', $apellido);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':celular', $celular);
                $stmt->bindParam(':sexo', $sexo);
                $stmt->bindParam(':juegoFavorito', $juegoFavorito);
                $stmt->bindParam(':tipoJuego', $tipoJuego);
                $stmt->bindParam(':comentario', $comentario);

                if ($stmt->execute()) {
                    echo "Nuevo comentario insertado con éxito";
                } else {
                    echo "Error al insertar el comentario";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Faltan campos obligatorios";
        }

        $conn = null;
    } else {
        echo "No se recibieron datos POST.";
    }
    ?>