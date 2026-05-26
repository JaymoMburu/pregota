<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BulkGiftController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\TxController;
use App\Http\Controllers\BillSplitController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\MpesaController;
use App\Http\Controllers\MultiGiftController;
use App\Http\Controllers\SchoolFeesController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\TipController;
use Illuminate\Support\Facades\Route;

// ── Transaction verification (public) ────────────────────────────────────
Route::get('/tx/{hash}', [TxController::class, 'verify'])->name('tx.verify')->where('hash', '[a-f0-9]{64}');

// ── Pitch deck ───────────────────────────────────────────────────────────
Route::get('/deck', fn() => view('pitch'))->name('pitch');

// ── Public gift routes ────────────────────────────────────────────────────
Route::get('/', [GiftController::class, 'home'])->name('home');
Route::get('/gift', [GiftController::class, 'giftPage'])->name('gift.home');
Route::get('/redeem', [GiftController::class, 'redeem'])->name('redeem');
Route::get('/track', [GiftController::class, 'track'])->name('track');
Route::post('/gift/initiate', [GiftController::class, 'initiate'])->name('gift.initiate');
Route::post('/gift/verify', [GiftController::class, 'verifyCode'])->name('gift.verify');
Route::post('/gift/claim', [GiftController::class, 'claim'])->name('gift.claim');
Route::post('/gift/track', [GiftController::class, 'trackStatus'])->name('gift.track');
Route::post('/gift/recall', [GiftController::class, 'recall'])->name('gift.recall');
Route::get('/gift/status', [GiftController::class, 'checkStatus'])->name('gift.status');
Route::post('/gift/direct', [GiftController::class, 'directInitiate'])->name('gift.direct');
Route::get('/gift/direct/status', [GiftController::class, 'directStatus'])->name('gift.direct.status');
Route::get('/gift/partner/{partner:slug}', [GiftController::class, 'partnerRedirect'])->name('gift.partner');

// ── Bulk / corporate gift codes ───────────────────────────────────────────
Route::get('/gift/bulk', [BulkGiftController::class, 'page'])->name('gift.bulk');
Route::post('/gift/bulk/initiate', [BulkGiftController::class, 'initiate'])->name('gift.bulk.initiate');
Route::get('/gift/bulk/status', [BulkGiftController::class, 'status'])->name('gift.bulk.status');
Route::get('/gift/bulk/download', [BulkGiftController::class, 'download'])->name('gift.bulk.download');

// ── Multi-creator gift ────────────────────────────────────────────────────
Route::get('/gift/multi-creator', [MultiGiftController::class, 'page'])->name('gift.multi');
Route::get('/gift/multi-creator/search', [MultiGiftController::class, 'searchCreator'])->name('gift.multi.search');
Route::post('/gift/multi-creator/initiate', [MultiGiftController::class, 'initiate'])->name('gift.multi.initiate');
Route::get('/gift/multi-creator/status', [MultiGiftController::class, 'status'])->name('gift.multi.status');

// ── School Collection ────────────────────────────────────────────────────
Route::get('/school-collection', fn() => redirect()->route('school-collection.new'));
Route::get('/school-collection/new', [SchoolFeesController::class, 'create'])->name('school-collection.new');
Route::post('/school-collection', [SchoolFeesController::class, 'store'])->name('school-collection.store');
Route::get('/school-collection/status', [SchoolFeesController::class, 'status'])->name('school-collection.status');
Route::get('/school-collection/student-balance', [SchoolFeesController::class, 'studentBalance'])->name('school-collection.balance');
Route::get('/school-collection/{slug}/verify', [SchoolFeesController::class, 'verify'])->name('school-collection.verify');
Route::get('/school-collection/{slug}/verify-status', [SchoolFeesController::class, 'verifyStatus'])->name('school-collection.verify-status');
Route::post('/school-collection/{slug}/resend-verify', [SchoolFeesController::class, 'resendVerification'])->name('school-collection.resend-verify');
Route::post('/school-collection/{slug}/report', [SchoolFeesController::class, 'reportFraud'])->name('school-collection.report');
Route::get('/school-collection/{slug}/admin', [SchoolFeesController::class, 'admin'])->name('school-collection.admin');
Route::post('/school-collection/{slug}/payout', [SchoolFeesController::class, 'payout'])->name('school-collection.payout');
Route::post('/school-collection/{slug}/close', [SchoolFeesController::class, 'close'])->name('school-collection.close');
Route::get('/school-collection/{slug}/class/{classToken}', [SchoolFeesController::class, 'classPage'])->name('school-collection.class');
Route::post('/school-collection/{slug}/class/{classToken}/pay', [SchoolFeesController::class, 'pay'])->name('school-collection.pay');
Route::get('/school-collection/{slug}/teacher/{teacherToken}', [SchoolFeesController::class, 'teacherView'])->name('school-collection.teacher');

