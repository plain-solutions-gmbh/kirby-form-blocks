<?php $form = $block; ?>
<?php if ($form->showForm()) : ?>

	<form method="post" id="<?= $form->id() ?>" action="<?= $page->url() . "#" . $form->id() ?>" novalidate>

		<div class="form-block">

			<?php foreach ($form->fields() as $field) : ?>

				<?php $isFieldGroup = ($field->type() === 'formfields/radio' || $field->type() === 'formfields/checkbox') ?>
				<?php $gtag = $isFieldGroup ? 'legend' : 'label' ?>
				<?php $ltag = $isFieldGroup ? 'fieldset' : 'div' ?>

				<<?= $ltag ?> class="form-block-field form-block-field-<?= $field->type(true) ?>" data-id="<?= $field->slug() ?>">
					<<?= $gtag ?> for="<?= $field->slug() ?>">

						<span class="form-block-field-label-text"><?= $field->label() ?></span>
						<span class="form-block-field-label-required" aria-hidden="true"><?= $field->required('asterisk') ?></span>

					</<?= $gtag ?>>

					<?php if (!$field->isValid()) : ?>
						<span id="<?= $field->id() ?>-error-message" class="form-block-message form-block-field-invalid"><?= $field->errorMessage() ?></span>
					<?php endif ?>

					<?= $field->toHtml() ?>

				</<?= $ltag ?>>
			<?php endforeach ?>

			<div class="form-block-field form-block-field-hpot">
				<label for="<?= $form->honeypotId() ?>" aria-hidden="true"> <?= ucfirst($form->honeypotId()) ?></label>
				<input type="search" id="<?= $form->honeypotId() ?>" name="<?= $form->honeypotId() ?>" value="" autocomplete="off" tabindex="1000" required />
			</div>

			<?php if (!$form->isValid()) : ?>
				<div class="form-block-message form-block-invalid column">
					<?= $form->errorMessage() ?>
				</div>
			<?php endif ?>

			<div class="form-block-button form-block-submit column">
				<input type="submit" name="<?= $form->id() ?>" value="<?= $form->message('send_button') ?>">
			</div>
		</div>
	</form>
<?php endif ?>

<?php if ($form->isFatal()) : ?>
	<div class="form-block-message form-block-fatal column">
		<?= $form->errorMessage() ?>
	</div>
<?php endif ?>

<?php if ($form->isSuccess()) : ?>
	<div class="form-block-message form-block-success column">
		<?= $form->successMessage() ?>
	</div>
<?php endif ?>

<style>
	.form-block-field-hpot {
		opacity: 0.001;
		position: absolute;
		z-index: -1;
	}
</style>