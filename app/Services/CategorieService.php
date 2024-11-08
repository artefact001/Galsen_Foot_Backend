<?php  

namespace App\Services;  

use App\Models\Categorie;  

class CategorieService  
{  
    public function getAllCategories()  
    {  
        return Categorie::all();  
    }  

    public function getCategoryById($id)  
    {  
        return Categorie::find($id);  
    }  

    public function createCategory(array $data)  
    {  
        return Categorie::create($data);  
    }  

    public function updateCategory($id, array $data)  
    {  
        $category = $this->getCategoryById($id);  
        $category->update($data);  
        return $category;  
    }  

    public function deleteCategory($id)  
    {  
        $category = $this->getCategoryById($id);  
        return $category->delete();  
    }  
}