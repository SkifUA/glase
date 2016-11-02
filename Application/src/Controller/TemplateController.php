<?php

namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class TemplateController extends AbstractActionController
{

    /**
     * list names for ViewModels
     * @var array
     */
    protected $viewModelsList = [];

    /**
     * @var array
     */
    protected $dateResponseJson = [
        'error' => [],
        'data' => [],
        'info' => []
    ];

    /**
     * list names
     * @param $array
     * @throws \Exception
     */
    public function setViewModelsList($array=[])
    {
        if ($array === null) {
            $this->viewModelsList = [];
        }

        if (!is_array($array)) {
            throw new \Exception ('ViewModalsArray is not array in '.__NAMESPACE__);
        }

        $result = $this->getViewModelsList();
        foreach ($array as $row) {
            if (!in_array($row, $result)) {
                $result[] = $row;
            }
        }

        $this->viewModelsList = $result;
    }

    /**
     * @return array
     */
    public function getViewModelsList()
    {
        return $this->viewModelsList;
    }

    public function getDateResponseJson() {
        return $this->dateResponseJson;
    }

    /**
     * @param $key
     * @param $data
     */
    public function setErrorResponseJson($key, $data)
    {
        $result = $this->getDateResponseJson();
        $result['error'][$key] = $data;
        $this->dateResponseJson = $result;
    }

    /**
     * @param $key
     * @param $data
     */
    public function setDataResponseJson($key, $data)
    {
        $result = $this->getDateResponseJson();
        $result['data'][$key] = $data;
        $this->dateResponseJson = $result;
    }

    /**
     * @param $key
     * @param $data
     */
    public function setInfoResponseJson($key, $data)
    {
        $result = $this->getDateResponseJson();
        $result['info'][$key] = $data;
        $this->dateResponseJson = $result;
    }

    /**
     * @return ViewModel
     * @throws \Exception
     */
    public function getViewModel($layout=true)
    {
        $result = [];
        foreach ($this->getViewModelsList() as $row) {
            if ($var = $this->__get($row)) {
                $result[$row] = $var;
            }
        }

        $viewModel = new ViewModel($result);
        if (!$layout) {
            $viewModel->setTerminal(true);
        }

        return $viewModel;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function responseJson()
    {
        $response = $this->getResponse();

        $jsonObjectWithExpression = \Zend\Json\Json::encode(
            $this->getDateResponseJson(),
            true,
            array('enableJsonExprFinder' => true)
        );

        return $response->setContent($jsonObjectWithExpression);
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        $method = 'get' . $name;

        if (!method_exists($this, $method)) {
            throw new \Exception('Invalid Method for get '. $name .' ViewModal in ' . __NAMESPACE__);
        }
        return $this->$method();
    }
}