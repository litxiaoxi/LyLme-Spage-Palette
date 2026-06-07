    <!-- 底部信息 -->
    <footer class="site-footer">
        <div class="footer-content">
            <?php if (!empty(theme_config('gonganbei', ""))) : ?>
                <?php preg_match_all('/\d+/', theme_config('gonganbei'), $gab); ?>
                <span class="footer-item">
                    <a href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=<?php echo $gab[0][0]; ?>" target="_blank" rel="nofollow noopener" class="footer-link">
                        <img src="/assets/img/icp.png" alt="公安网备" width="14" height="14" style="vertical-align:middle">
                        <?php echo theme_config('gonganbei'); ?>
                    </a>
                </span>
            <?php endif; ?>
            <?php if ($conf['icp'] != null) : ?>
                <span class="footer-item">
                    <a href="http://beian.miit.gov.cn/" target="_blank" class="footer-link"><?php echo $conf['icp']; ?></a>
                </span>
            <?php endif; ?>
            <span class="footer-item"><?php echo $conf['copyright']; ?></span>
            <?php if ($conf['wztj'] != null) : ?>
                <span class="footer-item"><?php echo $conf["wztj"]; ?></span>
            <?php endif; ?>
        </div>
    </footer>

    <script>
        var backgroundimg = "url(<?php echo background(); ?>)";
        var background_video = "<?php echo theme_config('background_video'); ?>";
    </script>
    <script src="<?php echo $cdnpublic ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $templatepath; ?>/js/script.js"></script>
    <script src="<?php echo $templatepath; ?>/js/tool.js"></script>
    <script>
        function solist() {
            return <?php echo $json ?>;
        }
    </script>
</div>
</body>
</html>
