<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json(Company::all(), 200);
    }

    public function show($id)
    {
        $company = Company::find($id);
        if (! $company) {
            return response()->json([
                'message' => 'Company Not Found',
            ], 404);
        }

        return response()->json([
            'message' => 'Company Found Successfully',
            'data' => $company,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employer_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role', 'employer'),
            ],
            
            'name' => 'required|string|max:255',
            'logo_company' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'total_employee' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'status' => 'sometimes|required|string|in:pending,approved,rejected',
            'website' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('logo_company')) {
            $filePath = $request->file('logo_company')->store('company_logos', 'public');
            if ($filePath === false) {
                return response()->json([
                    'message' => 'Logo file could not be stored',
                ], 500);
            }

            $validated['logo_company'] = $filePath;
        }

        $company = Company::create($validated);

        return response()->json([
            'message' => 'Company Created Successfully',
            'data' => $company,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id);
        if (! $company) {
            return response()->json([
                'message' => 'Company Not Found',
            ], 404);
        }

        $validated = $request->validate([
            'employer_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('users', 'id')->where('role', 'employer'),
            ],
            'name' => 'sometimes|required|string|max:255',
            'logo_company' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'title' => 'nullable|string|max:255',
            'total_employee' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'status' => 'sometimes|required|string|in:pending,approved,rejected',
            'website' => 'nullable|url|max:255',
        ]);

        $previousLogoPath = null;

        if ($request->hasFile('logo_company')) {
            $filePath = $request->file('logo_company')->store('company_logos', 'public');
            if ($filePath === false) {
                return response()->json([
                    'message' => 'Logo file could not be stored',
                ], 500);
            }

            $previousLogoPath = $company->getRawOriginal('logo_company');
            $validated['logo_company'] = $filePath;
        }

        $company->update($validated);

        if ($previousLogoPath !== null) {
            Storage::disk('public')->delete($previousLogoPath);
        }

        return response()->json([
            'message' => 'Company Updated Successfully',
            'data' => $company,
        ], 200);
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        if (! $company) {
            return response()->json([
                'message' => 'Company Not Found',
            ], 404);
        }

        $company->delete();
        Storage::disk('public')->delete($company->getRawOriginal('logo_company'));

        return response()->json([
            'message' => 'Company Deleted Successfully',
        ], 200);
    }
}
