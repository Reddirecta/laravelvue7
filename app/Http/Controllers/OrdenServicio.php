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
        //
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
        $total = DB::table('taller.moto_aa_servicios')
        ->select('taller.moto_aa_servicios.id','taller.moto_aa_servicios.fecha','taller.moto_aa_servicios.folio','taller.moto_aa_vehiculos.modelo','taller.moto_aa_vehiculos.serie','taller.moto_aa_servicios.trabajo','taller.moto_aa_mecanicos.corto')
        ->leftjoin('taller.moto_aa_vehiculos','taller.moto_aa_servicios.vid','=','taller.moto_aa_vehiculos.id')
        ->leftjoin('taller.moto_aa_mecanicos','taller.moto_aa_servicios.mecanico_id','=','taller.moto_aa_mecanicos.id')
        ->where('taller.moto_aa_servicios.folio','LIKE',$request->buscar.'%')
        ->orWhere('taller.moto_aa_vehiculos.modelo','LIKE',$request->buscar.'%')
        ->orWhere('taller.moto_aa_vehiculos.serie','LIKE',$request->buscar.'%')
        ->count();
        $datos = DB::table('taller.moto_aa_servicios')
        ->select('taller.moto_aa_servicios.id','taller.moto_aa_servicios.fecha','taller.moto_aa_servicios.folio','taller.moto_aa_vehiculos.modelo','taller.moto_aa_vehiculos.serie','taller.moto_aa_servicios.trabajo','taller.moto_aa_mecanicos.corto')
        ->leftjoin('taller.moto_aa_vehiculos','taller.moto_aa_servicios.vid','=','taller.moto_aa_vehiculos.id')
        ->leftjoin('taller.moto_aa_mecanicos','taller.moto_aa_servicios.mecanico_id','=','taller.moto_aa_mecanicos.id')
        ->where('taller.moto_aa_servicios.folio','LIKE',$request->buscar.'%')
        ->orWhere('taller.moto_aa_vehiculos.modelo','LIKE',$request->buscar.'%')
        ->orWhere('taller.moto_aa_vehiculos.serie','LIKE',$request->buscar.'%')
        ->offset($offset)->limit($request->registrosporpagina)
        ->get();
        $salida = array();
        $salida['datos'] = $datos;
        $salida['totales'] = $total;
        echo json_encode($salida);
    }
}
