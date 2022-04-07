<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Facade\FlareClient\Http\Exceptions\NotFound;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class EquipmentController extends Controller
{

    public function __construct()
    {
        $this->middleware(
            'auth:api',
            [
                'except' => ['guestView']
            ]
        );
    }

    public function index(Request $request)
    {
        $equipments = Equipment::all();

        return response()->json([
            "message" => "200",
            "equipments" => $equipments
        ]);
    }

    public function guestView(Request $request, $id=null)
    {
        if(!$id)
            $equipments = Equipment::all('id','name','quantity');
        else
            $equipments = Equipment::all('id','name','quantity')->where('id', $id);

        if (!$equipments)
            return response()->json([
                "message" => "Not found",
                "status" => 404
            ]);
        else
        return response()->json([
            "message" => "200",
            "data" => $equipments
        ]);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function create(Request $request)
    {
        $inputs = json_decode($request->getContent());
        foreach ($inputs as $key => $value) {

            $equipment[$key] = $value;
        }
        try {
            $equipment = new Equipment($equipment);
            $equipment->save();
        } catch (Exception $exception) {
            return response()->json([
                "message" => $exception
            ]);
        }
        return response()->json([
            "message" => "Equipment added successfully",
            "status" => 201,
            "data" => $equipment
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function viewOrUpdate(Request $request, $id)
    {
        if ($request->getMethod() == "POST") {
            return response()->json([
                "message" => "Method not allowed",
                "status" => 500
            ]);
        }
        $equipment = Equipment::find($id);
        if (!$equipment)
            return response()->json([
                "message" => "Not found",
                "status" => 404
            ]);
        if ($request->getMethod() == "GET") {
            return response()->json([
                "data" => $equipment,
            ]);
        }
        $inputs = json_decode($request->getContent());
        foreach ($inputs as $key => $value) {

            $equipment[$key] = $value;
        }
        $equipment->save();
        return response()->json([
            "data" => $equipment,
            "status" => 200
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     *
     */
    public function destroy(Request $request, $id)
    {
        if ($request->getMethod() != "DELETE") {
            return response()->json([
                "message" => "Method not allowed",
                "status" => 500
            ]);
        }

        try {
            $equipment = Equipment::findOrFail($id);

            $equipment->delete();

            return response()->json([
                "message" => "Deleted successfully",
                "status" => 203
            ]);

        } catch (NotFound $exception) {
            return response()->json([
                "message" => $exception,
                "status" => 404
            ]);
        };
    }


}
