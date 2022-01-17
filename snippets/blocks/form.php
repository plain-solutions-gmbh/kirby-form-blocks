<?php if ($form->showForm()) : ?>

	<form method="post" id="<?= $form->id() ?>" action="<?= $page->url() . "#" . $form->id() ?>">

		<div class="form-block" style="display:grid;grid-gap: 1em 0.5em; grid-template-columns: repeat(12, 1fr);" >

            <?php if($form->honeypot()->isTrue()) : ?>
                <div class="form-block-honeypot" style="position: absolute; left: -9999px;">
                    <label for="form-<?= $form->id() ?>-<?= $form->honeypot_name() ?>">Website <abbr title="required">*</abbr></label>
                    <input type="url" id="form-<?= $form->id() ?>-<?= $form->honeypot_name() ?>" name="<?= $form->honeypot_name() ?>" tabindex="-1" aria-hidden="true">
                </div>
            <?php endif ?>

			<?php foreach ($form->fields() as $field) : ?>
				<div
					class="form-block-field form-block-field-<?= $field->type(true) ?>"
					style="grid-column: span <?= $field->width('grid') ?>"
					data-id="<?= $field->slug() ?>"
					>

					<label for="<?= $field->slug() ?>"><?= $field->label() ?> <?= e($field->required()->isTrue(), " *") ?></label>

					<?php if (!$field->isValid()) : ?>
						<span class="form-block-message form-block-field-invalid"><?= $field->errorMessage() ?></span>
					<?php endif ?>

					<?= $field->toHtml() ?>
				</div>
			<?php endforeach ?>

			<?php if (!$form->isValid()) : ?>
				<div class="form-block-message form-block-invalid column" style="grid-column: span 12">
					<?= $form->errorMessage() ?>
				</div>
			<?php endif ?>

			<div class="form-block-button form-block-submit column" style="grid-column: span 12">
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