<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>TPP Pegawai</h3></div>
        <div class="col-md-12" style="margin-bottom: 20px"><h4><?= $unit->nama ?></h4></div>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th style="vertical-align: middle;">NO.</th>
                    <th style="vertical-align: middle;">NIP</th>
                    <th style="vertical-align: middle;">NAMA</th>
                    <th style="vertical-align: middle; text-align: center;">ACTION</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($model as $data): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $data->NIP ?></td>
                    <td><?= $data->nama ?></td>
                    <td style="text-align: center;">
                    <?= Html::a('Lihat', ['detail-tpp-pegawai', 'nip'=>$data->NIP], ['class' => 'btn-sm btn-success']) ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</section>