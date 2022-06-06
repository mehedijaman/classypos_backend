<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductBrands;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class BrandController extends Controller
{
    /**
     * Brand manipulation
     * Create, Update, Find Brand
     * return @void
     *
     * */

    public function listBrand()
    {
        return ProductBrands::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductBrands::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($BrandID)
    {
        // Recover soft deleted items back to list
        ProductBrands::withTrashed()->find($BrandID)->restore();
    }

    public function clearTrash($BrandID)
    {
        // Permanently Delete
        ProductBrands::withTrashed()->find($BrandID)->forceDelete();
    }


    public function showBrand(ProductBrands $BrandID)
    {
        // View specific Brand @param Brand $BrandID.

        return response()->json($BrandID, 200);
    }

    public function storeBrand(Request $request)
    {
        $BrandID = ProductBrands::create($request->all());

        return response()->json($BrandID, 201);
    }

    public function updateBrand(Request $request, ProductBrands $BrandID)
    {
        $BrandID->update($request->all());

        return response()->json($BrandID, 200);
    }

    public function destroyBrand(ProductBrands $BrandID)
    {
        $BrandID->delete();

        return response()->json(null, 204);
    }
}
