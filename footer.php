    <?php wp_footer(); ?>

    <?php if(get_option("tuffin_general_ga_ua")){ ?>
    <script type="1092b8c34cd5d825bfdf20e9-text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo get_option("tuffin_general_ga_ua"); ?>');
    </script>
    <?php } ?>
</body>
</html>