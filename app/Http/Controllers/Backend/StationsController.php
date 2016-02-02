<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StationsController extends Controller
{
    public function index(Request $request)
    {
        $stations = \App\Station::with('tags');
        if($request->exists('tag_id')) {
            $stations = $stations->whereHas('tags', function($q) use($request) {
                $q->whereRaw('`tags`.`id` = ' . $request->get('tag_id', 0));
            });
        }
        if($request->user()->isAdmin()) {
            if($request->exists('user_id')) {
                $stations = $stations->where('user_id', $request->get('user_id', 0));
            }
            $stations = $stations->get();
        }
        else {
            $stations = $stations->where('user_id', $request->user()->id)->get();
        }
        return view('backend.stations.index', compact('stations'));
    }

    public function show(Request $request, $id) {
        $station = \App\Station::with('user', 'tags')->findOrFail($id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        $aerometric = $station->aerometrics()->latest()->first();
        return view('backend.stations.show', compact('station', 'aerometric'));
    }

    public function create(Request $request) {
        $selected_tags = old('tags', []);
        $tags = array_unique(array_merge($selected_tags, \App\Tag::get()->lists('name')->toArray()));
        return view('backend.stations.create', compact('tags', 'selected_tags'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => '',
            'unique_id' => 'required|unique:stations,unique_id',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $station = new \App\Station();
        $station->name = $request->input('name');
        $station->description = $request->input('description', '');
        $station->unique_id = $request->input('unique_id');
        $station->lat = $request->input('lat');
        $station->lng = $request->input('lng');
        $station->user_id = $request->user()->id;

        $station->save();
        $tags = [];
        foreach($request->get('tags', []) as $tag) {
            if($tag == "") continue;
            $t = \App\Tag::where('name', $tag)->first();
            if(!$t) {
                $t = new \App\Tag();
                $t->name = strtolower($tag);
                $t->save();
            }
            $tags[] = $t->id;
        }
        $station->tags()->sync(array_unique($tags));
        return redirect('backend/stations/' . $station->id);
    }

    public function edit(Request $request, $id) {
        $station = \App\Station::findOrFail($id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }
        $selected_tags = old('tags', $station->tags()->lists('name')->toArray());
        $tags = array_unique(array_merge($selected_tags, \App\Tag::get()->lists('name')->toArray()));

        return view('backend.stations.edit', compact('station', 'tags', 'selected_tags'));
    }

    public function update(Request $request, $id) {
        $station = \App\Station::findOrFail($id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }
        $this->validate($request, [
            'name' => 'required',
            'description' => '',
            'unique_id' => 'required|unique:stations,unique_id,' . $station->id,
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $station->name = $request->input('name');
        $station->description = $request->input('description', '');
        $station->unique_id = $request->input('unique_id');
        $station->lat = $request->input('lat');
        $station->lng = $request->input('lng');

        $station->save();

        $tags = [];
        foreach($request->get('tags', $station->tags()->lists('name')->toArray()) as $tag) {
            if($tag == "") continue;
            $t = \App\Tag::where('name', $tag)->first();
            if(!$t) {
                $t = new \App\Tag();
                $t->name = strtolower($tag);
                $t->save();
            }
            $tags[] = $t->id;
        }
        $station->tags()->sync(array_unique($tags));
        return redirect('backend/stations/' . $station->id);
    }

    public function destroy(Request $request, $id)
    {
        $station = \App\Station::findOrFail($id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }
        $station->delete();

        return redirect()->to('backend/stations');
    }

    public function getImport(Request $request, $id) {
        $station = \App\Station::findOrFail($id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        return view('backend.stations.import', compact('station'));
    }

    public function postImport(Request $request, $id) {
        $station = \App\Station::findOrFail($id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }
        $this->validate($request, [
            'file' => 'required|mimes:txt|max:5000',
        ]);

        $file = request()->file('file');

        $contents = file_get_contents($file->getPathname());
        $data = [];
        if($file->getClientOriginalExtension() == 'csv') {
            $lines = explode("\r\n", $contents);
            $array = array_map("str_getcsv", $lines);
            $header = array_shift($array);

            foreach($array as $v) {
                $data[] = array_combine($header, $v);
            }
        }
        else
        if($file->getClientOriginalExtension() == 'json') {
            $data = json_decode($contents, true);
        }
        foreach($data as $d) {
            $aerometric = new \App\Aerometric();
            $aerometric->station_id = $station->id;
            foreach(array_keys(config('aerometrics.properties')) as $property) {
                $aerometric->{$property} = array_get($d, $property, '0.0');
            }
            $aerometric->created_at = $aerometric->updated_at = array_get($d, 'created_at', (new \DateTime('now')));
            $aerometric->save();
        }

        return redirect('backend/stations/' . $station->id);
    }
}
