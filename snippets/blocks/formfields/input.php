
<input
    type="<?= $formfield->inputtype() ?>"
    id="input-<?= $formfield->slug() ?>-<?= $formfield->id() ?>"
    name="<?= $formfield->slug() ?>"
    value="<?= $formfield->value() ?>"
    <?= e($formfield->required()->isTrue(), "required") ?>
    <?= e($formfield->isInvalid(), "invalid") ?>
    <?= e($formfield->isInvalid(), "aria-describedby='input-" . $formfield->slug() . '-' . $formfield->id() . "-error-message'") ?>
    />