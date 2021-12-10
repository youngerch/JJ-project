<?php
class Alert
{
    private $_ci;
    public function __construct()
    {
        $this->_ci = &get_instance();
    }

    public function reload()
    {
        echo <<<END
    <!doctype html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
    <script>
        location.reload();
    </script>
    </body>
    </html>
END;
        exit;
    }


    public function url_move($url)
    {
        echo <<<END
    <!doctype html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
    <script>
        location.href = "{$url}";
    </script>
    </body>
    </html>
END;
        exit;
    }


    public function error($msg, $title = "BOOK CAFE", $url = '')
    {
        echo <<<END

    <!doctype html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/alertify.min.js"></script>
        <link rel="stylesheet" href="/assets/css/alertify.css" />
        <script>const __TITLE__ = "BookCafe";var ablex = {};</script>
        <script src="/assets/js/alert.js"></script>
    </head>
    <body>
    <script>
        ablex.alert.error("{$msg}", "{$title}", "{$url}");
    </script>
    </body>
    </html>
END;
        exit;
    }

    public function callback($msg, $title = "BOOK CAFE")
    {
        echo <<<END

    <!doctype html>
    <html lang="ko">
    <head>
        <meta charset="UTF-8">
        <script src="/assets/js/jquery-3.2.1.min.js"></script>
        <script src="/assets/js/vendors/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="/assets/css/cielclub.css">
        <script>var ablex = {};const __TITLE__ = "BOOK CAFE";</script>
        <script src="/assets/js/alert2.js"></script>
    </head>
    <body>
    <script>
        ablex.alert.callback("{$msg}", "{$title}");
    </script>
    </body>
    </html>
END;
        exit;
    }



}
