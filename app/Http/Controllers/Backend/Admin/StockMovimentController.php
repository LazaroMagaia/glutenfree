<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Enums\StockMovementType;
use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\StockMovement;
use App\Services\StockService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StockMovimentController extends Controller
{
    public function __construct(private readonly StockService $stock)
    {
    }

    /**
     * Página de gestão de stock de um produto + histórico.
     */
    public function index(Request $request, string $category, string $slug): View
    {
        $product = $this->findProduct($slug);
        $type = $request->enum('type', StockMovementType::class);

        $movements = StockMovement::query()
            ->with('user:id,name')
            ->where('product_id', $product->id)
            ->when($type, fn ($query) => $query->where('type', $type->value))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('Backend.Admin.Products.Stock.index', [
            'product' => $product,
            'categorySlug' => $category,
            'movements' => $movements,
            'manualTypes' => StockMovementType::manual(),
            'allTypes' => StockMovementType::cases(),
            'filterType' => $type,
            'idempotencyKey' => (string) Str::uuid(), // campo hidden no formulário
        ]);
    }

    /**
     * Regista um movimento manual (entrada, ajuste, perda, dano, descarte).
     */
    public function store(Request $request, string $category, string $slug): RedirectResponse
    {
        $product = $this->findProduct($slug);
        $type = $request->enum('type', StockMovementType::class);

        $data = $request->validate([
            'type' => [
                'required',
                Rule::enum(StockMovementType::class)->only(StockMovementType::manual()),
            ],
            'quantity' => [
                'required',
                'integer',
                $type === StockMovementType::Adjustment ? 'min:0' : 'min:1',
                'max:1000000',
            ],
            'reason' => [
                Rule::requiredIf($type?->requiresReason() ?? false),
                'nullable',
                'string',
                'max:500',
            ],
            'idempotency_key' => ['nullable', 'string', 'max:60'],
        ], [
            'type.required' => 'Seleciona o tipo de movimento.',
            'quantity.required' => 'Indica a quantidade.',
            'quantity.integer' => 'A quantidade tem de ser um número inteiro.',
            'quantity.min' => 'A quantidade indicada é inválida.',
            'reason.required' => 'O motivo é obrigatório para este tipo de movimento.',
        ]);

        try {
            $movement = $this->stock->register(
                productId: $product->id,
                type: StockMovementType::from($data['type']),
                quantity: (int) $data['quantity'],
                reason: $data['reason'] ?? null,
                userId: $request->user()->id,
                idempotencyKey: ! empty($data['idempotency_key'])
                    ? 'admin-stock:'.$data['idempotency_key']
                    : null,
            );
        } catch (DomainException $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }

        return redirect()
            ->route('admin.products.stock', ['category' => $category, 'slug' => $product->slug])
            ->with('success', "Movimento registado: {$movement->type->label()}.");
    }

    private function findProduct(string $slug): Product
    {
        return Product::query()
            ->with(['category', 'primaryImage'])
            ->where('slug', $slug)
            ->firstOrFail();
    }
}