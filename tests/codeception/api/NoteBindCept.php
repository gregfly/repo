<?php

/*$I = new ApiGuyTester($scenario);

$I->wantTo('bind note 1');

$I->sendGET('/note/bind', [
    'tid' => 1,
    'reqs' => [
        [
            'UserID' => 'jksdadsasd',
            'NoteID' => 1,
            'NoteActionID' => 1,
        ],
    ],
]);

$I->seeResponseContains('qqqqq');
/*$I->amGoingTo('login without data');
$I->sendPOST('/user/login?_format=json', []);
$I->seeResponseCodeIs(400);
$I->seeResponseContains('Login');
$I->seeResponseContains('Password');
$I->seeResponseIsJson();

$I->amGoingTo('login with incorrect data');
$I->sendPOST('/user/login?_format=json', ['Login' => 'mmm', 'Password' => 'mmm']);
$I->seeResponseCodeIs(401);
$I->seeResponseIsJson();

$I->amGoingTo('login with correct data');
$I->sendPOST('/user/login?_format=json', ['Login' => 'ponor.ns', 'Password' => 'mkysyz']);
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();*/