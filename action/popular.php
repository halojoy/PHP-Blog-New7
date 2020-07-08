<?php

$view->header();

$numviews = $db->getViews();
$view->listViews($numviews);

$view->footer();
    