<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Supprimer la colonne 'name' si elle existe
            if (Schema::hasColumn('products', 'name')) {
                $table->dropColumn('name');
            }

            // Ajouter 'quantity_sold' si elle n'existe pas déjà
            if (!Schema::hasColumn('products', 'quantity_sold')) {
                $table->integer('quantity_sold')->default(0)->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            if (Schema::hasColumn('products', 'quantity_sold')) {
                $table->dropColumn('quantity_sold');
            }
        });
    }
};