<?php namespace App\Http\Controllers\Admin;

use App\Entity\Scholarship;
use App\Entity\ScholarshipFile;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ScholarshipFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->validateWith([
            'scholarshipId' => 'required|entity:Scholarship',
            'category'      => 'required|entity:AccountFileCategory',
            'fileTypes.*'   => 'required|entity:AccountFileType',
            'description'   => 'required',
            'maxSize'       => 'required',
        ]);

        /** @var Scholarship $scholarship */
        $scholarship = \EntityManager::findById(Scholarship::class, $request->get('scholarshipId'));
        $scholarship->addScholarshipFile(new ScholarshipFile(
            $request->get('description'),
            $request->get('maxSize'),
            $request->get('category'),
            $request->get('fileTypes')
        ));

        \EntityManager::flush($scholarship);

        return \Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
