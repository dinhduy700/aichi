<?php


namespace App\Http\Controllers\Uriage;


use App\Helpers\Excel\XlsNouhinsyou;
use App\Http\Repositories\UriageRepository;
use App\Http\Requests\UriageNouhinsyoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NouhinsyoController
{
    protected $uriageRepository;


    public function __construct(UriageRepository $uriageRepository)
    {
        $this->uriageRepository = $uriageRepository;
    }

    public function filterForm(Request $request)
    {
        $repo = $this->uriageRepository;
        $syuturyokuOpts = $repo->getNouhinsyoSyuturyokuOpts();
        $syuhaiOpts = $repo->getNouhinsyoSyuhaiOpts();
        $titleOpts = $this->getTitleSetting();
        $otherOpts = $repo->getNouhinsyoOtherOpts();
        $initValue = [];
        if ($request->method() === "POST") {
            $initValue = $request->except('_token');
        }
        return view('uriage.nouhinsyo-filter-form', compact(
            'syuturyokuOpts', 'syuhaiOpts', 'titleOpts', 'otherOpts', 'initValue'
        ));
    }

    public function filterValidate(UriageNouhinsyoRequest $request)
    {
        return responseSendForward($this->uriageRepository->getNouhinsyoQb($request));
    }

    public function excel(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_uriage_nouhinsyo.xlsx';
        return response()->download(
            $this->exportExcel($request, $outDir, $fileNm),
            getPageTitle('uriage.nouhinsyo.filterForm') . '_' . date('YmdHis') . '.xlsx'
        )->deleteFileAfterSend(true);
    }

    public function pdf(Request $request)
    {
        $outDir = storage_path('app/download');
        $fileNm = date('YmdHis') . '_t_jyutyu.xlsx';
        $xlsPath = $this->exportExcel($request, $outDir, $fileNm);
        cnvXlsToPdf($xlsPath, $outDir);
        File::delete($xlsPath);
        $pdfPath = $outDir . DIRECTORY_SEPARATOR .  str_replace('.xlsx', '.pdf', $fileNm);
        $fileName = getPageTitle('uriage.nouhinsyo.filterForm') . '_' . date('YmdHis') . '.pdf';
        return response()->file($pdfPath, ['Content-Disposition' => 'filename="' . $fileName . '"'])
            ->deleteFileAfterSend(true);
    }

    private function exportExcel(Request $request, $outDir, $filename)
    {
        set_time_limit(-1);
        $exporter = new XlsNouhinsyou();
        $qb = $this->uriageRepository->getNouhinsyoQb($request);
        $qb = $this->uriageRepository->handleNouhinsyoOkurijyoNo($qb, $request);

        $data = $qb->get();

        $config = require(app_path('Helpers/Excel/config/t_uriage_nouhinsyo.php'));

        $reqExp = $request->exp ?? [];

        // set customize title from request
        $settings = $this->getTitleSetting();

        //受領印
        foreach ($settings as $form =>$setting) {
            $reqForm = data_get($request->exp, $form, []);
            if (data_get($reqForm, 'render')) {
                //タイトル
                $config['base']['header'][$form]['others']['title']['value'] = data_get($reqForm, 'title');
                if (data_get($reqForm, 'stamp') != 1) $config['base']['stamp'][$form] = false;
            } else {
                $config['base']['template']['page'][$form] = false;
            }
        }

        // Check if the directory exists, if not, create it
        if (!File::exists($outDir)) {
            File::makeDirectory($outDir, 0755, true, true);
        }
        $savePath = $outDir . DIRECTORY_SEPARATOR . $filename;

        $exporter->export(
            app_path('Helpers/Excel/template/t_uriage_nouhinsyo.xlsx'),//template
            $config,
            $data,
            $savePath
        );

        return $savePath;
    }

    protected function getTitleSetting()
    {
        return [
            XlsNouhinsyou::FORM_1 => [
                'text' => '送　り　状',
                'render' => 0,
                'stamp' => 0,//受領印
            ],
            XlsNouhinsyou::FORM_2 => [
                'text' => '受　領　書',
                'render' => 0,
                'stamp' => 0,//受領印
            ],
            XlsNouhinsyou::FORM_3 => [
                'text' => '送　り　状（控）',
                'render' => 0,
                'stamp' => 0,//受領印
            ],
            XlsNouhinsyou::FORM_4 => [
                'text' => '送　り　状（控）',
                'render' => 0,
                'stamp' => 0,//受領印
            ],
        ];
    }


}
