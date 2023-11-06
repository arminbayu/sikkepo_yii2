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
            <?= Html::a('<i class="fa fa-plus"></i> Tambah Pengguna', ['tambah-pengguna'], ['class' => 'btn-sm btn-success']) ?>
            </p>

            <table class="table table-striped">
                <tr>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Unit</th>
                    <!--
                    <th>Tanggal Dibuat</th>
                    -->
                    <th style="width: 160px; text-align: center;">Action</th>
                </tr>
                <?php foreach($dataProvider as $pengguna): ?>
                <tr>
                    <td><?= $pengguna->username ?></td>
                    <td><?= $pengguna->pegawai->nama ?></td>
                    <td><?= $pengguna->pegawai->unitKerja->nama ?></td>
                    <!--
                    <td><?= date('d-m-Y', $pengguna->created_at) ?></td>
                    `-->
                    <td style="text-align: center;">
                    <?= Html::a('<i class="fa fa-pencil"></i> Ubah', ['edit-pengguna', 'id'=>$pengguna->id], ['class' => 'btn-sm btn-warning']) ?>
                    <?= Html::a('<i class="fa fa-trash"></i> Hapus', ['hapus-pengguna', 'id'=>$pengguna->id], [
                            'class' => 'btn-sm btn-danger',
                            'data' => [
                                'confirm' => 'Apakah Pengguna ingin dihapus?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            
        </div>
    </div>
</section>