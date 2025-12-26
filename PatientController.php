<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PatientsImport;
use App\Exports\PatientsExport;

class PatientController extends Controller
{

public function store(Request $request, $doctorId)
{
    $request->validate([
        'name' => 'required',
        'ic_no' => 'required',
        'handphone_no' => 'required'
    ]);

    Patient::create([
        'name' => $request->name,
        'ic_no' => $request->ic_no,
        'handphone_no' => $request->handphone_no,
        'doctor_id' => $doctorId
    ]);

    return back()->with('success', 'Patient added successfully');
}
public function import(Request $request, $doctorId)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new PatientsImport($doctorId), $request->file('file'));

    return back()->with('success', 'Patients imported successfully');
}

public function update(Request $request, Patient $patient)
{
    $request->validate([
        'name' => 'required',
        'ic_no' => 'required',
        'handphone_no' => 'required',
    ]);

    $patient->update([
        'name' => $request->name,
        'ic_no' => $request->ic_no,
        'handphone_no' => $request->handphone_no,
    ]);

    return back()->with('success', 'Patient updated successfully');
}

public function destroy(Patient $patient)
{
    $patient->delete();
    return back()->with('success', 'Patient deleted successfully');
}
public function bulkDelete(Request $request)
{
    $ids = $request->ids;
    Patient::whereIn('id', $ids)->delete();
    return response()->json(['success' => true]);
}

public function export($doctorId)
{
    return Excel::download(
        new PatientsExport($doctorId),
        'patients.xlsx'
    );
}

}
