<?php

namespace ClassyPOS\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use ClassyPOS\Models\Users\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
//use Auth;

class Role extends BaseMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard=null) {

        //$user=auth()->user();
        $user=JWTAuth::parseToken()->toUser();
        $UserID = $user->id;
        // if (Auth::guard($guard)->guest()) {
        //     if ($request->ajax() || $request->wantsJson()) {
        //         return response('Unauthorized.', 401);
        //     }
        //     //  else 
        //     // {
        //     //     return redirect()->guest('login');
        //     // }
        // }

        // if((Auth::guard($guard)->user()->admin)>=5)
        // {
        //     return $next($request);
        // }
        //$User=Auth::user()->name;
        //return response($User);

        $url=$request->path();
        return response($url);
        // $Name=auth()->user()->email;
        // return response('Alif');
        // $id = Auth::user()->name;
        // return response($id);
        //return response($url);

        $all=UserRoleCategory::where('RoleRouteName','=',$url)->get();

        if(sizeof($all)==0)
            return $next($request);

        $roleid= $all[0]->RoleCategoryID;
        //$id = Auth::user()->id;

        $Admin=Auth::user()->admin;

        if($url=="Sales" && $Admin==1)
          return $next($request);

        if($url=="Kitchen" && $Admin==3)
          return $next($request);

        //echo $id."<br>";


        $Access=UserRole::where('RoleCategoryID','=',$roleid)
                ->where('UserID','=',$id)
                ->get();


        if(sizeof($Access)==0)
        {
          //return "You Are not authorized";
          //return redirect('/Dashboard')->with('status', 'You are Not Authorized to Use This Option');

          return back()->with('status', 'You are Not Authorized to Use This Option');
        }
        else
        {

          return $next($request);
        }
        return $next($request);
    }

}