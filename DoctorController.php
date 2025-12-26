<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DoctorsExport;
use App\Imports\DoctorsImport;
class DoctorController extends Controller
{
    public function index() {
        return view('doctors.index', [
            'doctors' => Doctor::latest()->get()
        ]);
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required']);
        Doctor::create($request->only('name'));
        return back()->with('success','Doctor added');
    }

    public function update(Request $request, Doctor $doctor) {
        $request->validate(['name' => 'required']);
        $doctor->update($request->only('name'));
        return back()->with('success','Doctor updated');
    }

    public function destroy(Doctor $doctor) {
        $doctor->delete();
        return back()->with('success','Doctor deleted');
    }

    public function export() {
        return Excel::download(new DoctorsExport, 'doctors.xlsx');
    }

   public function import(Request $request)
{
    Excel::import(new DoctorsImport, $request->file('file'));
    return back();
}

public function patients(Doctor $doctor)
{
    $patients = $doctor->patients()->latest()->get();

    return view('doctors.patients', compact('doctor','patients'));
}

}
