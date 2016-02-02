<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    public function index()
    {
        $configs = \App\Config::get();
        return view('backend.configs.index', compact('configs'));
    }

    public function show(Request $request, $id) {
        $config = \App\Config::findOrFail($id);
        return view('backend.configs.show', compact('config'));
    }

    public function create(Request $request) {
        return view('backend.configs.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'key' => 'required|unique:configs,key',
            'value' => 'required'
        ]);

        $config = new \App\Config();
        $config->key = strtolower($request->input('key'));
        $config->value = $request->input('value');
        $config->save();
        return redirect('backend/configs' . $config->id);
    }

    public function edit(Request $request, $id) {
        $config = \App\Config::findOrFail($id);
        return view('backend.configs.edit', compact('config'));
    }

    public function update(Request $request, $id) {
        $config = \App\Config::findOrFail($id);
        $this->validate($request, [
            'key' => 'required|unique:configs,key,' . $config->id,
            'value' => 'required'
        ]);
        $config->key = strtolower($request->input('key'));
        $config->value = $request->input('value');
        $config->save();
        return redirect('backend/configs/' . $config->id);
    }

    public function destroy(Request $request, $id)
    {
        $config = \App\Config::findOrFail($id);
        $config->delete();

        return redirect()->to('backend/configs');
    }
}