// ── Collections ──────────────────────────────────────────────────────────
Route::get('/collections/new', [CollectionController::class, 'create'])->name('collection.new');
Route::post('/collections', [CollectionController::class, 'store'])->name('collection.store');
Route::get('/collections/status', [CollectionController::class, 'status'])->name('collection.status');
Route::get('/collections/{slug}/verify', [CollectionController::class, 'verify'])->name('collection.verify');
Route::get('/collections/{slug}/verify-status', [CollectionController::class, 'verifyStatus'])->name('collection.verify-status');
Route::post('/collections/{slug}/resend-verify', [CollectionController::class, 'resendVerification'])->name('collection.resend-verify');
Route::post('/collections/{slug}/report', [CollectionController::class, 'reportFraud'])->name('collection.report');
Route::get('/collections/{slug}', [CollectionController::class, 'show'])->name('collection.show');
Route::post('/collections/{slug}/contribute', [CollectionController::class, 'contribute'])->name('collection.contribute');
Route::get('/collections/{slug}/manage', [CollectionController::class, 'manage'])->name('collection.manage');
Route::post('/collections/{slug}/payout', [CollectionController::class, 'payout'])->name('collection.payout');
Route::post('/collections/{slug}/close', [CollectionController::class, 'close'])->name('collection.close');

// ── Staff pages ───────────────────────────────────────────────────────────
Route::get('/for-staff', fn() => view('staff.landing'))->name('staff.landing');
Route::get('/for-creators', fn() => view('creator.landing'))->name('creator.landing');
Route::get('/for-groups', fn() => view('collections.landing'))->name('collection.landing');
Route::get('/send-gift', fn() => view('gift.landing'))->name('gift.landing');
Route::get('/for-schools', fn() => view('school-collection.landing'))->name('school.landing');
Route::get('/staff/register', fn() => redirect()->route('staff.register'));

