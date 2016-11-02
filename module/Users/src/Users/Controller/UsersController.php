<?php


namespace Users\Controller;

use Application\Controller\TemplateController;
use Application\Model\HashModel;
use Users\Entity\User;
use Users\Exception\MailModelException;
use Users\Exception\UserModelException;
use Users\Exception\UsersControllerException;
use Users\Form\AuthForm;
use Users\Form\RegistrationForm;
use Users\Model\UserModel;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Zend\View\Model\ViewModel;


class UsersController extends TemplateController
{

    /**
     * @var object EntityManager
     */
    public $em;

    /**
     * @var object FlashMessenger
     */
    protected $fm;

    /**
     * @var object UserModel
     */
    protected $userModel;

    /**
     * @var object MailModel
     */
    protected $mailModel;

    /**
     * @var object AuthModel
     */
    protected $authModel;

    /**
     * @var object Session
     */
    protected $authStorage;

    /**
     * @var object Application\Model\HashModel
     */
    protected $hashModel;

    /**
     * @var object Form
     */
    protected $form;

    /**
     * @param $container
     */
    public function __construct(
        $em,
        $userModel,
        $mailModel,
        $authModel,
        $authStorage
    )
    {
        $this->fm = $this->plugin(FlashMessenger::class);
        $this->em = $em;
        $this->userModel = $userModel;
        $this->mailModel = $mailModel;
        $this->authModel = $authModel;
        $this->authStorage = $authStorage;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $this->layout()->setVariable('active', 'home');
        return $this->getViewModel();
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function authAction()
    {
        $this->layout()->setVariable('active', 'auth');
        if($this->authStorage->read()[UserModel::AUTH_NAME_STAGING_USER_ID]) {
            return $this->redirect()->toRoute('goods', ['action' => 'products']);
        }
        $this->setForm(new AuthForm());
        $this->setViewModelsList(['form', 'fm']);

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->getViewModel();
        }

        if (!$this->userModel->checkIssetRegistrationUser($request->getPost('email'))) {
            $this->fm->addMessage(UserModel::MESSAGE_NOT_REGISTRATION);
            return $this->redirect()->toRoute('users', ['action' => 'auth']);
        }

        if ($this->authModel->isValid($request->getPost('password'), $request->getPost('email'))) {
            if (!$this->userModel->writeAuthInStorage($request->getPost('email'))) {
                throw new UsersControllerException('Error save date in Auth Storage');
            }
            return $this->redirect()->toRoute('home');
        }
        $this->setForm($this->getForm()->setData($request->getPost()));
        $password = $this->getForm()->get('password')->setMessages([UserModel::ERROR_EMAIL_OR_PASSWORD]);
        $this->setForm($this->getForm()->add($password));
        return $this->getViewModel();
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @throws MailModelException
     * @throws UserModelException
     */
    public function registrationAction()
    {
        $this->layout()->setVariable('active', 'registration');
        if($this->authStorage->read()[UserModel::AUTH_NAME_STAGING_USER_ID]) {
            return $this->redirect()->toRoute('goods', ['action' => 'products']);
        }
        $this->setForm(new RegistrationForm());
        $this->setViewModelsList(['form', 'fm']);

        $request = $this->getRequest();
        // check key registration
        if ($id = $this->params()->fromRoute('id', null)) {
            if ($this->userModel->checkRegistrationUrl($id)) {
                $this->fm->addMessage('You have successfully signed up');
                return $this->redirect()->toRoute('users', ['action' => 'auth']);
            }

            $this->fm->addMessage('Sorry Yor registration not exit! Pleas repeat');
            return $this->redirect()->toRoute('users', ['action' => 'registration']);
        }

        if (!$request->isPost()) {
            return $this->getViewModel();
        }

        $this->setForm($this->getForm()->setData($request->getPost()));
        // check identical passwords
        if ($request->getPost('password', null) != $request->getPost('repeat_password', null)) {
            $repeatPassword = $this->getForm()->get('repeat_password')->setMessages([UserModel::PASSWORDS_DO_NOT_MATH]);
            $this->setForm($this->getForm()->add($repeatPassword));
            return $this->getViewModel();
        }

        if (!$this->getForm()->isValid()) {
            return $this->getViewModel();
        }
        // save data registration
        if ($userFromDb = $this->userModel->getUserByEmail($request->getPost('email'))) {
            if ($userFromDb->getState()) {
                $this->fm->addMessage('This email: ' . $request->getPost('email') . ' Isset');
                return $this->redirect()->toRoute('users', ['action' => 'registration']);
            }

            $userFromDb->setOptions($request->getPost()->toArray());
            $userFromDb->setPassword($this->getHashModel()->hash($request->getPost('password')));

        } else {
            $user = new User($request->getPost()->toArray());
            $user->setPassword($this->getHashModel()->hash($request->getPost('password')))
                ->setRole($this->userModel->getAlcRole($request->getPost('email')));

            $this->em->persist($user);
        }
        $this->em->flush();

        $inviteId = $this->getHashModel()->getNumbersKey();
        // save key
        if (!$this->userModel->saveKey($request->getPost('email'), $inviteId)) {
            throw new UserModelException('Error save Key');
        }
        // send registrations mail
        $action = $this->params()->fromRoute('action');
        if (!$this->mailModel->sendRegistrationMail($request->getPost('email'), $action, $inviteId)) {
            throw new MailModelException('Error send Mail');
        }
        $this->fm->addMessage(UserModel::MESSAGE_SUCCESS_REGISTRATION);
        return $this->redirect()->toRoute('users', ['action' => 'auth']);
    }

    /**
     * @return \Zend\Http\Response
     */
    public function layoutAction() {
        if ($this->userModel->checkAuth()) {
            $this->authStorage->clear();
        }
        return $this->redirect()->toRoute('users', ['action' => 'auth']);
    }

    /**
     * @return HashModel
     */
    public function getHashModel()
    {
        if (!$this->hashModel) {
            $this->hashModel = new HashModel();
        }
        return $this->hashModel;
    }

    /**
     * @param $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return object
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return mixed|object
     */
    public function getFm()
    {
        return $this->fm;
    }


}