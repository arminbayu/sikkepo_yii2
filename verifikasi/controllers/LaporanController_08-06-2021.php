<?php

namespace admin\controllers;

use Yii;
use yii\base\Model;
use common\models\DataPegawai;
use common\models\DataAbsen;
use common\models\AbsenPegawai;
use common\models\UnitKerja;
use common\models\HariLibur;
use common\models\Keterangan;
use common\models\Upacara;
use common\models\PrestasiPerilakuKerja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use kartik\mpdf\Pdf;
use Da\QrCode\QrCode;

/**
 * LaporanController implements the CRUD actions for AbsenPegawai model.
 */
class LaporanController extends Controller
{
    public $layout = 'admin';


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['kehadiran-per-pegawai', 'kehadiran-pegawai', 'lihat-kehadiran', 'kehadiran-per-unit-kerja', 'kehadiran-pegawai-per-unit-kerja', 'tpp-per-pegawai', 'tpp-pegawai', 'detail-tpp-pegawai', 'tpp-per-unit-kerja', 'tpp-pegawai-per-unit-kerja', 'tpp-pajak-per-unit-kerja', 'tpp-pajak-pegawai-per-unit-kerja', 'print-kehadiran-pegawai-pdf', 'print-kehadiran-pegawai-per-unit-kerja-pdf', 'print-tpp-pegawai-pdf', 'print-tpp-pegawai-per-unit-kerja-pdf', 'print-tpp-pajak-pegawai-per-unit-kerja-pdf'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function checkKeterangan($nip, $ymd) {
        $data = Keterangan::find()->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m-%d")'=>$ymd])->one();
        return $data;
    }


    public function actionKehadiranPerPegawai()
    {
        $model = UnitKerja::find()->all();

        return $this->render('kehadiran-per-pegawai', [
            'model'=>$model,
        ]);
    }


    public function actionKehadiranPegawai($unit)
    {
        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        return $this->render('kehadiran-pegawai', [
            'model'=>$model,
            'unit'=>$unit,
        ]);
    }


    public function actionLihatKehadiran($nip)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        $model = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        return $this->render('lihat-kehadiran', [
            'model'=>$model,
            'pegawai'=>$data,
            'bulan'=>$ym,
        ]);
    }


    public function actionKehadiranPerUnitKerja()
    {
        $model = UnitKerja::find()->all();

        return $this->render('kehadiran-per-unit-kerja', [
            'model'=>$model,
        ]);
    }


    public function actionKehadiranPegawaiPerUnitKerja($unit)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        // $model = AbsenPegawai::find()
        //     ->select(['DATE_FORMAT(tanggal, "%m") AS bulan, absen_pegawai.NIP AS NIP, data_pegawai.nama AS nama, SUM(CASE WHEN absen_pegawai.status = "HD" THEN 1 ELSE 0 END) AS hadir, SUM(CASE WHEN absen_pegawai.status = "TH" THEN 1 ELSE 0 END) AS tidak_hadir, SUM(CASE WHEN absen_pegawai.status_masuk = "DT" THEN 1 ELSE 0 END) AS datang_terlambat, SUM(CASE WHEN absen_pegawai.status_siang = "TA" THEN 1 ELSE 0 END) AS tidak_absen_siang, SUM(CASE WHEN absen_pegawai.status_keluar = "PC" THEN 1 ELSE 0 END) AS pulang_cepat, COUNT(absen_pegawai.status) AS hari_kerja'])
        //     ->groupBy(['absen_pegawai.NIP'])
        //     ->where(['data_pegawai.unit_kerja'=>$unit])
        //     ->andWhere(['data_pegawai.status'=>1])
        //     ->andWhere(['=', 'DATE_FORMAT(tanggal, "%Y-%m")', $ym])
        //     ->innerJoinWith(['nip'])
        //     ->orderBy(['hadir' => SORT_DESC])
        //     ->all();

