<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ResumeController extends Controller
{

    public function __construct()
    {
        // is necessary to be authentificated
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $resume = auth()->user()->resumes;
        // a better version of var_dump
        //dd($resume);
        return view('resumes.index', compact('resume'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = 'test';
        return view('resumes.create',['data'=>$data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();
        $resume = $user->resumes()->where('title', $request->title)->first();
        // validacion de titulo de forma manual
        if ($resume) {
            return back()->withErrors(['title' => 'You already have a resume with this title'])
            ->withInput(['title'=> $request->title]);
        }
        $resume = $user->resumes()->create([
            'title' => $request['title'],
            'name' => $user->name,
            'email' => $user->email,
            'about' => null
        ]);

        return redirect()->route('resumes.index')->with('alert', [
            'type' => 'primary',
            'message' => "$resume->title created successfully"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function show(Resume $resume)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function edit(Resume $resume)
    {
        //
        //dd($resume);
        return view('resumes.edit', compact('resume'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resume $resume)
    {
        //
        $data = $request->validate([
            'name' => 'required|string',
            'email' =>'required|email',
            'website' => 'nullable|url',
            'picture' => 'nullable|image',
            'about' => 'nullable|string',
            'title' => Rule::unique('resumes')
            ->where(fn ($query) => $query->where('user_id', $resume->user->id))
            ->ignore($resume->id)
        ]);

        if (array_key_exists('picture', $data)) {
            $picture = $data['picture']->store('pictures', 'public');
            // usar un tamanio estandar
            Image::make(public_path("storage/$picture"))->fit(800, 800)->save();
            $data['picture'] = $picture;
        }

        $resume->update($data);
        //dd($data);
        // redireccionar a la ruta con alert
        return redirect()->route('resumes.index')->with('alert', [
            'type' => 'success',
            'message' => "$resume->title updated successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Resume  $resume
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resume $resume)
    {
        //
    }
}
