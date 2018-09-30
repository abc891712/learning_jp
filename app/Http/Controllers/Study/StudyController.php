<?php

namespace App\Http\Controllers\Study;

use App\Http\Requests\StoreWord;
use App\Japanese;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('study.layouts.template');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('study.learning.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWord $request)
    {
        $check = Japanese::where('japanese', '=', $request->japanese)->where('word', '=', $request->word)->first();

        if ($check){
            return response()->json([
                'word' => $check,
                'status'  => 202
            ],202);
        }

        $japanese = new Japanese();

        $japanese->japanese = $request->japanese;
        $japanese->level    = $request->level;
        $japanese->word     = $request->word;
        $japanese->chinese  = $request->chinese;

        $japanese->save();

        return response()->json([
            'message' => $request->all(),
            'status'  => 200
        ]);
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

    public function list()
    {

    }
}
