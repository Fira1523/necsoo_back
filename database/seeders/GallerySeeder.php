<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GalleryItem; // Adjust the model name as necessary

class GallerySeeder extends Seeder
{
    public function run()
    {
        GalleryItem::create([
            'title' => 'Sample Gallery Item 1',
            'image' => '/images/gallery/sample1.jpg',
            'description' => 'Description for sample item 1',
        ]);

        GalleryItem::create([
            'title' => 'Sample Gallery Item 2',
            'image' => '/images/gallery/sample2.jpg',
            'description' => 'Description for sample item 2',
        ]);
    }
}
