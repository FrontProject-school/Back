<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Freeboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment) {
        $this->comment = $comment;
    }

    public function index() {
        //
    }

    public function create() {
        //
    }

    public function store(Request $request) {
        // 유효성 검사
        $validator = Validator::make(request()->all(), [
            'parent_id' => 'required',
            'commentStory' => 'required|max:255'
        ]);
        if($validator->fails()){
            return redirect()->back();
        }
        else{
            Comment::create([
                'uId' => request()->uid,
                'category' => request()->category,
                'uName' => Auth::user()->name,
                'content' => request()->content
            ]);
            return redirect();
        }
    }

    public function show($id) {
        //
    }

    public function edit() {
        //
    }

    public function update(Request $request, $id) {
       //
    }

    public function destroy($id) {
        //
    }
}
