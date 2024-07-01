<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TUriage extends BaseModel
{
    use HasFactory;

    const CREATED_AT = 'add_dt';

    const UPDATED_AT = 'upd_dt';

    protected $table = 't_uriage';

    protected $primaryKey = 'uriage_den_no';

    protected $fillable = [
        'uriage_den_no',
        'bumon_cd',
        'hatuti_cd',
        'hatuti_hachaku_nm',
        'genkin_cd',
        'ninusi_cd',
        'syuka_dt',
        'haitatu_dt',
        'hachaku_cd',
        'hachaku_nm',
        'syubetu_cd',
        'hinmei_cd',
        'hinmei_nm',
        'su',
        'tani_cd',
        'unso_dt',
        'jyotai',
        'sitadori',
        'gyosya_cd',
        'unchin_mikakutei_kbn',
        'unchin_kin',
        'tyukei_kin',
        'tukoryo_kin',
        'syuka_kin',
        'tesuryo_kin',
        'unten_kin',
        'seikyu_keijyo_dt',
        'seikyu_sime_dt',
        'syuka_tm',
        'tyuki',
        'tanka_kbn',
        'seikyu_tanka',
        'okurijyo_no',
        'jyutyu_kbn',
        'kaisyu_dt',
        'kaisyu_kin',
        'add_tanto_cd',
        'add_tanto_nm',
        'haitatu_tel',
        'jikoku',
        'syaryo_kin',
        'haitatu_jyusyo1',
        'haitatu_jyusyo2',
        'haitatu_atena',
        'haitatu_fax',
        'jisya_km',
        'yosya_kin_mikakutei_kbn',
        'yosya_tyukei_kin',
        'yosya_tukoryo_kin',
        'yosya_kin_tax',
        'syaban',
        'jyomuin_cd',
        'yousya_cd',
        'denpyo_send_dt',
        'nipou_dt',
        'nipou_no',
        'biko_cd',
        'biko',
        'add_user_cd',
        'add_dt',
        'upd_user_cd',
        'upd_dt',
        'sime_kakutei_kbn',
        'seikyu_no',
        'menzei_kbn',
        'nieki_kin',
        'seikyu_kin_tax',
        'souryo_kbn'
    ];

    // public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->upd_user_cd = Auth::id();
            $model->upd_dt = \Carbon\Carbon::now();
            $model->add_user_cd = Auth::id();
            $model->add_dt = \Carbon\Carbon::now();
        });

        static::updating(function ($model) {
            $model->upd_user_cd = Auth::id();
            $model->upd_dt = \Carbon\Carbon::now();
            $oldAddDt = $model->getOriginal('add_dt');
            if ($oldAddDt !== null) {
                $model->add_dt = $oldAddDt;
            }
        });
    }


    public function scopeFilter($query, $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            $fromTo = [
                'bumon_cd', 'ninusi_cd',
                'hatuti_cd', 'hachaku_cd',
                'unso_dt', 'syuka_dt',
                'uriage_den_no',
                'jyutyu_kbn',
                'yousya_cd',
                'jyomuin_cd',
            ];
            foreach ($fromTo as $field) {
                if (is_null($value)) continue;
                if ($key == "{$field}_from") $query->where("{$this->table}.{$field}", '>=', $value);
                if ($key == "{$field}_to") $query->where("{$this->table}.{$field}", '<=', $value);
            }
        }

        if(!empty($request->sort)) {
            $query->orderBy($request->sort, $request->order);
        }
    }

    public function scopeFilterList($query, $request, $tableAndColumn = []) {
        if (!empty($request->sort)) {
            if (!empty($tableAndColumn[$request->sort])) {
                $sort = $tableAndColumn[$request->sort]['table'].".".$tableAndColumn[$request->sort]['column'];
                $query->orderBy($sort, $request->order);
            } else {
                $query->orderBy($request->sort, $request->order);
            }
        }
    }

    // 部門マスタ
    public function scopeJoinMBumon($query, $type = 'left', $joinAlias = 'm_bumon')
    {
        $query->join("m_bumon AS {$joinAlias}", $this->table . ".bumon_cd", '=', "{$joinAlias}.bumon_cd", $type);
    }

    // 荷主マスタ
    public function scopeJoinMNinusi($query, $type = 'left', $joinAlias = 'm_ninusi', $first = 'ninusi_cd')
    {
        $query->join("m_ninusi AS {$joinAlias}", "{$this->table}.{$first}", '=', "{$joinAlias}.ninusi_cd", $type);
    }

    // 発地着地マスタ
    public function scopeJoinMHachaku($query, $type = 'left', $joinAlias = 'm_hachaku')
    {
        $query->join("m_hachaku AS {$joinAlias}", $this->table . ".hachaku_cd", '=', "{$joinAlias}.hachaku_cd", $type);
    }
    public function scopeJoinHatuti($query, $type = 'left', $joinAlias = 'm_hatuti')
    {
        $query->join("m_hachaku AS {$joinAlias}", $this->table . ".hatuti_cd", '=', "{$joinAlias}.hachaku_cd", $type);
    }

    // 庸車先マスタ
    public function scopeJoinMYousya($query, $type = 'left', $joinAlias = 'm_yousya')
    {
        $query->join("m_yousya AS {$joinAlias}", $this->table . ".yousya_cd", '=', "{$joinAlias}.yousya_cd", $type);
    }

    // 品名マスタ, 品目マスタ
    public function scopeJoinMHinmei($query, $type = 'left', $joinAlias = 'm_hinmei', $joinMHinmoku = false)
    {
        $query->join("m_hinmei AS {$joinAlias}", $this->table . ".hinmei_cd", '=', "{$joinAlias}.hinmei_cd", $type);
        if ($joinMHinmoku) {
            $query->join('m_hinmoku', "{$joinAlias}.hinmoku_cd", '=', "m_hinmoku.hinmoku_cd", $type);
        }
    }

    // 乗務員マスタ
    public function scopeJoinMJyomuin($query, $type = 'left', $joinAlias = 'm_jyomuin', $first = 'jyomuin_cd')
    {
        $query->join("m_jyomuin AS {$joinAlias}", $this->table . ".{$first}", '=', "{$joinAlias}.jyomuin_cd", $type);
    }

    // 単位CD tani_cd 名称マスタと連携
    public function scopeJoinMMeisyoTani($query, $type = 'left', $meisyoAlias = 'm_meisyo_tani')
    {
        $table = $this->table;
        $query->join("m_meisyo AS {$meisyoAlias}", function ($j) use ($table, $meisyoAlias) {
            $j->on($table . ".tani_cd", '=', "{$meisyoAlias}.meisyo_cd");
            $j->where("{$meisyoAlias}.meisyo_kbn", '=', configParam('MEISYO_KBN_TANI'));
        }, null, null, $type);
    }

    // 業者CD gyosya_cd 名称マスタと連携
    public function scopeJoinMMeisyoGyosya($query, $type = 'left', $meisyoAlias = 'm_meisyo_gyosya')
    {
        $table = $this->table;
        $query->join("m_meisyo AS {$meisyoAlias}", function ($j) use ($table, $meisyoAlias) {
            $j->on($table . ".gyosya_cd", '=', "{$meisyoAlias}.meisyo_cd");
            $j->where("{$meisyoAlias}.meisyo_kbn", '=', configParam('MEISYO_KBN_GYOSYA'));
        }, null, null, $type);
    }

    // 受注区分 jyutyu_kbn 名称マスタと連携
    public function scopeJoinMMeisyoJyutyu($query, $type = 'left', $meisyoAlias = 'm_meisyo_jyutyu')
    {
        $table = $this->table;
        $query->join("m_meisyo AS {$meisyoAlias}", function ($j) use ($table, $meisyoAlias) {
            $j->on($table . ".jyutyu_kbn", '=', "{$meisyoAlias}.meisyo_cd");
            $j->where("{$meisyoAlias}.meisyo_kbn", '=', configParam('MEISYO_KBN_JYUTYU'));
        }, null, null, $type);
    }

    // 現金CD genkin_cd 名称マスタと連携
    public function scopeJoinMMeisyoGenkin($query, $type = 'left', $meisyoAlias = 'm_meisyo_genkin')
    {
        $table = $this->table;
        $query->join("m_meisyo AS {$meisyoAlias}", function ($j) use ($table, $meisyoAlias) {
            $j->on($table . ".genkin_cd", '=', "{$meisyoAlias}.meisyo_cd");
            $j->where("{$meisyoAlias}.meisyo_kbn", '=', configParam('MEISYO_KBN_GENKIN'));
        }, null, null, $type);
    }

    // 種別CD syubetu_cd 名称マスタと連携
    public function scopeJoinMMeisyoSyubetu($query, $type = 'left', $meisyoAlias = 'm_meisyo_syubetu')
    {
        $table = $this->table;
        $query->join("m_meisyo AS {$meisyoAlias}", function ($j) use ($table, $meisyoAlias) {
            $j->on($table . ".syubetu_cd", '=', "{$meisyoAlias}.meisyo_cd");
            $j->where("{$meisyoAlias}.meisyo_kbn", '=', configParam('MEISYO_KBN_SYUBETU'));
        }, null, null, $type);
    }

    //MEISYO TANKA
    public function scopeJoinMMeisyoTanka($query, $type = 'left', $meisyoAlias = 'm_meisyo_tanka')
    {
        $table = $this->table;
        $query->join("m_meisyo AS {$meisyoAlias}", function ($j) use ($table, $meisyoAlias) {
            $j->on($table . ".tanka_kbn", '=', "{$meisyoAlias}.meisyo_cd");
            $j->where("{$meisyoAlias}.meisyo_kbn", '=', configParam('MEISYO_KBN_TANKA'));
        }, null, null, $type);
    }

    public function scopeJoinMSyaryo($query, $type = 'left', $joinAlias = 'm_syaryo')
    {
        $query->join("m_syaryo AS {$joinAlias}", $this->table . ".syaban", '=', "{$joinAlias}.syaryo_cd", $type);
    }

    public function scopeJoinTSeikyu($query, $type = 'left', $joinAlias = 't_seikyu')
    {
        $table = $this->table;
        $query->join("t_seikyu AS {$joinAlias}", function ($j) use ($table, $joinAlias) {
            $j->on($table . ".seikyu_no", '=', "{$joinAlias}.seikyu_no");
            $j->on($table . ".seikyu_sime_dt", '=', "{$joinAlias}.seikyu_sime_dt");
        }, null, null, $type);
    }
}
