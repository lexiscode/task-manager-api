<?php

use App\Models\Task;
use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Task::getTableName(), function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained(User::getTableName())->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', StatusEnum::values());
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Task::getTableName());
    }
};
