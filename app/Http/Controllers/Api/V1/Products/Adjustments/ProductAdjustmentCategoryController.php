<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products\Adjustments;

use ClassyPOS\Models\Products\Adjustments\ProductAdjustmentCategories;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductAdjustmentCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AdjustmentCategories = ProductAdjustmentCategories::paginate(15);

        return response()->json($AdjustmentCategories, 200);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductAdjustmentCategories::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($AdjustmentCategoryID)
    {
        // Recover soft deleted items back to list
        ProductAdjustmentCategories::withTrashed()->find($AdjustmentCategoryID)->restore();
    }

    public function clearTrash($AdjustmentCategoryID)
    {
        // Permanently Delete
        ProductAdjustmentCategories::withTrashed()->find($AdjustmentCategoryID)->forceDelete();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $AdjustmentCategory = ProductAdjustmentCategories::create($request->all());

        return response()->json($AdjustmentCategory, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductAdjustmentCategories $AdjustmentCategoryID)
    {
        return response()->json($AdjustmentCategoryID, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json(ProductAdjustmentCategories::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductAdjustmentCategories $AdjustmentCategoryID)
    {
        $AdjustmentCategoryID->update($request->all());

        return response()->json($AdjustmentCategoryID, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAdjustmentCategories $AdjustmentCategoryID)
    {
        $AdjustmentCategoryID->delete();

        return response()->json(null, 204);
    }
}
