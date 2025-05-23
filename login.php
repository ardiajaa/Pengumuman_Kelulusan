<?php
require_once 'config/auth.php';

if (isLoggedIn()) {
    header("Location: admin/dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (login($email, $password)) {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        $error = 'Email atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bank Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <style>
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            background-size: 200% 200%;
            animation: gradientBG 10s ease infinite;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .login-card {
            transition: all 0.5s ease;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border-radius: 20px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
        }

        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        .input-field {
            transition: all 0.3s ease;
            background: rgba(249, 250, 251, 0.8);
        }

        .input-field:focus {
            background: white;
            transform: scale(1.02);
        }

        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 relative">
    <div id="particles-js"></div>
    <div class="login-card w-full max-w-md animate__animated animate__fadeInUp">
        <div class="p-8">
            <div class="text-center mb-8">
                <?php
                // Pastikan path yang benar ke file database.php
                require_once __DIR__ . '/config/database.php';
                require_once __DIR__ . '/includes/functions.php';

                // Cek apakah koneksi database berhasil
                if ($conn) {
                    $settings = getSettings($conn);
                    if ($settings && !empty($settings['logo'])) {
                        $logoPath = 'assets/images/' . $settings['logo'];
                        if (file_exists($logoPath)) {
                            echo '<img src="' . $logoPath . '" alt="Logo Sekolah" 
                                  class="w-32 h-32 mx-auto mb-8 animate__animated animate__bounceIn object-contain">';
                        } else {
                            echo '<i class="fas fa-school text-blue-600 text-8xl mx-auto mb-8 animate__animated animate__bounceIn"></i>';
                        }
                    } else {
                        echo '<i class="fas fa-school text-blue-600 text-8xl mx-auto mb-8 animate__animated animate__bounceIn"></i>';
                    }
                } else {
                    echo '<i class="fas fa-school text-blue-600 text-8xl mx-auto mb-8 animate__animated animate__bounceIn"></i>';
                }
                ?>
                <h1 class="text-4xl font-bold text-gray-800 mb-2 animate__animated animate__fadeIn">Login Admin</h1>
                <p class="text-gray-600 text-lg animate__animated animate__fadeIn">Silahkan Masuk Disini!</p>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 animate__animated animate__shakeX">
                    <i class="fas fa-exclamation-circle mr-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"></i>
                        <input type="email" id="email" name="email" required
                            class="input-field w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 relative"
                            placeholder="contoh@email.com">
                    </div>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"></i>
                        <input type="password" id="password" name="password" required
                            class="input-field w-full pl-10 pr-10 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 relative"
                            placeholder="••••••••">
                        <i class="fas fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer hover:text-blue-600 transition-colors duration-200"
                            onclick="togglePasswordVisibility('password', this)"></i>
                    </div>
                    <script>
                        function togglePasswordVisibility(inputId, icon) {
                            const input = document.getElementById(inputId);
                            if (input.type === "password") {
                                input.type = "text";
                                icon.classList.replace('fa-eye', 'fa-eye-slash');
                            } else {
                                input.type = "password";
                                icon.classList.replace('fa-eye-slash', 'fa-eye');
                            }
                        }
                    </script>
                </div>
                <button type="submit"
                    class="btn-login w-full text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-8 text-center">
                <?php
                // Include file database untuk koneksi
                require_once __DIR__ . '/config/database.php';

                // Query untuk mendapatkan settings
                $sql = "SELECT * FROM settings WHERE id = 1";
                $result = mysqli_query($conn, $sql);
                $settings = mysqli_fetch_assoc($result);
                ?>
                <p class="text-gray-600 text-sm">
                    &copy; <?= date('Y') ?> <?= isset($settings['nama_sekolah']) ? $settings['nama_sekolah'] : 'Nama Sekolah' ?>. All rights reserved.
                </p>
            </div>
        </div>
    </div>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: "#ffffff"
                },
                shape: {
                    type: "circle",
                    stroke: {
                        width: 0,
                        color: "#000000"
                    },
                    polygon: {
                        nb_sides: 5
                    }
                },
                opacity: {
                    value: 0.5,
                    random: false,
                    anim: {
                        enable: false,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false,
                        speed: 40,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: "#ffffff",
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 6,
                    direction: "none",
                    random: false,
                    straight: false,
                    out_mode: "bounce",
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: true,
                        mode: "repulse"
                    },
                    onclick: {
                        enable: true,
                        mode: "push"
                    },
                    resize: true
                },
                modes: {
                    repulse: {
                        distance: 100,
                        duration: 0.4
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    </script>
</body>

</html>