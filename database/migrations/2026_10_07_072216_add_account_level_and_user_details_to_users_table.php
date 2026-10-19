<?php

use App\Enums\AccountLevel;
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
            $table->string('first_name')->nullable()->after('id');
            $table->string('middle_initial', 10)->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_initial');
            $table->string('username')->unique()->nullable()->after('last_name');
            $table->string('account_level')->default(AccountLevel::Staff->value)->after('username');
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn([
                'first_name',
                'middle_initial',
                'last_name',
                'username',
                'account_level',
            ]);
        });
    }
};
