<?php
//initialise storage object with previously learned data
$storage = new wotsit_storage_Sqlite('data.db');

$extractor = new wotsit_feature_WordExtractor();
$classfier = new wotsit_classifier_NaiveBayesian($extractor, $storage);

//classify the passed in text
$category = $classfier->classify($someText);