<?php

namespace Database\Seeders;

use App\Models\ElectedOfficial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElectedOfficialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officials = [
            ['name' => 'HON. WILLIAM R. JAO', 'title' => 'Municipal Mayor', 'image' => ''],
            ['name' => 'HON. RENATO C. VILLABER', 'title' => 'Municipal Vice Mayor', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080194-7382724028-unkno.png'],
            ['name' => 'HON. ANICETO U. ALIPOYO', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080203-3155899631-unkno.png'],
            ['name' => 'HON. ERNEST PAULO L. MASCARINAS', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434111-473592_sanji_time_skip_1.png'],
            ['name' => 'HON. DELIA Y. FUENTESPINA-LAST', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/original/11117/111178336/5895749-8006855349-latest'],
            ['name' => 'HON. FLAVIANO R. ADTOON', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434115-473629_untitled_2.png'],
            ['name' => 'HON. LUV S. UY', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/5871669-66fe3267-f7ee-405d-c083-9f3e6e5f00f9.png'],
            ['name' => 'HON. PACIENTE D. FUENTES', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434117-473497_brook_timeskip1.png'],
            ['name' => 'HON. VIRGELLE GAIL B. JAO', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7080201-7164581366-unkno.png'],
            ['name' => 'ATTY. SUSAN ESPERA L. CONCHA-LOPEZ', 'title' => 'SB Member', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/1/15776/1153175-lucci.jpg'],
            ['name' => 'HON. DEXTER L. CALUNIA', 'title' => 'Ex-Officio Member/ABC Pres.', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/11117/111178336/7078671-9363995852-39fc3.jpg'],
            ['name' => 'HON. JOHN LOUISE BENEDICT LIMOCON', 'title' => 'Ex-Officio Member/SK Fed Pres.', 'image' => 'https://i.pinimg.com/736x/3b/8d/be/3b8dbe1c1389c4e818c643050be1bbc3.jpg'],
            ['name' => 'Ms. KAREN D. ITABLE-ROSCO', 'title' => 'SB Secretary', 'image' => 'https://comicvine.gamespot.com/a/uploads/square_small/4/47703/2434105-render_nicorobin.png'],
        ];

        foreach ($officials as $official) {
            ElectedOfficial::create($official);
        }
    }
}
