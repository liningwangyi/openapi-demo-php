<!DOCTYPE html>
<?php
require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Http.php");
require_once(__DIR__ . "/api/Auth.php");

?>
<html>
    <head>
        <title>jsapi demo</title>
        <link rel="stylesheet" href="/public/stylesheets/style.css" type="text/css" />
        <!-- config中signature由jsticket产生，若jsticket失效，则signature失效，表现为dd.error()返回“权限校验失败”之错误。 -->
        <!-- 在请求新的jsticket之后，旧的ticket会失效，导致旧ticket产生的signature失效。 -->
        <script type="text/javascript">var _config = <?php echo Auth::getConfig();?></script>
        <script type="text/javascript" src="/public/javascripts/zepto.min.js"></script>
        <script type="text/javascript" src="https://g.alicdn.com/ilw/ding/0.3.8/scripts/dingtalk.js"></script>
    </head>
    <body>
        <script type="text/javascript" src="/public/javascripts/logger.js"></script>
        <script type="text/javascript" src="/public/javascripts/demo.js"></script>
    </body>
</html>