<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Verificar si la columna existe
            if (!Schema::hasColumn('users', 'documento')) {
                $table->string('documento')->unique()->after('apellido');
            }
        });

        // Verificar si el índice unique ya existe antes de agregarlo
        $indexExists = $this->uniqueIndexExists('users', 'users_documento_unique');
        
        if (!$indexExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('documento');
            });
        }
    }

    public function down(): void
    {
        $indexExists = $this->uniqueIndexExists('users', 'users_documento_unique');
        
        if ($indexExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique(['documento']);
            });
        }
    }

    /**
     * Verificar si existe un índice unique
     */
    private function uniqueIndexExists($table, $indexName): bool
    {
        $connection = config('database.default');
        $driver = config("database.connections.{$connection}.driver");

        if ($driver === 'mysql') {
            $database = config("database.connections.{$connection}.database");
            
            $result = DB::select(
                "SELECT COUNT(*) as count 
                 FROM information_schema.statistics 
                 WHERE table_schema = ? 
                 AND table_name = ? 
                 AND index_name = ?",
                [$database, $table, $indexName]
            );

            return $result[0]->count > 0;
        }

        return false;
    }
};