<?php

foreach (glob(__DIR__ . "/Helpers/*.php") as $file) {
    include_once $file;
}