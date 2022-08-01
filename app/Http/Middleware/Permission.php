<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Constants;
use App\Models\PermissionModel;
use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Helpers\Token;
use Closure;


class Permission
{
  public function __construct(Request $request)
  {
    $constants = new Constants();
    $this->superAdmin = $constants->roleSuperAdmin();
    $this->excludedPermission = $constants->excludedPermission();
    $getTokenAuth = json_decode(Token::viewToken($request->header('Authorization')));
    $this->generateToken = $getTokenAuth->response->data;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $curentSlug = str_replace('api/', '', $request->path());
    $curentSlugSlice = (empty(strpos($curentSlug, '/'))) ? $curentSlug : explode('/', $curentSlug);
    if (count(explode('/', $curentSlug)) < 3) {
      $slug = is_array($curentSlugSlice) ? $curentSlugSlice[0] : $curentSlugSlice;
      $generateToken = json_decode(Token::viewToken($request->header('Authorization')));
      $permission = PermissionModel::get()
        ->where('id_role', $generateToken->response->data->role)
        ->where('slug', $slug)->first();

      $isSuperAdmin = $this->generateToken->role === $this->superAdmin->id;

      $condition = $isSuperAdmin
      || (!$isSuperAdmin && $curentSlug === 'permission')
      || (!$isSuperAdmin && $permission[is_array($curentSlugSlice) ? $curentSlugSlice[1] : 'view']);

      if ($condition)
        return $next($request);
      else 
        abort(404);
    }
  }
}
