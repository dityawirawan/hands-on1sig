<?php
namespace App\Http\Controllers;

use App\Models\Marker;
use App\Models\Polygon;
use Illuminate\Http\Request;

class MapDataController extends Controller
{
    public function Latihan2(){
        return view('interactive');
    }
    public function index()
    {
        return view('tugas2new');
    }

    public function getMarkers()
    {
        return response()->json(Marker::all());
    }

    public function getPolygons()
    {
        return response()->json(Polygon::all());
    }

    public function storeMarker(Request $request)
    {
        $marker = Marker::create($request->all());
        return response()->json($marker);
    }

    public function storePolygon(Request $request)
    {
        $polygon = Polygon::create([
            'coordinates' => json_encode($request->coordinates),
        ]);
        return response()->json($polygon);
    }

    public function deleteMarker($id)
    {
        $marker = Marker::findOrFail($id);
        $marker->delete();
        return response()->json(['message' => 'Marker berhasil dihapus!']);
    }

    public function deletePolygon($id)
    {
        $polygon = Polygon::findOrFail($id);
        $polygon->delete();
        return response()->json(['message' => 'Polygon berhasil dihapus!']);
    }

    public function viewMarker($id)
    {
        $marker = Marker::findOrFail($id);
        return response()->json($marker);
    }

    public function viewPolygon($id)
    {
        $polygon = Polygon::findOrFail($id);
        return response()->json($polygon);
    }
}