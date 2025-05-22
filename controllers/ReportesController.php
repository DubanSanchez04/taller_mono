<?php
require_once 'models/drivers/ConexDB.php';
require_once 'models/entities/Ingreso.php';
require_once 'models/entities/Gasto.php';
require_once 'models/entities/Categoria.php';

class ReportesController {

    public function verReporte($mes, $anio) {
        $db = new ConexDB();
        $conn = $db->getConexion();

        $mes = (int)$mes;
        $anio = (int)$anio;

        $stmt = $conn->prepare("SELECT id FROM reports WHERE month = ? AND year = ?");
        if (!$stmt) {
            die("Error en prepare reports: " . $conn->error);
        }
        $stmt->bind_param("ii", $mes, $anio);
        if (!$stmt->execute()) {
            die("Error en execute reports: " . $stmt->error);
        }

        if (!$stmt->bind_result($idReporte)) {
            die("Error en bind_result reports: " . $stmt->error);
        }
        $stmt->fetch();
        $stmt->close();

        if (!$idReporte) {
            echo "No hay datos para este mes.";
            return;
        }

        $stmt = $conn->prepare("SELECT value FROM income WHERE idReport = ?");
        if (!$stmt) {
            die("Error en prepare income: " . $conn->error);
        }
        $stmt->bind_param("i", $idReporte);
        if (!$stmt->execute()) {
            die("Error en execute income: " . $stmt->error);
        }
        if (!$stmt->bind_result($ingreso)) {
            die("Error en bind_result income: " . $stmt->error);
        }
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("
        SELECT b.value, c.name, c.percentage 
        FROM bills b 
        JOIN categories c ON b.idCategory = c.id 
        WHERE b.idReport = ?
    ");
        if (!$stmt) {
            die("Error en prepare bills: " . $conn->error);
        }
        $stmt->bind_param("i", $idReporte);
        if (!$stmt->execute()) {
            die("Error en execute bills: " . $stmt->error);
        }
        $result = $stmt->get_result();

        $detalles = [];
        $totalGastos = 0;

        while ($row = $result->fetch_assoc()) {
            $gasto = $row['value'];
            $categoria = $row['name'];
            $limite = $row['percentage'];
            $exceso = $gasto > (($limite / 100) * $ingreso);

            $detalles[] = [
                'categoria' => $categoria,
                'gasto' => $gasto,
                'limite' => $limite,
                'exceso' => $exceso
            ];

            $totalGastos += $gasto;
        }

        $ahorro = $ingreso - $totalGastos;
        $porcentajeAhorro = $ingreso > 0 ? ($ahorro / $ingreso) * 100 : 0;

        include 'views/Reportes.php';
    }

}
