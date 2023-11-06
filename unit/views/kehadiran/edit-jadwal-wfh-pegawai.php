<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Progress;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section style="padding-top: 30px; min-height: 600px">
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px"><h3>Jadwal WFH</h3></div>
		<div class="col-md-12">
			<?php if ($model) : ?>
				<?php $form = ActiveForm::begin(); ?>
				<table class="table table-hover">
					<tr>
						<th>Tanggal</th>
						<th>Action</th>
					</tr>
					<?php foreach($model as $model): ?>
					<tr>
						<td style="vertical-align: middle;" style="vertical-align: middle;"><?= (new \DateTime($model->tanggal, new \DateTimeZone(TIMEZONE)))->format('d-m-Y') ?></td>
						<td style="vertical-align: middle;">
							<?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete-jadwal-wfh', 'id'=>$model->id, 'nip'=>$model->NIP], [
								'class' => 'btn-sm btn-danger',
								'data' => [
									'confirm' => 'Apakah jadwal WFH ingin dihapus?',
									'method' => 'post',
								],
							]) ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</table>
				<?php ActiveForm::end(); ?>
			<?php else : ?>
				Data tidak ditemukan.
			<?php endif; ?>
		</div>
	</div>
</section>