<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Tag, ProductImage, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// Native PHP GD — no extra package needed

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.index', compact('categories'));
    }

    /**
     * Server-side DataTables AJAX endpoint.
     */
    public function datatable(Request $request)
    {
        $draw        = (int) $request->input('draw', 1);
        $start       = (int) $request->input('start', 0);
        $length      = (int) $request->input('length', 25);
        $search      = $request->input('search.value', '');
        $orderColIdx = (int) $request->input('order.0.column', 0);
        $orderDir    = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

        // Map DT column index → DB column (col 0 = hidden created_at for LIFO sort)
        $cols    = ['created_at', 'id', 'id', 'name', 'category_id', 'price', 'stock', 'is_active', 'id'];
        $orderBy = $cols[$orderColIdx] ?? 'created_at';

        $query = Product::with('category')->withTrashed();

        // Extra filters sent from the view via ajax.data
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            match ($request->status) {
                'active'   => $query->where('is_active', true)->whereNull('deleted_at'),
                'inactive' => $query->where('is_active', false)->whereNull('deleted_at'),
                'deleted'  => $query->onlyTrashed(),
                default    => null,
            };
        }

        $total = (clone $query)->count();

        if ($search) {
            $query->where(fn ($q) =>
                $q->where('name', 'like', "%$search%")
                  ->orWhere('sku',  'like', "%$search%")
            );
        }

        $filtered = (clone $query)->count();
        $products = $query->orderBy($orderBy, $orderDir)->offset($start)->limit($length)->get();

        $rows = $products->map(function ($p) {
            // Image
            $img = $p->featured_image
                ? '<img src="'.asset('storage/'.$p->featured_image).'" loading="lazy" style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1.5px solid #E2E6EE">'
                : '<div style="width:44px;height:44px;background:#F1F4F8;border-radius:8px;border:1.5px solid #E2E6EE;display:flex;align-items:center;justify-content:center;color:#9CA3AF"><i class="bi bi-image"></i></div>';

            // Name + meta
            $name = '<div style="font-weight:600;font-size:.875rem;color:#111827">'.\Str::limit($p->name, 50).'</div>';
            if ($p->sku)         $name .= '<div style="font-size:.72rem;color:#9CA3AF">SKU: '.$p->sku.'</div>';
            if ($p->is_featured) $name .= '<span style="font-size:.62rem;font-weight:700;color:#F0A500"><i class="bi bi-star-fill me-1"></i>Featured</span> ';
            if ($p->trashed())   $name .= '<span class="badge bg-danger" style="font-size:.6rem">Trashed</span>';

            // Price (handled by Eloquent accessor)
            $price = '<div style="font-weight:700;color:#1A3A5C">৳'.number_format($p->price, 2).'</div>';
            if ($p->sale_price)  $price .= '<div style="font-size:.72rem;color:#10B981">Sale: ৳'.number_format($p->sale_price, 2).'</div>';

            // Stock badge
            if ($p->stock <= 0) {
                $stock = '<span style="background:rgba(239,68,68,.1);color:#DC2626;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Out of Stock</span>';
            } elseif ($p->stock <= ($p->low_stock_threshold ?? 5)) {
                $stock = '<span style="background:rgba(245,158,11,.1);color:#D97706;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Low ('.$p->stock.')</span>';
            } else {
                $stock = '<span style="background:rgba(16,185,129,.1);color:#059669;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">'.$p->stock.'</span>';
            }

            // Status badge
            $status = $p->is_active
                ? '<span style="background:rgba(13,115,119,.1);color:#0D7377;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Active</span>'
                : '<span style="background:rgba(107,114,128,.1);color:#6B7280;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Draft</span>';

            // Actions
            $editUrl   = route('admin.products.edit', $p->id);
            $viewUrl   = route('shop.product', $p->slug);
            $delId     = 'del-'.$p->id;
            $delAction = route('admin.products.destroy', $p->id);

            $actions = '
            <div class="d-flex gap-1 table-row-actions">
                <a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                <a href="'.$viewUrl.'" target="_blank" class="btn btn-sm" style="border:1.5px solid #E2E6EE;color:#6B7280" title="View"><i class="bi bi-eye"></i></a>
                <form id="'.$delId.'" action="'.$delAction.'" method="POST" style="display:inline">
                    '.csrf_field().'<input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete(\''.$delId.'\')"><i class="bi bi-trash"></i></button>
                </form>
            </div>';

            return [
                'created_at' => $p->created_at->toISOString(),
                'id'       => $p->id,
                'image'    => $img,
                'name'     => $name,
                'category' => $p->category->name ?? '—',
                'price'    => $price,
                'stock'    => $stock,
                'status'   => $status,
                'actions'  => $actions,
            ];
        });

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $rows,
        ]);
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->storeImage($request->file('featured_image'), 'products');
        }

        $product = Product::create($data);

        // Sync tags
        if ($request->tags) {
            $product->tags()->sync($request->tags);
        }

        $this->handleVariantsAndTabs($request, $product);

        // Upload gallery images
        $this->handleGalleryImages($request, $product);

        ActivityLog::record('Created product: ' . $product->name, $product);
        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants', 'tags', 'tabs']);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);

        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) Storage::disk('public')->delete($product->featured_image);
            $data['featured_image'] = $this->storeImage($request->file('featured_image'), 'products');
        }

        $old = $product->toArray();
        $product->update($data);
        $product->tags()->sync($request->tags ?? []);

        $this->handleVariantsAndTabs($request, $product);
        $this->handleGalleryImages($request, $product);

        ActivityLog::record('Updated product: ' . $product->name, $product, $old, $product->fresh()->toArray());
        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        ActivityLog::record('Deleted product: ' . $product->name, $product);
        $product->delete(); // soft delete
        return redirect()->route('admin.products.index')->with('success', 'Product moved to trash.');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);
        return back()->with('success', 'Product status updated.');
    }

    public function uploadImages(Request $request, Product $product)
    {
        $request->validate(['images.*' => 'image|max:3072']);
        $this->handleGalleryImages($request, $product);
        return back()->with('success', 'Images uploaded.');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Image removed.');
    }

    // ── Helpers ──────────────────────────────────────
    private function validateProduct(Request $request, ?int $excludeId = null): array
    {
        $rules = [
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'sku'               => "nullable|string|unique:products,sku,{$excludeId}",
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'specifications'    => 'nullable|array',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'stock'             => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'weight'            => 'nullable|numeric|min:0',
            'dimensions'        => 'nullable|string|max:100',
            'video_url'         => 'nullable|url|max:255',
            'meta_title'        => 'nullable|string|max:160',
            'meta_description'  => 'nullable|string|max:320',
            'is_active'         => 'boolean',
            'is_featured'       => 'boolean',
            'variants'          => 'nullable|array',
            'variants.*.id'     => 'nullable|integer',
            'variants.*.name'   => 'required_with:variants|string|max:255',
            'variants.*.sku'    => 'nullable|string|max:100',
            'variants.*.price'  => 'nullable|numeric|min:0',
            'variants.*.stock'  => 'required_with:variants|integer|min:0',
            'tabs'              => 'nullable|array',
            'tabs.*.id'         => 'nullable|integer',
            'tabs.*.heading'    => 'required_with:tabs|string|max:255',
            'tabs.*.content'    => 'nullable|string',
        ];

        $data = $request->validate($rules);
        unset($data['variants'], $data['tabs']);
        return $data;
    }

    private function storeImage($file, string $folder): string
    {
        $sourcePath = $file->getRealPath();
        $mime       = $file->getMimeType();
        $filename   = $folder . '/' . uniqid() . '.jpg';

        // Read source image
        $src = match (true) {
            str_contains($mime, 'jpeg') => imagecreatefromjpeg($sourcePath),
            str_contains($mime, 'png')  => imagecreatefrompng($sourcePath),
            str_contains($mime, 'webp') => imagecreatefromwebp($sourcePath),
            str_contains($mime, 'gif')  => imagecreatefromgif($sourcePath),
            default                     => null,
        };

        if (! $src) {
            return $file->store($folder, 'public');
        }

        // Scale down to max 1200px
        $w = imagesx($src); $h = imagesy($src);
        if ($w > 1200 || $h > 1200) {
            $ratio = min(1200 / $w, 1200 / $h);
            $nw = (int)($w * $ratio); $nh = (int)($h * $ratio);
            $dst = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($src); $src = $dst;
        }

        ob_start();
        imagejpeg($src, null, 85);
        $imageData = ob_get_clean();
        imagedestroy($src);

        Storage::disk('public')->put($filename, $imageData);
        return $filename;
    }

    private function handleGalleryImages(Request $request, Product $product): void
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $this->storeImage($file, 'products/gallery');
                $product->images()->create([
                    'image_path' => $path,
                    'sort_order' => $product->images()->count(),
                ]);
            }
        }
    }

    private function handleVariantsAndTabs(Request $request, Product $product): void
    {
        // Handle Variants
        $variantIds = [];
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $vData) {
                if (empty($vData['name'])) continue;
                $variant = $product->variants()->updateOrCreate(
                    ['id' => $vData['id'] ?? null],
                    [
                        'name' => $vData['name'],
                        'sku' => $vData['sku'] ?? null,
                        // if price is submitted as a blank string, map it to null so it doesn't try to store ""
                        'price' => (isset($vData['price']) && $vData['price'] !== '') ? $vData['price'] : null,
                        'stock' => $vData['stock'] ?? 0,
                        'is_active' => true,
                    ]
                );
                $variantIds[] = $variant->id;
            }
        }
        $product->variants()->whereNotIn('id', $variantIds)->delete();

        // Handle Tabs
        $tabIds = [];
        if ($request->has('tabs') && is_array($request->tabs)) {
            foreach ($request->tabs as $index => $tData) {
                if (empty($tData['heading'])) continue;
                $tab = $product->tabs()->updateOrCreate(
                    ['id' => $tData['id'] ?? null],
                    [
                        'heading' => $tData['heading'],
                        'content' => $tData['content'] ?? '',
                        'sort_order' => $index,
                    ]
                );
                $tabIds[] = $tab->id;
            }
        }
        $product->tabs()->whereNotIn('id', $tabIds)->delete();
    }
}
