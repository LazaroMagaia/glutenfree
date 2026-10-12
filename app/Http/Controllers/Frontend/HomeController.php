<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
class HomeController extends Controller
{
        public function index(): View
    {
        // TODO: trocar os arrays por Eloquent, ex.:
        // $products = Product::where('featured', true)->latest()->take(4)->get();

        $marquee = ['Sem glúten', 'Sem contaminação cruzada', 'Feito em Moçambique', 'Entrega em todo o país', 'Ingredientes naturais'];

        $categories = [
            ['name' => 'Farinhas',        'sub' => 'Arroz, mandioca, milho e mais', 'emoji' => '🌾', 'img' => '1586201375761-83865001e31c', 'span' => 'md:col-span-2 md:row-span-2'],
            ['name' => 'Pães',            'sub' => 'Frescos e congelados',          'emoji' => '🍞', 'img' => '1509440159596-0249088772ff', 'span' => ''],
            ['name' => 'Massas',          'sub' => 'Penne, espargete, lasanha',     'emoji' => '🍝', 'img' => '1473093295043-cdd812d0e601', 'span' => ''],
            ['name' => 'Snacks e doces',  'sub' => 'Biscoitos, bolos e barras',     'emoji' => '🍪', 'img' => '1499636136210-6f4ee915583e', 'span' => 'md:col-span-2'],
        ];

        $products = [
            ['name' => 'Farinha de arroz',  'desc' => '1 kg · fina',            'price' => 180, 'emoji' => '🌾', 'img' => '1586201375761-83865001e31c', 'tag' => 'Novo'],
            ['name' => 'Pão de forma',      'desc' => '400 g · fatiado',        'price' => 320, 'emoji' => '🍞', 'img' => '1509440159596-0249088772ff', 'tag' => 'Fresco'],
            ['name' => 'Massa penne',       'desc' => '500 g · milho e arroz',  'price' => 260, 'emoji' => '🍝', 'img' => '1473093295043-cdd812d0e601', 'tag' => null],
            ['name' => 'Biscoitos de coco', 'desc' => '200 g · crocantes',      'price' => 150, 'emoji' => '🍪', 'img' => '1499636136210-6f4ee915583e', 'tag' => 'Favorito'],
        ];

        $quality = [
            ['🌿', '100% Natural',   'Sem conservantes artificiais ou aditivos químicos'],
            ['🏅', 'Certificado',    'Produto certificado e com garantia de qualidade'],
            ['🚚', 'Entrega rápida', '24–48h em Maputo, envio nacional disponível'],
            ['♻️', 'Eco-friendly',   'Embalagens recicláveis e produção sustentável'],
        ];

        $steps = [
            ['01', 'Escolhe o produto',    'Explora as nossas farinhas naturais e seleciona o que precisas.'],
            ['02', 'Adiciona ao carrinho', 'Um clique e o produto é guardado no teu carrinho de compra.'],
            ['03', 'Recebe em casa',       'Entregamos em Maputo em 24–48h e para todo o país.'],
        ];

        $stats = [['40+', 'Produtos'], ['100%', 'Sem glúten'], ['24h', 'Entrega Maputo']];

        $recipes = [
            ['emoji' => '🥥', 'img' => '1578985545062-69928b1d9587', 'meta' => '45 min · Fácil', 'title' => 'Bolo de mandioca e coco', 'text' => 'Húmido, doce e perfeito para o chá da tarde.'],
            ['emoji' => '🍤', 'img' => '1565557623262-b51c2513a641', 'meta' => '30 min · Fácil', 'title' => 'Matapa com camarão',      'text' => 'Um clássico moçambicano, naturalmente sem glúten.'],
            ['emoji' => '🥞', 'img' => '1567620905732-2d1ec7ab7445', 'meta' => '20 min · Fácil', 'title' => 'Panquecas de arroz',      'text' => 'Leves e fofas, ideais para o pequeno-almoço.'],
            ['emoji' => '🍝', 'img' => '1473093295043-cdd812d0e601', 'meta' => '25 min · Fácil', 'title' => 'Massa com legumes',       'text' => 'Rápida e cheia de cor para o dia a dia.'],
        ];

        $community = [
            ['🥗', '1490645935967-10de6ba17061'], ['🍲', '1504674900247-0877df9cc836'], ['🍕', '1565299624946-b28f40a0ae38'],
            ['🎂', '1578985545062-69928b1d9587'], ['🥞', '1567620905732-2d1ec7ab7445'], ['🍪', '1499636136210-6f4ee915583e'],
        ];

        $reviews = [
            ['name' => 'Ana Machava',   'role' => 'Maputo', 'text' => 'Finalmente encontrei pão sem glúten com sabor de verdade. A minha filha adora.'],
            ['name' => 'Carlos Sitoe',  'role' => 'Matola', 'text' => 'A farinha de arroz é fina e rende muito. A entrega chegou no dia seguinte.'],
            ['name' => 'Marta Jossias', 'role' => 'Beira',  'text' => 'Como celíaca, a confiança é tudo. Os rótulos são claros e o apoio responde rápido.'],
        ];

        $faqs = [
            ['Os produtos são seguros para celíacos?', 'Sim. Trabalhamos com ingredientes sem glúten e evitamos a contaminação cruzada na produção. Consulte sempre o rótulo de cada produto.'],
            ['Quanto tempo demora a entrega?', 'Em Maputo entregamos em 24–48h. Para as outras províncias o envio nacional depende da localidade.'],
            ['Quais as formas de pagamento?', 'Aceitamos M-Pesa, e-Mola e transferência bancária. Confirmamos o pedido por WhatsApp.'],
            ['Posso fazer encomendas grandes ou revender?', 'Sim. Fale connosco por WhatsApp para preços de revenda e encomendas em quantidade.'],
            ['Como devo guardar as farinhas?', 'Num local fresco e seco, em recipiente bem fechado, para manter a qualidade por mais tempo.'],
        ];

        return view('Frontend.Home.Index', compact(
            'marquee', 'categories', 'products', 'quality', 'steps', 'stats',
            'recipes', 'community', 'reviews', 'faqs'
        ));
    }
}
