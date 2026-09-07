<footer class="footer">

    <div class="footer-overlay"></div>

    <div class="footer-container">

        {{-- ================================================= --}}
        {{-- COMPANY --}}
        {{-- ================================================= --}}

        <div class="footer-column company">

            <a href="{{ route('employee.dashboard') }}" class="footer-logo">

                <div class="footer-logo-icon">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIPCUTI">
                </div>

                <div class="footer-logo-text">
                    <h3>SIPCUTI</h3>
                    <span>Employee Leave Management System</span>
                </div>

            </a>

            <p class="footer-description">

                SIPCUTI merupakan sistem informasi pengajuan cuti berbasis web
                yang memudahkan proses pengajuan, persetujuan Manager,
                HRD serta monitoring cuti secara real-time.

            </p>

            <div class="footer-social">

                <a href="#">
                    <img width="25" height="25" src="https://img.icons8.com/ios-filled/50/facebook-new.png" alt="facebook-new"/>
                </a>

                <a href="#">
                    <img width="25" height="25" src="https://img.icons8.com/ios-filled/50/instagram-new.png" alt="instagram-new"/>
                </a>

                <a href="#">
                    <img width="25" height="25" src="https://img.icons8.com/ios-filled/50/linkedin-2.png" alt="linkedin-2"/>
                </a>

                <a href="#">
                    <img width="25" height="25" src="https://img.icons8.com/ios-filled/50/github.png" alt="github"/>
                </a>

            </div>

        </div>

        {{-- ================================================= --}}
        {{-- MENU --}}
        {{-- ================================================= --}}

        <div class="footer-column">

            <h4>Quick Menu</h4>

            <ul>

                <li>
                    <a href="{{ route('employee.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('employee.leave.index') }}">
                        Pengajuan Cuti
                    </a>
                </li>

                <li>
                    <a href="{{ route('employee.profile') }}">
                        Profile
                    </a>
                </li>

            </ul>

        </div>

        {{-- ================================================= --}}
        {{-- CONTACT --}}
        {{-- ================================================= --}}

        <div class="footer-column">

            <h4>Contact</h4>

            <ul class="contact-list">

                <li>
                    <img width="20" height="20" src="https://img.icons8.com/ios-filled/50/email.png" alt="email"/>
                    hrd@company.co.id
                </li>

                <li>
                    <img width="20" height="20" src="https://img.icons8.com/ios-filled/50/phone.png" alt="phone"/>
                    (021) 12345678
                </li>

                <li>
                    <img width="20" height="20" src="https://img.icons8.com/ios-filled/50/marker.png" alt="marker"/>
                    Jakarta, Indonesia
                </li>

            </ul>

        </div>

        {{-- ================================================= --}}
        {{-- MAP --}}
        {{-- ================================================= --}}

        <div class="footer-column">

            <h4>Office Location</h4>

            <div class="footer-map">
                
                    <iframe
                        src="https://maps.google.com/maps?q=PT%20TOA%20Galva%20Industries%20Depok&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
        
            </div>

        </div>

    </div>

    <div class="footer-bottom">

        <div>

            © {{ date('Y') }}

            <strong>SIPCUTI</strong>

            All Rights Reserved.

        </div>

        <div>

            Version 1.0

        </div>

    </div>

</footer>