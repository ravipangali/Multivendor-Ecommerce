<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasPage;
use Illuminate\Http\Request;

class SaasPageController extends Controller
{
    /**
     * Display the specified page.
     */
    public function show($slug)
    {
        $page = SaasPage::published()
                       ->where('slug', $slug)
                       ->firstOrFail();

        return view('saas_customer.saas_page', compact('page'));
    }

    /**
     * Display terms and conditions page.
     */
    public function terms()
    {
        $page = SaasPage::published()
                       ->where('slug', 'terms-and-conditions')
                       ->first();

        if (!$page) {
            abort(404, 'Terms and conditions page not found.');
        }

        return view('saas_customer.saas_page', compact('page'));
    }

    /**
     * Display privacy policy page.
     */
    public function privacy()
    {
        $page = SaasPage::published()
                       ->where('slug', 'privacy-policy')
                       ->first();

        if (!$page) {
            abort(404, 'Privacy policy page not found.');
        }

        return view('saas_customer.saas_page', compact('page'));
    }

    /**
     * Display about us page.
     */
    public function about()
    {
        $page = SaasPage::published()
                       ->where('slug', 'about-us')
                       ->first();

        if (!$page) {
            abort(404, 'About us page not found.');
        }

        return view('saas_customer.saas_page', compact('page'));
    }

    /**
     * Display contact us page.
     */
    public function contact()
    {
        $page = SaasPage::published()
                       ->where('slug', 'contact-us')
                       ->first();

        if (!$page) {
            abort(404, 'Contact us page not found.');
        }

        return view('saas_customer.saas_page', compact('page'));
    }

    /**
     * Get footer pages for display in footer.
     */
    public static function getFooterPages()
    {
        return SaasPage::published()
                      ->footer()
                      ->orderBy('position', 'asc')
                      ->get();
    }

    /**
     * Get header pages for display in header.
     */
    public static function getHeaderPages()
    {
        return SaasPage::published()
                      ->header()
                      ->orderBy('position', 'asc')
                      ->get();
    }
}
