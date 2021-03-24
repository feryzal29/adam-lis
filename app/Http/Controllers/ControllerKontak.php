<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ControllerKontak extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
            $data = \App\Kontak::all();

        if(count($data) > 0){ //mengecek apakah data kosong atau tidak
            $res['message'] = "Success!";
            $res['values'] = $data;
            return response($res);
        }
        else{
            $res['message'] = "Empty!";
            return response($res);
        }
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
        $input = $request->all();
        $no_reg=$request->input('no_registrasi');
        $no_lab=$request->input('no_laboratorium');
        $w_reg=$request->input('waktu_registrasi');
        $umur_thn=$request->input('umur.tahun');
        $umur_bln=$request->input('umur.bulan');
        $umur_hr=$request->input('umur.hari');
        $psn_alt=$request->input('pasien.alamat');
        $psn_nm=$request->input('pasien.nama_pasien');
        $psn_rm=$request->input('pasien.no_rm');
        $psn_klm=$request->input('pasien.jenis_kelamin');
        $psn_lahir=$request->input('pasien.tanggal_lahir');
        $psn_tlp=$request->input('pasien.no_telphone');
        $dr_kd=$request->input('dokter_pengirim.kode');
        $dr_nm=$request->input('dokter_pengirim.nama');
        $unit_kd=$request->input('unit_asal.kode');
        $unit_nama=$request->input('unit_asal.nama');
        $pj_nm=$request->input('penjamin.nama');
        $pj_kd=$request->input('penjamin.kode');
        $dga_awal=$request->input('diagnosa_awal');
      
        $cek=DB::table('h_registrasi')->where('no_lab',$no_lab)->get();
        if(count($cek)>0){
            $res['success'] = false;
            $res['message'] = "Data gagal disimpan karena no laboratorium sudah ada";
            return response($res);
        }else{
            DB::beginTransaction();
            try {
                $registrasi=DB::table('h_registrasi')
                    ->insert([
                        'no_reg_rs' => $no_reg,
                        'no_lab' => $no_lab,
                        'waktu_registrasi' => $w_reg,
                        'pasien_umur_tahun' => $umur_thn,
                        'pasien_umur_bulan' => $umur_bln,
                        'pasien_umur_hari' => $umur_hr,
                        'pasien_alamat' => $psn_alt,
                        'pasien_nama' => $psn_nm,
                        'pasien_no_rm' => $psn_rm,
                        'pasien_jenis_kelamin' => $psn_klm,
                        'pasien_tanggal_lahir' => $psn_lahir,
                        'pasien_no_telphone' => $psn_tlp,
                        'dokter_pengirim_kode' => $dr_kd,
                        'dokter_pengirim_nama' => $dr_nm,
                        'unit_asal_kode' => $unit_kd,
                        'unit_asal_nama' => $unit_nama,
                        'penjamin_nama' => $pj_nm,
                        'penjamin_kode' => $pj_kd,
                        'diagnosa_awal' => $dga_awal
                    ]);
            $periksa=$request->input('pemeriksaan');
            //$jum=count($periksa);
            foreach($input['pemeriksaan'] as $item){
                $tindakan=DB::table('h_item_pemeriksaan')
                        ->insert([
                            'h_registrasi_no_lab' => $no_lab,
                            'kategori_pemeriksaan_nama' => $item['kategori_pemeriksaan']['nama_kategori'],
                            'kategori_pemeriksaan_no_urut' => $item['kategori_pemeriksaan']['nomor_urut'],
                            'sub_kategori_pemeriksaan_nama' => $item['sub_kategori_pemeriksaan']['nama_sub_kategori'],
                            'sub_kategori_pemeriksaan_no_urut' => $item['sub_kategori_pemeriksaan']['nomor_urut'],
                            'item_pemeriksaan_no_urut' => $item['nomor_urut'],
                            'item_pemeriksaan_kode' => $item['kode_pemeriksaan'],
                            'item_pemeriksaan_nama' => $item['nama_pemeriksaan'],
                            'item_pemeriksaan_metode' => $item['metode'],
                            'item_pemeriksaan_satuan' => $item['hasil']['satuan'],
                            'hasil_pemeriksaan' => $item['hasil']['nilai_hasil'],
                            'nilai_rujukan' => $item['hasil']['nilai_rujukan'],
                            'flag_kode' => $item['hasil']['flag_kode'],
                        ]);
            }
                try {
                    $get_periksa_lab = DB::table('h_item_pemeriksaan')
                            ->select('h_registrasi.no_lab','h_registrasi.no_reg_rs','maping_lab_adamlabs.id_template','jns_perawatan_lab.kd_jenis_prw',
                            'h_item_pemeriksaan.hasil_pemeriksaan','waktu_registrasi AS tanggal')
                            ->join('h_registrasi','h_registrasi.no_lab','=','h_item_pemeriksaan.h_registrasi_no_lab')
                            ->join('maping_lab_adamlabs','maping_lab_adamlabs.id_item_adam','=','h_item_pemeriksaan.item_pemeriksaan_kode')
                            ->join('template_laboratorium','template_laboratorium.id_template','=','maping_lab_adamlabs.id_template')
                            ->join('jns_perawatan_lab','jns_perawatan_lab.kd_jenis_prw','=','template_laboratorium.kd_jenis_prw')
                            ->where('h_item_pemeriksaan.h_registrasi_no_lab','like','%'.$no_lab.'%')
                            ->distinct()
                            ->get();
                    foreach($get_periksa_lab as $get_periksa){
                        $input = array();
                        $input['no_lab'] = $get_periksa->no_lab;
                        $input['no_rawat'] = $get_periksa->no_reg_rs;
                        $input['id_template'] = $get_periksa->id_template;
                        $input['kd_jenis_prw'] = $get_periksa->kd_jenis_prw;
                        $input['hasil_pemeriksaan'] = $get_periksa->hasil_pemeriksaan;
                        $get_tanggal = date('Y-m-d',strtotime($get_periksa->tanggal));
                        $tgl_pemeriksaan_2 = $get_tanggal;

                        $detail_periksa_lab = DB::table('detail_periksa_lab')
                        ->where([
                            ['no_rawat','=',$get_periksa->no_reg_rs],
                            ['kd_jenis_prw','=',$get_periksa->kd_jenis_prw],
                            ['id_template','=',$get_periksa->id_template],
                            ['tgl_periksa','=',$tgl_pemeriksaan_2]
                        ])
                        ->update([
                            'nilai' => $input['hasil_pemeriksaan']
                        ]);
                    }
                    DB::commit();
                    $res['success'] = true;
                    $res['message'] = "Data berhasil disimpan";
                    $res['payload'] = "Message from simrs";
                    return response($res);
                } catch (Exception $e) {
                    DB::rollback();
                    $res['success'] = false;
                    $res['message'] = "Data gagal disimpan baris 2";
                }
            } catch (Exception $e) {
                DB::rollback();
                $res['success'] = false;
                $res['message'] = "Data gagal disimpan baris 1";
            }
            

            

            
        }
        
        //return response()->json($input);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($limit,$id)
    {
        
        $pasien = DB::table('pasien')
                ->select('reg_periksa.kd_poli as kd_poli','periksa_lab.status as jenis_poli',
                    'poliklinik.nm_poli as nm_poli','reg_periksa.tgl_registrasi as tgl',
                    'reg_periksa.jam_reg as jam','periksa_lab.dokter_perujuk as kd_dokter',
                    'dokter.nm_dokter as nm_dokter','reg_periksa.kd_pj as kode','penjab.png_jawab as nama_pj',
                    'reg_periksa.no_rawat as no_rawat','pasien.no_rkm_medis as no_rm', 'pasien.nm_pasien as nama','pasien.jk as jenis_kelamin','pasien.alamat','pasien.tgl_lahir as tanggal_lahir','pasien.no_tlp as no_telphone')
                ->join('reg_periksa','pasien.no_rkm_medis','=','reg_periksa.no_rkm_medis')
                ->join('penjab','reg_periksa.kd_pj','=','penjab.kd_pj')
                ->join('periksa_lab','periksa_lab.no_rawat','=','reg_periksa.no_rawat')
                ->join('dokter','periksa_lab.dokter_perujuk','=','dokter.kd_dokter')
                ->join('poliklinik','reg_periksa.kd_poli','=','poliklinik.kd_poli')
                ->where('reg_periksa.no_rawat','like','%'.$id.'%')
                ->offset(0)
                ->limit($limit)
                ->distinct()
				->groupBy('reg_periksa.no_rawat')
                ->get();

        if(count($pasien) > 0){ //mengecek apakah data kosong atau tidak
            $res['success'] = "true";
            $res['message'] = "Success!";
            $res['payload'] = array();
            $tindakan = array();
            //$res['Payload']['no_rawat'] = array();
            foreach($pasien as $pasien){
                $temp['no_rm'] = $pasien->no_rm;
                $temp['nama'] = $pasien->nama;
                $temp['jenis_kelamin'] = $pasien->jenis_kelamin;
                $temp['alamat'] = $pasien->alamat;
                $temp['tanggal_lahir'] = $pasien->tanggal_lahir;
                $temp['no_telphone'] = $pasien->no_telphone;
				if($pasien->nama_pj == "-" && $pasien->kode == "-"){
					$a['nama'] = "UMUM";
					$a['kode'] = "A01";
				}else{
					$a['nama'] = $pasien->nama_pj;
					$a['kode'] = $pasien->kode;
				}
                
				if($pasien->nm_dokter == "-" && $pasien->kd_dokter == "-"){
					$b['nama'] = "NIKO SUKMAWAN, dr, Sp.PK";
					$b['kode'] = "D0001";
				}else{
					$b['nama'] = $pasien->nm_dokter;
					$b['kode'] = $pasien->kd_dokter;
				}
                $b['nama'] = $pasien->nm_dokter;
                $b['kode'] = $pasien->kd_dokter;
		        $c['nama'] = $pasien->nm_poli;
		        $c['jenis'] = $pasien->jenis_poli;
		        $c['kode'] = $pasien->kd_poli;
                $lab = DB::table('detail_periksa_lab')
        			->select('detail_periksa_lab.id_template','template_laboratorium.Pemeriksaan')
					//->distinct('periksa_lab.kd_jenis_prw')
        			->join('template_laboratorium','detail_periksa_lab.id_template','=','template_laboratorium.id_template')
        			->where('detail_periksa_lab.no_rawat',$pasien->no_rawat)
					->where('template_laboratorium.Pemeriksaan','<>','')
					->where('template_laboratorium.Pemeriksaan','<>','TERLAMPIR')
					->groupBy('Pemeriksaan')
					->orderBy('detail_periksa_lab.id_template')
        			->get();
        		if(count($lab) > 0){
        		foreach($lab as $lab){
        		    $l=array(
        		        'kode_tindakan'=>(string) $lab->id_template,
        		        'nama_tindakan'=>$lab->Pemeriksaan
        		        );
        			array_push($tindakan,$l);
        		    }
        		}else{/*
        		    $l=array(
        		        'kode_tindakan'=>null,
        		        'nama_tindakan'=>null
        		        );
        			array_push($tindakan,$l);*/
        		}
                $pas['penjamin'] = $a;
                $pas=array(
                    'no_rawat'=>$pasien->no_rawat,
                    'waktu_registrasi'=>$pasien->tgl.' '.$pasien->jam,
                    'pasien'=>$temp,
                    'dokter_pengirim'=>$b,
                    'unit_asal'=>$c,
                    'tindakan'=>$tindakan,
                    'penjamin'=>$a
                    );
                array_push($res['payload'],$pas);
            }
            
            return response($res);
        }
        else if(count($pasien) == 0){
            $res['success'] = "false";
            $res['message'] = "Data tidak ditemukan";
            return response($res);
        }else{
            $res['success'] = "false";
            $res['message'] = "Server Error";
            return response($res); 
        }
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
}
