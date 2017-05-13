<?php
namespace Page;

class Login
{
    public static $emailInput = ['id' => 'identifierId'];
    public static $nextButton = ['id' => 'identifierNext'];
    public static $passwordNext = ['id' => 'passwordNext'];
    public static $passwordInput = ['name' => 'password'];
    public static $letters = ['xpath' => '//div[@role="main"]'];

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    /**
     * login in gmail
     */
    public function login($email, $password)
    {
        $I = $this->tester;

        $I->amOnPage('/');
        $I->waitForElement(self::$emailInput, 10);
        $I->click(self::$emailInput);
        $I->fillField(self::$emailInput, $email);
        $I->click(self::$nextButton);
        $I->waitForElementVisible(self::$passwordInput, 10);
        $I->wait(1);
        $I->click(self::$passwordInput);
        $I->fillField(self::$passwordInput, $password);
        $I->click(self::$passwordNext);
        $I->waitForElement(self::$letters, 10);
    }
}