        $model = AbsenPegawai::find()
            ->select(['DATE_FORMAT(tanggal, "%m") AS bulan, absen_pegawai.NIP AS NIP, data_pegawai.nama AS nama, SUM(CASE WHEN absen_pegawai.status = "HD" THEN 1 ELSE 0 END) AS hadir, SUM(CASE WHEN absen_pegawai.status_masuk = "TA" AND absen_pegawai.status_siang = "TA" AND absen_pegawai.status_keluar = "TA" THEN 1 ELSE 0 END) AS tidak_hadir, SUM(CASE WHEN absen_pegawai.status_masuk = "TA" OR absen_pegawai.status_siang = "TA" OR absen_pegawai.status_keluar = "TA" THEN 1 ELSE 0 END) AS tidak_absen_3x, SUM(CASE WHEN absen_pegawai.status_masuk = "TW" THEN 1 ELSE 0 END) AS datang_tepat_waktu, SUM(CASE WHEN absen_pegawai.status_masuk = "DT" THEN 1 ELSE 0 END) AS datang_terlambat, SUM(CASE WHEN absen_pegawai.status_keluar = "PC" THEN 1 ELSE 0 END) AS pulang_cepat, SUM(CASE WHEN absen_pegawai.status_masuk = "TA" THEN 1 ELSE 0 END) AS tidak_absen_masuk, SUM(CASE WHEN absen_pegawai.status_siang = "TA" THEN 1 ELSE 0 END) AS tidak_absen_siang, SUM(CASE WHEN absen_pegawai.status_keluar = "TA" THEN 1 ELSE 0 END) AS tidak_absen_pulang, COUNT(absen_pegawai.status) AS hari_kerja'])
            ->groupBy(['absen_pegawai.NIP'])
            ->where(['data_pegawai.unit_kerja'=>$unit])
            ->andWhere(['data_pegawai.status'=>1])
            ->andWhere(['=', 'DATE_FORMAT(tanggal, "%Y-%m")', $ym])
            ->innerJoinWith(['nip'])
            ->orderBy(['hadir' => SORT_DESC])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        return $this->render('kehadiran-pegawai-per-unit-kerja', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);
    }



    public function actionTppPerPegawai()
    {
        $model = UnitKerja::find()->all();

        return $this->render('tpp-per-pegawai', [
            'model'=>$model,
        ]);
    }


    public function actionTppPegawai($unit)
    {
        $model = DataPegawai::find()
            ->where(['unit_kerja'=>$unit, 'status'=>1])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        return $this->render('tpp-pegawai', [
            'model'=>$model,
            'unit'=>$unit,
        ]);
    }


    public function actionDetailTppPegawai($nip)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        $model = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        return $this->render('detail-tpp-pegawai', [
            'model'=>$model,
            'pegawai'=>$data,
            'bulan'=>$ym,
        ]);        
    }


    public function actionTppPerUnitKerja()
    {
        $model = UnitKerja::find()->all();

        return $this->render('tpp-per-unit-kerja', [
            'model'=>$model,
        ]);
    }


    public function actionTppPegawaiPerUnitKerja($unit)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        $model = PrestasiPerilakuKerja::find()
            ->where(['unit_kerja'=>$unit])
            ->andWhere(['status'=>1])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->orderBy(['gol_ruang' => SORT_DESC])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        return $this->render('tpp-pegawai-per-unit-kerja', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);
    }


    public function actionTppPajakPerUnitKerja()
    {
        $model = UnitKerja::find()->all();

        return $this->render('tpp-pajak-per-unit-kerja', [
            'model'=>$model,
        ]);
    }


    public function actionTppPajakPegawaiPerUnitKerja($unit)
    {
        if (Yii::$app->request->post()) {
            $month = Yii::$app->request->post('month');
            $year = Yii::$app->request->post('year');
            $ym = $year.'-'.$month;
        }
        else {
            // $bulan = strtotime('01-'.date('m').'-'.date('Y'));
            // $bulan = strtotime('-1 month', $bulan);
            // $ym = date('Y-m', $bulan);

            $bulan = new \DateTime('first day of this month', new \DateTimeZone(TIMEZONE));
            $bulan->sub(new \DateInterval('P1M'));
            $ym = $bulan->format('Y-m');
        }

        $model = PrestasiPerilakuKerja::find()
            ->where(['unit_kerja'=>$unit])
            ->andWhere(['status'=>1])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->orderBy(['gol_ruang' => SORT_DESC])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        return $this->render('tpp-pajak-pegawai-per-unit-kerja', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);
    }


















    public function actionPrintKehadiranPegawaiPdf($nip, $ym)
    {
        $model = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        $unit = UnitKerja::find()
            ->where(['kode'=>$data->unit_kerja])
            ->one();

        $content = $this->renderPartial('lihat-kehadiran-report', [
            'model'=>$model,
            'pegawai'=>$data,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);

        $qrCode = (new QrCode(Yii::$app->user->identity->username))
            ->useEncoding('UTF-8')
            ->setSize(500)
            ->setMargin(0)
            ->useForegroundColor(0, 0, 0);

        $qrCode->writeFile('admin/img/code.png');

        // display directly to the browser 
        // header('Content-Type: '.$qrCode->getContentType());
        // echo $qrCode->writeString();

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, //BLANK
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            // 'cssInline' => '.heading-1{font-size:16px}',
            'cssInline' => '
                @media print{
                    .page-break{page-break-after: avoid;}
                }
            ',
            'marginTop' => 45,
            // file name
            'filename' => 'LAPORAN-KEHADIRAN-PEGAWAI-'.$nip.'-'.$ym.'.pdf',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Title'],
            // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>[
                    '<div style="text-align: left; border: 0px solid #000; height: 90px;">
                        <div style="float: left; width: 8%; height: 90px; border: 0px solid #000;">
                            <img style="height: 80px" src="custom/img/lambang.png" />
                        </div>
                        <div style="float: left; width: 82%; height: 90px; border: 0px solid #000;">
                            <div style="height: 20px; text-align: left; font-size: 1.8em; font-style: normal; border: 0px solid #000;">PEMERINTAH PROVINSI PAPUA BARAT</div>
                            <div style="height: 25px; text-align: left; font-size: 2.4em; font-style: normal; border: 0px solid #000;">BADAN KEPEGAWAIAN DAERAH</div>
                            <div style="height: 20px; font-style: normal; border: 0px solid #000;">Alamat: Jl. Perkantoran - Arfai Manokwari - Papua Barat &nbsp;&nbsp;&nbsp;&nbsp; Tlp. 0986-215695, Fax. 0986-215696</div>
                        </div>
                        <div style="float: left; width: 10%; height: 90px; border: 0px solid #000; text-align: right">
                            <img style="height: 80px" src="admin/img/code.png" />
                        </div>
                    </div>'
                ],
                'SetFooter'=>[
                    '<div style="border: 0px solid #000;">
                        <div style="border: 0px solid #000; float: left; width: 50%; text-align: left">{DATE j-m-Y}</div>
                        <div style="border: 0px solid #000; float: left">Halaman {PAGENO}/{nbpg}</div>
                    </div>'
                ],
            ]
        ]);
        
        return $pdf->render(); 
    }


    public function actionPrintKehadiranPegawaiPerUnitKerjaPdf($unit, $ym)
    {
        $model = AbsenPegawai::find()
            ->select(['DATE_FORMAT(tanggal, "%m") AS bulan, absen_pegawai.NIP AS NIP, data_pegawai.nama AS nama, SUM(CASE WHEN absen_pegawai.status = "HD" THEN 1 ELSE 0 END) AS hadir, SUM(CASE WHEN absen_pegawai.status_masuk = "TA" AND absen_pegawai.status_siang = "TA" AND absen_pegawai.status_keluar = "TA" THEN 1 ELSE 0 END) AS tidak_hadir, SUM(CASE WHEN absen_pegawai.status_masuk = "TA" OR absen_pegawai.status_siang = "TA" OR absen_pegawai.status_keluar = "TA" THEN 1 ELSE 0 END) AS tidak_absen_3x, SUM(CASE WHEN absen_pegawai.status_masuk = "TW" THEN 1 ELSE 0 END) AS datang_tepat_waktu, SUM(CASE WHEN absen_pegawai.status_masuk = "DT" THEN 1 ELSE 0 END) AS datang_terlambat, SUM(CASE WHEN absen_pegawai.status_keluar = "PC" THEN 1 ELSE 0 END) AS pulang_cepat, SUM(CASE WHEN absen_pegawai.status_masuk = "TA" THEN 1 ELSE 0 END) AS tidak_absen_masuk, SUM(CASE WHEN absen_pegawai.status_siang = "TA" THEN 1 ELSE 0 END) AS tidak_absen_siang, SUM(CASE WHEN absen_pegawai.status_keluar = "TA" THEN 1 ELSE 0 END) AS tidak_absen_pulang, COUNT(absen_pegawai.status) AS hari_kerja'])
            ->groupBy(['absen_pegawai.NIP'])
            ->where(['data_pegawai.unit_kerja'=>$unit])
            ->andWhere(['data_pegawai.status'=>1])
            ->andWhere(['=', 'DATE_FORMAT(tanggal, "%Y-%m")', $ym])
            ->innerJoinWith(['nip'])
            ->orderBy(['hadir' => SORT_DESC])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        $content = $this->renderPartial('kehadiran-pegawai-per-unit-kerja-report', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);

        $qrCode = (new QrCode(Yii::$app->user->identity->username))
            ->useEncoding('UTF-8')
            ->setSize(500)
            ->setMargin(0)
            ->useForegroundColor(0, 0, 0);

        $qrCode->writeFile('admin/img/code.png');

        // display directly to the browser 
        // header('Content-Type: '.$qrCode->getContentType());
        // echo $qrCode->writeString();

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, //BLANK
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            // 'cssInline' => '.heading-1{font-size:16px}',
            'cssInline' => '
                @media print{
                    .page-break{page-break-after: avoid;}
                }
            ',
            'marginTop' => 45,
            // file name
            'filename' => 'LAPORAN-KEHADIRAN-PEGAWAI-PER-UNIT-KERJA-'.$unit->kode.'-'.$ym.'.pdf',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Title'],
            // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>[
                    '<div style="text-align: left; border: 0px solid #000; height: 90px;">
                        <div style="float: left; width: 8%; height: 90px; border: 0px solid #000;">
                            <img style="height: 80px" src="custom/img/lambang.png" />
                        </div>
                        <div style="float: left; width: 82%; height: 90px; border: 0px solid #000;">
                            <div style="height: 20px; text-align: left; font-size: 1.8em; font-style: normal; border: 0px solid #000;">PEMERINTAH PROVINSI PAPUA BARAT</div>
                            <div style="height: 25px; text-align: left; font-size: 2.4em; font-style: normal; border: 0px solid #000;">BADAN KEPEGAWAIAN DAERAH</div>
                            <div style="height: 20px; font-style: normal; border: 0px solid #000;">Alamat: Jl. Perkantoran - Arfai Manokwari - Papua Barat &nbsp;&nbsp;&nbsp;&nbsp; Tlp. 0986-215695, Fax. 0986-215696</div>
                        </div>
                        <div style="float: left; width: 10%; height: 90px; border: 0px solid #000; text-align: right">
                            <img style="height: 80px" src="admin/img/code.png" />
                        </div>
                    </div>'
                ],
                'SetFooter'=>[
                    '<div style="border: 0px solid #000;">
                        <div style="border: 0px solid #000; float: left; width: 50%; text-align: left">{DATE j-m-Y}</div>
                        <div style="border: 0px solid #000; float: left">Halaman {PAGENO}/{nbpg}</div>
                    </div>'
                ],
            ]
        ]);
        
        return $pdf->render(); 
    }



    public function actionPrintTppPegawaiPdf($nip, $ym)
    {
        $model = AbsenPegawai::find()
            ->where(['NIP'=>$nip, 'DATE_FORMAT(tanggal, "%Y-%m")'=>$ym])
            ->all();

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        $unit = UnitKerja::find()
            ->where(['kode'=>$data->unit_kerja])
            ->one();

        $content = $this->renderPartial('detail-tpp-pegawai-report', [
            'model'=>$model,
            'pegawai'=>$data,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);

        $qrCode = (new QrCode(Yii::$app->user->identity->username))
            ->useEncoding('UTF-8')
            ->setSize(500)
            ->setMargin(0)
            ->useForegroundColor(0, 0, 0);

        $qrCode->writeFile('admin/img/code.png');

        // display directly to the browser 
        // header('Content-Type: '.$qrCode->getContentType());
        // echo $qrCode->writeString();

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, //BLANK
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            // 'cssInline' => '.heading-1{font-size:16px}',
            'cssInline' => '
                @media print{
                    .page-break{page-break-after: avoid;}
                }
            ',
            'marginTop' => 45,
            // file name
            'filename' => 'LAPORAN-TPP-PEGAWAI-'.$nip.'-'.$ym.'.pdf',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Title'],
            // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>[
                    '<div style="text-align: left; border: 0px solid #000; height: 90px;">
                        <div style="float: left; width: 8%; height: 90px; border: 0px solid #000;">
                            <img style="height: 80px" src="custom/img/lambang.png" />
                        </div>
                        <div style="float: left; width: 82%; height: 90px; border: 0px solid #000;">
                            <div style="height: 20px; text-align: left; font-size: 1.8em; font-style: normal; border: 0px solid #000;">PEMERINTAH PROVINSI PAPUA BARAT</div>
                            <div style="height: 25px; text-align: left; font-size: 2.4em; font-style: normal; border: 0px solid #000;">BADAN KEPEGAWAIAN DAERAH</div>
                            <div style="height: 20px; font-style: normal; border: 0px solid #000;">Alamat: Jl. Perkantoran - Arfai Manokwari - Papua Barat &nbsp;&nbsp;&nbsp;&nbsp; Tlp. 0986-215695, Fax. 0986-215696</div>
                        </div>
                        <div style="float: left; width: 10%; height: 90px; border: 0px solid #000; text-align: right">
                            <img style="height: 80px" src="admin/img/code.png" />
                        </div>
                    </div>'
                ],
                'SetFooter'=>[
                    '<div style="border: 0px solid #000;">
                        <div style="border: 0px solid #000; float: left; width: 50%; text-align: left">{DATE j-m-Y}</div>
                        <div style="border: 0px solid #000; float: left">Halaman {PAGENO}/{nbpg}</div>
                    </div>'
                ],
            ]
        ]);
        
        return $pdf->render(); 
    }


    public function actionPrintTppPegawaiPerUnitKerjaPdf($unit, $ym)
    {
        $model = PrestasiPerilakuKerja::find()
            ->where(['unit_kerja'=>$unit])
            ->andWhere(['status'=>1])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->orderBy(['gol_ruang' => SORT_DESC])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        $content = $this->renderPartial('tpp-pegawai-per-unit-kerja-report', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);

        $qrCode = (new QrCode(Yii::$app->user->identity->username))
            ->useEncoding('UTF-8')
            ->setSize(500)
            ->setMargin(0)
            ->useForegroundColor(0, 0, 0);

        $qrCode->writeFile('admin/img/code.png');

        // display directly to the browser 
        // header('Content-Type: '.$qrCode->getContentType());
        // echo $qrCode->writeString();

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, //BLANK
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            // 'cssInline' => '.heading-1{font-size:16px}',
            'cssInline' => '
                @media print{
                    .page-break{page-break-after: avoid;}
                }
            ',
            'marginTop' => 45,
            // file name
            'filename' => 'LAPORAN-TPP-PEGAWAI-PER-UNIT-KERJA-'.$unit->kode.'-'.$ym.'.pdf',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Title'],
            // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>[
                    '<div style="text-align: left; border: 0px solid #000; height: 90px;">
                        <div style="float: left; width: 8%; height: 90px; border: 0px solid #000;">
                            <img style="height: 80px" src="custom/img/lambang.png" />
                        </div>
                        <div style="float: left; width: 82%; height: 90px; border: 0px solid #000;">
                            <div style="height: 20px; text-align: left; font-size: 1.8em; font-style: normal; border: 0px solid #000;">PEMERINTAH PROVINSI PAPUA BARAT</div>
                            <div style="height: 25px; text-align: left; font-size: 2.4em; font-style: normal; border: 0px solid #000;">BADAN KEPEGAWAIAN DAERAH</div>
                            <div style="height: 20px; font-style: normal; border: 0px solid #000;">Alamat: Jl. Perkantoran - Arfai Manokwari - Papua Barat &nbsp;&nbsp;&nbsp;&nbsp; Tlp. 0986-215695, Fax. 0986-215696</div>
                        </div>
                        <div style="float: left; width: 10%; height: 90px; border: 0px solid #000; text-align: right">
                            <img style="height: 80px" src="admin/img/code.png" />
                        </div>
                    </div>'
                ],
                'SetFooter'=>[
                    '<div style="border: 0px solid #000;">
                        <div style="border: 0px solid #000; float: left; width: 50%; text-align: left">{DATE j-m-Y}</div>
                        <div style="border: 0px solid #000; float: left">Halaman {PAGENO}/{nbpg}</div>
                    </div>'
                ],
            ]
        ]);
        
        return $pdf->render(); 
    }


    public function actionPrintTppPajakPegawaiPerUnitKerjaPdf($unit, $ym)
    {
        $model = PrestasiPerilakuKerja::find()
            ->where(['unit_kerja'=>$unit])
            ->andWhere(['status'=>1])
            ->andWhere(['DATE_FORMAT(bulan, "%Y-%m")'=>$ym])
            ->innerJoinWith(['nip'])
            ->orderBy(['gol_ruang' => SORT_DESC])
            ->all();

        $unit = UnitKerja::find()
            ->where(['kode'=>$unit])
            ->one();

        $content = $this->renderPartial('tpp-pajak-pegawai-per-unit-kerja-report', [
            'model'=>$model,
            'unit'=>$unit,
            'bulan'=>$ym,
        ]);

        $qrCode = (new QrCode(Yii::$app->user->identity->username))
            ->useEncoding('UTF-8')
            ->setSize(500)
            ->setMargin(0)
            ->useForegroundColor(0, 0, 0);

        $qrCode->writeFile('admin/img/code.png');

        // display directly to the browser 
        // header('Content-Type: '.$qrCode->getContentType());
        // echo $qrCode->writeString();

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, //BLANK
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            // 'cssInline' => '.heading-1{font-size:16px}',
            'cssInline' => '
                @media print{
                    .page-break{page-break-after: avoid;}
                }
            ',
            'marginTop' => 45,
            // file name
            'filename' => 'LAPORAN-TPP-PEGAWAI-PER-UNIT-KERJA-'.$unit->kode.'-'.$ym.'.pdf',
            // set mPDF properties on the fly
            'options' => ['title' => 'Report Title'],
            // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>[
                    '<div style="text-align: left; border: 0px solid #000; height: 90px;">
                        <div style="float: left; width: 8%; height: 90px; border: 0px solid #000;">
                            <img style="height: 80px" src="custom/img/lambang.png" />
                        </div>
                        <div style="float: left; width: 82%; height: 90px; border: 0px solid #000;">
                            <div style="height: 20px; text-align: left; font-size: 1.8em; font-style: normal; border: 0px solid #000;">PEMERINTAH PROVINSI PAPUA BARAT</div>
                            <div style="height: 25px; text-align: left; font-size: 2.4em; font-style: normal; border: 0px solid #000;">BADAN KEPEGAWAIAN DAERAH</div>
                            <div style="height: 20px; font-style: normal; border: 0px solid #000;">Alamat: Jl. Perkantoran - Arfai Manokwari - Papua Barat &nbsp;&nbsp;&nbsp;&nbsp; Tlp. 0986-215695, Fax. 0986-215696</div>
                        </div>
                        <div style="float: left; width: 10%; height: 90px; border: 0px solid #000; text-align: right">
                            <img style="height: 80px" src="admin/img/code.png" />
                        </div>
                    </div>'
                ],
                'SetFooter'=>[
                    '<div style="border: 0px solid #000;">
                        <div style="border: 0px solid #000; float: left; width: 50%; text-align: left">{DATE j-m-Y}</div>
                        <div style="border: 0px solid #000; float: left">Halaman {PAGENO}/{nbpg}</div>
                    </div>'
                ],
            ]
        ]);
        
        return $pdf->render(); 
    }























































    public function actionPrintKehadiranPerPegawaiXls($nip, $ym)
    {
        $current_month = date('m', strtotime($ym));
        $current_year = date('Y', strtotime($ym));

        $dayList = array(
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu',
            'Sun' => 'Minggu'
        );

        $monthList = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );

        $data = DataPegawai::find()
            ->where(['NIP'=>$nip])
            ->one();

        $model = AbsenPegawai::find()
            ->where(['DATE_FORMAT(tanggal, "%Y-%m")'=>$ym, 'NIP'=>$nip])
            ->all();

        $filename = 'LAPORAN-KEHADIRAN-PER-PEGAWAI-'.$nip.'-'.$ym.'.xls';
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=".$filename);

        echo '<table style="width: 810px">';
            echo '<tr>';
                echo '<td style="text-align: center">LAPORAN KEHADIRAN BULAN ' . strtoupper($monthList[$current_month]) . ' ' . $current_year . '</td>';
            echo '</tr>';
            echo '<tr><td style="text-align: center">'.$nip.'</td></tr>';
            echo '<tr><td style="text-align: center">'.$data->nama.'</td></tr>';
            echo '<tr><td></td></tr>';
            echo '<tr>';
                echo '<td>';
                    echo '<table border="1">';
                        echo '<tr>';
                            echo '<th style="text-align: center; width: 70px">Tanggal</th>';
                            echo '<th style="text-align: center; width: 70px">Hari</th>';
                            echo '<th style="text-align: center; width: 80px">Masuk</th>';
                            echo '<th style="text-align: center; width: 80px">Absen Siang</th>';
                            echo '<th style="text-align: center; width: 80px">Keluar</th>';
                            echo '<th style="text-align: center; width: 80px">Status Masuk</th>';
                            echo '<th style="text-align: center; width: 80px">Status Absen Siang</th>';
                            echo '<th style="text-align: center; width: 80px">Status Keluar</th>';
                            echo '<th style="text-align: center; width: 70px">Status</th>';
                            echo '<th style="text-align: center; width: 120px">Keterangan</th>';
                        echo '</tr>';
                        $total_selisih_masuk = 0;
                        $total_selisih_keluar = 0;
                        $total_kehadiran = 0;
                        $total_hadir = 0;
                        foreach($model as $data) {
                        $hari = strtotime($data->tanggal);
                        echo '<tr>';
                            echo '<td style="text-align: center;">'.date('d-M-Y', strtotime($data->tanggal)).'</td>';
                            echo '<td style="text-align: center;">'.$dayList[date('D', $hari)].'</td>';
                            echo '<td style="text-align: center;">'.$data->jam_masuk.'</td>';
                            echo '<td style="text-align: center;">'.$data->jam_siang.'</td>';
                            echo '<td style="text-align: center;">'.$data->jam_keluar.'</td>';
                            echo '<td style="text-align: center;">';
                                echo ($data->selisih_jam_masuk != '') ? $data->status_masuk . ' (' . $data->selisih_jam_masuk . ')' : $data->status_masuk;
                            echo '</td>';
                            echo '<td style="text-align: center;">'.$data->status_siang.'</td>';
                            echo '<td style="text-align: center;">';
                            echo ($data->selisih_jam_keluar != '') ? $data->status_keluar . ' (' . $data->selisih_jam_keluar . ')' : $data->status_keluar;
                            echo '</td>';
                            echo '<td style="text-align: center;">'.$data->status.'</td>';
                            echo '<td style="text-align: center;">'.$data->keterangan.'</td>';
                        echo '</tr>';
                        }
                    echo '</table>';
                echo '</td>';
            echo '</tr>';
            
        echo '</table>';
    }








}
