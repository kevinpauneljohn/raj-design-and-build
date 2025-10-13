<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserWasAllowedToAccessProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $project_id = htmlspecialchars($request->segment(2));
        $project = Project::findOrFail($project_id);
        if(
            !\auth()->user()->hasRole('super admin') &&
            $project->users()->where('user_id', Auth::user()->id)->count() == 0 &&
            $project->user_id !== Auth::user()->id
        )
        {
            abort(404, 'Unauthorized action');
        }
        return $next($request);
    }
}
