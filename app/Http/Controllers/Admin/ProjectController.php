<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    private $rules = [
        'name_project'=>['required', 'min:3', 'max:255'],
        'author'=>['required', 'min:3', 'max:255'],
        'type_id'=>['exists:types, id'],
        'image'=>['required', 'url:https'],
        'date'=>['required', 'date'],
        'linguaggio_usato'=>['required', 'min:3', 'max:255'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();
        $types = Type::all();
        return view('admin.projects.create', compact('project', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules);
        $project = Project::create($data);

        return redirect()->route('admin.projects.show', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project )
    {
        $data = $request->validate($this->rules);
        $project->update($data);

        return redirect()->route('admin.projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        
        return redirect()->route('admin.projects.index');
    }


    public function deletedIndex(){
        $projects = Project::onlyTrashed()->get();
        
        return view('admin.projects.deleted-index', compact('projects'));
    }

    public function deletedShow(string $id){

        $project = Project::withTrashed()->where('id', $id)->first();
        
        return view('admin.projects.deleted-show', compact('project'));
    }

    public function deletedRestore(string $id){
        $project = Project::withTrashed()->where('id', $id)->first();
        $project->restore();
    }

    public function deletedDestroy(string $id){

        $project = Project::withTrashed()->where('id', $id)->first();

       
        $project->forceDelete () ;
        return redirect () ->route ('admin.projects.deleted.index') ;    

    }

}
