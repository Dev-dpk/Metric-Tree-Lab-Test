<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Note;
use App\Http\Resources\NoteResource;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return NoteResource::collection(Note::where('user_id',$request->user()->id)->with('user')->paginate(25));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = Note::create([
            'user_id' => $request->user()->id,
            'user_profile_id' => $request->user()->profile->id,
            'note' => $request->note,
            'file' => $request->file,
        ]);

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note=Note::findOrFail($id);
        return new NoteResource($note);
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
        $note=Note::findOrFail($id);
        // check if currently authenticated user is the owner of the book
        if ($request->user()->id !== $note->user_id) {
            return response()->json(['error' => 'You can only edit your own notes.'], 403);
        }

        $note->update($request->only(['note', 'file']));

        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note=Note::findOrFail($id);
        $note->delete();

        return response()->json(null, 204);
    }
}
