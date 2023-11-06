<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section id="mu-contact" style="padding-top: 30px; min-height: 600px">
  <div class="container">
    <div class="row">
      <div class="mu-contact-area" style="border: 0px solid #000">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Data Pegawai <?= $unit->nama ?></h3></div>
        <div class="col-md-12">
          <table class="table table-hover">
            <tr>
              <th>No.</th>
              <th>NIP</th>
              <th>Nama</th>
              <th>Terminal</th>
              <th>No. Absen</th>
              <th>Action</th>
            </tr>
            <?php $i = 1; ?>
            <?php foreach($model as $data): ?>
            <tr>
              <td><?= $i ?></td>
              <td><?= $data->NIP ?></td>
              <td><?= $data->nama ?></td>
              <td><?= $data->kode_terminal ?></td>
              <td><?= $data->no_absen ?></td>
              <td>
                <?= Html::a('<i class="fa fa-pencil"></i> Set Terminal', ['edit-kode-terminal', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) ?>
                <?= Html::a('<i class="fa fa-pencil"></i> Set Absen', ['edit-no-absen', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], ['class' => 'btn-sm btn-success']) ?>
                <?= Html::a('<i class="fa fa-trash"></i> Hapus', ['delete-pegawai', 'nip'=>$data->NIP, 'unit'=>$data->unit_kerja], [
                    'class' => 'btn-sm btn-danger',
                    'data' => [
                        'confirm' => 'Apakah Pegawai ingin dihapus?',
                        'method' => 'post',
                    ],
                ]) ?>
              </td>
            </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
          </table>
          <?php
            echo LinkPager::widget([
                'pagination' => $pages,
            ]);
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
