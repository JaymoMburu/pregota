<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            // ── Shop ─────────────────────────────────────────────────────────
            ['category' => 'shop', 'sort_order' => 1,  'name' => 'Jumia Kenya',  'slug' => 'jumia',    'tagline' => 'Kenya\'s leading online marketplace',       'logo_emoji' => '🛍️', 'brand_color' => '#F68B1E', 'cta_text' => 'Shop on Jumia',    'url' => 'https://www.jumia.co.ke'],
            ['category' => 'shop', 'sort_order' => 2,  'name' => 'Naivas',       'slug' => 'naivas',   'tagline' => 'Fresh groceries & household essentials',     'logo_emoji' => '🛒', 'brand_color' => '#E31837', 'cta_text' => 'Shop at Naivas',   'url' => 'https://www.naivas.co.ke'],
            ['category' => 'shop', 'sort_order' => 3,  'name' => 'Carrefour',    'slug' => 'carrefour','tagline' => 'Quality products at great prices',           'logo_emoji' => '🏪', 'brand_color' => '#004E9A', 'cta_text' => 'Shop at Carrefour','url' => 'https://www.carrefour.ke'],
            ['category' => 'shop', 'sort_order' => 4,  'name' => 'Java House',   'slug' => 'java',     'tagline' => 'Coffee, meals & great vibes',                'logo_emoji' => '☕', 'brand_color' => '#6B3A2A', 'cta_text' => 'Order at Java',    'url' => 'https://www.javahouseafrica.com'],
            ['category' => 'shop', 'sort_order' => 5,  'name' => 'KFC Kenya',    'slug' => 'kfc',      'tagline' => 'Finger Lickin\' Good',                       'logo_emoji' => '🍗', 'brand_color' => '#F40027', 'cta_text' => 'Order at KFC',     'url' => 'https://www.kfc.co.ke'],
            ['category' => 'shop', 'sort_order' => 6,  'name' => 'Hotpoint',     'slug' => 'hotpoint', 'tagline' => 'Electronics, appliances & gadgets',          'logo_emoji' => '📱', 'brand_color' => '#1A73E8', 'cta_text' => 'Shop Electronics', 'url' => 'https://www.hotpoint.co.ke'],

            // ── Save ─────────────────────────────────────────────────────────
            ['category' => 'save', 'sort_order' => 1,  'name' => 'M-Shwari',     'slug' => 'mshwari',  'tagline' => 'Save & earn interest on M-Pesa',             'logo_emoji' => '💙', 'brand_color' => '#00A651', 'cta_text' => 'Save with M-Shwari', 'url' => 'https://www.safaricom.co.ke/personal/m-pesa/do-more-with-m-pesa/m-shwari'],
            ['category' => 'save', 'sort_order' => 2,  'name' => 'Equity Bank',  'slug' => 'equity',   'tagline' => 'Open an account, start growing your money',   'logo_emoji' => '🏦', 'brand_color' => '#EE2A24', 'cta_text' => 'Bank with Equity',   'url' => 'https://equitygroupholdings.com'],
            ['category' => 'save', 'sort_order' => 3,  'name' => 'KCB M-Pesa',   'slug' => 'kcb-mpesa','tagline' => 'Save directly from your M-Pesa wallet',       'logo_emoji' => '💚', 'brand_color' => '#006633', 'cta_text' => 'Save with KCB',      'url' => 'https://ke.kcbgroup.com/personal/ways-to-bank/mobile-banking/kcb-mpesa'],
            ['category' => 'save', 'sort_order' => 4,  'name' => 'NCBA Loop',    'slug' => 'ncba',     'tagline' => 'Digital banking with competitive interest',    'logo_emoji' => '🏛️', 'brand_color' => '#002147', 'cta_text' => 'Open NCBA Loop',     'url' => 'https://www.ncbagroup.com/ke/personal/loop'],

            // ── Invest ───────────────────────────────────────────────────────
            ['category' => 'invest', 'sort_order' => 1, 'name' => 'Cytonn MMF',   'slug' => 'cytonn',  'tagline' => '~14% p.a. · Min KES 1,000 · Withdraw anytime','logo_emoji' => '📈', 'brand_color' => '#1B3A6B', 'cta_text' => 'Invest with Cytonn', 'url' => 'https://cytonn.com/investments/money-market-fund'],
            ['category' => 'invest', 'sort_order' => 2, 'name' => 'CIC Money Market','slug' => 'cic',  'tagline' => '~12% p.a. · Trusted fund since 2004',          'logo_emoji' => '💰', 'brand_color' => '#006B3F', 'cta_text' => 'Invest with CIC',    'url' => 'https://cic.co.ke/money-market-fund'],
            ['category' => 'invest', 'sort_order' => 3, 'name' => 'Britam MMF',   'slug' => 'britam',  'tagline' => '~13% p.a. · Low risk, high liquidity',         'logo_emoji' => '📊', 'brand_color' => '#C8102E', 'cta_text' => 'Invest with Britam', 'url' => 'https://www.britam.com/ke/personal/investments/money-market-fund'],
            ['category' => 'invest', 'sort_order' => 4, 'name' => 'Sanlam MMF',   'slug' => 'sanlam',  'tagline' => '~13% p.a. · Regulated by CMA Kenya',           'logo_emoji' => '🏛️', 'brand_color' => '#003087', 'cta_text' => 'Invest with Sanlam', 'url' => 'https://www.sanlam.co.ke/investment/money-market-fund'],
        ];

        foreach ($partners as $p) {
            Partner::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
