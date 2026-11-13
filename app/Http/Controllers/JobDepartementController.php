<?php

namespace App\Http\Controllers;

use App\Models\JobDepartement;
use Illuminate\Http\Request;

class JobDepartementController extends Controller
{
    //
    public function index() {
        return response()->json(JobDepartement::all(), 200);
    }
   
    public function show($id){
         $departement = JobDepartement::find($id);
         if(!$departement){
            return response()-> json([
                'message' => 'Job Department Not found'
            ],400);
         }

         return response()-> json([
            'message' => 'Job Departtement Found Successfully',
            'date' => $departement
         ],200);
    }

    public function store(Request $request){
        $validated = $request-> validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]); 

        $departement = JobDepartement::create($validated);
        return response()-> json([
            'message' => 'Job Department Created Successfully',
            'data' => $departement
        ],201);
    }

    public function update(Request $request, $id){
        $departement = JobDepartement::find($id);
        if(!$departement){
            return response()-> json([
                'message' => 'Job Department Not Found'
            ],404);
        }

        $validated = $request-> validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_departement,slug,'.$id,
        ]);

        $departement->update($validated);
        return response()-> json([
            'message' => 'Job Department Updated Successfully',
            'data' => $departement
        ],200);
    }
    public function destroy($id){
        $departement = JobDepartement::find($id);
        if(!$departement){
            return response()-> json([
                'message' => 'Job Department Not Found'
            ],404);
        }

        $departement->delete();
        return response()-> json([
            'message' => 'Job Department Deleted Successfully'
        ],200);
    }

}
