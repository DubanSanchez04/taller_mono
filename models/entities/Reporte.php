<?php

class Reporte {
    public $id, $month, $year;

    public function __construct($id, $month, $year) {
        $this->id = $id;
        $this->month = $month;
        $this->year = $year;
    }
}
