<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Storage::drive(config('filesystems.default'))->deleteDirectory('brands/images');
        Storage::drive(config('filesystems.default'))->createDir('brands/images');
        Storage::drive(config('filesystems.default'))->deleteDirectory('carModels/images');
        Storage::drive(config('filesystems.default'))->createDir('carModels/images');

        $this->call(UserSeeder::class);

        $brands = $this->brands();
        $this->call(BrandSeeder::class, false, ['brands' => $brands]);

        $carModels = [];
        foreach ($brands as $index => $brand) {
            $data[$index] = $this->carModels((int)$brand->Value);
            foreach ($data[$index]->Modelos ?? [] as $index2 => $carModel) {
                $carModels[] = [
                    'name' => $carModel->Label ?? null,
                    'brand_id' => (int)$index + 1
                ];
                if ($index2 == 4)
                    break;
            }
        }
        $this->call(CarModelSeeder::class, false, ['carModels' => $carModels]);
        $this->call([CarSeeder::class, CustomerSeeder::class, RentalSeeder::class]);
    }

    private function brands()
    {
        return $this->FipeJson("ConsultarMarcas", array(
            "codigoTabelaReferencia" => 231,
            "codigoTipoVeiculo" => 1
        ));
    }

    private function FipeJson($resource, $data = null)
    {
        $url = "http://veiculos.fipe.org.br/api/veiculos";

        $header = [
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "Host: veiculos.fipe.org.br",
            "Referer: http://veiculos.fipe.org.br"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "/" . $resource);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output);
    }

    private function carModels(int $id)
    {
        return $this->FipeJson("ConsultarModelos", array(
            "codigoTabelaReferencia" => 231,
            "codigoTipoVeiculo" => 1,
            "codigoMarca" => $id,
        ));
    }
}
