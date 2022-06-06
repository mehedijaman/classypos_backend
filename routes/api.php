<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/authtest', function () {
   return "Auth test work!";
})->middleware('jwt');

Route::get('/User/Role/Category/List','Api\V1\Users\RoleController@test');
Route::get('/User/Name/{UserID}','Api\V1\Users\RoleController@name');
Route::get('/User/Role/Assignment/{RoleID}/{UserID}','Api\V1\Users\RoleController@assignRole');
Route::get('/User/Role/Previous/{UserID}','Api\V1\Users\RoleController@previousRole');
Route::get('/User/List','Api\V1\Users\UserController@list');
Route::post('/User/Role/Category/New','Api\V1\Users\RoleController@newRole');
Route::get('/User/Role/Delete/{CategoryID}','Api\V1\Users\RoleController@deleteRole');
// Route::get('/UserRoleCategory','Api\V1\Users\RoleController@test');

Route::get('/logout','Api\V1\Auth\AuthController@logout');
Route::get('/UserInformation','Api\V1\Auth\AuthController@me');
Route::get('/User/Permission/{RoleName}','Api\V1\Users\RoleController@permission');


// Tenant Route
Route::group(['namespace' => 'Api\V1\System'], function () {
    Route::post('/Tenant/Register', 'RegisterCustomerController@createTenant')->name('register-customer');
    Route::post('/Tenant/Delete', 'RegisterCustomerController@deleteTenant')->name('delete-customer');
});

    /**************** Tenant Specific Route ******************/
    Route::group(['middleware' => 'enforce.tenancy'], function () {

        Route::group(['middleware' => 'jwt'], function () {
            // Protected routes
            Route::get('/home', 'HomeController@index')->name('home');
        });

        /********* Media URL ***********/
        Route::get('/media/{filename}', function ($filename) {
            return Storage::disk('tenant')->get("media/{$filename}");
        });
        Route::post('/media/upload', 'Api\V1\Others\FileUploadController@store');


        // JWT Auth ROUTE
        Route::group(['prefix' => 'auth', 'namespace' => 'Api\V1\Auth'], function () {

            Route::post('login',        'AuthController@login');
            Route::post('register',     'AuthController@register');
            Route::post('logout',       'AuthController@logout');
            Route::post('me',           'AuthController@me');

            Route::post('password/reset', 'ForgotPasswordController');
        });



        Route::middleware('auth:api')->get('/user', function (Request $request) {
            return $request->user();
        });

        Auth::routes();


        Route::group(['prefix' => 'V1'], function () {

            /******* Account Related Route *********/
            Route::group(['prefix' => 'Accounts', 'namespace' => 'Api\V1\Accounts'], function () {
                Route::get('/Account/List',                  'AccountController@listBank');
                Route::get('/Account/Details/{Account}',      'AccountController@showBank');
                Route::post('/Account/New',                  'AccountController@store');
                Route::put('/Account/Update/{Account}',       'AccountController@update');
                Route::post('/Account/Transaction',          'AccountController@accountTransaction');
            });
            /******* Bank Related Route *********/
            Route::group(['prefix' => 'Banks', 'namespace' => 'Api\V1\Banks'], function () {

                /********* Bank Route ********/
                Route::get('/Bank/List',                  'BankController@listBank');
                Route::get('/Bank/Details/{BankID}',      'BankController@showBank');
                Route::post('/Bank/New',                  'BankController@storeBank');
                Route::put('/Bank/Update/{BankID}',       'BankController@updateBank');
                Route::post('/Bank/Transaction',          'BankController@bankTransaction');

                Route::get('/Bank/Trash/List',              'BankController@listTrash');
                Route::delete('/Bank/Trash/New/{BankID}',   'BankController@destroyBank');
                Route::get('/Bank/Trash/Recover/{BankID}',  'BankController@restoreTrash');
                Route::get('/Bank/Trash/Clear/{BankID}',    'BankController@clearTrash');

                Route::get('/Bank/Ledger/List/{BankID}/{UserID}/{Status}/{FromPaymentDate}/{ToPaymentDate}/{FromDate}/{ToDate}', 'BankController@ledger');
                Route::get('/Bank/Filter/{ID}/{Name}/{IsDefault}/{Status}/{FromDate}/{ToDate}', 'BankController@filterBankList');

            });

            /******* Contact Related Route *********/
            Route::group(['prefix' => 'Contacts', 'namespace' => 'Api\V1\Contacts'], function () {

                /********* Contact Route ********/
                Route::get('/Contact/List',                     'ContactController@listContact');
                Route::get('/Contact/Details/{ContactID}',      'ContactController@showContact');
                Route::post('/Contact/New',                     'ContactController@storeContact');
                Route::post('/Contact/Transaction',             'ContactController@contactTransaction');
                Route::put('/Contact/Update/{ContactID}',       'ContactController@updateContact');

                Route::get('/Contact/Trash/List',                 'ContactController@listTrash');
                Route::delete('/Contact/Trash/New/{ContactID}',   'ContactController@destroyContact');
                Route::get('/Contact/Trash/Recover/{ContactID}',  'ContactController@restoreTrash');
                Route::get('/Contact/Trash/Clear/{ContactID}',    'ContactController@clearTrash');

                Route::get('/Contact/Filter/{ID}/{Phone}/{Email}/{Status}/{FromDate}/{ToDate}', 'ContactController@filterContactList');
                Route::get('/Vendor/List',           'ContactController@listVendors');
                Route::get('/Customer/List',           'ContactController@listCustomers');
                Route::get('/Contact/Ledger/List/{ContactID}/{UserID}/{IsApproved}/{Status}/{DueDateFrom}/{ToDueDate}/{PaymentDateFrom}/{ToPaymentDate}/{FromDate}/{ToDate}',    'ContactController@contactLedger');

            });


            /******* Employee Related Route *********/
            Route::group(['prefix' => 'Employees', 'namespace' => 'Api\V1\Employees'], function () {

                /********* Employee Route ********/
                Route::get('/Employee/List',                        'EmployeeController@listEmployee');
                Route::get('/Employee/Ledger/List/{EmployeeID}',                        'EmployeeController@ledgerListEmployee');
                Route::get('/Employee/Details/{employee}',          'EmployeeController@showEmployee');
                Route::post('/Employee/New',                        'EmployeeController@storeEmployee');
                Route::post('/Employee/Transaction',                'EmployeeController@employeeTransaction');
                Route::put('/Employee/Update/{employee}',           'EmployeeController@updateEmployee');
                Route::get('/Employee/Ledger/List/{EmployeeID}/{UserID}/{IsApproved}/{Status}/{PaymentDateFrom}/{ToPaymentDate}/{FromDate}/{ToDate}',    'EmployeeController@ledger');

                Route::get('/Employee/Trash/List',                  'EmployeeController@listTrash');
                Route::delete('/Employee/Trash/New/{employee}',     'EmployeeController@destroyEmployee');
                Route::get('/Employee/Trash/Recover/{EmployeeID}',  'EmployeeController@restoreTrash');
                Route::get('/Employee/Trash/Clear/{EmployeeID}',    'EmployeeController@clearTrash');

            });

            /******* Accounts Related Route *********/
            Route::group(['prefix' => 'Accounts', 'namespace' => 'Api\V1\Accounts\Expense'], function () {

                /********* Expense Route ********/
                Route::get('/Expense/List',                     'ExpenseController@listExpense');
                Route::get('/Expense/Details/{ExpenseID}',      'ExpenseController@showExpense');
                Route::post('/Expense/New',                     'ExpenseController@storeExpense');
                Route::put('/Expense/Update/{ExpenseID}',       'ExpenseController@updateExpense');

                Route::get('/Expense/Trash/List',                 'ExpenseController@listTrash');
                Route::delete('/Expense/Trash/New/{ExpenseID}',   'ExpenseController@destroyExpense');
                Route::get('/Expense/Trash/Recover/{ExpenseID}',  'ExpenseController@restoreTrash');
                Route::get('/Expense/Trash/Clear/{ExpenseID}',    'ExpenseController@clearTrash');

                Route::get('/Expense/Filter/{ID}/{Phone}/{Email}/{Status}/{FromDate}/{ToDate}', 'ExpenseController@filterExpenseList');

                /********* ExpenseCategory Route ********/
                Route::get('/Expense/Category/List',                                'ExpenseCategoryController@listExpenseCategory');
                Route::get('/Expense/Category/Details/{ExpenseCategoryID}',         'ExpenseCategoryController@showExpenseCategory');
                Route::post('/Expense/Category/New',                                'ExpenseCategoryController@storeExpenseCategory');
                Route::put('/Expense/Category/Update/{ExpenseCategoryID}',          'ExpenseCategoryController@updateExpenseCategory');

                Route::get('/Expense/Category/Trash/List',                         'ExpenseCategoryController@listTrash');
                Route::delete('/Expense/Category/Trash/New/{ExpenseCategoryID}',   'ExpenseCategoryController@destroyExpenseCategory');
                Route::get('/Expense/Category/Trash/Recover/{ExpenseCategoryID}',  'ExpenseCategoryController@restoreTrash');
                Route::get('/Expense/Category/Trash/Clear/{ExpenseCategoryID}',    'ExpenseCategoryController@clearTrash');

            });

            Route::group(['prefix' => 'Accounts', 'namespace' => 'Api\V1\Accounts\Income'], function () {
                /********* Income Route ********/
                Route::get('/Income/List',                    'IncomeController@listIncome');
                Route::get('/Income/Details/{IncomeID}',      'IncomeController@showIncome');
                Route::post('/Income/New',                    'IncomeController@storeIncome');
                Route::put('/Income/Update/{IncomeID}',       'IncomeController@updateIncome');

                Route::get('/Income/Trash/List',                'IncomeController@listTrash');
                Route::delete('/Income/Trash/New/{IncomeID}',   'IncomeController@destroyIncome');
                Route::get('/Income/Trash/Recover/{IncomeID}',  'IncomeController@restoreTrash');
                Route::get('/Income/Trash/Clear/{IncomeID}',    'IncomeController@clearTrash');

                Route::get('/Income/Filter/{ID}/{Phone}/{Email}/{Status}/{FromDate}/{ToDate}', 'IncomeController@filterIncomeList');

                /********* IncomeCategory Route ********/
                Route::get('/Income/Category/List',                               'IncomeCategoryController@listIncomeCategory');
                Route::get('/Income/Category/Details/{IncomeCategoryID}',         'IncomeCategoryController@showIncomeCategory');
                Route::post('/Income/Category/New',                               'IncomeCategoryController@storeIncomeCategory');
                Route::put('/Income/Category/Update/{IncomeCategoryID}',          'IncomeCategoryController@updateIncomeCategory');

                Route::get('/Income/Category/Trash/List',                        'IncomeCategoryController@listTrash');
                Route::delete('/Income/Category/Trash/New/{IncomeCategoryID}',   'IncomeCategoryController@destroyIncomeCategory');
                Route::get('/Income/Category/Trash/Recover/{IncomeCategoryID}',  'IncomeCategoryController@restoreTrash');
                Route::get('/Income/Category/Trash/Clear/{IncomeCategoryID}',    'IncomeCategoryController@clearTrash');
            });

            /******* Notice Related Route *********/
            Route::group(['prefix' => 'Notices', 'namespace' => 'Api\V1\Notices'], function () {
                /****** Notices Route *******/
                Route::get('/Notice/List',                       'NoticeController@listNotice');
                Route::get('/Notice/Details/{NoticeID}',         'NoticeController@showNotice');
                Route::post('/Notice/New',                       'NoticeController@storeNotice');
                Route::put('/Notice/Update/{NoticeID}',          'NoticeController@updateNotice');

                Route::get('/Notice/Trash/List',                'NoticeController@listTrash');
                Route::delete('/Notice/Trash/New/{NoticeID}',   'NoticeController@destroyNotice');
                Route::get('/Notice/Trash/Recover/{NoticeID}',  'NoticeController@restoreTrash');
                Route::get('/Notice/Trash/Clear/{NoticeID}',    'NoticeController@clearTrash');
            });


            /******* Product Related Route *********/
            Route::group(['prefix' => 'Products', 'namespace' => 'Api\V1\Products\Adjustments'], function () {
                /** Product Adjustment */
                Route::get('/Adjustment/List',                          'ProductAdjustmentController@index');
                Route::get('/Adjustment/Details/{AdjustmentID}',        'ProductAdjustmentController@show');
                Route::post('/Adjustment/New',                          'ProductAdjustmentController@store');
                Route::put('/Adjustment/Update/{AdjustmentID}',         'ProductAdjustmentController@update');

                Route::get('/Adjustment/Trash/List',                      'ProductAdjustmentController@listTrash');
                Route::delete('/Adjustment/Trash/New/{AdjustmentID}',     'ProductAdjustmentController@destroyCategory');
                Route::get('/Adjustment/Trash/Recover/{AdjustmentID}',    'ProductAdjustmentController@restoreTrash');
                Route::get('/Adjustment/Trash/Clear/{AdjustmentID}',      'ProductAdjustmentController@clearTrash');

                /** Product Adjustment Category */
                Route::get('/Adjustment/Category/List',                          'ProductAdjustmentCategoryController@index');
                Route::get('/Adjustment/Category/Details/{AdjustmentID}',        'ProductAdjustmentCategoryController@show');
                Route::post('/Adjustment/Category/New',                          'ProductAdjustmentCategoryController@store');
                Route::put('/Adjustment/Category/Update/{AdjustmentID}',         'ProductAdjustmentCategoryController@update');

                Route::get('/Adjustment/Category/Trash/List',                      'ProductAdjustmentCategoryController@listTrash');
                Route::delete('/Adjustment/Category/Trash/New/{AdjustmentID}',     'ProductAdjustmentCategoryController@destroyCategory');
                Route::get('/Adjustment/Category/Trash/Recover/{AdjustmentID}',    'ProductAdjustmentCategoryController@restoreTrash');
                Route::get('/Adjustment/Category/Trash/Clear/{AdjustmentID}',      'ProductAdjustmentCategoryController@clearTrash');
            });


            Route::group(['prefix' => 'Products', 'namespace' => 'Api\V1\Products'], function () {
                /** Product Barcode */
                Route::get('/Barcode/List',                             'ProductBarcodeSettingController@index');
                Route::get('/Barcode/Details/{BarcodeSettings}',        'ProductBarcodeSettingController@show');
                Route::post('/Barcode/New',                             'ProductBarcodeSettingController@store');
                Route::put('/Barcode/Update/{BarcodeSettings}',         'ProductBarcodeSettingController@update');

                Route::get('/Barcode/Trash/List',                         'ProductBarcodeSettingController@listTrash');
                Route::delete('/Barcode/Trash/New/{BarcodeSettings}',     'ProductBarcodeSettingController@destroy');
                Route::get('/Barcode/Trash/Recover/{BarcodeSettings}',    'ProductBarcodeSettingController@restoreTrash');
                Route::get('/Barcode/Trash/Clear/{BarcodeSettings}',      'ProductBarcodeSettingController@clearTrash');


                /********* Product Route ********/
                Route::get('/Product/List',                     'ProductController@listProduct');
                Route::get('/Product/Details/{ProductID}',      'ProductController@showProduct');
                Route::post('/Product/New',                     'ProductController@storeProduct');
                Route::put('/Product/Update/{ProductID}',       'ProductController@updateProduct');

                Route::post('/Product/New/Bulk',                'ProductController@bulkInsertProduct');

                Route::post('/Product/Distribute',              'ProductDistributeController@distributeProduct');
                Route::post('/Product/Return/Warehouse',        'ProductDistributeController@returnToWareHouse');

                Route::get('/Product/Trash/List',                 'ProductController@listTrash');
                Route::delete('/Product/Trash/New/{ProductID}',   'ProductController@destroyProduct');
                Route::get('/Product/Trash/Recover/{ProductID}',  'ProductController@restoreTrash');
                Route::get('/Product/Trash/Clear/{ProductID}',    'ProductController@clearTrash');

                Route::get('/Product/Filter/{CategoryID}/{VendorID}/{BrandID}/{InactiveProduct}/{FromDate}/{ToDate}', 'ProductController@filterProductList');
//                Route::get('/Product/Ledger/Filter/{ProductID}/{PurchaseID}/{WarehouseID}/{RackID}/{BatchNo}/{CategoryID}/{VendorID}/{BrandID}/{Status}/{FromDate}/{ToDate}', 'ProductController@filterProductLedger');
                Route::get('/Product/Ledger/{CategoryID}/{ContactID}/{FromDate}/{ToDate}', 'ProductController@filterProduct');
                Route::get('/Product/Ledger/Filter/{ProductID}/{PurchaseID}/{WarehouseID}/{RackID}/{BatchNo}/{Status}/{FromDate}/{ToDate}', 'ProductController@filterProductLedger');
                Route::get('/Product/Ledger/{ProductID}',    'ProductController@getProductLedgerByProductID');

                // Testing
                Route::get('/Product/Ledger/Test/{PRID}',    'ProductController@testLedger');
                Route::post('/Product/Ledger/Test',    'ProductController@ledgerTest');

                /********** Product Category Route **********/
                Route::get('/Category/List',                     'ProductCategoryController@listCategory');
                Route::get('/Category/Details/{CategoryID}',     'ProductCategoryController@showCategory');
                Route::post('/Category/New',                     'ProductCategoryController@storeCategory');
                Route::put('/Category/Update/{CategoryID}',      'ProductCategoryController@updateCategory');

                Route::get('/Category/Trash/List',                  'ProductCategoryController@listTrash');
                Route::delete('/Category/Trash/New/{CategoryID}',   'ProductCategoryController@destroyCategory');
                Route::get('/Category/Trash/Recover/{CategoryID}',  'ProductCategoryController@restoreTrash');
                Route::get('/Category/Trash/Clear/{CategoryID}',    'ProductCategoryController@clearTrash');

                /********** Product Brand Route **********/
                Route::get('/Brand/List',                       'BrandController@listBrand');
                Route::get('/Brand/Details/{BrandID}',          'BrandController@showBrand');
                Route::post('/Brand/New',                       'BrandController@storeBrand');
                Route::put('/Brand/Update/{BrandID}',           'BrandController@updateBrand');

                Route::get('/Brand/Trash/List',               'BrandController@listTrash');
                Route::delete('/Brand/Trash/New/{BrandID}',   'BrandController@destroyBrand');
                Route::get('/Brand/Trash/Recover/{BrandID}',  'BrandController@restoreTrash');
                Route::get('/Brand/Trash/Clear/{BrandID}',    'BrandController@clearTrash');

                /********** Product Rack Route **********/
                Route::get('/Rack/List',                       'ProductRackController@listProductRack');
                Route::get('/Rack/Details/{ProductRackID}',    'ProductRackController@showProductRack');
                Route::post('/Rack/New',                       'ProductRackController@storeProductRack');
                Route::put('/Rack/Update/{ProductRackID}',     'ProductRackController@updateProductRack');

                Route::get('/Rack/Trash/List',                      'ProductRackController@listTrash');
                Route::delete('/Rack/Trash/New/{ProductRackID}',    'ProductRackController@destroyProductRack');
                Route::get('/Rack/Trash/Recover/{ProductRackID}',   'ProductRackController@restoreTrash');
                Route::get('/Rack/Trash/Clear/{ProductRackID}',     'ProductRackController@clearTrash');

                /******* Tax Related Route ********/
                Route::get('/Tax/List',                      'TaxController@listTax');
                Route::get('/Tax/Details/{TaxID}',           'TaxController@showTax');
                Route::post('/Tax/New',                      'TaxController@storeTax');
                Route::put('/Tax/Update/{TaxID}',            'TaxController@updateTax');

                Route::get('/Tax/Trash/List',             'TaxController@listTrash');
                Route::delete('/Tax/Trash/New/{TaxID}',   'TaxController@destroyTax');
                Route::get('/Tax/Trash/Recover/{TaxID}',  'TaxController@restoreTrash');
                Route::get('/Tax/Trash/Clear/{TaxID}',    'TaxController@clearTrash');

                /******* Warehouse Related Route ********/
                Route::get('/Warehouse/List',                           'ProductWarehouseController@listProductWarehouse');
                Route::get('/Warehouse/Details/{ProductWarehouseID}',   'ProductWarehouseController@showProductWarehouse');
                Route::post('/Warehouse/New',                           'ProductWarehouseController@storeProductWarehouse');
                Route::put('/Warehouse/Update/{ProductWarehouseID}',    'ProductWarehouseController@updateProductWarehouse');

                Route::get('/Warehouse/Trash/List',                             'ProductWarehouseController@listTrash');
                Route::delete('/Warehouse/Trash/New/{ProductWarehouseID}',      'ProductWarehouseController@destroyProductWarehouse');
                Route::get('/Warehouse/Trash/Recover/{ProductWarehouseID}',     'ProductWarehouseController@restoreTrash');
                Route::get('/Warehouse/Trash/Clear/{ProductWarehouseID}',       'ProductWarehouseController@clearTrash');

            });

            /******* Purchase Related Route *********/
            Route::group(['prefix' => 'Purchase', 'namespace' => 'Api\V1\Purchase'], function () {

                // Purchase order routes
                Route::get('/Order/List',                       'PurchaseOrderController@listPurchaseOrder');
                Route::post('/Order/New',                       'PurchaseOrderController@storePurchaseOrder');
                Route::get('/Order/Details/{PurchaseOrder}',    'PurchaseOrderController@detailsPurchaseOrder');
                Route::put('/Order/Update/{PurchaseOrder}',     'PurchaseOrderController@updatePurchaseOrder');

                // Purchase Order Trash routes
                Route::get('/Order/Trash/List',                         'PurchaseOrderController@listTrash');
                Route::delete('/Order/Trash/New/{PurchaseOrder}',       'PurchaseOrderController@destroyPurchaseOrder');
                Route::get('/Order/Trash/Recover/{PurchaseOrderID}',    'PurchaseOrderController@restoreTrash');
                Route::get('/Order/Trash/Clear/{PurchaseOrderID}',      'PurchaseOrderController@clearTrash');

                // Purchase Receive routes
                Route::get('/Receive/List',                         'PurchaseReceiveController@purchaseReceiveList');
                Route::get('/Receive/List/Trash',                   'PurchaseOrderController@listTrash');
                Route::post('/Receive/New',                         'PurchaseReceiveController@purchaseReceiveStore');
                Route::get('/Receive/Details/{PurchaseID}',         'PurchaseReceiveController@purchaseReceiveDetails');

                // Purchase Receive trash routes
                Route::get('/Receive/Trash/List',                           'PurchaseReceiveController@listTrash');
                Route::delete('/Receive/Trash/New/{PurchaseReceive}',       'PurchaseReceiveController@destroyPurchaseReceive');
                Route::get('/Receive/Trash/Recover/{PurchaseReceiveID}',    'PurchaseReceiveController@restoreTrash');
                Route::get('/Receive/Trash/Clear/{PurchaseReceiveID}',      'PurchaseReceiveController@clearTrash');

                //Purchase return routes
                Route::get('/Return/List',                          'PurchaseReturnController@purchaseReturnList');
                Route::get('/Return/List/Trash',                    'PurchaseOrderController@listTrash');
                Route::post('/Return/New',                          'PurchaseReturnController@purchaseReturnStore');
                Route::get('/Return/Details/{PurchaseReturn}',      'PurchaseReturnController@purchaseReturnDetails');

                // Purchase return trash routes
                Route::get('/Return/Trash/List',                            'PurchaseReturnController@listTrash');
                Route::delete('/Return/Trash/New/{PurchaseReturn}',         'PurchaseReturnController@destroyPurchaseReturn');
                Route::get('/Return/Trash/Recover/{PurchaseReturnID}',      'PurchaseReturnController@restoreTrash');
                Route::get('/Return/Trash/Clear/{PurchaseReturnID}',        'PurchaseReturnController@clearTrash');


            });

            /******* Shop Related Route *********/
            Route::group(['prefix' => 'Shop', 'namespace' => 'Api\V1\Shops'], function () {

                /****** Shop CashDrawer Route *******/
                Route::get('/CashDrawer/List',                              'ShopCashDrawerController@listShopCashDrawer');
                Route::get('/CashDrawer/Details/{ShopCashDrawerID}',        'ShopCashDrawerController@showShopCashDrawer');
                Route::post('/CashDrawer/New',                              'ShopCashDrawerController@storeShopCashDrawer');
                Route::put('/CashDrawer/Update/{ShopCashDrawerID}',         'ShopCashDrawerController@updateShopCashDrawer');

                Route::get('/CashDrawer/Trash/List',                        'ShopCashDrawerController@listTrash');
                Route::delete('/CashDrawer/Trash/New/{ShopCashDrawerID}',   'ShopCashDrawerController@destroyShopCashDrawer');
                Route::get('/CashDrawer/Trash/Recover/{ShopCashDrawerID}',  'ShopCashDrawerController@restoreTrash');
                Route::get('/CashDrawer/Trash/Clear/{ShopCashDrawerID}',    'ShopCashDrawerController@clearTrash');


                /****** Shop Floor Route *******/
                Route::get('/Floor/List',                           'ShopFloorController@listShopFloor');
                Route::get('/Floor/Details/{ShopFloorID}',          'ShopFloorController@showShopFloor');
                Route::post('/Floor/New',                           'ShopFloorController@storeShopFloor');
                Route::put('/Floor/Update/{ShopFloorID}',           'ShopFloorController@updateShopFloor');

                Route::get('/Floor/Trash/List',                   'ShopFloorController@listTrash');
                Route::delete('/Floor/Trash/New/{ShopFloorID}',   'ShopFloorController@destroyShopFloor');
                Route::get('/Floor/Trash/Recover/{ShopFloorID}',  'ShopFloorController@restoreTrash');
                Route::get('/Floor/Trash/Clear/{ShopFloorID}',    'ShopFloorController@clearTrash');

                /****** Shop Route *******/
                Route::get('/Shop/List',                        'ShopController@listShop');
                Route::get('/Shop/Details/{ShopID}',            'ShopController@showShop');
                Route::post('/Shop/New',                        'ShopController@storeShop');
                Route::post('/Shop/Invoice/Update',             'ShopController@updateInvoiceSetting');
                Route::put('/Shop/Update/{ShopID}',             'ShopController@updateShop');

                Route::get('/Shop/Trash/List',              'ShopController@listTrash');
                Route::delete('/Shop/Trash/New/{ShopID}',   'ShopController@destroyShop');
                Route::get('/Shop/Trash/Recover/{ShopID}',  'ShopController@restoreTrash');
                Route::get('/Shop/Trash/Clear/{ShopID}',    'ShopController@clearTrash');

                /****** Shop Terminal Route *******/
                Route::get('/Terminal/List',                            'ShopTerminalController@listShopTerminal');
                Route::get('/Terminal/Details/{ShopTerminalID}',        'ShopTerminalController@showShopTerminal');
                Route::post('/Terminal/New',                            'ShopTerminalController@storeShopTerminal');
                Route::put('/Terminal/Update/{ShopTerminalID}',         'ShopTerminalController@updateShopTerminal');

                Route::get('/Terminal/Trash/List',                      'ShopTerminalController@listTrash');
                Route::delete('/Terminal/Trash/New/{ShopTerminalID}',   'ShopTerminalController@destroyShopTerminal');
                Route::get('/Terminal/Trash/Recover/{ShopTerminalID}',  'ShopTerminalController@restoreTrash');
                Route::get('/Terminal/Trash/Clear/{ShopTerminalID}',    'ShopTerminalController@clearTrash');

            });

            /***************** Sales Route *********************/
            Route::group(['prefix' => 'Sales', 'namespace' => 'Api\V1\Sales'], function () {
                Route::post('/Sale/New',                'SaleController@storeSale');
                Route::post('/Hold/New',                'SaleHoldController@store');
                Route::post('/Advance/New',             'SaleAdvanceController@store');
                Route::post('/Invoice/New',             'SaleInvoiceController@store');
                Route::get('/Invoice/List',            'SaleInvoiceController@index');
                Route::get('/Invoice/Details/{id}',    'SaleInvoiceController@show');
                Route::get('/Invoice/List/{IsPaid}/{from}/{to}',    'SaleInvoiceController@filterInvoice');



                /******************** Sale Quotation ************************/
                Route::get('/Quote/List',                               'SaleQuoteController@index');
                Route::get('/Quote/Details/{SaleQuoteID}',              'SaleQuoteController@show');
                Route::post('/Quote/New',                               'SaleQuoteController@store');
                Route::put('/Quote/Update/{SaleQuoteID}',               'SaleQuoteController@update');

                Route::get('/Quote/Trash/List',                         'SaleQuoteController@listTrash');
                Route::delete('/Quote/Trash/New/{SaleQuoteID}',         'SaleQuoteController@destroyShopTerminal');
                Route::get('/Quote/Trash/Recover/{SaleQuoteID}',        'SaleQuoteController@restoreTrash');
                Route::get('/Quote/Trash/Clear/{SaleQuoteID}',          'SaleQuoteController@clearTrash');


                /******************** Sale Quotation ************************/
                Route::get('/Order/List',                               'SaleOrderController@index');
                Route::get('/Order/Details/{SaleOrderID}',              'SaleOrderController@show');
                Route::post('/Order/New',                               'SaleOrderController@store');
                Route::put('/Order/Update/{SaleOrderID}',               'SaleOrderController@update');

                Route::get('/Order/Trash/List',                         'SaleOrderController@listTrash');
                Route::delete('/Order/Trash/New/{SaleOrderID}',         'SaleOrderController@destroyShopTerminal');
                Route::get('/Order/Trash/Recover/{SaleOrderID}',        'SaleOrderController@restoreTrash');
                Route::get('/Order/Trash/Clear/{SaleOrderID}',          'SaleQuoteController@clearTrash');
            });

        });
    });
