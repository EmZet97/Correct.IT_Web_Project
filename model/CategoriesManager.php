<?php

require_once __DIR__.'/DatabaseConnector.php';

class CategoriesManager extends DatabaseConnector
{
    public function getCategories(){
        $stmt = $this->database->connect()->prepare('
            SELECT *
            FROM category
            ORDER BY category.id_category
            ');
            $stmt->execute();

            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $category) { 
                $cat = new Category($category["name"], $category["id_category"]);               
                $result[] = $cat;
            }
    
            if(isset($result))
                return $result;

            return null;
        
        
    }
}

class Category{
    private $name;
    private $id;

    public function __construct($name, $id){
        $this->name = $name;
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function getId(){
        return $this->id;
    }
}