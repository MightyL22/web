<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Adivina el Número</title>
    <link rel="stylesheet" href="/css/adivinaNumero.css">
</head>

<body>
    <div class="contenedor">
        <h1>Juego de Adivina el Número</h1>
        <?php
        // Funciones del juego de cartas
        function obtenerCarta() // Función para generar una carta aleatoria
        {
            $palos = ['Corazones', 'Diamantes', 'Tréboles', 'Picas']; // Define un arreglo con los palos de la baraja
            $valores = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A']; // Define un arreglo con los valores de las cartas
            $palo = $palos[array_rand($palos)]; // Selecciona un palo al azar
            $valor = $valores[array_rand($valores)]; // Selecciona un valor al azar
            return ['palo' => $palo, 'valor' => $valor]; // Devuelve un arreglo asociativo con el palo y valor de la carta
        }

        function compararCartas($cartaJugador1, $cartaJugador2) // Función para comparar dos cartas
        {
            $valores = ['2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10, 'J' => 11, 'Q' => 12, 'K' => 13, 'A' => 14]; // Define un arreglo asociativo con los valores numéricos de las cartas
            $valorJugador1 = $valores[$cartaJugador1['valor']]; // Obtiene el valor numérico de la carta del Jugador 1
            $valorJugador2 = $valores[$cartaJugador2['valor']]; // Obtiene el valor numérico de la carta del Jugador 2
            if ($valorJugador1 > $valorJugador2) { // Compara los valores numéricos
                return 'Jugador 1 gana'; // Si el valor del Jugador 1 es mayor, devuelve "Jugador 1 gana"
            } elseif ($valorJugador1 < $valorJugador2) {
                return 'Jugador 2 gana'; // Si el valor del Jugador 1 es menor, devuelve "Jugador 2 gana"
            } else {
                return 'Empate'; // Si los valores son iguales, devuelve "Empate"
            }
        }

        // Inicialización de variables
        $numeroSecreto = null; // Inicializa la variable $numeroSecreto como nula
        $intentosMaximos = 5; // Define el número máximo de intentos permitidos
        $nombreJugador1 = ''; // Inicializa la variable $nombreJugador1 como una cadena vacía
        $nombreJugador2 = ''; // Inicializa la variable $nombreJugador2 como una cadena vacía
        $adivinanzasJugador1 = []; // Inicializa la variable $adivinanzasJugador1 como un arreglo vacío
        $adivinanzasJugador2 = []; // Inicializa la variable $adivinanzasJugador2 como un arreglo vacío
        $resultado = ''; // Inicializa la variable $resultado como una cadena vacía
        $minimo = 1; // Define el valor mínimo del rango de números
        $maximo = 100; // Define el valor máximo del rango de números
        $turnoJugador = null; // Inicializa la variable $turnoJugador como nula
        $ganadorCartas = ''; // Inicializa la variable $ganadorCartas como una cadena vacía

        // Función para verificar la adivinanza
        function verificarAdivinanza($adivinanza, $numeroSecreto) // Función para verificar si una adivinanza es correcta, baja o alta
        {
            if ($adivinanza == $numeroSecreto) { // Compara la adivinanza con el número secreto
                return 'correcto'; // Si son iguales, devuelve "correcto"
            } elseif ($adivinanza < $numeroSecreto) {
                return 'bajo'; // Si la adivinanza es menor, devuelve "bajo"
            } else {
                return 'alto'; // Si la adivinanza es mayor, devuelve "alto"
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jugar'])) { // Verifica si se ha enviado el formulario para iniciar el juego
            $nombreJugador1 = htmlspecialchars($_POST['nombre1']); // Obtiene el nombre del Jugador 1 del formulario y lo sanitiza
            $nombreJugador2 = htmlspecialchars($_POST['nombre2']); // Obtiene el nombre del Jugador 2 del formulario y lo sanitiza
            $minimo = (int)$_POST['minimo']; // Obtiene el valor mínimo del rango de números del formulario y lo convierte a entero
            $maximo = (int)$_POST['maximo']; // Obtiene el valor máximo del rango de números del formulario y lo convierte a entero
            $numeroSecreto = rand($minimo, $maximo); // Genera un número secreto aleatorio dentro del rango especificado

            // Juego de cartas para decidir el turno
            do { // Bucle para repetir el juego de cartas hasta que haya un ganador
                $cartaJugador1 = obtenerCarta(); // Obtiene una carta aleatoria para el Jugador 1
                $cartaJugador2 = obtenerCarta(); // Obtiene una carta aleatoria para el Jugador 2
                $ganadorCartas = compararCartas($cartaJugador1, $cartaJugador2); // Compara las cartas y determina el ganador
            } while ($ganadorCartas === 'Empate'); // Repite el bucle si hay un empate

            if ($ganadorCartas === 'Jugador 1 gana') { // Si el Jugador 1 ganó el juego de cartas
                $turnoJugador = 1; // Asigna el turno al Jugador 1
            } elseif ($ganadorCartas === 'Jugador 2 gana') {
                $turnoJugador = 2; // Asigna el turno al Jugador 2
            }

            $adivinanzasJugador1 = $_POST['adivinanza1'] ?? []; // Obtiene las adivinanzas del Jugador 1 del formulario, si existen
            $adivinanzasJugador2 = $_POST['adivinanza2'] ?? []; // Obtiene las adivinanzas del Jugador 2 del formulario, si existen
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adivinar'])) { // Verifica si se ha enviado el formulario de adivinanzas
            $nombreJugador1 = htmlspecialchars($_POST['nombre1']); // Obtiene el nombre del Jugador 1 del formulario y lo sanitiza
            $nombreJugador2 = htmlspecialchars($_POST['nombre2']); // Obtiene el nombre del Jugador 2 del formulario y lo sanitiza
            $minimo = (int)$_POST['minimo']; // Obtiene el valor mínimo del rango de números del formulario y lo convierte a entero
            $maximo = (int)$_POST['maximo']; // Obtiene el valor máximo del rango de números del formulario y lo convierte a entero
            $numeroSecreto = (int)$_POST['numeroSecreto']; // Obtiene el número secreto del formulario y lo convierte a entero

            $adivinanzasJugador1 = $_POST['adivinanza1'] ?? []; // Obtiene las adivinanzas del Jugador 1 del formulario, si existen
            $adivinanzasJugador2 = $_POST['adivinanza2'] ?? []; // Obtiene las adivinanzas del Jugador 2 del formulario, si existen

            $resultadoJugador1 = 'sigue intentándolo'; // Inicializa el resultado del Jugador 1 como "sigue intentándolo"
            $resultadoJugador2 = 'sigue intentándolo'; // Inicializa el resultado del Jugador 2 como "sigue intentándolo"

            // Verificar adivinanzas con un bucle while
            $i = 0; // Inicializa el contador del bucle
            while ($i < count($adivinanzasJugador1) && $resultadoJugador1 !== 'correcto') { // Bucle while para verificar las adivinanzas del Jugador 1
                $resultadoJugador1 = verificarAdivinanza($adivinanzasJugador1[$i], $numeroSecreto); // Verifica la adivinanza actual
                $i++; // Incrementa el contador del bucle
            }

            // Verificar adivinanzas con un bucle for
            for ($i = 0; $i < count($adivinanzasJugador2); $i++) { // Bucle for para verificar las adivinanzas del Jugador 2
                if ($resultadoJugador2 === 'correcto') { // Si el Jugador 2 ya adivinó correctamente
                    break; // Sale del bucle
                }
                $resultadoJugador2 = verificarAdivinanza($adivinanzasJugador2[$i], $numeroSecreto); // Verifica la adivinanza actual
            }

            if ($resultadoJugador1 === 'correcto' && $resultadoJugador2 === 'correcto') { // Si ambos jugadores adivinaron correctamente
                $resultado = 'Ambos jugadores adivinaron correctamente'; // Establece el mensaje del resultado
            } elseif ($resultadoJugador1 === 'correcto') { // Si solo el Jugador 1 adivinó correctamente
                $resultado = $nombreJugador1 . ' ha adivinado correctamente. El número secreto era ' . $numeroSecreto; // Establece el mensaje del resultado
            } elseif ($resultadoJugador2 === 'correcto') { // Si solo el Jugador 2 adivinó correctamente
                $resultado = $nombreJugador2 . ' ha adivinado correctamente. El número secreto era ' . $numeroSecreto; // Establece el mensaje del resultado
            } else { // Si ningún jugador adivinó correctamente
                $resultado = 'Ninguno de los jugadores ha adivinado el número. El número secreto era ' . $numeroSecreto; // Establece el mensaje del resultado
            }
        }
        ?>

        <?php if (!$resultado) : ?> <!-- Si no hay un resultado definido, muestra el formulario de inicio o de adivinanzas -->
            <?php if ($turnoJugador === null) : ?> <!-- Si el turno del jugador no se ha establecido, muestra el formulario de inicio -->
                <!-- Formulario para ingresar nombres y rango -->
                <div class="caja">
                    <form action="" method="post">
                        <label for="nombre1">Nombre del Jugador 1:</label>
                        <input type="text" id="nombre1" name="nombre1" required> <!-- Campo para ingresar el nombre del Jugador 1 -->
                        <br><br>
                        <label for="nombre2">Nombre del Jugador 2:</label>
                        <input type="text" id="nombre2" name="nombre2" required> <!-- Campo para ingresar el nombre del Jugador 2 -->
                        <br><br>
                        <h3>Rango de números para el número secreto:</h3>
                        <label for="minimo">Número mínimo:</label>
                        <input type="number" id="minimo" name="minimo" value="1" required> <!-- Campo para ingresar el valor mínimo del rango -->
                        <br><br>
                        <label for="maximo">Número máximo:</label>
                        <input type="number" id="maximo" name="maximo" value="100" required> <!-- Campo para ingresar el valor máximo del rango -->
                        <br><br>
                        <input type="hidden" name="jugar" value="1"> <!-- Campo oculto para indicar que se ha iniciado el juego -->
                        <input type="submit" class="boton" value="Iniciar Juego de Cartas"> <!-- Botón para enviar el formulario e iniciar el juego -->
                    </form>
                </div>
            <?php else : ?> <!-- Si el turno del jugador se ha establecido, muestra el formulario de adivinanzas -->

                <!-- Mostrar el resultado del juego de cartas y continuar con adivinanzas -->
                <div class="caja">
                    <h2>Juego de Cartas: <?php echo $ganadorCartas; ?></h2> <!-- Muestra el resultado del juego de cartas -->
                    <p><?php echo $nombreJugador1; ?>: <?php echo $cartaJugador1['valor'] . ' de ' . $cartaJugador1['palo']; ?></p> <!-- Muestra la carta del Jugador 1 -->
                    <p><?php echo $nombreJugador2; ?>: <?php echo $cartaJugador2['valor'] . ' de ' . $cartaJugador2['palo']; ?></p> <!-- Muestra la carta del Jugador 2 -->
                    <h3>El ganador del juego de cartas es <?php echo $ganadorCartas === 'Jugador 1 gana' ? $nombreJugador1 : $nombreJugador2; ?></h3> <!-- Muestra el nombre del ganador del juego de cartas -->
                </div>

                <div class="caja">
                    <form action="" method="post">
                        <?php if ($turnoJugador === 1) : ?> <!-- Si el turno es del Jugador 1, muestra primero sus campos de adivinanzas -->
                            <!-- Jugador 1 ingresa sus números primero -->
                            <h3>Primero adivina <?php echo $nombreJugador1; ?></h3>
                            <?php for ($i = 0; $i < $intentosMaximos; $i++) : ?> <!-- Bucle para mostrar los campos de adivinanzas del Jugador 1 -->
                                <label for="adivinanza1_<?php echo $i; ?>">Adivinanza <?php echo $i + 1; ?> de <?php echo $nombreJugador1; ?>:</label>
                                <input type="number" id="adivinanza1_<?php echo $i; ?>" name="adivinanza1[]" min="<?php echo $minimo; ?>" max="<?php echo $maximo; ?>"> <!-- Campo para ingresar la adivinanza del Jugador 1 -->
                                <br><br>
                            <?php endfor; ?>
                            <h3>Ahora adivina <?php echo $nombreJugador2; ?></h3>
                            <?php for ($i = 0; $i < $intentosMaximos; $i++) : ?> <!-- Bucle para mostrar los campos de adivinanzas del Jugador 2 -->
                                <label for="adivinanza2_<?php echo $i; ?>">Adivinanza <?php echo $i + 1; ?> de <?php echo $nombreJugador2; ?>:</label>
                                <input type="number" id="adivinanza2_<?php echo $i; ?>" name="adivinanza2[]" min="<?php echo $minimo; ?>" max="<?php echo $maximo; ?>"> <!-- Campo para ingresar la adivinanza del Jugador 2 -->
                                <br><br>
                            <?php endfor; ?>

                        <?php else : ?> <!-- Si el turno es del Jugador 2, muestra primero sus campos de adivinanzas -->
                            <!-- Jugador 2 ingresa sus números primero -->
                            <h3>Primero adivina <?php echo $nombreJugador2; ?></h3>
                            <?php for ($i = 0; $i < $intentosMaximos; $i++) : ?> <!-- Bucle para mostrar los campos de adivinanzas del Jugador 2 -->
                                <label for="adivinanza2_<?php echo $i; ?>">Adivinanza <?php echo $i + 1; ?> de <?php echo $nombreJugador2; ?>:</label>
                                <input type="number" id="adivinanza2_<?php echo $i; ?>" name="adivinanza2[]" min="<?php echo $minimo; ?>" max="<?php echo $maximo; ?>"> <!-- Campo para ingresar la adivinanza del Jugador 2 -->
                                <br><br>
                            <?php endfor; ?>

                            <h3>Ahora adivina <?php echo $nombreJugador1; ?></h3>
                            <?php for ($i = 0; $i < $intentosMaximos; $i++) : ?> <!-- Bucle para mostrar los campos de adivinanzas del Jugador 1 -->
                                <label for="adivinanza1_<?php echo $i; ?>">Adivinanza <?php echo $i + 1; ?> de <?php echo $nombreJugador1; ?>:</label>
                                <input type="number" id="adivinanza1_<?php echo $i; ?>" name="adivinanza1[]" min="<?php echo $minimo; ?>" max="<?php echo $maximo; ?>"> <!-- Campo para ingresar la adivinanza del Jugador 1 -->
                                <br><br>
                            <?php endfor; ?>
                        <?php endif; ?>

                        <input type="hidden" name="nombre1" value="<?php echo $nombreJugador1; ?>"> <!-- Campo oculto para almacenar el nombre del Jugador 1 -->
                        <input type="hidden" name="nombre2" value="<?php echo $nombreJugador2; ?>"> <!-- Campo oculto para almacenar el nombre del Jugador 2 -->
                        <input type="hidden" name="minimo" value="<?php echo $minimo; ?>"> <!-- Campo oculto para almacenar el valor mínimo del rango -->
                        <input type="hidden" name="maximo" value="<?php echo $maximo; ?>"> <!-- Campo oculto para almacenar el valor máximo del rango -->
                        <input type="hidden" name="numeroSecreto" value="<?php echo $numeroSecreto; ?>"> <!-- Campo oculto para almacenar el número secreto -->
                        <input type="submit" class="boton" name="adivinar" value="Enviar Adivinanzas"> <!-- Botón para enviar el formulario de adivinanzas -->
                    </form>
                </div>
            <?php endif; ?>
        <?php else : ?> <!-- Si hay un resultado definido, muestra el resultado final del juego -->
            <!-- Mostrar el resultado del juego -->
            <div class="caja">
                <h2><?php echo $resultado; ?></h2> <!-- Muestra el mensaje del resultado final -->
            </div>
            <div class="caja">
                <a href="/php/adivinaNumero.php" class="boton">Jugar de nuevo</a> <!-- Enlace para iniciar un nuevo juego -->
            </div>
        <?php endif; ?>
    </div>
</body>

</html>