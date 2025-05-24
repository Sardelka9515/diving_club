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
        Schema::table('users', function (Blueprint $table) {
            // Add ncu_identifier column if it doesn't exist
            if (!Schema::hasColumn('users', 'ncu_identifier')) {
                $table->string('ncu_identifier')->nullable()->unique()->after('id')->comment('NCU Portal Identifier (username)');
            }

            // The email column is already created with a unique constraint in the
            // initial users table migration (0001_01_01_000000_create_users_table.php).
            // Therefore, attempting to check and add it again here is unnecessary.
            // The problematic code for checking/changing email's unique constraint has been removed.

            // If you still intend to make the password nullable (optional):
            // if (Schema::hasColumn('users', 'password')) {
            //     // Note: ->change() requires doctrine/dbal. For SQLite, if this is an issue,
            //     // and you only use SQLite for testing/local dev, this might be acceptable to omit
            //     // or handle differently if absolutely needed for SQLite.
            //     // For now, focusing on the primary error.
            //     // $table->string('password')->nullable()->change();
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ncu_identifier')) {
                // Dropping the column typically also drops its associated unique index.
                // If not, you might need $table->dropUnique('users_ncu_identifier_unique'); first.
                $table->dropColumn('ncu_identifier');
            }

            // If password was made nullable and you need to revert:
            // if (Schema::hasColumn('users', 'password')) {
            //     // Ensure doctrine/dbal is installed if using ->change()
            //     // $table->string('password')->nullable(false)->change();
            // }
        });
    }
};