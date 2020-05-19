<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index(Request $request)
    {

        $page = $request->input('page', 1);
        $perPage = 16;
        $params = [
            'index' => 'products',
            'body' => [
                'from' => ($page - 1) * $perPage,
                'size' => $perPage,
                'query' => [
                    'bool' => [
                        'filter' => [
                            ['term' => ['on_sale' => true]],
                        ],
                    ],
                ],
            ],
        ];
        $propertyFilters = [];
        if($filterString = $request->input('filters')){
            $filterArray = explode('|', $filterString);
            foreach($filterArray as $filter){
                list($name,$value) = explode(':', $filter);
                $propertyFilters[$name] = $value;
                $params['body']['query']['bool']['filter'][] = [
                    'nested' => [
                        'path' => 'properties',
                        'query' => [
                            [
                                'term' => [
                                    'properties.name' => $name,
                                ],
                                'term' => [
                                    'properties.value' => $value,
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }
        if ($order = $request->input('order', '')) {
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    $params['body']['sort'] = [[$m[1] => $m[2]]];
                }
            }
        }
        if ($request->input('category_id') && $category = Category::find($request->input('category_id'))) {
            if ($category->is_directory) {
                $params['body']['query']['bool']['filter'][] = [
                    'prefix' => ['category_path' => $category->path . $category->id . '-'],
                ];
            } else {
                $params['body']['query']['bool']['filter'][] = [
                    'term' => ['category_id' => $category->id]
                ];
            }
        }

        if ($search = $request->input('search', '')) {
            $keywords = array_filter(explode(' ', $search));
            $params['body']['query']['bool']['must'] = [];
            foreach ($keywords as $keyword) {
                $params['body']['query']['bool']['must'] = [
                    [
                        'multi_match' => [
                            'query' => $keyword,
                            'fields' => [
                                'title^3',
                                'long_title^2',
                                'category^2',
                                'description',
                                'skus_title',
                                'skus_description',
                                'properties_value',
                            ]
                        ]
                    ]
                ];
            }

        }
        if($search || isset($category)){
            $params['body']['aggs'] = [
                'properties' => [
                    'nested' => [
                        'path' => 'properties',
                    ],
                    'aggs' => [
                        'properties' => [
                            'terms' => [
                                'field' => 'properties.name',
                            ],
                            'aggs' => [
                                'value' => [
                                    'terms' => [
                                        'field' => 'properties.value',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        $result = app('es')->search($params);
        $productIds = collect($result['hits']['hits'])->pluck('_id')->all();
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->orderByRaw(sprintf("FIND_IN_SET(id,'%s')", join(',', $productIds)))
            ->get();
        $pager = new LengthAwarePaginator($products, $result['hits']['total']['value'], $perPage, $page, [
            'path' => route('products.index'),
        ]);
        $properties = [];
        if(isset($result['aggregations'])){
            $properties = collect($result['aggregations']['properties']['properties']['buckets'])->map(function($bucket){
                return [
                    'key' => $bucket['key'],
                    'values' => collect($bucket['value']['buckets'])->pluck('key')->all(),
                ];
            })->filter(function($property) use($propertyFilters){
                return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]);
            });
        }
        return view('products.index', [
            'products' => $pager,
            'filters' => [
                'search' => $search,
                'order' => $order,
            ],
            'category' => $category ?? null,
            'properties' => $properties,
            'propertyFilters' => $propertyFilters,
        ]);
    }

    public function show(Product $product, Request $request)
    {
        // 判断商品是否已经上架，如果没有上架则抛出异常。
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }

        $favored = false;
        // 用户未登录时返回的是 null，已登录时返回的是对应的用户对象
        if ($user = $request->user()) {
            // 从当前用户已收藏的商品中搜索 id 为当前商品 id 的商品
            // boolval() 函数用于把值转为布尔值
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        $reviews = OrderItem::query()
            ->with(['order.user', 'productSku']) // 预先加载关联关系
            ->where('product_id', $product->id)
            ->whereNotNull('reviewed_at') // 筛选出已评价的
            ->orderBy('reviewed_at', 'desc') // 按评价时间倒序
            ->limit(10) // 取出 10 条
            ->get();

        // 最后别忘了注入到模板中
        return view('products.show', [
            'product' => $product,
            'favored' => $favored,
            'reviews' => $reviews
        ]);
    }

    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(16);

        return view('products.favorites', ['products' => $products]);
    }

    public function favor(Product $product, Request $request)
    {
        $user = $request->user();
        if ($user->favoriteProducts()->find($product->id)) {
            return [];
        }

        $user->favoriteProducts()->attach($product);

        return [];
    }

    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();
        $user->favoriteProducts()->detach($product);

        return [];
    }
}
