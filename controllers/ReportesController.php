<?php
namespace App\controllers;

require_once __DIR__.'/../models/entities/Reportes.php';
require_once __DIR__.'/../models/entities/Ingreso.php';
require_once __DIR__.'/../models/entities/Gasto.php';

class ReportesController {
    private $reporteModel;
    private $ingresoModel;
    private $gastoModel;

    public function __construct() {
        $this->reporteModel = new \App\models\entities\Reportes();
        $this->ingresoModel = new \App\models\entities\Ingreso();
        $this->gastoModel = new \App\models\entities\Gasto();
    }

    public function getReporteMensual($month, $year) {
        $reportId = $this->reporteModel->getOrCreate($month, $year)['id'];

        $this->ingresoModel->set('idReport', $reportId);
        $ingreso = $this->ingresoModel->all()[0] ?? null;

        $this->gastoModel->set('idReport', $reportId);
        $gastos = $this->gastoModel->all();

        $totalGastos = array_sum(array_column($gastos, 'value'));
        $ahorro = $ingreso ? ($ingreso['value'] - $totalGastos) : 0;
        $porcentajeAhorro = $ingreso ? ($ahorro / $ingreso['value'] * 100) : 0;

        return [
            'ingreso' => $ingreso,
            'gastos' => $gastos,
            'total_gastos' => $totalGastos,
            'ahorro' => $ahorro,
            'porcentaje_ahorro' => $porcentajeAhorro
        ];
    }
}