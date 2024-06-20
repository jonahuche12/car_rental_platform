@extends('layouts.app')

@section('title', "Central School System - Privacy Policy")
@section('page_title', "Privacy Policy for Central School System")
@section('breadcrumb2', "Privacy Policy")

@section('sidebar')
    @include('sidebar')
@endsection

@section('style')
    <style>
        .content {
            margin: 20px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .content h1, .content h2, .content h3 {
            color: #333;
        }
        .content h1 {
            font-size: 2.5em;
            margin-bottom: 0.5em;
        }
        .content h2 {
            font-size: 1.5em;
            margin-top: 1em;
            margin-bottom: 0.5em;
        }
        .content h3 {
            font-size: 1.2em;
            margin-top: 1em;
            margin-bottom: 0.5em;
        }
        .content p {
            line-height: 1.6;
            color: #555;
        }
        .content ul {
            list-style: disc inside;
            margin: 1em 0;
            padding-left: 20px;
            color: #555;
        }
        .content li {
            margin-bottom: 0.5em;
        }
        .content .contact-info {
            margin-top: 2em;
        }
        .content .contact-info a {
            color: #007bff;
        }
        .content .contact-info a:hover {
            text-decoration: none;
        }
        .content .fa {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-shield-alt"></i> @yield('page_title')</h1>
        <p>Effective Date: 1<sup>st</sup> June 2024</p>

        <h2><i class="fas fa-info-circle"></i> Introduction</h2>
        <p>Welcome to the Central School System ("we," "our," "us"). Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our application and services. Please read this policy carefully. By accessing or using our services, you agree to this Privacy Policy.</p>

        <h2>1. Information We Collect</h2>
        <h3><i class="fas fa-user"></i> 1.1 Personal Information</h3>
        <p>We may collect personal information that you provide to us, including but not limited to:</p>
        <ul>
            <li>Name</li>
            <li>Email address</li>
            <li>Phone number</li>
            <li>School name</li>
            <li>Usernames and passwords</li>
            <li>Payment information (for subscription and purchases)</li>
        </ul>

        <h3><i class="fas fa-laptop"></i> 1.2 Non-Personal Information</h3>
        <p>We may collect non-personal information, such as:</p>
        <ul>
            <li>Device information (e.g., IP address, browser type, operating system)</li>
            <li>Usage data (e.g., pages visited, time spent on the site, links clicked)</li>
        </ul>

        <h3><i class="fas fa-cookie"></i> 1.3 Cookies and Tracking Technologies</h3>
        <p>We use cookies and similar tracking technologies to enhance your experience on our site. These technologies help us understand user behavior, track user preferences, and improve our services.</p>

        <h2>2. How We Use Your Information</h2>
        <p>We use the collected information for various purposes, including:</p>
        <ul>
            <li>To provide, operate, and maintain our services</li>
            <li>To improve, personalize, and expand our services</li>
            <li>To understand and analyze how you use our services</li>
            <li>To develop new products, services, features, and functionality</li>
            <li>To process transactions and manage subscriptions</li>
            <li>To communicate with you, including sending updates, newsletters, and promotional materials</li>
            <li>To provide customer support and respond to inquiries</li>
            <li>To enforce our terms, conditions, and policies</li>
            <li>To detect, prevent, and address technical issues and security breaches</li>
            <li>To comply with legal obligations and protect our rights and interests</li>
        </ul>

        <h2>3. Information Sharing and Disclosure</h2>
        <p>We do not sell, trade, or otherwise transfer to outside parties your Personally Identifiable Information unless we provide users with advance notice. This does not include website hosting partners and other parties who assist us in operating our website, conducting our business, or serving our users, so long as those parties agree to keep this information confidential. We may also release information when its release is appropriate to comply with the law, enforce our site policies, or protect ours or others' rights, property, or safety.</p>

        <h3><i class="fas fa-handshake"></i> 3.1 Service Providers</h3>
        <p>We may employ third-party companies and individuals to facilitate our services ("Service Providers"), to provide the services on our behalf, to perform service-related services, or to assist us in analyzing how our services are used. These third parties have access to your personal information only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>

        <h3><i class="fas fa-building"></i> 3.2 Business Transfers</h3>
        <p>In the event of a merger, acquisition, or asset sale, your personal information may be transferred. We will provide notice before your personal information is transferred and becomes subject to a different privacy policy.</p>

        <h3><i class="fas fa-balance-scale"></i> 3.3 Legal Requirements</h3>
        <p>We may disclose your personal information if required to do so by law or in response to valid requests by public authorities (e.g., a court or a government agency).</p>

        <h2>4. Data Security</h2>
        <p>We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information. These measures include encryption, secure socket layer (SSL) technology, and other measures to protect your information from unauthorized access, alteration, disclosure, or destruction.</p>

        <h2>5. Data Retention</h2>
        <p>We will retain your personal information only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use your personal information to the extent necessary to comply with our legal obligations (for example, if we are required to retain your data to comply with applicable laws), resolve disputes, and enforce our legal agreements and policies.</p>

        <h2>6. Your Data Protection Rights</h2>
        <p>Depending on your location, you may have the following rights regarding your personal information:</p>
        <ul>
            <li>The right to access – You have the right to request copies of your personal information.</li>
            <li>The right to rectification – You have the right to request that we correct any information you believe is inaccurate or complete information you believe is incomplete.</li>
            <li>The right to erasure – You have the right to request that we erase your personal information, under certain conditions.</li>
            <li>The right to restrict processing – You have the right to request that we restrict the processing of your personal information, under certain conditions.</li>
            <li>The right to object to processing – You have the right to object to our processing of your personal information, under certain conditions.</li>
            <li>The right to data portability – You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.</li>
        </ul>
        <p>If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us at our contact information below.</p>

        <h2>7. Children's Privacy</h2>
        <p>Our services are designed to be used by children as part of our educational platform. We collect personal information from children under 13 solely for the purpose of providing appropriate educational content and ensuring a personalized learning experience. If you become aware that a Child has provided us with Personal Information without parental consent, please contact us. We take steps to ensure that we obtain parental consent where necessary and to remove any information collected without such consent.</p>

        <h2>8. Changes to This Privacy Policy</h2>
        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page. If we make significant changes,
        <h2>9. Contact Us</h2>
    <p>If you have any questions about this Privacy Policy, please contact us:</p>
    <div class="contact-info">
        <p><i class="fas fa-envelope"></i> By email: <a href="mailto:support@centralschoolsystem.com">support@centralschoolsystem.com</a></p>
        <p><i class="fas fa-globe"></i> By visiting this page on our website: <a href="https://www.centralschoolsystem.com">https://www.centralschoolsystem.com</a></p>
        <p><i class="fas fa-phone"></i> By phone number: +234 9036518913</p>
        <p><i class="fas fa-map-marker-alt"></i> P.O. Box 8036</p>
    </div>
</div>
@endsection

