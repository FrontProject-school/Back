<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {   
        try{
            $boolean = Auth::user()->hasRole($role);
            if(!$boolean){
                $report = response()->json([
                    'status' => false,
                    'message' => '해당 계정은 접근 권한이 없습니다...',
                ]);
                $request->merge(['role' => $report]);

                throw new \Exception($report,403);
            } else {
                if($boolean->original['role']==='general_admin'){
                    $message = '총관리자님이 확인 되었습니다!';
                } else {
                    $message = '관리자님이 확인 되었습니다!';
                }
                $report = response()->json([
                    'status' => true,
                    'role' => $boolean->original['role'],
                    'message' => $message,
                ]);
                $request->merge(['role' => $report]);
                return $next($request);
            }
        } catch (\Exception $e){
            // report($e);
            return $next($request);
        }
    
    }
}
