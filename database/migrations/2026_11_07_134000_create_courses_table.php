<?php

/*
|--------------------------------------------------------------------------
| الكتلة البرمجية الثانية: إدارة المواد والمقررات الدراسية
| اسم الطالب: عمر  -  الرقم الجامعي: ..........  (الكتلة الثانية)
|--------------------------------------------------------------------------
| ملف الترحيل (Migration) الخاص بجدول المواد courses
| يبني الجدول حسب تصميم قاعدة البيانات في ملف المشروع (الجدول رقم 4)
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    /*
    |----------------------------------------------------------------------
    | الكود القديم (الموجود سابقاً بالمشروع) - تم وضعه كتعليق بدون حذف
    | كان الجدول يحتوي على حقول مؤقتة فقط (name, code) ولا يطابق ملف المشروع
    |----------------------------------------------------------------------
    public function up(): void
    {
       Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->timestamps();
});
    }
    */

    /**
     * إنشاء جدول المواد courses بكامل الحقول المطلوبة في ملف المشروع
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            // معرف المادة: مفتاح أساسي يتزايد تلقائياً (PK, AUTO_INCREMENT)
            $table->id();

            // اسم المادة: نص بطول 100 حرف وإجباري (NOT NULL)
            $table->string('course_name', 100);

            // رمز المادة مثل CS101: فريد (UNIQUE) وإجباري
            $table->string('course_code', 20)->unique();

            // عدد الساعات المعتمدة: رقم صحيح إجباري (القيد CHECK من 1 إلى 6 مضاف بالأسفل)
            $table->integer('credit_hours');

            // القسم الذي تتبع له المادة: مفتاح خارجي يشير إلى جدول departments
            $table->foreignId('department_id')->constrained('departments');

            // المدرس المسؤول عن المادة: مفتاح خارجي يشير إلى جدول users
            $table->foreignId('instructor_id')->constrained('users');

            // الفصل الدراسي: مفتاح خارجي يشير إلى جدول semesters
            $table->foreignId('semester_id')->constrained('semesters');

            // السنة الدراسية التي تُدرّس فيها المادة (1، 2، 3، 4)
            $table->string('academic_year', 20);

            // وصف المادة: نص طويل اختياري (NULL)
            $table->text('description')->nullable();

            // المتطلب السابق: مفتاح خارجي اختياري يشير إلى نفس جدول courses
            // (علاقة المادة مع نفسها: مادة تحتاج نجاح مادة أخرى قبلها)
            $table->foreignId('prerequisite_id')->nullable()->constrained('courses');

            // حالة المادة: مفعلة أو معطلة والقيمة الافتراضية مفعلة
            $table->enum('status', ['active', 'inactive'])->default('active');

            // تاريخ الإنشاء: يأخذ الوقت الحالي تلقائياً (DEFAULT CURRENT_TIMESTAMP)
            $table->timestamp('created_at')->useCurrent();

            // تاريخ آخر تعديل: يمكن أن يكون فارغاً (NULL)
            $table->timestamp('updated_at')->nullable();
        });

        // قيد التحقق CHECK: عدد الساعات يجب أن يكون بين 1 و 6
        // (يعمل على MySQL / MariaDB الموجودة في XAMPP)
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement('ALTER TABLE courses ADD CONSTRAINT chk_courses_credit_hours CHECK (credit_hours BETWEEN 1 AND 6)');
        }
    }

    /**
     * Reverse the migrations.
     * حذف جدول المواد عند التراجع (rollback)
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }


};
