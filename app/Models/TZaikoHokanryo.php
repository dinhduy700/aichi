<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TZaikoHokanryo extends Model
{
    use HasFactory;

    protected $table = 't_zaiko_hokanryo';

    // 荷主マスタ
    public function scopeJoinMNinusi($query, $type = 'left', $joinAlias = 'm_ninusi', $first = 'ninusi_cd')
    {
        $query->join("m_ninusi AS {$joinAlias}", "{$this->table}.{$first}", '=', "{$joinAlias}.ninusi_cd", $type);
    }

    //
    public function scopeJoinMSokoHinmei($query, $type = 'left', $joinAlias = 'm_soko_hinmei')
    {
        $query->leftJoinSub(
            MNinusi::query()->selectRaw('seikyu_cd, min(ninusi_cd) AS ninusi_cd')->whereNotNull('seikyu_cd')->groupBy('seikyu_cd'),
            'priority1',
            "{$this->table}.ninusi_cd", "=", "priority1.ninusi_cd"
        );
        $query->leftJoinSub(
            MNinusi::query()->select('ninusi_cd')->whereNull('seikyu_cd'),
            'priority2',
            "{$this->table}.ninusi_cd", "=", "priority2.ninusi_cd"
        );

        $query->join("m_soko_hinmei AS {$joinAlias}", function($j) use ($joinAlias) {
            $j->where(DB::raw("COALESCE(priority1.ninusi_cd, priority2.ninusi_cd)"), '=', DB::raw("{$joinAlias}.ninusi_cd"));
            $j->on("{$this->table}.hinmei_cd", '=', "{$joinAlias}.hinmei_cd");
        }, null, null, $type);
    }

    public function scopeAddSelectSuGroup(
        $query, $prefix,
        $irisu = 'm_soko_hinmei.irisu', $baraTaniJuryo = 'm_soko_hinmei.bara_tani_juryo'
    )
    {
        $su = "{$prefix}_su";
        $query->addSelect($su);//数量
        $query->selectRaw(self::getSelectRawField('case_su', ['su' => $su, 'irisu' => $irisu]) . " AS {$prefix}_case_su");//ケース数
        $query->selectRaw(self::getSelectRawField('hasu', ['su' => $su, 'irisu' => $irisu]) . " AS {$prefix}_hasu");//端数
        $query->selectRaw(self::getSelectRawField('juryo', ['su' => $su, 'bara_tani_juryo' => $baraTaniJuryo]) . " AS {$prefix}_juryo");//重量
    }

    static function getSelectRawField($field, $params = [])
    {
        switch ($field) {
            case 'case_su':
                return "CASE WHEN COALESCE({$params['irisu']}, 1) > 1 THEN DIV({$params['su']}, COALESCE({$params['irisu']}, 1)) ELSE {$params['su']} END ";
            case 'hasu':
                return "CASE WHEN COALESCE({$params['irisu']}, 1) > 1 THEN MOD({$params['su']}, COALESCE({$params['irisu']}, 1)) ELSE 0 END ";
            case 'juryo':
                return "({$params['su']} * {$params['bara_tani_juryo']})";
        }
    }
}
