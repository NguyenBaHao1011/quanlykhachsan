<?php
/**
 * ACCOUNTANT - INVOICE SERVICE
 * Actor: Accountant
 * Service: Invoice Management (View/Create/Update/Delete invoices)
 */

namespace App\Controllers\Accountant\Invoice;

use App\Models\Invoice;
use App\Core\Logger;

class AccountantInvoiceController {

    public function index() {
        header('Content-Type: application/json');
        Logger::info("[Accountant][Invoice] GET all invoices");
        $invoiceModel = new Invoice();
        echo json_encode($invoiceModel->getAll());
    }

    public function create() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['booking_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu booking_id"]);
            return;
        }
        Logger::info("[Accountant][Invoice] Creating invoice for booking #{$data['booking_id']}");
        $invoiceModel = new Invoice();
        $id = $invoiceModel->generate($data['booking_id']);
        if ($id) {
            echo json_encode(["message" => "Hóa đơn đã được tạo", "invoice_id" => $id]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Lỗi khi tạo hóa đơn"]);
        }
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id']) || empty($data['status'])) {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu id hoặc status"]);
            return;
        }
        Logger::info("[Accountant][Invoice] Update invoice #{$data['id']} payment to {$data['status']}");
        $invoiceModel = new Invoice();
        if ($invoiceModel->updateStatus($data['id'], $data['status'])) {
            echo json_encode(["message" => "Cập nhật trạng thái hóa đơn thành công"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Cập nhật thất bại"]);
        }
    }

    public function destroy() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        Logger::info("[Accountant][Invoice] Deleting invoice: " . ($data['id'] ?? 'N/A'));
        $invoiceModel = new Invoice();
        if ($invoiceModel->delete($data['id'])) {
            echo json_encode(["message" => "Xóa hóa đơn thành công"]);
        } else {
            http_response_code(500); 
            echo json_encode(["message" => "Xóa thất bại"]);
        }
    }
}
