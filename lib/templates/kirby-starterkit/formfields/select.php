<select name="<?= $formfield->slug() ?>" id="<?= $formfield->slug() ?>">

    <option value="" disabled <?= e($formfield->value() == "", ' selected') ?>><?= $formfield->placeholder() ?></option>

    <?php foreach ($formfield->options() as $option) : ?>

        <option value="<?= $option->slug() ?>" <?= e($option->selected()->isTrue(), " selected") ?>>
            <?= $option->label() ?>
        </option>
    <?php endforeach ?>

</select>