<?php if ($form->showForm()) : ?>

    <form method="post" id="<?= $form->id() ?>" action="<?= $page->url() . "#" . $form->id() ?>">

        <div class="form-block" style="display:grid;grid-gap: 1em 0.5em; grid-template-columns: repeat(12, 1fr);">

            <?php foreach ($form->fields() as $field) : ?>
                <div class="form-block-field form-block-field-<?= $field->type(true) ?>" style="grid-column: span <?= $field->width('grid') ?>" data-id="<?= $field->slug() ?>">
                    <?php if ($field->type() === 'formfields/radio' || $field->type() === 'formfields/checkbox') : ?>
                        <p for="<?= $field->slug() ?>" class="form-block-field-label">
                            <span class="form-block-field-label-text"><?= $field->label() ?></span>
                            <?php if ($field->required()->isTrue()) : ?>
                                <span class="form-block-field-label-required" aria-hidden="true">*</span>
                            <?php endif ?>
                        </p>
                    <?php else : ?>
                        <label for="<?= $field->slug() ?>" class="form-block-field-label">
                            <span class="form-block-field-label-text"><?= $field->label() ?></span>
                            <?php if ($field->required()->isTrue()) : ?>
                                <span class="form-block-field-label-required" aria-hidden="true">*</span>
                            <?php endif ?>
                        </label>
                    <?php endif ?>

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
