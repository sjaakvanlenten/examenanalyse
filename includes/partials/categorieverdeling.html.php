<script type="text/javascript">
    $(function () {
        var data = [
<?php
$categoriedata = checkCategorie();
$gegevens = getAllExamquestionCategories();
$x = count($gegevens);
$e = 1;
foreach ($gegevens as $gegeven) {
    ?>
            { label: "<?php
    foreach ($categoriedata as $t) {
        $q = $t['categorieomschrijving'];
        if ($gegeven['categorie_id'] == $t['categorie_id']) {
            echo $q;
        }
    }
    ?>", data:"<?php echo $gegeven['count(categorie_id)']; ?>"}
    <?php
    $e++;
    if ($x >= $e) {
        echo",";
    }
    ?>
    <?php
}
?>
        ];
                var placeholder = $("#placeholder");
        placeholder.unbind();
        $("#title").text("Resultaten");
        $("#description").text("The pie can be tilted at an angle.");
        $.plot(placeholder, data, {
            series: {
                pie: {
                    innerRadius: 0.5,
                    show: true
                },

            },
            legend: {
                show: true

            },

             grid: {
                hoverable: true,
                clickable: true
            }
        });
        
    });
</script>
<style>
    .categorieverdeling-container {
        width: 100%;
        height: 450px;
    }
    .categorieverdeling-placeholder {
        width: 100%;
        height: 90%;
        font-size: 10px;
        line-height: 1.0em;
    }
    td.legendLabel {
    font-size: 19px !important;
}
</style>
<div class="categorieverdeling-container">
    <div id="placeholder" class="categorieverdeling-placeholder"></div>
    <div id="menu">
        <button style="display: none;" id="example-1"></button><!--deze regel niet weghalen-->

    </div>
</div>

