<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Users;

use ClassyPOS\Http\Controllers\Controller;
use ClassyPOS\Models\Users\User;
use ClassyPOS\Models\Users\UserRoleCategory;
use ClassyPOS\Models\Users\UserRole;
use Illuminate\Http\Request;

class RoleController extends Controller {

    /**
     * This code is a Technical debt.
     * should repay soon.
     * If technical debt is not repaid,
     * it can accumulate 'interest',
     * making it harder to implement changes later on.
     *
     * @mah3uz
     */
    public function test()
    {
        $role = UserRoleCategory::all();
        return $role;
    }
    public function newRole(Request $request)
    {
        $Category = new UserRoleCategory();
        $Category->RoleCategoryName = $request->RoleName;
        $Category->save();

        $ID = $Category->id;
        $AddedRole = UserRoleCategory::findOrFail($ID);

        return $AddedRole;
    }
    public function name($UserID)
    {
        $User = User::findOrFail($UserID);

        return $User;
    }
    public function previousRole($UserID)
    {
        $RoleCategoryID = [];
        $AllCategoryID = [];
        $UserRoleInformation = [];
        $Role = UserRole::where('UserID', '=', $UserID)->get();

        for ($i=0; $i<count($Role); $i++) {
            array_push($RoleCategoryID, $Role[$i]->RoleCategoryID);
        }
        $AllCategory = UserRoleCategory::all();
        for ($i=0; $i<count($AllCategory); $i++) {
            array_push($AllCategoryID, $AllCategory[$i]->id);
        }
        for ($i=0; $i<count($AllCategoryID); $i++) {
            $Check = 0;

            for ($j=0; $j<count($Role); $j++) {
                if ($RoleCategoryID[$j] == $AllCategoryID[$i]) {
                    $Check = 1;
                    break;
                }
            }

            if ($Check==1) {
                array_push($UserRoleInformation,1);
            }
            else
                array_push($UserRoleInformation,0);
        }

        return $UserRoleInformation;
    }

    public function assignRole($RoleID, $UserID)
    {

        $RoleCheck=UserRole::where('UserID', '=', $UserID)->where('RoleCategoryID', '=', $RoleID)->get();
        if (count($RoleCheck) == 0) {
            $Role = new UserRole();
            $Role->RoleCategoryID = $RoleID;
            $Role->UserID = $UserID;

            $Role->save();

            return "Successfully Changed";
        }
        else {
            $ID = $RoleCheck[0]->id;
            USerRole::findOrFail($ID)->forceDelete();
            return "Fahad";
        }
    }

    public function deleteRole($CategoryID)
    {
        UserRoleCategory::findOrFail($CategoryID)->delete();
        return response('Deleted');
    }

    public function permission($RoleName)
    {
        $UserID = auth()->user()->id;
        $Role = UserRoleCategory::where('RoleCategoryName', '=', $RoleName)->get();

        if (count($Role) == 0) {
            return "Role Found";
        }

        $RoleCategoryID = $Role[0]->id;
        $RoleInformation = UserRole::where('UserID', '=', $UserID)->where('RoleCategoryID', '=', $RoleCategoryID)->first();

        if (empty($RoleInformation)) {
            return "Empty Array";
        }
        
        return "Role Found";
    }

}