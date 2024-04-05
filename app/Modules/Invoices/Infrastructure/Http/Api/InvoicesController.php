<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Infrastructure\Http\Api;

use App\Domain\Invoice;
use App\Infrastructure\Controller;
use App\Modules\Invoices\Api\InvoicesFacadeInterface;
use App\Modules\Invoices\Application\Mappers\InvoiceToArray;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvoicesController extends Controller
{
    public function __construct(
        private InvoicesFacadeInterface $invoicesFacade
    ) {
    }

    public function index(Request $request, InvoiceToArray $invoiceToArray): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $page = max(1, intval($page));
        $limit = min(100, max(1, intval($limit)));

        $invoices = $this->invoicesFacade->getInvoices(($page - 1) * $limit, $limit);

        return response()
            ->json($invoices->map(fn(Invoice $data) => $invoiceToArray->map($data)))
            ->setStatusCode(Response::HTTP_OK);
    }

    public function approveInvoice(string $uuid): JsonResponse
    {
        try {
            $invoiceId = Uuid::fromString($uuid);
            $this->invoicesFacade->approveInvoice($invoiceId);
        } catch (Throwable $exception) {
            return response()
                ->json(['message' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function rejectInvoice(string $uuid): JsonResponse
    {
        try {
            $invoiceId = Uuid::fromString($uuid);
            $this->invoicesFacade->rejectInvoice($invoiceId);
        } catch (Throwable $exception) {
            return response()
                ->json(['message' => $exception->getMessage()])
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()
            ->json()
            ->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
