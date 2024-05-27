<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ProjectController extends Controller
{
    const GIT = 'https://api.github.com/search/repositories';

    public function index(Request $request)
    {

        $filter = $request->get('search');

        $projects = Project::query()
            ->whereJsonContains('info->name', $filter)
            ->orWhere('subject', 'LIKE', '%' . $filter . '%')
            ->paginate(config('app.pagination.perpage'))->withQueryString();

        if ($projects->isEmpty()) {
            $this->search($filter);
        }

        return View::make('welcome', ['projects' => $projects]);
    }

    public function search($filter)
    {
        $response = Http::get(self::GIT, ['q' => $filter]);
        $response = json_decode($response->body());

        if ($response->total_count != 0) {
            foreach ($response->items as $item) {
                $projects = Project::create(['subject' => $filter, 'info' => $item]);
            }
            $projects->refresh();
        }
        return redirect(url()->current());
    }
}
