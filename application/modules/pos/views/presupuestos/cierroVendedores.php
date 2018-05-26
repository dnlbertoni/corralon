<div class="row">
    <div class="col-lg-12">
        <div class="btn-group" id="vendedores" data-toggle="buttons">
            <?php foreach ( $vendedores as $vendedor ): ?>
                <label class="btn btn-default">
                    <input type="radio" name="vendedor"
                           value="<?= $vendedor->id ?>"/><?= $vendedor->nombre ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
</div>