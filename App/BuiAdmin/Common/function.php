<?php

    function getSn() {
        list($usec, $sec) = explode(' ', microtime());
        return date('ymdHis', $sec) . substr($usec, 3, 3) . rand(10000, 99999);
    }

