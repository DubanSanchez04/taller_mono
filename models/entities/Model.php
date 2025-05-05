<?php
namespace App\models\entities;
abstract class Model
{
    abstract function all();
    abstract function save();
    abstract function update();
    abstract function delete();
    public function get($nameProp)
    {
        return $this->{$nameProp};
    }
    public function set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}
