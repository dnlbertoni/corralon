<h1>Subir Lista CSV</h1>
<p>La estructura del archivo debe ser la siguiente:</p>
<p>BARRA;PRODUCTO;COSTO</p>
<?php echo $error; ?>
<?php echo form_open_multipart('articulos/subirListaASDo'); ?>
<input type="file" name="userfile" size="20"/>
<br/>
<br/>
<input type="submit" value="upload"/>
<?php echo form_close() ?>