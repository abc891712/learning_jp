<?php

namespace App\Http\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Closure;

class TransformExcel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasFile('excel'))
        {
            $path = $request->excel->path();
            $data = Excel::load($path)->all();
            $data = $data->toArray();

            if (!$data){
                response('excel 內沒東西');
            }
            $request->merge($data);
        }

        return $next($request);
    }
}
