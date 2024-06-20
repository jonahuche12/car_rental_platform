@extends('layouts.app')

@section('title', "Central School System - Terms and Conditions")
@section('page_title', "Terms and Conditions for Central School System")
@section('breadcrumb2', "Terms and Conditions")

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
    </style>
@endsection

@section('content')
    <div class="content">
        <h1>@yield('page_title')</h1>

        <h2>1. Introduction</h2>
        <p>Welcome to the Central School System ("we," "our," "us"). These Terms and Conditions govern your use of our website and services. By accessing or using our services, you agree to comply with and be bound by these Terms and Conditions. If you do not agree with these terms, please do not use our services.</p>

        <h2>2. Eligibility</h2>
        <p>Our services are intended for use by students, educators, and parents. Children under the age of 13 must have parental consent to use our services. By using our services, you confirm that you meet these eligibility requirements.</p>

        <h2>3. User Accounts</h2>
        <p>To access certain features of our services, you may need to create an account. You are responsible for maintaining the confidentiality of your account information, including your username and password, and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.</p>

        <h2>4. Use of Services</h2>
        <p>You agree to use our services only for lawful purposes and in accordance with these Terms and Conditions. You agree not to:</p>
        <ul>
            <li>Use our services in any way that violates any applicable federal, state, local, or international law or regulation.</li>
            <li>Engage in any activity that is harmful to others or that could damage, disable, overburden, or impair our services.</li>
            <li>Attempt to gain unauthorized access to any part of our services, accounts, computer systems, or networks.</li>
            <li>Use any automated means to access our services for any purpose without our express written permission.</li>
        </ul>

        <h2>5. Content</h2>
        <p>Our services may include content provided by us, our users, and third parties. We do not guarantee the accuracy, completeness, or usefulness of this content. Any reliance you place on such content is strictly at your own risk. We disclaim all liability and responsibility arising from any reliance placed on such materials by you or any other visitor to our services, or by anyone who may be informed of any of its contents.</p>

        <h2>6. Intellectual Property Rights</h2>
        <p>All content and materials on our services, including but not limited to text, graphics, logos, icons, images, audio clips, and software, are the property of Central School System or its content suppliers and are protected by copyright, trademark, and other intellectual property laws. You may not use, reproduce, modify, distribute, or display any content from our services without our prior written permission.</p>

        <h2>7. Privacy</h2>
        <p>Your use of our services is also governed by our Privacy Policy, which can be found at [link to Privacy Policy]. By using our services, you consent to the collection and use of your information as described in our Privacy Policy.</p>

        <h2>8. Termination</h2>
        <p>We may terminate or suspend your access to our services at any time, without prior notice or liability, for any reason, including if you breach these Terms and Conditions. Upon termination, your right to use our services will immediately cease.</p>

        <h2>9. Disclaimers</h2>
        <p>Our services are provided on an "as is" and "as available" basis. We make no warranties, express or implied, regarding the operation or availability of our services. We disclaim all warranties, including but not limited to, implied warranties of merchantability and fitness for a particular purpose.</p>

        <h2>10. Limitation of Liability</h2>
        <p>In no event shall Central School System, its affiliates, or their respective directors, officers, employees, or agents be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of our services, even if we have been advised of the possibility of such damages. Our total liability to you for any claims arising out of or related to these Terms and Conditions or your use of our services shall not exceed the amount paid by you, if any, for accessing our services.</p>

        <h2>11. Governing Law</h2>
        <p>These Terms and Conditions shall be governed by and construed in accordance with the laws of [Your State/Country], without regard to its conflict of law provisions. You agree to submit to the personal jurisdiction of the state and federal courts located in [Your State/Country] for the purpose of litigating all such claims or disputes.</p>

        <h2>12. Changes to These Terms and Conditions</h2>
        <p>We may update these Terms and Conditions from time to time. We will notify you of any changes by posting the new Terms and Conditions on this page. You are advised to review these Terms and Conditions periodically for any changes. Changes to these Terms and Conditions are effective when they are posted on this page. Your continued use of our services after any such changes constitutes your acceptance of the new Terms and Conditions.</p>

        <h2>13. Contact Us</h2>
        <p>If you have any questions about these Terms and Conditions, please contact us:</p>
        <div class="contact-info">
            <p>By email: [insert email address]</p>
            <p>By visiting this page on our website: [insert website contact page URL]</p>
            <p>By phone number: [insert phone number]</p>
            <p>By mail: [insert physical address]</p>
        </div>
    </div>
@endsection
