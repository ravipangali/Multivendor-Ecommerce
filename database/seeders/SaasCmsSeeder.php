<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SaasPage;
use App\Models\SaasBlogCategory;
use App\Models\SaasBlogPost;
use App\Models\User;
use Carbon\Carbon;

class SaasCmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create admin user
        $admin = User::where('role', 'admin')->first() ?? User::where('role', 'super_admin')->first();

        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]);
        }

        // Create CMS Pages
        $pages = [
            [
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'excerpt' => 'Read our terms and conditions to understand your rights and responsibilities when using our platform.',
                'content' => $this->getTermsContent(),
                'meta_title' => 'Terms and Conditions - AllSewa',
                'meta_description' => 'Terms and conditions for using AllSewa marketplace platform.',
                'status' => true,
                'in_footer' => true,
                'position' => 1,
                'author_id' => $admin->id,
                'published_at' => now(),
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'Learn how we collect, use, and protect your personal information on our platform.',
                'content' => $this->getPrivacyContent(),
                'meta_title' => 'Privacy Policy - AllSewa',
                'meta_description' => 'Privacy policy explaining how AllSewa handles your personal data.',
                'status' => true,
                'in_footer' => true,
                'position' => 2,
                'author_id' => $admin->id,
                'published_at' => now(),
            ],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Discover the story behind AllSewa and our mission to connect buyers and sellers.',
                'content' => $this->getAboutContent(),
                'meta_title' => 'About Us - AllSewa',
                'meta_description' => 'Learn about AllSewa\'s mission, vision, and the team behind the platform.',
                'status' => true,
                'in_header' => true,
                'position' => 1,
                'author_id' => $admin->id,
                'published_at' => now(),
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'excerpt' => 'Get in touch with our team for support, partnerships, or general inquiries.',
                'content' => $this->getContactContent(),
                'meta_title' => 'Contact Us - AllSewa',
                'meta_description' => 'Contact AllSewa for support, partnerships, or general inquiries.',
                'status' => true,
                'in_header' => true,
                'position' => 2,
                'author_id' => $admin->id,
                'published_at' => now(),
            ],
        ];

        foreach ($pages as $pageData) {
            SaasPage::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        // Create Blog Categories
        $categories = [
            [
                'name' => 'E-commerce Tips',
                'slug' => 'ecommerce-tips',
                'description' => 'Tips and strategies for successful online selling and buying.',
                'meta_title' => 'E-commerce Tips - AllSewa Blog',
                'meta_description' => 'Expert tips and strategies for e-commerce success.',
                'status' => true,
                'position' => 1,
            ],
            [
                'name' => 'Seller Guides',
                'slug' => 'seller-guides',
                'description' => 'Comprehensive guides for sellers to maximize their success on our platform.',
                'meta_title' => 'Seller Guides - AllSewa Blog',
                'meta_description' => 'Complete guides for sellers on AllSewa marketplace.',
                'status' => true,
                'position' => 2,
            ],
            [
                'name' => 'Platform Updates',
                'slug' => 'platform-updates',
                'description' => 'Latest updates, features, and announcements from AllSewa.',
                'meta_title' => 'Platform Updates - AllSewa Blog',
                'meta_description' => 'Stay updated with the latest AllSewa platform features and updates.',
                'status' => true,
                'position' => 3,
            ],
            [
                'name' => 'Success Stories',
                'slug' => 'success-stories',
                'description' => 'Inspiring success stories from our sellers and customers.',
                'meta_title' => 'Success Stories - AllSewa Blog',
                'meta_description' => 'Read inspiring success stories from AllSewa community.',
                'status' => true,
                'position' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            SaasBlogCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create Sample Blog Posts
        $ecommerceTips = SaasBlogCategory::where('slug', 'ecommerce-tips')->first();
        $sellerGuides = SaasBlogCategory::where('slug', 'seller-guides')->first();
        $platformUpdates = SaasBlogCategory::where('slug', 'platform-updates')->first();

        $posts = [
            [
                'title' => '10 Essential Tips for New Online Sellers',
                'slug' => '10-essential-tips-new-online-sellers',
                'excerpt' => 'Starting your online selling journey? Here are 10 essential tips to help you succeed from day one.',
                'content' => $this->getBlogPostContent1(),
                'meta_title' => '10 Essential Tips for New Online Sellers - AllSewa Blog',
                'meta_description' => 'Essential tips for new online sellers to start their e-commerce journey successfully.',
                'status' => true,
                'is_featured' => true,
                'author_id' => $admin->id,
                'category_id' => $sellerGuides->id,
                'published_at' => now()->subDays(5),
                'tags' => ['selling', 'tips', 'beginners', 'ecommerce'],
            ],
            [
                'title' => 'How to Optimize Your Product Listings for Better Sales',
                'slug' => 'optimize-product-listings-better-sales',
                'excerpt' => 'Learn the art of creating compelling product listings that convert browsers into buyers.',
                'content' => $this->getBlogPostContent2(),
                'meta_title' => 'Optimize Product Listings for Better Sales - AllSewa Blog',
                'meta_description' => 'Learn how to create compelling product listings that drive more sales.',
                'status' => true,
                'is_featured' => true,
                'author_id' => $admin->id,
                'category_id' => $ecommerceTips->id,
                'published_at' => now()->subDays(3),
                'tags' => ['optimization', 'listings', 'sales', 'conversion'],
            ],
            [
                'title' => 'New Features: Enhanced Seller Dashboard',
                'slug' => 'new-features-enhanced-seller-dashboard',
                'excerpt' => 'Discover the new and improved seller dashboard with advanced analytics and management tools.',
                'content' => $this->getBlogPostContent3(),
                'meta_title' => 'New Features: Enhanced Seller Dashboard - AllSewa Blog',
                'meta_description' => 'Explore the new seller dashboard features and improvements.',
                'status' => true,
                'is_featured' => false,
                'author_id' => $admin->id,
                'category_id' => $platformUpdates->id,
                'published_at' => now()->subDays(1),
                'tags' => ['features', 'dashboard', 'sellers', 'updates'],
            ],
        ];

        foreach ($posts as $postData) {
            SaasBlogPost::updateOrCreate(
                ['slug' => $postData['slug']],
                $postData
            );
        }

        $this->command->info('CMS and Blog data seeded successfully!');
    }

    private function getTermsContent()
    {
        return '<h2>Terms of Service</h2>
        <p>Welcome to AllSewa! These terms and conditions outline the rules and regulations for the use of AllSewa\'s Website.</p>

        <h3>1. Acceptance of Terms</h3>
        <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>

        <h3>2. User Accounts</h3>
        <p>When you create an account with us, you must provide information that is accurate, complete, and current at all times.</p>

        <h3>3. Prohibited Uses</h3>
        <p>You may not use our service:</p>
        <ul>
            <li>For any unlawful purpose or to solicit others to perform unlawful acts</li>
            <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
            <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others</li>
        </ul>

        <h3>4. Termination</h3>
        <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever.</p>';
    }

    private function getPrivacyContent()
    {
        return '<h2>Privacy Policy</h2>
        <p>This Privacy Policy describes how AllSewa collects, uses, and protects your personal information.</p>

        <h3>Information We Collect</h3>
        <p>We collect information you provide directly to us, such as when you:</p>
        <ul>
            <li>Create an account</li>
            <li>Make a purchase</li>
            <li>Contact us for support</li>
            <li>Subscribe to our newsletter</li>
        </ul>

        <h3>How We Use Your Information</h3>
        <p>We use the information we collect to:</p>
        <ul>
            <li>Provide, maintain, and improve our services</li>
            <li>Process transactions and send related information</li>
            <li>Send you technical notices and support messages</li>
            <li>Communicate with you about products, services, and events</li>
        </ul>

        <h3>Information Sharing</h3>
        <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>';
    }

    private function getAboutContent()
    {
        return '<h2>About AllSewa</h2>
        <p>AllSewa is a comprehensive multi-tenant e-commerce platform designed to connect buyers and sellers in a seamless marketplace experience.</p>

        <h3>Our Mission</h3>
        <p>To empower entrepreneurs and businesses by providing them with the tools and platform they need to succeed in the digital marketplace.</p>

        <h3>Our Vision</h3>
        <p>To become the leading e-commerce platform that fosters innovation, growth, and success for all our users.</p>

        <h3>What We Offer</h3>
        <ul>
            <li>Multi-vendor marketplace</li>
            <li>Secure payment processing</li>
            <li>Advanced seller tools</li>
            <li>Customer support</li>
            <li>Mobile-responsive design</li>
        </ul>

        <h3>Why Choose AllSewa?</h3>
        <p>With years of experience in e-commerce and a dedicated team of professionals, we provide:</p>
        <ul>
            <li>Reliable and secure platform</li>
            <li>24/7 customer support</li>
            <li>Competitive pricing</li>
            <li>Regular updates and improvements</li>
        </ul>';
    }

    private function getContactContent()
    {
        return '<h2>Contact Us</h2>
        <p>We\'d love to hear from you! Get in touch with us through any of the following methods:</p>

        <h3>Customer Support</h3>
        <p>For general inquiries and customer support:</p>
        <ul>
            <li><strong>Email:</strong> support@allsewa.com</li>
            <li><strong>Phone:</strong> +1 (555) 123-4567</li>
            <li><strong>Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM EST</li>
        </ul>

        <h3>Business Inquiries</h3>
        <p>For partnerships and business opportunities:</p>
        <ul>
            <li><strong>Email:</strong> business@allsewa.com</li>
            <li><strong>Phone:</strong> +1 (555) 123-4568</li>
        </ul>

        <h3>Technical Support</h3>
        <p>For technical issues and platform support:</p>
        <ul>
            <li><strong>Email:</strong> tech@allsewa.com</li>
            <li><strong>Phone:</strong> +1 (555) 123-4569</li>
        </ul>

        <h3>Office Address</h3>
        <p>AllSewa Headquarters<br>
        123 E-commerce Street<br>
        Digital City, DC 12345<br>
        United States</p>';
    }

    private function getBlogPostContent1()
    {
        return '<p>Starting your journey as an online seller can be both exciting and overwhelming. With the right strategies and mindset, you can build a successful online business. Here are 10 essential tips to help you get started:</p>

        <h3>1. Choose the Right Products</h3>
        <p>Research market demand and competition before selecting products to sell. Focus on items you\'re passionate about and that have a proven market.</p>

        <h3>2. Create Compelling Product Descriptions</h3>
        <p>Write detailed, engaging descriptions that highlight benefits and features. Use keywords that your customers are likely to search for.</p>

        <h3>3. Invest in Quality Photos</h3>
        <p>High-quality images are crucial for online sales. Show your products from multiple angles and in good lighting.</p>

        <h3>4. Price Competitively</h3>
        <p>Research competitor pricing and position your products competitively while maintaining healthy profit margins.</p>

        <h3>5. Provide Excellent Customer Service</h3>
        <p>Respond promptly to customer inquiries and resolve issues quickly. Great customer service leads to positive reviews and repeat customers.</p>';
    }

    private function getBlogPostContent2()
    {
        return '<p>Your product listings are your digital storefront. They\'re often the first impression potential customers have of your products. Here\'s how to optimize them for maximum impact:</p>

        <h3>Title Optimization</h3>
        <p>Create clear, descriptive titles that include relevant keywords. Make sure they accurately represent your product while being search-friendly.</p>

        <h3>High-Quality Images</h3>
        <p>Use professional-quality photos that show your product clearly. Include multiple angles, close-ups of important features, and lifestyle shots when appropriate.</p>

        <h3>Detailed Descriptions</h3>
        <p>Write comprehensive descriptions that answer common customer questions. Include dimensions, materials, care instructions, and any other relevant details.</p>

        <h3>Competitive Pricing</h3>
        <p>Research your competition and price your products competitively. Consider offering bundle deals or volume discounts to increase average order value.</p>

        <h3>Customer Reviews</h3>
        <p>Encourage satisfied customers to leave reviews. Positive reviews build trust and can significantly impact purchasing decisions.</p>';
    }

    private function getBlogPostContent3()
    {
        return '<p>We\'re excited to announce the launch of our enhanced seller dashboard, packed with new features and improvements to help you manage your business more effectively.</p>

        <h3>Advanced Analytics</h3>
        <p>Get deeper insights into your sales performance with our new analytics dashboard. Track revenue, orders, and customer behavior with detailed charts and reports.</p>

        <h3>Inventory Management</h3>
        <p>Our improved inventory system now includes low-stock alerts, bulk editing capabilities, and automated reorder suggestions.</p>

        <h3>Order Processing</h3>
        <p>Streamlined order management with batch processing, automated status updates, and integrated shipping label printing.</p>

        <h3>Customer Communication</h3>
        <p>New messaging system allows direct communication with customers, automated follow-ups, and review request campaigns.</p>

        <h3>Mobile Optimization</h3>
        <p>The dashboard is now fully optimized for mobile devices, allowing you to manage your business on the go.</p>';
    }
}
