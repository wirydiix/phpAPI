<?php

use yii\helpers\Url;

class AboutCest
{
    public function ensureThatAboutWorks(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/app/about'));
        $I->see('About', 'h1');
    }
}
