<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 20px"><h3>Pengguna</h3></div>
        <div class="col-md-12">
            <p style="margin-bottom: 20px">

            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th style="width: 150">Action</th>
                </tr>
                <?php $i = 1; ?>
                <?php foreach($dataProvider as $pengguna): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $pengguna->NIP ?></td>
                    <td><?= $pengguna->nama ?></td>
                    <td>
                    <?= Html::a('<i class="fa fa-stop"></i> Reset Password', ['reset-password', 'id'=>$pengguna->NIP], [
                            'class' => 'btn-sm btn-warning',
                            'data' => [
                                'confirm' => 'Apakah password ingin di-reset?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>

            
        </div>
    </div>
</section>