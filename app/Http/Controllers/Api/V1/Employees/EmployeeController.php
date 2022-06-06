<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Employees;

use ClassyPOS\Models\Employees\Employee;
use ClassyPOS\Models\Employees\EmployeeBalance;
use ClassyPOS\Models\Employees\EmployeeLedger;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listEmployee()
    {
        return Employee::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return Employee::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($EmployeeID)
    {
        // Recover soft deleted items back to list
        Employee::withTrashed()->find($EmployeeID)->restore();
    }

    public function clearTrash($EmployeeID)
    {
        // Permanently Delete
        Employee::withTrashed()->find($EmployeeID)->forceDelete();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeEmployee(Request $request)
    {
        $Employee = Employee::create($request->all());

        $EmployeeLedger = new EmployeeLedger();
        $EmployeeLedger->UserID = $request->UserID;
        $EmployeeLedger->EmployeeID = $Employee->id;
        $EmployeeLedger->save();

        $EmployeeBalance = new EmployeeBalance();
        $EmployeeBalance->EmployeeID = $Employee->id;
        $EmployeeBalance->save();

        return response()->json($Employee, 201);
    }

    public function employeeTransaction(Request $request)
    {
        $EmployeeTransaction = new EmployeeLedger();
        $EmployeeTransaction->transaction($request);

        return response()->json("Transaction: OK", 201);
    }

    public function ledger($EmployeeID=0, $UserID=0, $IsApproved=0, $Status=0, $PaymentDateFrom=0, $ToPaymentDate=0, $FromDate=0, $ToDate=0)
    {
        $EmployeeLedger = new EmployeeLedger();
        $FilteredLedger = $EmployeeLedger->filterEmployeeLedger($EmployeeID, $UserID, $IsApproved, $Status, $PaymentDateFrom, $ToPaymentDate, $FromDate, $ToDate);

        return $FilteredLedger;
    }



    /**
     * Display the specified resource.
     *
     * @param  \ClassyPOS\Models\Employees\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function showEmployee(Employee $employee)
    {
        return response()->json($employee, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \ClassyPOS\Models\Employees\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function updateEmployee(Request $request, Employee $employee)
    {
        $employee->update($request->all());

        return response()->json("Updated", 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ClassyPOS\Models\Employees\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroyEmployee(Employee $employee)
    {
        $employee->delete();

        return response()->json(null, 204);
    }
    public function ledgerListEmployee($EmployeeID)
    {

        $EmployeeLedger = EmployeeLedger::where('EmployeeID','=',$EmployeeID)->leftjoin('employees','employees.id','=','employee_ledgers.EmployeeID')->select('employees.Name','employees.Code','employee_ledgers.Debit','employee_ledgers.Credit','employee_ledgers.Balance','employee_ledgers.created_at')->get();
        return $EmployeeLedger;
    }
}
