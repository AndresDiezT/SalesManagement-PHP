<?php
require_once __DIR__ . "/Controllers/HomeController.php";
require_once __DIR__ . "/Controllers/ProductsController.php";
require_once __DIR__ . "/Controllers/SettingsProductsController.php";
require_once __DIR__ . "/Controllers/ClientsController.php";
require_once __DIR__ . "/Controllers/EmployeesController.php";
require_once __DIR__ . "/Controllers/SalesController.php";
require_once __DIR__ . "/Controllers/SettingsSalesController.php";

require_once __DIR__ . "/Helpers/AuthMiddleware.php";

$homeController = new HomeController();
$productsController = new ProductsController();
$settingsProductsController = new settingsProductsController();
$clientsController = new ClientsController();
$employeesController = new EmployeesController();
$salesController = new SalesController();
$settingsSalesController = new settingsSalesController();

$route = isset($_GET['route']) ? $_GET['route'] : 'index';

switch ($route) {
    case "login":
        $employeesController->login();
        break;
    case "logout":
        $employeesController->logout();
        break;

    default:
        AuthMiddleware::checkAuth();
        switch ($route) {
            case "index":
                $homeController->index();
                break;

            case "products":
            case "products/index":
                $productsController->index();
                break;

            case "products/details":
                if (isset($_GET["cod"])) {
                    $productsController->details($_GET['cod']);
                } else {
                    echo "Codigo no proporcionado";
                }
                break;

            case "products/create":
                $productsController->create();
                break;

            case "products/edit":
                if (isset($_GET["cod"])) {
                    $productsController->update($_GET['cod']);
                } else {
                    echo "Codigo no proporcionado";
                }
                break;

            case "products/delete":
                if (isset($_GET["cod"])) {
                    $productsController->delete($_GET['cod']);
                } else {
                    echo "Codigo no proporcionado";
                }
                break;

            case "products/export":
                $productsController->exportProducts();
                break;

            case "products/settings":
                $settingsProductsController->settings();
                break;

            case "products/settings/states/create":
                $settingsProductsController->createState();
                break;

            case "products/settings/states/edit":
                $settingsProductsController->updateState();
                break;

            case "products/settings/states/delete":
                if (isset($_GET["id"])) {
                    $settingsProductsController->deleteState($_GET['id']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "products/settings/categories/create":
                $settingsProductsController->createCategory();
                break;

            case "products/settings/categories/edit":
                $settingsProductsController->updateCategory();
                break;

            case "products/settings/categories/delete":
                if (isset($_GET["id"])) {
                    $settingsProductsController->deleteCategory($_GET['id']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "clients":
            case "clients/index":
                $clientsController->index();
                break;

            case "clients/details":
                if (isset($_GET["id_client"])) {
                    $clientsController->details($_GET['id_client']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "clients/create":
                $clientsController->create();
                break;

            case "clients/edit":
                if (isset($_GET["id_client"])) {
                    $clientsController->update($_GET['id_client']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "clients/delete":
                if (isset($_GET["id_client"])) {
                    $clientsController->delete($_GET['id_client']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "clients/export":
                $clientsController->exportClients();
                break;

            case "employees":
            case "employees/index":
                $employeesController->index();
                break;

            case "employees/create":
                $employeesController->create();
                break;

            case "employees/details":
                if (isset($_GET["id_employee"])) {
                    $employeesController->details($_GET['id_employee']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "employees/edit":
                if (isset($_GET["id_employee"])) {
                    $employeesController->update($_GET['id_employee']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "employees/delete":
                if (isset($_GET["id_employee"])) {
                    $employeesController->delete($_GET['id_employee']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "employees/export":
                $employeesController->exportEmployees();
                break;

            case "sales":
            case "sales/index":
                $salesController->index();
                break;

            case "sales/details":
                if (isset($_GET["nro_invoice"])) {
                    $salesController->details($_GET['nro_invoice']);
                } else {
                    echo "Numero de factura no proporcionado";
                }
                break;

            case "sales/create":
                $salesController->create();
                break;

            case "sales/edit":
                if (isset($_GET["nro_invoice"])) {
                    $salesController->update($_GET['nro_invoice']);
                } else {
                    echo "Numero de factura no proporcionado";
                }
                break;

            case "sales/delete":
                if (isset($_GET["nro_invoice"])) {
                    $salesController->delete($_GET['nro_invoice']);
                } else {
                    echo "Numero de factura no proporcionado";
                }
                break;

            case "sales/export":
                $salesController->exportSales();
                break;

            case "sales/generate-pdf":
                if (isset($_GET['nro_invoice'])) {
                    $salesController->generatePdf($_GET['nro_invoice']);
                }
                break;

            case "sales/send-invoice":
                if (isset($_GET["nro_invoice"])) {
                    $nro_invoice = $_GET["nro_invoice"];
                    $salesController->sendInvoiceByEmail($nro_invoice);
                } else {
                    echo "Faltan parametros necesarios";
                }
                break;

            case "sales/settings":
                $settingsSalesController->settings();
                break;

            case "sales/settings/taxes/create":
                $settingsSalesController->createTax();
                break;

            case "sales/settings/taxes/edit":
                $settingsSalesController->updateTax();
                break;

            case "sales/settings/taxes/delete":
                if (isset($_GET["id"])) {
                    $settingsSalesController->deleteTax($_GET['id']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            case "sales/settings/sale-types/create":
                $settingsSalesController->createSaleType();
                break;

            case "sales/settings/sale-types/edit":
                $settingsSalesController->updateSaleType();
                break;

            case "sales/settings/sale-types/delete":
                if (isset($_GET["id"])) {
                    $settingsSalesController->deleteSaleType($_GET['id']);
                } else {
                    echo "ID no proporcionado";
                }
                break;

            default:
                echo "Página no encontrada";
                break;
        }
}
?>