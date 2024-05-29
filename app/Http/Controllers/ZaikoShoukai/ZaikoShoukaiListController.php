<?php

namespace App\Http\Controllers\ZaikoShoukai;

use App\Http\Repositories\ZaikoShoukaiRepository;
use App\Http\Requests\UkebaraiShoukaiFormSearchRequest;
use App\Http\Requests\ZaikoShoukaiFormSearchRequest;
use App\Models\MBumon;
use App\Models\MNinusi;
use App\Models\MSokoHinmei;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ZaikoShoukaiListController
{
    protected $zaikoShoukaiRepository;

    protected $defaultSettingZaikoShoukai;

    protected $defaultSettingUkebaraiShoukai;

    public function __construct(ZaikoShoukaiRepository $zaikoShoukaiRepository)
    {
        $this->zaikoShoukaiRepository = $zaikoShoukaiRepository;
        $this->defaultSettingZaikoShoukai = require(app_path('Helpers/Grid/config/zaiko_shoukai.php'));
        $this->defaultSettingUkebaraiShoukai = require(app_path('Helpers/Grid/config/ukebarai_shoukai.php'));
    }

    public function index(Request $request)
    {
        $searchOpts = $this->zaikoShoukaiRepository->getSearchOpts();
        $searchDrds = $this->zaikoShoukaiRepository->getSearchDrds();
        $customSetting = $this->defaultSettingZaikoShoukai;
        $setting = array_slice($this->defaultSettingZaikoShoukai, 0, -3);
        $dataInit = [];
        return view('zaiko_shoukai.index', compact(
            'searchOpts', 'searchDrds', 'setting', 'dataInit', 'customSetting'
        ));
    }

    public function valdateFormSearchZaikoShoukai(ZaikoShoukaiFormSearchRequest $request)
    {
        $isShowLot = false;
        $customSetting = $this->defaultSettingZaikoShoukai;
        if ($request->filled('option')) {
            $options = array_values($request['option']);
            if (in_array($this->zaikoShoukaiRepository::SHOW_LIST_LOT, $options)) {
                $isShowLot = true;
            }
        }
        if ($isShowLot) {
            $lotIndex = $this->getLotIndex($customSetting);
            $customSetting = $this->customSettingByLotKanriKbn($customSetting, $request, $lotIndex);
        }
        return response()->json([
            'status' => Response::HTTP_OK,
            'isShowLot' => $isShowLot,
            'customSetting' => $customSetting,
        ]);
    }

    public function dataList(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');
        $listData = $this->zaikoShoukaiRepository->getListDataZaikoShoukai($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        return response()->json($data);
    }

    public function indexUkebaraiShoukai(Request $request)
    {
        $ninusiCd = '';
        $dataInit = [
            'bumon_nm' => '',
            'ninusi_ryaku_nm' => '',
            'hinmei_nm' => '',
            'kikaku' => '',
            'irisu' => '',
            'kisan_dt_from' => '',
            'kisan_dt_to' => ''
        ];
        if ($request->filled('bumonCd')) {
            $dataInit['bumon_nm'] = MBumon::where('bumon_cd', $request->bumonCd)
                ->value('bumon_nm');
        }
        if ($request->filled('ninusiCd')) {
            $dataInit['ninusi_ryaku_nm'] = MNinusi::where('ninusi_cd', $request->ninusiCd)
                ->value('ninusi_ryaku_nm');
            $ninusiCd = $request->ninusiCd;
        }
        if ($request->filled('hinmeiCd')) {
            $mSokoHinmei = MSokoHinmei::where('hinmei_cd', $request->hinmeiCd)
                ->where('ninusi_cd', $request->ninusiCd)
                ->first(['hinmei_nm', 'kikaku', 'irisu']);
            if ($mSokoHinmei) {
                $dataInit['hinmei_nm'] = $mSokoHinmei->hinmei_nm;
                $dataInit['kikaku'] = $mSokoHinmei->kikaku;
                $dataInit['irisu'] = $mSokoHinmei->irisu;
            }
        }
        if ($request->filled('kisanDt')) {
            $dataInit['kisan_dt_from'] = Carbon::parse($request['kisanDt'])->firstOfMonth()->format('Y/m/d');
            $dataInit['kisan_dt_to'] = $request['kisanDt'];
        }
        $configNyusyukoKbn = $this->zaikoShoukaiRepository->getConfNyusyukoKbn();
        return view('zaiko_shoukai.ukebarai_shoukai.index', compact(
            'dataInit',
            'ninusiCd',
            'configNyusyukoKbn'
        ));
    }

    public function valdateFormSearchUkebaraiShoukai(UkebaraiShoukaiFormSearchRequest $request)
    {
        $setting = $this->defaultSettingUkebaraiShoukai;
        $lotIndex = $this->getLotIndex($setting);
        $customSetting = $this->customSettingByLotKanriKbn($setting, $request, $lotIndex);

        return response()->json([
            'status' => Response::HTTP_OK,
            'setting' => $customSetting
        ]);
    }

    public function dataListUkebaraiShoukai(Request $request)
    {
        $page = $request->page ?? 1;
        $perPage = config('params.PAGE_SIZE');
        $listData = $this->zaikoShoukaiRepository->getListDataUkebaraiShoukai($request);

        $data['total'] = $listData['total'];
        $data['rows'] = $listData['rows']
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        return response()->json($data);
    }


    public function customSettingByLotKanriKbn($setting, $request, $lotIndex)
    {
        if ($request->filled('search_ninusi_cd')) {
            $mNinusi = MNinusi::query()->where('ninusi_cd', $request->search_ninusi_cd)->first(['lot_kanri_kbn', 'lot1_nm', 'lot2_nm', 'lot3_nm']);
            if ($mNinusi) {
                switch ($mNinusi->lot_kanri_kbn) {
                    case $this->zaikoShoukaiRepository::LOT_KANRI_KBN_1:
                        $setting[$lotIndex['lot1']]['visible'] = true;
                        if (!empty($mNinusi->lot1_nm)) {
                            $setting[$lotIndex['lot1']]['title'] = $mNinusi->lot1_nm;
                        }
                        break;
                    case $this->zaikoShoukaiRepository::LOT_KANRI_KBN_2:
                        $setting[$lotIndex['lot1']]['visible'] = true;
                        $setting[$lotIndex['lot2']]['visible'] = true;
                        if (!empty($mNinusi->lot1_nm)) {
                            $setting[$lotIndex['lot1']]['title'] = $mNinusi->lot1_nm;
                        }
                        if (!empty($mNinusi->lot2_nm)) {
                            $setting[$lotIndex['lot2']]['title'] = $mNinusi->lot2_nm;
                        }
                        break;
                    case $this->zaikoShoukaiRepository::LOT_KANRI_KBN_3:
                        $setting[$lotIndex['lot1']]['visible'] = true;
                        $setting[$lotIndex['lot2']]['visible'] = true;
                        $setting[$lotIndex['lot3']]['visible'] = true;
                        if (!empty($mNinusi->lot1_nm)) {
                            $setting[$lotIndex['lot1']]['title'] = $mNinusi->lot1_nm;
                        }
                        if (!empty($mNinusi->lot2_nm)) {
                            $setting[$lotIndex['lot2']]['title'] = $mNinusi->lot2_nm;
                        }
                        if (!empty($mNinusi->lot3_nm)) {
                            $setting[$lotIndex['lot3']]['title'] = $mNinusi->lot3_nm;
                        }
                        break;
                }
            }
        }
        return $setting;
    }

    public function getLotIndex($setting)
    {
        $lotIndex = [];

        foreach ($setting as $index => $item) {
            if (isset($item['field']) && in_array($item['field'], ['lot1', 'lot2', 'lot3'])) {
                $lotIndex[$item['field']] = $index;
            }
        }
        return $lotIndex;
    }
}