Route::get('/t/register', [StaffAuthController::class, 'registerForm'])->name('staff.register');
Route::post('/t/register', [StaffAuthController::class, 'register'])->name('staff.register.post');
Route::get('/t/login', [StaffAuthController::class, 'loginForm'])->name('staff.login');
Route::post('/t/login', [StaffAuthController::class, 'login'])->name('staff.login.post');
Route::post('/t/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

Route::middleware(\App\Http\Middleware\StaffAuth::class)->group(function () {
    Route::get('/t/dashboard', [StaffAuthController::class, 'dashboard'])->name('staff.dashboard');
    Route::patch('/t/profile', [StaffAuthController::class, 'updateProfile'])->name('staff.profile.update');
    Route::get('/t/charge', [StaffAuthController::class, 'chargeForm'])->name('staff.charge');
    Route::post('/t/charge', [StaffAuthController::class, 'chargeStore'])->name('staff.charge.store');
    Route::get('/t/leads', [StaffAuthController::class, 'leads'])->name('staff.leads');
});

// ── Tip pages ────────────────────────────────────────────────────────────
Route::get('/t/{handle}', [TipController::class, 'page'])->name('tip.page');
Route::post('/t/{handle}/tip', [TipController::class, 'initiate'])->name('tip.initiate');
Route::get('/tip/status', [TipController::class, 'checkStatus'])->name('tip.status');
Route::post('/tip/feedback', [TipController::class, 'submitFeedback'])->name('tip.feedback');
Route::get('/tip/tags', [TipController::class, 'tags'])->name('tip.tags');

// ── Business pages ────────────────────────────────────────────────────────
Route::get('/business/register', [BusinessController::class, 'registerForm'])->name('business.register');
Route::post('/business/register', [BusinessController::class, 'register'])->name('business.register.post');
Route::get('/business/login', [BusinessController::class, 'loginForm'])->name('business.login');
Route::post('/business/login', [BusinessController::class, 'login'])->name('business.login.post');
Route::post('/business/logout', [BusinessController::class, 'logout'])->name('business.logout');

Route::middleware(\App\Http\Middleware\BusinessAuth::class)->group(function () {
    Route::get('/business/dashboard', [BusinessController::class, 'dashboard'])->name('business.dashboard');
    Route::get('/business/leads', [BusinessController::class, 'leads'])->name('business.leads');
    Route::post('/business/staff', [BusinessController::class, 'addStaff'])->name('business.staff.add');
    Route::post('/business/staff/{staff}/toggle', [BusinessController::class, 'toggleStaff'])->name('business.staff.toggle');
    Route::delete('/business/staff/{staff}', [BusinessController::class, 'removeStaff'])->name('business.staff.remove');
    Route::get('/business/staff/{staff}/stats', [BusinessController::class, 'staffStats'])->name('business.staff.stats');
});

// ── Bill Split ────────────────────────────────────────────────────────────
Route::get('/split', [BillSplitController::class, 'create'])->name('bill-split.new');
Route::post('/split', [BillSplitController::class, 'store'])->name('bill-split.store');
Route::get('/split/payment-status', [BillSplitController::class, 'paymentStatus'])->name('bill-split.payment-status');
Route::get('/split/{waiterToken}', [BillSplitController::class, 'manage'])->name('bill-split.manage');
Route::get('/split/{token}/status', [BillSplitController::class, 'billStatus'])->name('bill-split.bill-status');
Route::get('/s/{splitToken}', [BillSplitController::class, 'show'])->name('bill-split.show');
Route::post('/s/{splitToken}/pay', [BillSplitController::class, 'pay'])->name('bill-split.pay');
Route::post('/s/{splitToken}/optin', [BillSplitController::class, 'optIn'])->name('bill-split.optin');

// ── Creator pages ─────────────────────────────────────────────────────────
Route::get('/creator/register', [CreatorController::class, 'registerForm'])->name('creator.register');
Route::post('/creator/register', [CreatorController::class, 'register'])->name('creator.register.post');
Route::get('/creator/login', [CreatorController::class, 'loginForm'])->name('creator.login');
Route::post('/creator/login', [CreatorController::class, 'login'])->name('creator.login.post');
Route::post('/creator/logout', [CreatorController::class, 'logout'])->name('creator.logout');
Route::get('/creator/dashboard', [CreatorController::class, 'dashboard'])->name('creator.dashboard')->middleware(\App\Http\Middleware\CreatorAuth::class);
Route::post('/creator/profile', [CreatorController::class, 'updateProfile'])->name('creator.profile')->middleware(\App\Http\Middleware\CreatorAuth::class);

// Creator search
Route::get('/gift/search', [CreatorController::class, 'search'])->name('gift.search');
// Public creator gift page
Route::get('/c/{handle}', [CreatorController::class, 'publicPage'])->name('creator.page');
Route::post('/c/{handle}/gift', [CreatorController::class, 'sendGift'])->name('creator.gift');

// OBS alert overlay (token-auth, no CSRF)
Route::get('/c/{handle}/alert/{token}', [CreatorController::class, 'alertOverlay'])->name('creator.alert');
Route::get('/c/{handle}/alert/{token}/poll', [CreatorController::class, 'alertPoll'])->name('creator.alert.poll');

// ── Seller Pay Links ─────────────────────────────────────────────────────
Route::get('/for-sellers', [SellerController::class, 'landing'])->name('seller.landing');
Route::get('/seller/register', [SellerController::class, 'registerForm'])->name('seller.register');
Route::post('/seller/register', [SellerController::class, 'register'])->name('seller.register.post');
Route::get('/seller/login', [SellerController::class, 'loginForm'])->name('seller.login');
Route::post('/seller/login', [SellerController::class, 'login'])->name('seller.login.post');
Route::post('/seller/logout', [SellerController::class, 'logout'])->name('seller.logout');
Route::get('/seller/dashboard', [SellerController::class, 'dashboard'])->name('seller.dashboard')->middleware(\App\Http\Middleware\SellerAuth::class);
Route::get('/seller/status', [SellerController::class, 'checkStatus'])->name('seller.status');

Route::get('/pay/{handle}', [SellerController::class, 'publicPage'])->name('seller.public');
Route::post('/pay/{handle}/pay', [SellerController::class, 'pay'])->name('seller.pay');
Route::get('/pay/{handle}/live', [SellerController::class, 'liveView'])->name('seller.live');
Route::get('/pay/{handle}/recent', [SellerController::class, 'recentPayments'])->name('seller.recent');

// ── M-Pesa Daraja webhooks (no CSRF) ─────────────────────────────────────
Route::prefix('mpesa')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    Route::post('/callback', [MpesaController::class, 'stkCallback'])->name('mpesa.callback');
    Route::post('/b2c/result', [MpesaController::class, 'b2cResult'])->name('mpesa.b2c.result');
    Route::post('/b2c/timeout', [MpesaController::class, 'b2cTimeout'])->name('mpesa.b2c.timeout');
});

