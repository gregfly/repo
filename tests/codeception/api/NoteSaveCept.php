<?php

$I = new ApiGuyTester($scenario);

$I->wantTo('save note 1');

$I->sendGET('/note/save', [
    'acts' => [
        [
            'UserID' => 'jksdadsasd',
            'NoteID' => 1,
            'Type' => 'w',
            'CursorBegin' => 13,
            'CursorEnd' => 13,
            'String' => ' ',
            'Timestamp' => time(),
        ],
        [
            'UserID' => 'jksdadsasd',
            'NoteID' => 1,
            'Type' => 'w',
            'CursorBegin' => 14,
            'CursorEnd' => 14,
            'String' => 'ё',
            'Timestamp' => time(),
        ],
        [
            'UserID' => 'jksdadsasd',
            'NoteID' => 1,
            'Type' => 'w',
            'CursorBegin' => 15,
            'CursorEnd' => 15,
            'String' => 'л',
            'Timestamp' => time(),
        ],
    ],
]);

//$I->seeResponseContains('');