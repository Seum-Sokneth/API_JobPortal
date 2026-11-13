<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(){
        return response()->json(Company::all(), 200);
    }

    public function show($id){
        $company = Company::find($id);
        if(!$company){
            return response()->json([
                'message' => 'Company Not Found'
            ],404);
        }

        return response()->json([
            'message' => 'Company Found Successfully',
            'data' => $company
        ],200);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'total_employee' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'status' => 'sometimes|required|string|in:pending,approved,rejected',
            'website' => 'nullable|url|max:255',
        ]);

        $company = Company::create($validated);

        return response()->json([
            'message' => 'Company Created Successfully',
            'data' => $company
        ],201);
    }

    public function update(Request $request, $id){
        $company = Company::find($id);
        if(!$company){
            return response()->json([
                'message' => 'Company Not Found'
            ],404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo_company' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'total_employee' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'status' => 'sometimes|required|string|in:pending,approved,rejected',
            'website' => 'nullable|url|max:255',
        ]);

        $company->update($validated);

        return response()->json([
            'message' => 'Company Updated Successfully',
            'data' => $company
        ],200);
    }

    public function destroy($id){
        $company = Company::find($id);
        if(!$company){
            return response()->json([
                'message' => 'Company Not Found'
            ],404);
        }

        $company->delete();

        return response()->json([
            'message' => 'Company Deleted Successfully'
        ],200);
    }

}
