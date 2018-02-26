<style type="text/css">
    .box_preview_cetak, .box_preview_preview{
        cursor: pointer;
        font-weight: bold;
        color: #1C4675;
        border: 2px solid #C0C0FF;
        background-color: #ECF0F5;
        text-align: center;
        border-radius: 0;
        margin: 3px;
        display: inline-block;
        height: 100px;
        width: 100px;
    }

    .box_preview_cetak:hover, .box_preview_preview:hover{
        background-color: #dedede;
    }
</style>
<div style="width: 490px">
    <header>
        <h3><?= $title ?> Evaluasi Renja</h3>
    </header>
    <div class="module_content">
<?php
    $add_link = "/";
    if (!is_null($pusat)) {
      $add_link .= $pusat;
    }
    foreach ($preview_data as $key => $value) {
?>
        <div href="<?= site_url('evaluasi_renja/'.$link.'/'.$value->triwulan.$add_link) ?>" class="<?= $class ?>">
            <img style="opacity: 0.8; margin-top: 5px; margin-left: -5px;" width="50px" src="<?= base_url('asset/images/detail.png') ?>">
            <BR>Tahun <?= $value->tahun ?>
            <BR>Triwulan <?= $this->triwulan[$value->triwulan]["romawi"] ?>
        </div>
<?php
    }
?>
    </div>
</div>
<script type="text/javascript">
    $(".box_preview_preview").click(function(){
        var link_redirect = $(this).attr("href");
        window.location.href = link_redirect;
    });

    $(".box_preview_cetak").click(function(event){
        event.preventDefault();
        window.open($(this).attr("href"));
    });
</script>
