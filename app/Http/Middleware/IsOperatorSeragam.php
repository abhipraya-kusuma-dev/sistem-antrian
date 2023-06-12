<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsOperatorSeragam
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if ($request->user()->role === 'op_seragam') return $next($request);
    if ($request->user()->role === 'op_bendahara') return redirect('/bendahara/antrian/belum');

    $getJenjangFromRole = strtolower(substr($request->user()->role, 3));

    return redirect('/operator/antrian/jenjang/' . $getJenjangFromRole . '/belum');

  }
}
