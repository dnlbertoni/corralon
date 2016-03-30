<h1>Subir Lista CSV con Precio Publico Sugerido</h1>
<p>La estructura del archivo debe ser la siguiente:</p>
<p>BARRAS;PRODUCTO;COSTO (punto decimal) ;PRECIO (punto decimal) </p>
<?php echo $error; ?>
<?php echo form_open_multipart('articulos/subirListaLSDo'); ?>
<input type="file" name="userfile" size="20"/>
<br/>
<br/>
<input type="submit" value="upload"/>
<?php echo form_close() ?>