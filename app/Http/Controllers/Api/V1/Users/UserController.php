<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Users;

use ClassyPOS\Http\Controllers\Controller;
use ClassyPOS\Models\Users\User;
use ClassyPOS\Models\Users\UserRoleCategory;
use Illuminate\Http\Request;

class UserController extends Controller {

    /**
     * This code is a Technical debt.
     * should repay soon.
     * If technical debt is not repaid,
     * it can accumulate 'interest',
     * making it harder to implement changes later on.
     *
     * @mah3uz
     */
    public function list()
    {
        $role = User::all();
        return $role;
    }
    public function newRole(Request $request)
    {
        $Category = new UserRoleCategory();
        $Category->RoleCategoryName = $request->RoleName;
        $Category->RoleRouteName = $request->RoleRoute;

        $Category->save();

        $ID = $Category->RoleCategoryID;
        $AddedRole = UserRoleCategory::findOrFail($ID);

        return $AddedRole;
    }

}