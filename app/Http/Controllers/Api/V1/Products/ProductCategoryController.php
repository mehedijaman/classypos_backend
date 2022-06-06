<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductCategories;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    /**
     * ProductCategories manipulation
     * Create, Update, Find ProductCategories
     * return @void
     *
     * */

    public function listCategory()
    {
        return ProductCategories::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductCategories::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($ProductCategoryID)
    {
        // Recover soft deleted items back to list
        ProductCategories::withTrashed()->find($ProductCategoryID)->restore();
    }

    public function clearTrash($ProductCategoryID)
    {
        // Permanently Delete
        ProductCategories::withTrashed()->find($ProductCategoryID)->forceDelete();
    }


    public function showCategory(ProductCategories $CategoryID)
    {
        // View specific ProductCategories @param ProductCategories $CategoryID.

        return response()->json($CategoryID, 200);
    }

    public function storeCategory(Request $request)
    {
        $CategoryID = ProductCategories::create($request->all());

        return response()->json($CategoryID, 201);
    }

    public function updateCategory(Request $request, ProductCategories $CategoryID)
    {
        $CategoryID->update($request->all());

        return response()->json($CategoryID, 200);
    }

    public function destroyCategory(ProductCategories $CategoryID)
    {
        $CategoryID->delete();

        return response()->json(null, 204);
    }
}
