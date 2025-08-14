<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zynolo MeetUp - Reset Password</title>
    <meta name="description" content="Zynolo MeetUp">
    <meta name="author" content="Tineth Pathirage">
    <link rel="shortcut icon" href="assets/images/zynolo-small.png">
    <script src="assets/js/hyper-config.js"></script>
    <link href="assets/css/app-saas.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <style>
        .auth-fluid-right {
            background-size: cover;
            background-position: center;
            transition: background-image 1s ease-in-out;
        }

        .auth-user-testimonial {
            border-radius: 15px;
        }

        .top-left-logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .top-left-logo img {
            width: 200px;
            height: auto;
        }
    </style>

</head>

<body class="authentication-bg pb-0">

    <div class="auth-fluid">

        <div class="auth-fluid-form-box">
            <div class="card-body d-flex flex-column h-100 gap-4">
                <div class="account-pages">
                    <div class="top-left-logo">
                        <img src="/assets/images/logo-black.png" alt="logo">
                    </div>

                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="p-0">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <div class="text-center w-full m-auto">
                                            <h4 class="text-dark-50 text-center fs-2 pb-0 fw-bold">Reset Password</h4>
                                            <p class="text-muted mb-4">Reset your password to access
                                                account.</p>
                                        </div>

                                        <form action="#">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">New Password</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control"
                                                        placeholder="Enter your new password">
                                                    <div class="input-group-text" data-password="false">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="password" class="form-label">Confirm Password</label>
                                                <div class="input-group input-group-merge">
                                                    <input type="password" id="password" class="form-control"
                                                        placeholder="Enter your confirm password">
                                                    <div class="input-group-text" data-password="false">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" mb-0 text-center">
                                                <button class="btn btn-primary w-100" type="submit"> Reset Password
                                                </button>
                                            </div>

                                        </form>
                                    </div> <!-- end card-body -->
                                </div>
                                <!-- end card -->
                                <footer class="footer footer-alt">
                                    <div class="d-flex justify-content-center align-items-center mt-0">
                                        <div class="d-flex align-items-center text-center">
                                            <p class="font-16 mb-0 fw-bold">Powerd By</p>
                                            <img src="/assets/images/footer-logo.png" alt="Footer Logo"
                                                class="img-fluid footer-logo" style="max-width: 100px;">
                                        </div>
                                    </div>
                                </footer>

                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end container -->
                </div>
            </div>
        </div>
        {{-- Slider Start --}}
        <div class="auth-fluid-right text-center" id="slideshow-container">
            <div
                class="auth-user-testimonial bg-dark bg-opacity-50 text-white w-75 p-3 d-inline-block w-[80%] w-sm-75 w-md-50 w-lg-40">
                <h2 class="mb-3 fs-2 fs-sm-4 fs-md-5 fs-lg-6" id="typing-text"></h2>
                <p class="lead fs-5 fs-sm-7 fs-md-8 fs-lg-9" id="typing-subtext"></p>
                <p class="fs-5 fs-sm-7 fs-md-8 fs-lg-9" id="typing-company"></p>
            </div>
        </div>
        {{-- Slider End --}}

    </div>

    <script>
        const slides = [{
                backgroundImage: 'assets/images/meating.jpg',
                text: 'ZYNOLO MEETUP',
                subtext: 'Simplify collaboration with smarter scheduling and meeting management tools. Plan, organize and run productive meetings effortlessly while keeping your team aligned and focused.',
                company: 'D Tech (Pvt) Ltd'
            },
            {
                backgroundImage: 'assets/images/auth-bg.jpg',
                text: 'ZYNOLO PEOPLE',
                subtext: 'Streamline your workforce with our all-in-one HRM system designed to simplify every aspect of employee management. From onboarding to performance tracking, ZYNOLO PEOPLE creates a connected and efficient workplace where teams thrive.',
                company: 'D Tech (Pvt) Ltd'
            },
            {
                backgroundImage: 'assets/images/learn.jpg',
                text: 'ZYNOLO LMS',
                subtext: 'Empower your organization’s growth with a seamless Learning Management System built to inspire development. Deliver engaging training, track progress and foster a culture of continuous learning for your team.',
                company: 'D Tech (Pvt) Ltd'
            },

        ];

        let currentSlide = 0;

        function updateSlide() {
            const slideshowContainer = document.getElementById('slideshow-container');
            const typingText = document.getElementById('typing-text');
            const typingSubtext = document.getElementById('typing-subtext');
            const typingCompany = document.getElementById('typing-company');

            const slide = slides[currentSlide];

            slideshowContainer.style.backgroundImage = `url('${slide.backgroundImage}')`;

            typingText.textContent = slide.text;
            typingSubtext.textContent = slide.subtext;
            typingCompany.textContent = slide.company;

            currentSlide = (currentSlide + 1) % slides.length;
        }

        updateSlide();
        setInterval(updateSlide, 3000);
    </script>


    <script src="assets/js/vendor.min.js"></script>

    <!-- App js -->
    <script src="assets/js/app.min.js"></script>

</body>

</html>
