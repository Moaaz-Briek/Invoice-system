<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:الاقسام', ['only' => ['index']]);
        $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
        $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
        $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }

    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
        ],[
            'section_name.required' => 'يرجى إدخال اسم القسم.',
            'section_name.unique' => 'اسم القسم موجود مسبقاً',
        ]);
        Section::create([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'Created_By' => Auth::User()->name,
        ]);
        session()->flash("Add", 'تمت إضافة القسم بنجاح');
        return redirect('/sections');

    }

    public function show(Section $section)
    {
        //
    }

    public function edit(Section $section)
    {
        //
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255'.$id,
        ],[
            'section_name.required' => 'يرجى إدخال اسم القسم.',
            'section_name.unique' => 'اسم القسم موجود مسبقاً',
        ]);
        Section::where('id', $request->id)->update([
           'section_name' => $request->section_name,
           'description' => $request->description,
        ]);
        session()->flash('edit', 'تم تعديل القسم بنجاح');
        return redirect('/sections');
    }

    public function destroy(Request $request)
    {
        Section::where('id', $request->id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
