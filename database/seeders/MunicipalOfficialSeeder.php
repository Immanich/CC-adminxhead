<?php

namespace Database\Seeders;

use App\Models\MunicipalOfficial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MunicipalOfficialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officials = [
            ['name' => 'William M. Jao', 'title' => 'Municipal Mayor', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080202-1635462455-unkno.png'],
            ['name' => 'Redacto G. Villarin', 'title' => 'Municipal Vice Mayor', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080194-7382724028-unkno.png'],
            ['name' => 'Aniceto Alijoto', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080203-3155899631-unkno.png'],
            ['name' => 'Edsel Manuel Maglajos', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434111-473592_sanji_time_skip_1.png'],
            ['name' => 'Delia Lasco', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/original/11117/111178336/5895749-8006855349-latest'],
            ['name' => 'Flaviano Arogo', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434115-473629_untitled_2.png'],
            ['name' => 'Liv S. Uy', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/5871669-66fe3267-f7ee-405d-c083-9f3e6e5f00f9.png'],
            ['name' => 'Paciente D. Fuentes', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434117-473497_brook_timeskip1.png'],
            ['name' => 'Virgelle Gail B. Jao', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080201-7164581366-unkno.png'],
            ['name' => 'Susan Sepela-Concepcion', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/1/15776/1153175-lucci.jpg'],
            ['name' => 'Dexter L. Calunia', 'title' => 'ABC President', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7078671-9363995852-39fc3.jpg'],
            ['name' => 'John Louie Bebite-Limocon', 'title' => 'SK Federation President', 'image' => 'https://i.pinimg.com/736x/3b/8d/be/3b8dbe1c1389c4e818c643050be1bbc3.jpg'],
            ['name' => 'Karen N. Table-Rosco', 'title' => 'SB Secretary', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434105-render_nicorobin.png'],
        ];

        foreach ($officials as $official) {
            MunicipalOfficial::create($official);
        }
    }
}
