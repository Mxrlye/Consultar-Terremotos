<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener fechas del formulario
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Asegurarse de que las fechas están en el formato correcto y agregar horas de inicio y fin del día
    $start_date .= "T00:00:00";
    $end_date .= "T23:59:59";

    // URL de la API de USGS
    $url = "https://earthquake.usgs.gov/fdsnws/event/1/query?starttime=$start_date&endtime=$end_date&minmagnitude=4.5&format=geojson";
    
    // Imprimir la URL para depuración
    echo "<p>URL de consulta: <a href=\"$url\" target=\"_blank\">$url</a></p>";

    // Realizar la consulta a la API
    $response = file_get_contents($url);

    // Comprobar si se obtuvo respuesta
    if ($response === FALSE) {
        die("Error al consultar la API.");
    }

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    // Imprimir resultados
    echo "<h1>Resultados de la Consulta</h1>";
    if (isset($data['features']) && count($data['features']) > 0) {
        echo "<ul>";
        foreach ($data['features'] as $feature) {
            $properties = $feature['properties'];
            echo "<li>";
            echo "Magnitud: " . $properties['mag'] . "<br>";
            echo "Ubicación: " . $properties['place'] . "<br>";
            echo "Fecha: " . date('Y-m-d H:i:s', $properties['time'] / 1000) . "<br>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "No se encontraron terremotos en el rango de fechas seleccionado.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
