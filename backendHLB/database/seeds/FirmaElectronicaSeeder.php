<?php

use Illuminate\Database\Seeder;
use App\FirmasElectronicas;

class FirmaElectronicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('firmas_electronicas')->delete();
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);


        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);



        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);


        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
        FirmasElectronicas::create([
            'image_name' => 'blob',
            'image_url' => 'https://res.cloudinary.com/dzdnf95ze/image/upload/v1596144940/ptcmygmzxtwtjjjnhgor.png',
        ]);
    }
}
