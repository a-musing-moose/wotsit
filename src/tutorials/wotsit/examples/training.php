<?php
//initialise storage object with previously learned data
$storage = new wotsit_storage_Sqlite('new.db');

$extractor = new wotsit_feature_WordExtractor(); //NB: You can also pass in a stemmer if you desire
$classfier = new wotsit_classifier_NaiveBayesian($extractor, $storage);

$goodData = array(); //should be examples of documents which are in the category 'good'
$badData = array(); //should be examples of documents which are in the category 'bad'

//Adding examples of 'good' data
foreach ($goodData as $goodDatum) {
    $classfier->train($goodDatum, 'good');
}

//adding examples of 'bad' data
foreach ($badData as $badDatum) {
    $classfier->train($badDatum, 'bad');
}