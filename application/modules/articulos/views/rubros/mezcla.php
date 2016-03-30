<meta charset="utf-8">
<style>
    #sortable1, #sortable2, #sortable3 {
        list-style-type: none;
        margin: 0;
        padding: 0;
        float: left;
        margin-right: 10px;
        background: #eee;
        padding: 5px;
        width: 143px;
    }

    #sortable1 li, #sortable2 li, #sortable3 li {
        margin: 5px;
        padding: 5px;
        font-size: 1.2em;
        width: 120px;
    }
</style>
<script>
    $(function () {
        $("ul").sortable({
            connectWith: "ul"
        });

        $("#sortable1, #sortable2, #sortable3").disableSelection();
    });
</script>
<ul id="sortable1" class='dropfalse'>
    <h2><?php echo $titulo ?></h2>
    <?php foreach ($subrubros as $subr): ?>
        <li class="ui-state-default"><?php echo $subr->nombre ?></li>
    <?php endforeach; ?>
</ul>

<ul id="sortable2" class='droptrue'>
    <?php foreach ($generales as $gral): ?>
        <li class="ui-state-highlight"><?php echo $gral->nombre ?></li>
    <?php endforeach; ?>
</ul>
<ul id="sortable3" class='droptrue'>
</ul>