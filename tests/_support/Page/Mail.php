<?php
namespace Page;

class Mail
{
    public static $createNewEmail = ['xpath' => '//div[text()="НАПИСАТЬ"]'];
    public static $newEmailPopup = ['xpath' => '//div[@role="dialog"]'];
    public static $sender = ['name' => 'to'];
    public static $themeInput = ['name' => 'subjectbox'];
    public static $mailTextInput = ['xpath' => '//div[@role="textbox"]'];
    public static $sendButton = ['xpath' => '//div[text()="Отправить"]'];
    public static $alertMessage = ['xpath' => '//div[@role="alert"]'];
    public static $refresh = ['xpath' => '//div[@act="20"]'];
    public static $inboxButton = ['xpath' => '//a[contains(@title, "Входящие")]'];
    public static $firstEmailInbox = ['xpath' => '//div[@class="Cp"]//tbody/tr[1]'];
    public static $replyButton = ['xpath' => '//div[@data-tooltip="Ответить"]'];
    public static $editBoxForReply = ['xpath' => '//div[@aria-label="Тело письма"]'];

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    /**
     * sends email
     * @param string $email - whom to send mail
     * @param string $theme - theme of the mail
     * @param string $text - text of the mail
     */
    public function sendEmail($email, $theme, $text)
    {
        $I = $this->tester;

        $I->click(self::$createNewEmail);
        $I->waitForElementVisible(self::$newEmailPopup, 10);
        $I->click(self::$sender);
        $I->fillField(self::$sender, $email);
        $I->click(self::$themeInput);
        $I->fillField(self::$themeInput, $theme);
        $I->click(self::$mailTextInput);
        $I->fillField(self::$mailTextInput, $text);
        $I->click(self::$sendButton);
        $I->waitForText('Письмо отправлено', 10, self::$alertMessage);
    }

    /**
     * checks if there  is a new email in inbox
     * @param string $maxTimeRefresh - how many times refresh the page
     * @return true - if there is a new email, otherwise - false
     */
    public function checkForNewEmail($maxTimeRefresh)
    {
        $I = $this->tester;

        $i = 0;
        $gotNewEmail = FALSE;
        while (($i < $maxTimeRefresh) and ($gotNewEmail === FALSE)) {
            $I->waitForElementVisible(self::$refresh, 10);
            $I->click(self::$refresh);
            $firstMessageClass = $I->grabAttributeFrom(self::$firstEmailInbox, 'class'); 
            if ($this->isInStr($firstMessageClass, 'zE') === TRUE) {
                $gotNewEmail = TRUE;
            }
            $i += 1;
        }
        return $gotNewEmail;
    }

    /**
     * replies on the mail
     * @param $text - text of the mail
     */
    public function replyOnMail($text)
    {
        $I = $this->tester;

        $I->click(self::$firstEmailInbox);   
        $I->waitForElementVisible(self::$replyButton);
        $I->click(self::$replyButton);
        $I->click(self::$editBoxForReply);
        $I->fillField(self::$editBoxForReply, $text);
        $I->click(self::$sendButton);
        $I->waitForText('Письмо отправлено', 10, self::$alertMessage);
    }

    /**
     * checks if string contain the substring
     * @param $str - string
     * @param $substr - substring
     * @return true - if string contain the substring, otherwise - false
     */
    protected function isInStr($str, $substr)
    {
        $result = strpos($str, $substr);
        if ($result === FALSE)
            return FALSE;
        else
            return TRUE;   
    }
}
