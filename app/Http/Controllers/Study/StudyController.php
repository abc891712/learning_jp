<?php

namespace App\Http\Controllers\Study;

use App\Http\Requests\ValidateWord;
use App\Http\Requests\ValidateWords;
use App\Japanese;
use App\UserJapanese;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
    public function store(ValidateWord $request)
    {
        if ($this->checkWordExist($request->japanese, $request->word)) {
            return response()->json([
                'word' => $request->word,
                'status'  => 422
            ],202);
        }

        Japanese::create([
            'japanese' => $request->japanese,
            'level'    => $request->level,
            'word'     => $request->word,
            'chinese'  => $request->chinese
        ]);

        return response($request);
    }

    public function show()
    {
        return view('study.learning.show');
    }

    public function update(ValidateWord $request)
    {
        $word = Japanese::find($request->id);

        $word->update($request->validated());

        return response()->json([
            'status' => 200,
            'data'=>$request->validated()
        ]);
    }

    public function destroy(Request $request)
    {
        UserJapanese::destroy($request->id);
    }

    public function import()
    {
        return view('study.learning.import');
    }

    public function save(ValidateWords $request)
    {
        $validated = $request->validated();

        foreach ($validated as $word) {
            if (!$this->checkWordExist($word['japanese'], $word['word'])) {
                Japanese::create([
                    'japanese' => $word['japanese'],
                    'level'    => $word['level']+1,
                    'word'     => $word['word'],
                    'chinese'  => $word['chinese'],
                ]);
            }
        }

        return response()->json([
            'datas' => $validated,
            'status' => 200
        ]);
    }

    public function checkWordExist($japanese,$word)
    {
        return Japanese::where('japanese', '=', $japanese)
                       ->where('word', '=', $word)->first();
    }

    public function list(Request $request)
    {
        $words="";

        if ($request->level == "12345") {
           $words = Japanese::selectRaw('japanese.id, japanese.japanese, japanese.level, japanese.word, japanese.chinese')
               ->paginate(8);
        }
        if ($request->level == "45"){
            $words = Japanese::selectRaw('japanese.id, japanese.japanese, japanese.level, japanese.word, japanese.chinese')
                ->whereIn('level', [4, 5])
                ->paginate(8);
        }
        if ($request->level == "3"){
            $words = Japanese::selectRaw('japanese.id, japanese.japanese, japanese.level, japanese.word, japanese.chinese')
                ->where('level', '=', 3)
                ->paginate(8);
        }
        if ($request->level == "12"){
            $words = Japanese::selectRaw('japanese.id, japanese.japanese, japanese.level, japanese.word, japanese.chinese')
                ->whereIn('level', [1, 2])
                ->paginate(8);
        }

        return response()->json([
            'datas' => $words,
            'status'=> 200,
        ]);
    }

    public function reserve(Request $request)
    {
        foreach ($request->datas as $japanese_id){
            if (!$this->checkIdExist($japanese_id)){
                UserJapanese::create([
                    'user_id'     => Auth::user()->id,
                    'japanese_id' => $japanese_id,
                ]);
            }

        }
    }

    public function checkIdExist($japanese_id)
    {
        return UserJapanese::where('user_id', '=', Auth::user()->id)
                           ->where('japanese_id', '=', $japanese_id)->first();
    }

    public function getUserNotes()
    {
        $userNotes = UserJapanese::selectRaw('japanese.japanese, japanese.level, japanese.word, japanese.chinese, user_japanese.id')
            ->leftjoin('japanese','user_japanese.japanese_id','=','japanese.id')
            ->paginate(8);

        return response()->json([
            'userNotes' => $userNotes,
            'status'    => 200
        ]);
    }
}
