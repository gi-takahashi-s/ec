<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:product-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate product image_path data to product_images table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting product images migration...');

        $products = Product::whereNotNull('image_path')
            ->whereDoesntHave('mainImage')
            ->get();

        $migratedCount = 0;

        DB::transaction(function () use ($products, &$migratedCount) {
            foreach ($products as $product) {
                // 既存のメイン画像がない場合のみ移行
                if (!$product->mainImage && $product->image_path) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $product->image_path,
                        'alt_text' => $product->name,
                        'sort_order' => 0,
                        'is_main' => true,
                    ]);
                    
                    $migratedCount++;
                    $this->line("Migrated: {$product->name} - {$product->image_path}");
                }
            }
        });

        $this->info("Migration completed. {$migratedCount} images migrated.");
        
        return Command::SUCCESS;
    }
} 