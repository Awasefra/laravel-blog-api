<?php

namespace App\Traits;

use Illuminate\Support\Facades\Redis;

trait RedisTrait
{
    // Mengambil semua data dari cache Redis
    private function getDatasFromCache($key)
    {
        $datas = Redis::get($key);
        return $datas ? json_decode($datas, true) : [];
    }

    // Menyimpan semua data di cache Redis dengan TTL (Time To Live)
    private function setDatasInCache($name, $datas)
    {
        Redis::set($name, json_encode($datas));
        Redis::expire($name, 60); // Set TTL (1 jam)
    }

    // Menyimpan data individual di cache Redis dengan TTL (Time To Live)
    private function cacheData($name, $data)
    {
        Redis::set($name . ':' . $data->id, json_encode($data->toArray()));
        Redis::expire($name . ':' . $data->id, 60); // Set TTL (1 jam)
    }

    // Menghapus data dari cache Redis berdasarkan ID
    private function removeFromCache($name, $id)
    {
        Redis::del($name . ':' . $id);
    }

    private function removeDataAtCache($datas, $id)
    {
        // Hapus data dari daftar cache jika ID diberikan
        $datas = array_filter($datas, fn ($p) => $p['id'] != $id);
        return array_values($datas); // Reset kunci array
    }

    private function addOrUpdateAtCache($datas, $dataUpdateOrCreate)
    {
        // Update atau tambahkan data ke daftar cache
        $exists = false;
        foreach ($datas as &$cachedData) {
            if ($cachedData['id'] == $dataUpdateOrCreate->id) {
                $cachedData = $dataUpdateOrCreate->toArray();
                $exists = true;
                break;
            }
        }
        if (!$exists) {
            $datas[] = $dataUpdateOrCreate->toArray();
        }

        return $datas;
    }
}
