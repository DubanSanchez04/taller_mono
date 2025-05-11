<?php

namespace App\models\entities;
require_once __DIR__ . '/ModelG.php';

use App\models\drivers\ConexDB;

class Categoria extends ModelG
{
    protected $id = null;
    protected $name = null;
    protected $percentage = null;

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'percentage' => $this->percentage
        ];
    }

    public function save()
    {
        $db = new ConexDB();
        $sql = "INSERT INTO categories (name, percentage) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sd", $this->name, $this->percentage);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    }

    public function all()
    {
        $db = new ConexDB();
        $sql = "SELECT * FROM categories ORDER BY name";
        $result = $db->exeSQL($sql);
        $categorias = [];

        while ($row = $result->fetch_assoc()) {
            $categoria = new Categoria();
            $categoria->set('id', $row['id']);
            $categoria->set('name', $row['name']);
            $categoria->set('percentage', $row['percentage']);
            $categorias[] = $categoria;
        }

        return $categorias;
    }

    public function update()
    {
        $db = new ConexDB();
        $sql = "UPDATE categories SET name = ?, percentage = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sdi", $this->name, $this->percentage, $this->id);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    }

    public function delete()
    {
        $db = new ConexDB();
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    }

    public function find()
    {
        $db = new ConexDB();
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categoria = null;
        if ($row = $result->fetch_assoc()) {
            $categoria = new Categoria();
            $categoria->set('id', $row['id']);
            $categoria->set('name', $row['name']);
            $categoria->set('percentage', $row['percentage']);
        }
        
        $stmt->close();
        $db->close();
        return $categoria;
    }

    public function isLinkedToExpenses()
    {
        $db = new ConexDB();
        $sql = "SELECT COUNT(*) as count FROM bills WHERE idCategory = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        $db->close();
        
        return $row['count'] > 0;
    }
}