// ── Investor portal ───────────────────────────────────────────────────────
Route::prefix('investors')->name('investor.')->group(function () {
    Route::get('/login',  [InvestorController::class, 'loginForm'])->name('login');
    Route::post('/login', [InvestorController::class, 'login'])->name('login.post');
    Route::post('/logout',[InvestorController::class, 'logout'])->name('logout');

    Route::middleware(\App\Http\Middleware\InvestorAuth::class)->group(function () {
        Route::get('/dashboard', [InvestorController::class, 'dashboard'])->name('dashboard');
    });
});

// ── Admin panel ───────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware(\App\Http\Middleware\AdminAuth::class)->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/voucher/{voucher}', [AdminController::class, 'voucher'])->name('voucher');
        Route::post('/voucher/{voucher}/activate', [AdminController::class, 'activateVoucher'])->name('voucher.activate');
        Route::post('/voucher/{voucher}/cancel', [AdminController::class, 'cancelVoucher'])->name('voucher.cancel');
        Route::post('/voucher/{voucher}/mark-paid', [AdminController::class, 'markPaid'])->name('voucher.mark-paid');
        Route::get('/partners', [AdminController::class, 'partners'])->name('partners');
        Route::post('/partners', [AdminController::class, 'createPartner'])->name('partners.create');
        Route::post('/partners/{partner}/toggle', [AdminController::class, 'togglePartner'])->name('partners.toggle');
        Route::delete('/partners/{partner}', [AdminController::class, 'deletePartner'])->name('partners.delete');

        Route::get('/businesses', [AdminController::class, 'businesses'])->name('businesses');
        Route::post('/businesses/{business}/subscribe', [AdminController::class, 'subscribeBusiness'])->name('businesses.subscribe');
        Route::post('/businesses/{business}/cancel', [AdminController::class, 'cancelSubscription'])->name('businesses.cancel');

        Route::post('/school-collection/{schoolCollection}/unfreeze', [AdminController::class, 'unfreezeSchoolCollection'])->name('school-collection.unfreeze');
        Route::post('/collection/{collection}/unfreeze', [AdminController::class, 'unfreezeCollection'])->name('collection.unfreeze');

        Route::get('/investors',                                    [AdminController::class, 'investors'])->name('investors');
        Route::post('/investors',                                   [AdminController::class, 'createInvestor'])->name('investors.create');
        Route::post('/investors/{investor}/toggle',                 [AdminController::class, 'toggleInvestor'])->name('investors.toggle');
        Route::post('/investors/{investor}/reset-password',         [AdminController::class, 'resetInvestorPassword'])->name('investors.reset-password');

        Route::get('/creators',                                     [AdminController::class, 'creators'])->name('creators');
        Route::post('/creators/{creator}/approve',                  [AdminController::class, 'approveCreator'])->name('creators.approve');
        Route::delete('/creators/{creator}',                        [AdminController::class, 'rejectCreator'])->name('creators.reject');
    });
});
