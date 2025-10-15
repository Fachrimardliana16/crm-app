<?php

namespace App\Http\Controllers\Api;

use App\Models\SubGolonganPelanggan;
use App\Models\Danameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk kalkulasi tarif air
 * 
 * Menghitung tarif air berdasarkan sub golongan pelanggan, volume pemakaian,
 * dan biaya danameter sesuai dengan struktur tarif PDAM Purbalingga.
 */
class TarifController extends BaseApiController
{
    /**
     * Menghitung tarif air berdasarkan parameter yang diberikan
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_sub_golongan_pelanggan' => 'required|uuid|exists:sub_golongan_pelanggan,id_sub_golongan_pelanggan',
            'volume_m3' => 'required|numeric|min:0',
            'id_danameter' => 'nullable|uuid|exists:danameter,id_danameter',
            'bulan_tagihan' => 'nullable|integer|min:1|max:12',
            'tahun_tagihan' => 'nullable|integer|min:2020'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $subGolongan = SubGolonganPelanggan::with('golonganPelanggan')->find($request->id_sub_golongan_pelanggan);
        $volume = $request->volume_m3;
        $danameter = null;

        if ($request->id_danameter) {
            $danameter = Danameter::find($request->id_danameter);
        }

        // Hitung tarif air berdasarkan blok
        $tarifCalculation = $subGolongan->hitungTotalTarif($volume);

        // Biaya danameter (jika ada)
        $biayaDanameter = $danameter ? $danameter->tarif_danameter : 0;

        // Total keseluruhan
        $totalKeseluruhan = $tarifCalculation['total_tarif'] + $biayaDanameter;

        // Format result
        $result = [
            'sub_golongan' => [
                'id' => $subGolongan->id_sub_golongan_pelanggan,
                'kode' => $subGolongan->kode_sub_golongan,
                'nama' => $subGolongan->nama_sub_golongan,
                'golongan' => $subGolongan->golonganPelanggan->nama_golongan
            ],
            'volume_m3' => $volume,
            'periode' => [
                'bulan' => $request->bulan_tagihan ?? date('n'),
                'tahun' => $request->tahun_tagihan ?? date('Y')
            ],
            'tarif_air' => $tarifCalculation,
            'danameter' => $danameter ? [
                'id' => $danameter->id_danameter,
                'kode' => $danameter->kode_danameter,
                'diameter' => $danameter->diameter_pipa,
                'tarif' => $danameter->tarif_danameter
            ] : null,
            'biaya_danameter' => $biayaDanameter,
            'total_keseluruhan' => $totalKeseluruhan,
            'detail_perhitungan' => [
                'biaya_tetap' => $tarifCalculation['biaya_tetap'],
                'biaya_pemakaian' => $tarifCalculation['biaya_pemakaian'],
                'subtotal_air' => $tarifCalculation['total_tarif'],
                'biaya_danameter' => $biayaDanameter,
                'total_tagihan' => $totalKeseluruhan
            ],
            'dihitung_pada' => now()
        ];

        return $this->successResponse($result, 'Tarif berhasil dihitung');
    }

    /**
     * Simulasi perhitungan tarif untuk berbagai volume
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function simulate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_sub_golongan_pelanggan' => 'required|uuid|exists:sub_golongan_pelanggan,id_sub_golongan_pelanggan',
            'volume_range' => 'required|array|min:1',
            'volume_range.*' => 'numeric|min:0',
            'id_danameter' => 'nullable|uuid|exists:danameter,id_danameter'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $subGolongan = SubGolonganPelanggan::with('golonganPelanggan')->find($request->id_sub_golongan_pelanggan);
        $volumes = $request->volume_range;
        $danameter = null;

        if ($request->id_danameter) {
            $danameter = Danameter::find($request->id_danameter);
        }

        $biayaDanameter = $danameter ? $danameter->tarif_danameter : 0;
        $simulations = [];

        foreach ($volumes as $volume) {
            $calculation = $subGolongan->hitungTotalTarif($volume);
            $totalKeseluruhan = $calculation['total_tarif'] + $biayaDanameter;

            $simulations[] = [
                'volume_m3' => $volume,
                'biaya_tetap' => $calculation['biaya_tetap'],
                'biaya_pemakaian' => $calculation['biaya_pemakaian'],
                'subtotal_air' => $calculation['total_tarif'],
                'biaya_danameter' => $biayaDanameter,
                'total_tagihan' => $totalKeseluruhan,
                'tarif_per_m3_rata' => $volume > 0 ? round($calculation['total_tarif'] / $volume, 2) : 0
            ];
        }

        $result = [
            'sub_golongan' => [
                'id' => $subGolongan->id_sub_golongan_pelanggan,
                'kode' => $subGolongan->kode_sub_golongan,
                'nama' => $subGolongan->nama_sub_golongan,
                'golongan' => $subGolongan->golonganPelanggan->nama_golongan
            ],
            'danameter' => $danameter ? [
                'id' => $danameter->id_danameter,
                'kode' => $danameter->kode_danameter,
                'diameter' => $danameter->diameter_pipa,
                'tarif' => $danameter->tarif_danameter
            ] : null,
            'simulations' => $simulations,
            'metadata' => [
                'total_simulations' => count($simulations),
                'volume_range' => [
                    'min' => min($volumes),
                    'max' => max($volumes)
                ],
                'dihitung_pada' => now()
            ]
        ];

        return $this->successResponse($result, 'Simulasi tarif berhasil dihitung');
    }

    /**
     * Mendapatkan struktur tarif untuk sub golongan tertentu
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getStructure(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_sub_golongan_pelanggan' => 'required|uuid|exists:sub_golongan_pelanggan,id_sub_golongan_pelanggan'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $subGolongan = SubGolonganPelanggan::with('golonganPelanggan')->find($request->id_sub_golongan_pelanggan);

        $structure = [
            'sub_golongan' => [
                'id' => $subGolongan->id_sub_golongan_pelanggan,
                'kode' => $subGolongan->kode_sub_golongan,
                'nama' => $subGolongan->nama_sub_golongan,
                'golongan' => $subGolongan->golonganPelanggan->nama_golongan
            ],
            'struktur_tarif' => [
                'biaya_tetap_bulanan' => $subGolongan->biaya_tetap_subgolongan,
                'blok_tarif' => [
                    [
                        'blok' => 1,
                        'range' => '0 - 10 m³',
                        'tarif_per_m3' => $subGolongan->tarif_blok_1
                    ],
                    [
                        'blok' => 2,
                        'range' => '11 - 20 m³',
                        'tarif_per_m3' => $subGolongan->tarif_blok_2
                    ],
                    [
                        'blok' => 3,
                        'range' => '21 - 30 m³',
                        'tarif_per_m3' => $subGolongan->tarif_blok_3
                    ],
                    [
                        'blok' => 4,
                        'range' => '> 30 m³',
                        'tarif_per_m3' => $subGolongan->tarif_blok_4
                    ]
                ]
            ],
            'contoh_perhitungan' => [
                $this->generateExample($subGolongan, 5),
                $this->generateExample($subGolongan, 15),
                $this->generateExample($subGolongan, 25),
                $this->generateExample($subGolongan, 35)
            ]
        ];

        return $this->successResponse($structure, 'Struktur tarif berhasil diambil');
    }

    /**
     * Generate example calculation
     * 
     * @param SubGolonganPelanggan $subGolongan
     * @param int $volume
     * @return array
     */
    private function generateExample(SubGolonganPelanggan $subGolongan, int $volume): array
    {
        $calculation = $subGolongan->hitungTotalTarif($volume);
        
        return [
            'volume_m3' => $volume,
            'detail' => $calculation,
            'total' => $calculation['total_tarif']
        ];
    }
}