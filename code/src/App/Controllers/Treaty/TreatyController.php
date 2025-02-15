<?php

namespace App\Controllers\Treaty;

use App\Models\Check;
use App\Models\company\Company;
use App\Models\regions\RegionsModel;
use App\Models\Treaty\Treaty;
use App\Utils\TemplatesEngine;


class TreatyController
{
    public $filterTreaty = null;
    public $filterTreatyName = null;
    public $filterTreatyBin = null;
    public $filterCount = null;

    public function __construct()
    {
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $this->check = new Check($userRole);
    }

    public function index(): void
    {
        $page = (int)($_GET['page'] ?? null);
        $count = (int)($_GET['count'] ?? null);
        if ($page < 1) {
            $page = null;
        }

        $this->filter(page: $page,limit: $count);
        $valueName = $this->filterTreatyName !== null ? $this->filterTreatyName : '';
        $valueBin = $this->filterTreatyBin !== null ? $this->filterTreatyBin : '';
        $treatyModel = new Treaty();
        $treatiesAll = $treatyModel->getAllTreaties(page: $page,limit: $count);
        $treaties = $this->filterTreaty !== null ? $this->filterTreaty : $treatiesAll;
        $totalAmount = $this->filterTreaty !== null ? $this->filterCount : $treatyModel->getTreatiesNumber();
        if (isset($_SESSION['user_id'])) {
            echo TemplatesEngine::render('layout', [
                'content' => TemplatesEngine::render('treaty/index', [
                    'treaties' => $treaties,
                    'valueBin' => $valueBin,
                    'valueName' => $valueName,
                    'totalAmount' => $totalAmount
                ]),
                'title' => 'Treaties list',
            ]);
        }else{
            header("Location: /auth/login");
        }

//        require_once ROOT_DIR . '/templates/company/index.php';
    }

    public function create(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
        }
//        var_dump('create');

        echo TemplatesEngine::render('layout', [
            'content' => TemplatesEngine::render('treaty/create', []),
            'title' => 'Reestr zapisi'
        ]);
    }

    public function store(): void
    {
//        var_dump('---------------------->',$_POST);
//
//        echo '<pre>';
//        var_dump('---------------------->',$_FILES);echo '</pre>';
//
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
        }
        if (isset($_POST['number_treties'])) {
            $number_treties = trim($_POST['number_treties']);
            $contractor = trim($_POST['contractor']);
            $iniciator = trim($_POST['iniciator']);
            $subject = trim($_POST['subject']);
            $sum = trim($_POST['sum']);
            $sum_service = trim($_POST['sum_service']);
            $created_at = trim($_POST['created_at']);


            if (empty($number_treties)) {
                echo "number_treties is required";
                return;
            }

            $file = $_FILES['file'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                die("Ошибка при загрузке файла.");
            }
            $file_content = file_get_contents($file['tmp_name']);

            $treatyModel = new Treaty();
            $treatyModel->createTreaty(
                $number_treties,
                $contractor,
                $iniciator,
                $subject,
                $sum,
                $sum_service,
                $created_at,
                $file,
                $file_content
            );
        }
//        exit;
        header('Location: /treaties?page=1&count=30');
    }

    public function info($params){
        if (!isset($_SESSION['user_id'])) {
            header("Location: /auth/login");
        }
        $treatyModel = new Treaty();
        $treaty = $treatyModel->getTreatiesById($params['id']);

        if (!$treaty) {
            echo "treaty not found";
            return;
        }

        echo TemplatesEngine::render('layout', [
            'content' => TemplatesEngine::render('treaty/info', [
                'treaty' => $treaty
            ]),
            'title' => 'Company info',
        ]);
    }

    public function filter($page, $limit = 30){
        $this->filterCompaniesName = $_GET['company_name_filter'] ?? null;
        $this->filterCompaniesBin = $_GET['company_bin_filter'] ?? null;
        $this->filterCompaniesRegion = $_GET['company_region_filter'] ?? null;
        if (isset($_GET['company_name_filter']) || isset($_GET['company_bin_filter']) || isset($_GET['company_region_filter'])) {
            $company_name = trim($_GET['company_name_filter']) ?? '';
            $company_bin = trim($_GET['company_bin_filter']) ?? '';
            $company_region = isset($_GET['company_region_filter'])? trim($_GET['company_region_filter']) : '';

            $companyModel = new Company();
            $this->filterCompanies = $companyModel->filter($company_name, $company_bin, $company_region, (int)$page, $limit = 30);
            $this->filterCount = $companyModel->filterCount($company_name, $company_bin, $company_region);
        }else{
            $this->filterCompanies = null;
        }
    }

    public function delete($params): void
    {
        $treatyModel = new Treaty();
        $treatyModel->deleteTreaty($params['id']);

        header('Location: /treaties?page=1&count=30');
    }

    public function edit($params): void
    {
        if (!isset($_SESSION['user_id'])) { header("Location: /auth/login");}
        $treatyModel = new Treaty();
        $treaty = $treatyModel->getTreatiesById($params['id']);

        echo TemplatesEngine::render('layout', [
            'content' => TemplatesEngine::render('treaty/edit', [
                'treaty' => $treaty,
            ]),
            'title' => 'Treaty edit',
        ]);
    }

    public function update($params): void
    {
        if (isset($params['id']) && isset($_POST['company_name']) && isset($_POST['email'])) {
            $id = trim($params['id']);
            $treaty_number = $_POST['number'] ?? '';
            $iniciator = $_POST['iniciator'] ?? '';
            $contractor = $_POST['contractor'] ?? '';
            $subject = $_POST['subject'] ?? '';
            $sum = $_POST['sum'] ?? '';
            $sum_service = $_POST['sum_service'] ?? '';
            $file_name = $_POST['file_name'] ?? '';
            $file_type = $_POST['file_type'] ?? '';
            $file_path = $_POST['file_path'] ?? '';

            if (empty($treaty_number)) {
                echo "treaty number is required";
                return;
            }

            $treatyModel = new Treaty();
            $treatyModel->updateTreaty($id, $treaty_number,$iniciator, $contractor, $subject, $sum, $sum_service, $file_name, $file_type,$file_path);
        }

        header('Location: /treaties?page=1&count=30');
    }

    public function qa($title = 'QA:', $value = ''): void
    {
        echo '<br/>';
        var_dump("$title __: ", $value);
        echo '<br/>';
    }

    public function getAllRowsCompanies(){
        $companyModel = new Company();
        $rows = $companyModel->getCompanies();
        return $rows;
    }

    public function getCompaniesNumber():int{
        $companyModel = new Company();
        if($this->filterCompanies !== null){
            echo "this filter not null". $this->filterCompanies;echo "<br>";
        }else{
            echo "gilter null". $this->filterCompanies;echo "<br>";
        }
        $rows = $this->filterCompanies !== null ?  $this->filterCount: $companyModel->getCompaniesNumber();
        return $rows;
    }
}