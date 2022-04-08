<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Mockery\Exception;

class EquipmentController extends Controller
{
    /**
     * EquipmentController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware(
            'auth:api',
            [
                'except' => ['guestView']
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function index(Request $request, $id=null)
    {
        if ($request->quantity) {
            $equipments = Equipment::all()->where('quantity', intval($request->quantity));

        } elseif (!$id && !$request->quantity) {
            $equipments = Equipment::all();
            var_dump(json_encode($equipments));

        } else {
            $equipments = Equipment::find($id);
        }

        return response()->json([
            "message" => "200",
            "equipments" => $equipments
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function guestView(Request $request, $id = null)
    {
        if ($request->quantity)
            $equipments = Equipment::all('id', 'name', 'quantity')->where('quantity', intval($request->quantity));
        elseif (!$id && !$request->quantity)
            $equipments = Equipment::all('id', 'name', 'quantity');
        else
            $equipments = Equipment::all('id', 'name', 'quantity')->where('id', $id);

        return response()->json(
            ["data"=> $id ? $equipments[0] : $equipments]
    );
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
        $equipment = Equipment::find($id);

        if (!$equipment)
            return response()->json([
                "message" => "Not found",
                "status" => 404
            ]);

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
            ]);
        }

        $equipment = Equipment::find($id);

        if (!$equipment)
            return response()->json([
                "message" => "Not found",
                "status" => 404
            ]);
        $equipment->delete();

        return response()->json([
            "message" => "Deleted successfully",
        ]);
    }
}
