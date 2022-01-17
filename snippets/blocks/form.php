<?php if ($form->showForm()) : ?>
    <form method="post" id="<?= $form->id() ?>" action="<?= $page->url() . "#" . $form->id() ?>" novalidate>

        <div class="form-block" style="display:grid;grid-gap: 1em 0.5em; grid-template-columns: repeat(12, 1fr);">
            <?php if (!$form->isValid()) : ?>
                <div class="form-block-message form-block-invalid column" style="grid-column: span 12">
                    <?= $form->errorMessage() ?>
                </div>
            <?php endif ?>

            <?php foreach ($form->fields() as $field) : ?>
                <?php $isFieldGroup = ($field->type() === 'formfields/radio' || $field->type() === 'formfields/checkbox') ?>
                <?php $fieldTag = $isFieldGroup ? 'fieldset' : 'div' ?>
                <?php $fieldGroupLabelTag = $isFieldGroup ? 'legend' : 'label' ?>
                <<?= $fieldTag ?> class="form-block-field form-block-field-<?= $field->type(true) ?>" style="grid-column: span <?= $field->width('grid') ?>" data-id="<?= $field->slug() ?>">
                    <<?= $fieldGroupLabelTag ?> for="input-<?= $field->slug() ?>-<?= $field->id() ?>" class="form-block-field-label">
                        <span class="form-block-field-label-text"><?= $field->label() ?></span>
                        <?php if ($field->required()->isTrue()) : ?>
                            <span class="form-block-field-label-required" aria-hidden="true">*</span>
                        <?php endif ?>
                    </<?= $fieldGroupLabelTag ?>>


                    <?php if (!$field->isValid()) : ?>
                        <span id="input-<?= $field->slug() ?>-<?= $field->id() ?>-error-message" class="form-block-message form-block-field-invalid"><?= $field->errorMessage() ?></span>
                    <?php endif ?>

                    <?= $field->toHtml() ?>
                </<?= $fieldTag ?>>
            <?php endforeach ?>

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
