<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kittens', function (Blueprint $table) {
            $table->string('source_litter_letter')->nullable();
        });

        $this->backfillSourceLitterLetter();

        foreach ([
            'breeding_cats',
            'content_pages',
            'gallery_images',
            'kittens',
            'litters',
            'news_posts',
            'reviews',
        ] as $table) {
            $this->guardAgainstDuplicateOldIds($table);

            Schema::table($table, function (Blueprint $table) {
                $table->unique('old_id');
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'breeding_cats',
            'content_pages',
            'gallery_images',
            'kittens',
            'litters',
            'news_posts',
            'reviews',
        ] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropUnique($table->getTable().'_old_id_unique');
            });
        }

        Schema::table('kittens', function (Blueprint $table) {
            $table->dropColumn('source_litter_letter');
        });
    }

    private function backfillSourceLitterLetter(): void
    {
        $litters = DB::table('litters')
            ->whereNotNull('letter')
            ->pluck('letter', 'id');

        DB::table('kittens')
            ->whereNull('source_litter_letter')
            ->orderBy('id')
            ->chunkById(100, function ($kittens) use ($litters): void {
                foreach ($kittens as $kitten) {
                    if ($kitten->litter_id === null) {
                        continue;
                    }

                    $letter = $litters[$kitten->litter_id] ?? null;

                    if ($letter === null) {
                        continue;
                    }

                    DB::table('kittens')
                        ->where('id', $kitten->id)
                        ->update(['source_litter_letter' => $letter]);
                }
            });
    }

    private function guardAgainstDuplicateOldIds(string $table): void
    {
        $duplicates = DB::table($table)
            ->select('old_id')
            ->whereNotNull('old_id')
            ->groupBy('old_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('old_id')
            ->all();

        if ($duplicates === []) {
            return;
        }

        throw new RuntimeException(sprintf(
            'Cannot add unique old_id index to %s. Duplicate old_id values found: %s',
            $table,
            implode(', ', $duplicates),
        ));
    }
};
