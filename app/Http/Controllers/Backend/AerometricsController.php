<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AerometricsController extends Controller
{
    public function index(Request $request, $station_id)
    {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        $aerometrics = \App\Aerometric::where('station_id', $station_id)->latest()->get();

        return view('backend.aerometrics.index', compact('station', 'aerometrics'));
    }

    public function show(Request $request, $station_id, $aerometric_id) {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        $aerometric = \App\Aerometric::findOrFail($aerometric_id);

        return view('backend.aerometrics.show', compact('station', 'aerometric'));
    }

    public function create(Request $request, $station_id) {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        return view('backend.aerometrics.create', compact('station'));
    }

    public function store(Request $request, $station_id) {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        $aerometric = new \App\Aerometric();
        $aerometric->station_id = $station->id;
        foreach(array_keys(config('aerometrics.properties')) as $property) {
            $aerometric->{$property} = request()->input($property, '0.0');
        }
        $aerometric->save();

        return redirect('backend/stations/'. $station->id .'/aerometrics/' . $aerometric->id);
    }

    public function edit(Request $request, $station_id, $aerometric_id) {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }

        $aerometric = \App\Aerometric::findOrFail($aerometric_id);

        return view('backend.aerometrics.edit', compact('station', 'aerometric'));
    }

    public function update(Request $request, $station_id, $aerometric_id) {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }
        $aerometric = \App\Aerometric::findOrFail($aerometric_id);

        foreach(array_keys(config('aerometrics.properties')) as $property) {
            $aerometric->{$property} = request()->input($property, '0.0');
        }
        $aerometric->save();

        return redirect('backend/stations/'. $station->id .'/aerometrics/' . $aerometric->id);
    }

    public function destroy(Request $request, $station_id, $aerometric_id)
    {
        $station = \App\Station::findOrFail($station_id);
        if(!$request->user()->isAdmin() && $station->user_id != $request->user()->id) {
            return abort(403);
        }
        $aerometric = \App\Aerometric::findOrFail($aerometric_id);
        $aerometric->delete();

        return redirect()->to('backend/stations/' . $station->id . '/aerometrics');
    }
}
