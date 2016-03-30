<?php echo $error; ?>
<?php echo form_open_multipart('iva/subirCSVDo'); ?>
    <input type="file" name="userfile" size="20"/>
    <br/>
    <br/>
    <input type="submit" value="upload"/>
<?php echo form_close() ?>