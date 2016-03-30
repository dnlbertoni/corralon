<div id="estados">
    <?php $aux = '';
    $tit = false; ?>
    <?php foreach ($numeros as $item): ?>
    <?php if ($aux != $item->subrubro): ?>
    <?php if ($tit): ?>
    </table>
</div>
<?php $tit = false; ?>
<?php endif; ?>
<h3><a href="#"><?php echo $item->subrubro ?></a></h3>
<?php $tit = true;
$aux = $item->subrubro; ?>
<div>
    <table>
        <?php endif; ?>
        <tr>
            <td><?php echo $item->submarca ?></td>
            <td class="numero"><?php echo $item->cantidad ?></td>
        </tr>
        <?php endforeach; ?>
</div>
<script>
    $(document).ready(function () {
        //$("#estados").accordion({autoHeight: false});
        $("#estados").css('width', '48%');
        $("td").css('padding', '2px');
        $('.numero').css('text-align', 'right');
    });
</script>

