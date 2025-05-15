<?php
namespace App\models\entities;
require_once __DIR__ . '/Model.php';
use App\models\drivers\ConexDB;

class Reportes extends Model {
    protected $db;
    protected $id;
    protected $month;
    protected $year;

    public function all()
    {
        $conexDb = new \App\models\drivers\ConexDB();
        $sql = "SELECT DISTINCT r.id, r.month, r.year, i.value as ingreso_value,SUM(b.value) as gastos_totalFROM reports rLEFT JOIN income i ON r.id = i.idReportLEFT JOIN bills b ON r.id = b.idReportGROUP BY r.id, r.month, r.year, i.value";
        $res = $conexDb->exeSQL($sql);
        $reportes = [];
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $reporte = new self();
                $reporte->set('id', $row['id']);
                $reporte->set('month', $row['month']);
                $reporte->set('year', $row['year']);
                $reporte->set('ingreso_value', $row['ingreso_value'] ?? 0);
                $reporte->set('gastos_total', $row['gastos_total'] ?? 0);
                $ahorro = ($row['ingreso_value'] ?? 0) - ($row['gastos_total'] ?? 0);
                $reporte->set('ahorro', $ahorro);
                array_push($reportes, $reporte);
            }
        }

        $conexDb->close();
        return $reportes;
    }

    public function save() {
        $query = $this->db->prepare(
            "INSERT INTO reports (month, year) VALUES (?, ?)"
        );
        return $query->execute([$this->month, $this->year]);
    }

    public function update() {
        $query = $this->db->prepare(
            "UPDATE reports SET month = ?, year = ? WHERE id = ?"
        );
        return $query->execute([$this->month, $this->year, $this->id]);
    }

    public function delete() {
        $query = $this->db->prepare("DELETE FROM reports WHERE id = ?");
        return $query->execute([$this->id]);
    }

    public function getByMonthYear($month, $year) {
        $query = $this->db->prepare(
            "SELECT * FROM reports WHERE month = ? AND year = ?"
        );
        $query->execute([$month, $year]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function getOrCreate($month, $year) {
        $report = $this->getByMonthYear($month, $year);
        if (!$report) {
            $this->set('month', $month);
            $this->set('year', $year);
            $this->save();
            return $this->getByMonthYear($month, $year);
        }
        return $report;
    }
}