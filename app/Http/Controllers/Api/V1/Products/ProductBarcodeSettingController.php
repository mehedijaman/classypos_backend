<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Products;

use ClassyPOS\Models\Products\ProductBarcodeSettings;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class ProductBarcodeSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductBarcodeSettings::paginate(15);
    }

    public function listTrash()
    {
        // view only trashed items
        return ProductBarcodeSettings::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($BarcodeSettings)
    {
        // Recover soft deleted items back to list
        ProductBarcodeSettings::withTrashed()->find($BarcodeSettings)->restore();
    }

    public function clearTrash($BarcodeSettings)
    {
        // Permanently Delete
        ProductBarcodeSettings::withTrashed()->find($BarcodeSettings)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $BarcodeSetting = ProductBarcodeSettings::create($request->all());

        return response()->json($BarcodeSetting, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductBarcodeSettings $BarcodeSettings)
    {
        return response()->json($BarcodeSettings, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductBarcodeSettings $BarcodeSettings)
    {
        $BarcodeSettings->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductBarcodeSettings $BarcodeSettings)
    {
        $BarcodeSettings->delete();
    }
}
