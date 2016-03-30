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


<div class="demo">

    <ul id="sortable1" class='dropfalse'>
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

    <br clear="both"/>

</div><!-- End demo -->


<div class="demo-description">
    <p>
        Prevent all items in a list from being dropped into a separate, empty list
        using the <code>dropOnEmpty</code> option set to <code>false</code>. By default,
        sortable items can be dropped on empty lists.
    </p>
</div><!-- End demo-description -->
