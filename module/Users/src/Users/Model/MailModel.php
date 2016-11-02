<?php

namespace Users\Model;


use Zend\Mime\Message;
use Zend\Mime\Part;

class MailModel
{

    /**
     * @var string
     */
    protected $urlServer;
    /**
     * @var object Message
     */
    protected $mailService;
    /**
     * @var object Smtp
     */
    protected $mailTransport;

    const PATH_USERS_CONTROLLER = 'users';


    public function __construct($urlServer, $mailService, $mailTransport)
    {
        $this->urlServer = $urlServer;
        $this->mailService = $mailService;
        $this->mailTransport = $mailTransport;
    }

    /**
     * @param $email
     * @param $action
     * @param $inviteId
     * @return bool
     */
    public function sendRegistrationMail($email, $action, $inviteId) {

        $path = $this->urlServer . '/' . self::PATH_USERS_CONTROLLER . '/'. $action .'/'. $inviteId;

        $body = new Message();
        $template =  $this->getTemplateMail($path);
        $htmlPart = new Part($template);
        $htmlPart->type = 'text/html';
        $body->setParts(array($htmlPart));

        $mail = $this->mailService;
        $mail->addTo($email);
        $mail->setBody($body);
        $mail->setSubject('Auction: registration');

        $transport = $this->mailTransport;
        try {
            $transport->send($mail);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $path
     * @return string
     */
    public function getTemplateMail($path)
    {
        $template =
        "<html>
            <body>
                <p>Please register on the site
                    <a style='text-decoration: none; color: #428bca' href=".$path.">It is Your link for registration
                    </a>.
                </p>
            </body>
        </html>";
        return $template;
    }

}