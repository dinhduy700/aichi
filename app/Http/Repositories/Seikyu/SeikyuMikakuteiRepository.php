<?php


namespace App\Http\Repositories\Seikyu;


use App\Models\TSeikyu;
use App\Models\TUriage;
use Illuminate\Http\Request;

class SeikyuMikakuteiRepository
{
    const EXP_PRINT_CSV_HEADER = 'M';

    public function getList($request)
    {
        $qb = (new SeikyuListRepository())->getListBuilder($request, 'inner');
        return ['total' => $qb->count(), 'rows' => $qb];
    }

    public function getOptionOpts() {
        return [
            self::EXP_PRINT_CSV_HEADER => ['text' => 'CSV見出し出力あり（M）'],
        ];
    }

    public function qbExport(Request $request)
    {
        $listRepo = new SeikyuListRepository();
        $qb = TSeikyu::query();
        $listRepo->joinTUriageMikakutei('inner', $qb, ['t_uriage.*']);
        $qb->select('t_uriage.*');
        $qb->addSelect($listRepo->getRawSelect('mikakutei_kin'));

        $qb->leftJoin('m_ninusi', 'm_ninusi.ninusi_cd', '=', 't_uriage.ninusi_cd')
            ->addSelect(['m_ninusi.ninusi1_nm', 'm_ninusi.seikyu_cd', 'm_ninusi.ninusi_ryaku_nm']);

        $qb->leftJoin('m_hachaku AS m_hatuti', 'm_hatuti.hachaku_cd', '=', 't_uriage.hatuti_cd')
            ->addSelect(['m_hatuti.hachaku_nm AS hatuti_nm']);
        $qb->leftJoin('m_hachaku', 'm_hachaku.hachaku_cd', '=', 't_uriage.hachaku_cd')
            ->addSelect(['m_hachaku.hachaku_nm']);

        $qb->leftJoin('m_hinmei', 'm_hinmei.hinmei_cd', '=', 't_uriage.hinmei_cd')
            ->addSelect(['m_hinmei.hinmei_nm']);

        $qb->leftJoin('m_meisyo AS m_meisyo_tani', function ($j) {
            $j->on('t_uriage.tani_cd', '=', 'm_meisyo_tani.meisyo_cd');
            $j->where('m_meisyo_tani.meisyo_kbn', '=', configParam('MEISYO_KBN_TANI'));
        })->addSelect(['m_meisyo_tani.meisyo_nm AS tani_nm']);

        $qb->orderBy('t_uriage.seikyu_sime_dt');
        $qb->orderBy('t_uriage.ninusi_cd');
        $qb->orderBy('t_uriage.unso_dt');
        $qb->orderBy('t_uriage.uriage_den_no');

        $qb->distinct();

        return $qb;
    }

    public function applyRequestToBuilder(Request $request)
    {
        $cloneReq = new Request($request->exp ?? []);
        $routeNm = $request->route()->getName();
        $qb = $this->qbExport($cloneReq, $routeNm);

        (new SeikyuListRepository())->applySelectedItemsToBuilder($cloneReq, $qb);

        return $qb;
    }
}
