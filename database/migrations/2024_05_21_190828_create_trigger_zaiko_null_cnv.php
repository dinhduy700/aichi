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
        \Illuminate\Support\Facades\DB::unprepared('
            DROP FUNCTION IF EXISTS zaiko_cnv_null();
            CREATE OR REPLACE FUNCTION zaiko_cnv_null() RETURNS TRIGGER AS $BODY$ 
            BEGIN
                IF NEW.location IS NULL THEN
                    NEW.location = \'\';
                END IF;
                IF NEW.lot1 IS NULL THEN
                    NEW.lot1 = \'\';
                END IF;
                IF NEW.lot2 IS NULL THEN
                    NEW.lot2 = \'\';
                END IF;
                IF NEW.lot3 IS NULL THEN
                    NEW.lot3 = \'\';
                END IF;
                RETURN NEW;
            END;
            $BODY$ LANGUAGE plpgsql;
            
            CREATE TRIGGER zaiko_cnv_null_nyusyuko_meisai
            BEFORE INSERT OR UPDATE ON t_nyusyuko_meisai
            FOR EACH ROW EXECUTE FUNCTION zaiko_cnv_null();
            
            CREATE TRIGGER zaiko_cnv_null_zaiko
            BEFORE INSERT OR UPDATE ON t_zaiko
            FOR EACH ROW EXECUTE FUNCTION zaiko_cnv_null();           
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::unprepared('
            DROP TRIGGER zaiko_cnv_null_nyusyuko_meisai ON t_nyusyuko_meisai;
            DROP TRIGGER zaiko_cnv_null_zaiko ON t_zaiko;
            DROP FUNCTION IF EXISTS zaiko_cnv_null();
        ');
    }
};
