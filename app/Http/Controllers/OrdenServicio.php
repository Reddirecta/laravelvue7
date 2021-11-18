<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class OrdenServicio extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        DB::table('motochec_taller.moto_aa_servicios')
        ->where('id',$id)
        ->update(['trabajo' => $request->trabajo,'mecanico_id' => $request->mid]);
        return $request->mid;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getdatostablas(Request $request){
        $request->pagina--;
        $offset = $request->pagina*$request->registrosporpagina;
        $request->buscar = (trim($request->buscar) == "" || trim($request->buscar) == "null") ? "" : trim($request->buscar);
        $total = DB::table('motochec_taller.moto_aa_servicios')
        ->select('motochec_taller.moto_aa_servicios.id','motochec_taller.moto_aa_servicios.fecha','motochec_taller.moto_aa_servicios.folio','motochec_taller.moto_aa_vehiculos.modelo','motochec_taller.moto_aa_vehiculos.serie','motochec_taller.moto_aa_servicios.trabajo','motochec_taller.moto_aa_mecanicos.corto')
        ->leftjoin('motochec_taller.moto_aa_vehiculos','motochec_taller.moto_aa_servicios.vid','=','motochec_taller.moto_aa_vehiculos.id')
        ->leftjoin('motochec_taller.moto_aa_mecanicos','motochec_taller.moto_aa_servicios.mecanico_id','=','motochec_taller.moto_aa_mecanicos.id')
        ->where('motochec_taller.moto_aa_servicios.folio','LIKE',$request->buscar.'%')
        ->orWhere('motochec_taller.moto_aa_vehiculos.modelo','LIKE',$request->buscar.'%')
        ->orWhere('motochec_taller.moto_aa_vehiculos.serie','LIKE',$request->buscar.'%')
        ->count();
        $datos = DB::table('motochec_taller.moto_aa_servicios')
        ->select('motochec_taller.moto_aa_servicios.id','motochec_taller.moto_aa_servicios.fecha','motochec_taller.moto_aa_servicios.folio','motochec_taller.moto_aa_vehiculos.modelo','motochec_taller.moto_aa_vehiculos.serie','motochec_taller.moto_aa_servicios.trabajo','motochec_taller.moto_aa_mecanicos.corto','motochec_taller.moto_aa_mecanicos.id AS mid')
        ->leftjoin('motochec_taller.moto_aa_vehiculos','motochec_taller.moto_aa_servicios.vid','=','motochec_taller.moto_aa_vehiculos.id')
        ->leftjoin('motochec_taller.moto_aa_mecanicos','motochec_taller.moto_aa_servicios.mecanico_id','=','motochec_taller.moto_aa_mecanicos.id')
        ->where('motochec_taller.moto_aa_servicios.folio','LIKE',$request->buscar.'%')
        ->orWhere('motochec_taller.moto_aa_vehiculos.modelo','LIKE',$request->buscar.'%')
        ->orWhere('motochec_taller.moto_aa_vehiculos.serie','LIKE',$request->buscar.'%')
        ->offset($offset)->limit($request->registrosporpagina)
        ->orderByDesc('motochec_taller.moto_aa_servicios.fecha')
        ->orderByDesc('motochec_taller.moto_aa_servicios.folio')
        ->get();
        $salida = array();
        $salida['datos'] = $datos;
        $salida['totales'] = $total;
        echo json_encode($salida, JSON_UNESCAPED_UNICODE);
    }

    public function getMecanicos(){
        $datos = DB::table('motochec_taller.moto_aa_mecanicos')
        ->select('id','corto')
        ->whereNotNull('corto')
        ->orderBy('corto')
        ->get();
        $salida = $datos->toArray();
        echo json_encode($salida);
    }
}
