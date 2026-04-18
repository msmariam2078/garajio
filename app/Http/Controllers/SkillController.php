<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkillList;

class SkillController extends Controller
{
    public function index()
    {
        $skills = SkillList::all();
        return view('skill.index', compact('skills'));
    }
    public function create()
    {
        return view('skill.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'skill_name' => 'required',
                
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $note = new SkillList();
        $note->skill_name = $request->skill_name;
        $note->save();

        return redirect()->back()->with('success', __('Note successfully created.'));
    }


    public function edit($id)
    {
        $skill = SkillList::find($id);
        return view('skill.edit', compact('skill'));
    }


    public function update(Request $request, $id)
    {

        $validator = \Validator::make(
            $request->all(),
            [
                'skill_name' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $note = SkillList::find($id);
        $note->skill_name = $request->skill_name;
        $note->save();

        return redirect()->back()->with('success', __('Note successfully updated.'));
    }




    public function destroy($id)
    {
        $skill = SkillList::find($id);
        $skill->delete();
        return redirect()->back()->with('success', 'Note successfully deleted.');
    }
}
