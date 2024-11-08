<?php  

namespace App\Http\Controllers;  

use App\Http\Requests\CategorieRequest;  
use App\Services\CategorieService;  
use Illuminate\Http\JsonResponse;  

class CategorieController extends Controller  
{  
    protected $categorieService;  

    public function __construct(CategorieService $categorieService)  
    {  
        $this->categorieService = $categorieService;  
    }  

    public function index(): JsonResponse  
    {  
        $categories = $this->categorieService->getAllCategories();  
        return response()->json($categories, 200);  
    }  

    public function show($id): JsonResponse  
    {  
        $category = $this->categorieService->getCategoryById($id);  
        if (!$category) {  
            return response()->json(['message' => 'Category not found'], 404);  
        }  
        return response()->json($category, 200);  
    }  

    public function store(CategorieRequest $request): JsonResponse  
    {  
        $category = $this->categorieService->createCategory($request->validated());  
        return response()->json($category, 201);  
    }  

    public function update(CategorieRequest $request, $id): JsonResponse  
    {  
        $category = $this->categorieService->updateCategory($id, $request->validated());  
        return response()->json($category, 200);  
    }  

    public function destroy($id): JsonResponse  
    {  
        if ($this->categorieService->deleteCategory($id)) {  
            return response()->json(['message' => 'Category deleted successfully'], 200);  
        }  
        return response()->json(['message' => 'Category not found'], 404);  
    }  
}