<?php

use Page\Mail as MailPage;
use Page\Login as LoginPage;

class mailCest
{
    public function _before(\AcceptanceTester $I)
    {
    }

    public function _after(\AcceptanceTester $I)
    {
    }

    public function testSendAndReplyEmail(\AcceptanceTester $I,  MailPage $mailPage, LoginPage $loginPage)
    {
        //user1 login
        $loginPage->login(USER1_EMAIL, USER1_PASSWORD);
        //user1 sends email to user2
        $mailPage->sendEmail(USER2_EMAIL, 'testTheme', 'send from user 1');
        //opening second windowd for user2 
        $user2 = $I->haveFriend('user2');
        $user2->does(function(AcceptanceTester $I) {
            $mailPage = new MailPage($I);
            $loginPage = new LoginPage($I);
            $I->amOnPage('/');
            //user2 login
            $loginPage->login(USER2_EMAIL, USER2_PASSWORD);
            //user2 checks if there is a new mail
            $isThereNewMailFromUser1 = FALSE;
            $isThereNewMailFromUser1 = $mailPage->checkForNewEmail(20);
            //user2 replies on the mail if it exist
            if ($isThereNewMailFromUser1 === TRUE) {
                $mailPage->replyOnMail('here is your reply from user2');
            }
            else
                $I->fail('mail have not recieved');
        });
        $isThereNewMailFromUser2 = FALSE;
        //user1 checks if there is a new mail
        $isThereNewMailFromUser2 = $mailPage->checkForNewEmail(20);
        //user1 replies on the mail if it exist
        if ($isThereNewMailFromUser2 === TRUE) {
            $mailPage->replyOnMail('here is your reply from user1');
        }
        else
            $I->fail('mail have not recieved');
    }
}
