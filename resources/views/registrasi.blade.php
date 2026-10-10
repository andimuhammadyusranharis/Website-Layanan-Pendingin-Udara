<!doctype html>
<html lang="id" style="">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="web_standard" name="shell-type" />
        <link href="https://fonts.googleapis.com" rel="preconnect" />
        <link crossorigin="" href="https://fonts.gstatic.com"rel="preconnect"/>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
        <style>
            @layer base {
                html,
                body {
                    margin: 0;
                    padding: 0;
                }
                body {
                    overscroll-behavior: none;
                }
                main > :first-child {
                    margin-top: 0 !important;
                }
                main > :last-child {
                    margin-bottom: 0 !important;
                }
            }
        </style>
        <script src="https://cdn.tailwindcss.com"></script>
        <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "text-secondary": "#5B6675",
                            "on-error": "#ffffff",
                            "secondary-container": "#92b6fd",
                            "on-error-container": "#93000a",
                            "on-tertiary-fixed": "#001f24",
                            error: "#ba1a1a",
                            "tertiary-fixed-dim": "#53d8ea",
                            tertiary: "#005a63",
                            "on-background": "#191c1e",
                            "inverse-surface": "#2d3133",
                            "on-tertiary": "#ffffff",
                            "on-primary-fixed": "#001945",
                            "surface-tint": "#0158cb",
                            "primary-container": "#1e63d6",
                            "status-warning": "#F2A93B",
                            "inverse-primary": "#b0c6ff",
                            "on-secondary": "#ffffff",
                            "surface-bright": "#f7f9fc",
                            "surface-container-lowest": "#ffffff",
                            "surface-container-low": "#f2f4f7",
                            "tertiary-fixed": "#96f0ff",
                            "on-secondary-container": "#1c4685",
                            "surface-container": "#eceef1",
                            primary: "#004bb0",
                            "surface-dim": "#d8dadd",
                            "on-primary-container": "#e3e9ff",
                            "on-surface-variant": "#424653",
                            "surface-container-high": "#e6e8eb",
                            "on-secondary-fixed": "#001a40",
                            "status-success": "#1E9E6A",
                            "surface-container-highest": "#e0e3e6",
                            "outline-variant": "#c2c6d6",
                            "border-subtle": "#E3E8EF",
                            surface: "#f7f9fc",
                            "tertiary-container": "#007480",
                            "secondary-fixed": "#d7e2ff",
                            "status-danger": "#D64545",
                            "on-tertiary-fixed-variant": "#004f57",
                            "on-surface": "#191c1e",
                            "secondary-fixed-dim": "#acc7ff",
                            "inverse-on-surface": "#eff1f4",
                            outline: "#737785",
                            "surface-dark": "#0F1F44",
                            "on-tertiary-container": "#b4f4ff",
                            "error-container": "#ffdad6",
                            "primary-fixed": "#d9e2ff",
                            secondary: "#385e9e",
                            "text-primary": "#1F2937",
                            "surface-variant": "#e0e3e6",
                            background: "#f7f9fc",
                            "on-secondary-fixed-variant": "#1c4585",
                            "primary-fixed-dim": "#b0c6ff",
                            "on-primary": "#ffffff",
                            "on-primary-fixed-variant": "#00429c",
                        },
                        borderRadius: {
                            DEFAULT: "0.25rem",
                            lg: "0.5rem",
                            xl: "0.75rem",
                            full: "9999px",
                        },
                        spacing: {
                            "margin-mobile": "1rem",
                            "space-xl": "2.5rem",
                            "space-lg": "1.5rem",
                            margin: "2rem",
                            "gutter-mobile": "1rem",
                            "space-xs": "0.5rem",
                            "space-sm": "0.75rem",
                            gutter: "1.5rem",
                            "space-md": "1rem",
                        },
                        fontFamily: {
                            "body-md": ["Plus Jakarta Sans"],
                            "label-lg": ["Plus Jakarta Sans"],
                            "headline-md": ["Plus Jakarta Sans"],
                            "label-sm": ["Plus Jakarta Sans"],
                            "display-hero": ["Plus Jakarta Sans"],
                            "display-hero-mobile": ["Plus Jakarta Sans"],
                            "headline-sm": ["Plus Jakarta Sans"],
                            "label-md": ["Plus Jakarta Sans"],
                            "headline-lg-mobile": ["Plus Jakarta Sans"],
                            "body-lg": ["Plus Jakarta Sans"],
                            "headline-lg": ["Plus Jakarta Sans"],
                            "body-semibold": ["Plus Jakarta Sans"],
                        },
                        fontSize: {
                            "body-md": [
                                "16px",
                                { lineHeight: "26px", fontWeight: "400" },
                            ],
                            "label-lg": [
                                "16px",
                                { lineHeight: "24px", fontWeight: "600" },
                            ],
                            "headline-md": [
                                "24px",
                                { lineHeight: "32px", fontWeight: "700" },
                            ],
                            "label-sm": [
                                "13px",
                                { lineHeight: "18px", fontWeight: "600" },
                            ],
                            "display-hero": [
                                "48px",
                                { lineHeight: "56px", fontWeight: "800" },
                            ],
                            "display-hero-mobile": [
                                "32px",
                                { lineHeight: "40px", fontWeight: "800" },
                            ],
                            "headline-sm": [
                                "20px",
                                { lineHeight: "28px", fontWeight: "600" },
                            ],
                            "label-md": [
                                "14px",
                                { lineHeight: "20px", fontWeight: "700" },
                            ],
                            "headline-lg-mobile": [
                                "26px",
                                { lineHeight: "34px", fontWeight: "700" },
                            ],
                            "body-lg": [
                                "18px",
                                { lineHeight: "28px", fontWeight: "400" },
                            ],
                            "headline-lg": [
                                "36px",
                                { lineHeight: "44px", fontWeight: "700" },
                            ],
                            "body-semibold": [
                                "16px",
                                { lineHeight: "26px", fontWeight: "600" },
                            ],
                        },
                    },
                },
            };
        </script>
    </head>
    <body class="bg-background font-body-md text-on-surface min-h-screen flex flex-col antialiased">
        <header class="sticky top-0 z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(20,64,127,0.05)]">
            <div class="h-20 max-w-[1280px] mx-auto px-margin flex items-center justify-between gap-gutter">
                <div class="flex items-center gap-space-md">
                    <div class="flex flex-col">
                        <span
                            class="font-label-md text-label-md text-primary tracking-tight leading-tight"
                            >CV. Tiga Putra Teknik</span
                        ><span
                            class="font-label-sm text-label-sm text-text-secondary leading-tight"
                            >Layanan AC Terpercaya Pangkep</span
                        >
                    </div>
                </div>
                <div class="flex items-center gap-space-lg">
                    <a
                        class="flex items-center gap-space-xs text-text-secondary hover:text-primary transition-colors font-label-sm text-label-sm py-space-xs px-space-md rounded-full bg-surface-container-low hover:bg-surface-container"
                        href="https://wa.me/6281234567890"
                        rel="noopener noreferrer"
                        target="_blank"
                        ><span
                            class="material-symbols-outlined text-[20px] text-status-success"
                            >headset_mic</span
                        ><span class=""
                            >Butuh bantuan?
                            <strong class="text-text-primary font-body-semibold"
                                >0812-3456-7890</strong
                            ></span
                        ></a
                    >
                </div>
            </div>
        </header>

        <main class="w-full bg-background flex-1 flex flex-col">
            <div class="flex flex-col w-full my-auto">
                <section class="w-full py-space-lg lg:py-space-md px-margin-mobile sm:px-margin max-w-[1280px] mx-auto">
                    <!-- Breadcrumb & Micro Tracker -->

                    <!-- Main Desktop Split Grid -->
                    <div class="w-full max-w-md mx-auto">
                        <!-- Formulir Pendaftaran Pelanggan -->
                        <div class="lg:col-span-7 bg-surface-container-lowest rounded-3xl p-space-md sm:p-space-lg shadow-[0_15px_35px_-5px_rgba(20,64,127,0.08),0_4px_10px_-2px_rgba(20,64,127,0.03)]">
                            <div>
                                <!-- Form Header -->
                                <div class="flex flex-col gap-1 pb-space-md">
                                    <h2 class="font-headline-md text-headline-sm font-bold text-text-primary">
                                        Buat Akun
                                    </h2>
                                </div>
                                <!-- Interactive Registration Form -->
                                <form
                                    class="flex flex-col gap-space-md"
                                    id="customer-register-form"
                                    onsubmit="
                                        event.preventDefault();
                                        handleSubmit();
                                    "
                                >
                                    <!-- Field 1 & 2: Nama & Email -->
                                    <div
                                        class="flex flex-col gap-space-md"
                                    >
                                        <!-- Field 1: Nama Lengkap -->
                                        <div class="flex flex-col gap-1.5">
                                            <label
                                                class="font-label-sm text-label-sm text-text-primary flex items-center justify-between"
                                                for="reg-name"
                                                ><span class=""
                                                    >Nama Lengkap
                                                    <span class="text-status-danger"
                                                        >*</span
                                                    ></span
                                                ></label
                                            >
                                            <div class="relative">
                                                <span
                                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[18px] pointer-events-none"
                                                    >badge</span
                                                >
                                                <input
                                                    class="w-full min-h-[38px] pl-10 pr-3 bg-surface-container-low text-text-primary font-body-md text-sm rounded-xl transition-all focus:bg-surface-container-lowest focus:shadow-md focus:shadow-primary/10 outline-none"
                                                    id="reg-name"
                                                    name="fullName"
                                                    autocomplete="name"
                                                    placeholder="Contoh: Raihan Nur Faiz"
                                                    required=""
                                                    type="text"
                                                />
                                            </div>
                                        </div>
                                        <!-- Field 2: Email -->
                                        <div class="flex flex-col gap-1.5">
                                            <label
                                                class="font-label-sm text-label-sm text-text-primary flex items-center justify-between"
                                                for="reg-email"
                                            >
                                                <span class=""
                                                    >Alamat Email
                                                    <span class="text-status-danger"
                                                        >*</span
                                                    ></span
                                                >
                                            </label>
                                            <div class="relative">
                                                <span
                                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[18px] pointer-events-none"
                                                    >mail</span
                                                >
                                                <input
                                                    class="w-full min-h-[38px] pl-10 pr-3 bg-surface-container-low text-text-primary font-body-md text-sm rounded-xl transition-all focus:bg-surface-container-lowest focus:shadow-md focus:shadow-primary/10 outline-none"
                                                    id="reg-email"
                                                    name="email"
                                                    autocomplete="email"
                                                    placeholder="nama@email.com"
                                                    required=""
                                                    type="email"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Field 3 & 4: Kata Sandi & Konfirmasi Grid -->
                                    <div
                                        class="flex flex-col gap-space-md"
                                    >
                                        <!-- Kata Sandi -->
                                        <div class="flex flex-col gap-1.5">
                                            <label
                                                class="font-label-sm text-label-sm text-text-primary"
                                                for="reg-password"
                                            >
                                                Kata Sandi
                                                <span class="text-status-danger"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <span
                                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[18px] pointer-events-none"
                                                    >lock</span
                                                >
                                                <input
                                                    class="w-full min-h-[38px] pl-10 pr-10 bg-surface-container-low text-text-primary font-body-md text-sm rounded-xl transition-all focus:bg-surface-container-lowest focus:shadow-md focus:shadow-primary/10 outline-none"
                                                    id="reg-password"
                                                    minlength="8"
                                                    name="password"
                                                    autocomplete="new-password"
                                                    oninput="
                                                        checkPasswordStrength(
                                                            this.value,
                                                        )
                                                    "
                                                    placeholder="Minimal 8 karakter"
                                                    required=""
                                                    type="password"
                                                />
                                                <button
                                                    aria-label="Tampilkan sandi"
                                                    class="absolute right-1.5 top-1/2 -translate-y-1/2 p-1.5 text-text-secondary hover:text-text-primary focus:outline-none"
                                                    onclick="
                                                        togglePasswordVisibility(
                                                            'reg-password',
                                                            'toggle-pass-icon',
                                                        )
                                                    "
                                                    type="button"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-[18px]"
                                                        id="toggle-pass-icon"
                                                        >visibility</span
                                                    >
                                                </button>
                                            </div>
                                            <!-- Indikator Kekuatan Sandi -->
                                            <div
                                                class="flex flex-col gap-1 mt-1"
                                            >
                                                <div
                                                    class="w-full h-1.5 bg-surface-container rounded-full overflow-hidden flex"
                                                >
                                                    <div
                                                        class="h-full w-1/3 bg-transparent transition-colors"
                                                        id="strength-bar-1"
                                                    ></div>
                                                    <div
                                                        class="h-full w-1/3 bg-transparent transition-colors ml-0.5"
                                                        id="strength-bar-2"
                                                    ></div>
                                                    <div
                                                        class="h-full w-1/3 bg-transparent transition-colors ml-0.5"
                                                        id="strength-bar-3"
                                                    ></div>
                                                </div>
                                                <span
                                                    class="font-label-sm text-label-sm text-text-secondary"
                                                    id="strength-label"
                                                    >Masukkan minimal 8
                                                    karakter</span
                                                >
                                            </div>
                                        </div>
                                        <!-- Konfirmasi Kata Sandi -->
                                        <div class="flex flex-col gap-1.5">
                                            <label
                                                class="font-label-sm text-label-sm text-text-primary"
                                                for="reg-password-confirm"
                                            >
                                                Konfirmasi Kata Sandi
                                                <span class="text-status-danger"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="relative">
                                                <span
                                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[18px] pointer-events-none"
                                                    >lock_reset</span
                                                >
                                                <input
                                                    class="w-full min-h-[38px] pl-10 pr-10 bg-surface-container-low text-text-primary font-body-md text-sm rounded-xl transition-all focus:bg-surface-container-lowest focus:shadow-md focus:shadow-primary/10 outline-none"
                                                    id="reg-password-confirm"
                                                    minlength="8"
                                                    name="passwordConfirm"
                                                    autocomplete="new-password"
                                                    oninput="validateMatch()"
                                                    placeholder="Ulangi kata sandi"
                                                    required=""
                                                    type="password"
                                                />
                                                <button
                                                    aria-label="Tampilkan sandi"
                                                    class="absolute right-1.5 top-1/2 -translate-y-1/2 p-1.5 text-text-secondary hover:text-text-primary focus:outline-none"
                                                    onclick="
                                                        togglePasswordVisibility(
                                                            'reg-password-confirm',
                                                            'toggle-confirm-icon',
                                                        )
                                                    "
                                                    type="button"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-[18px]"
                                                        id="toggle-confirm-icon"
                                                        >visibility</span
                                                    >
                                                </button>
                                            </div>
                                            <div class="mt-1">
                                                <span
                                                    class="font-label-sm text-label-sm text-text-secondary"
                                                    id="match-label"
                                                    >Harus sama dengan kata sandi di
                                                    atas</span
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Checkbox Persetujuan Ketentuan & Privasi -->
                                    <div>
                                        <label
                                            class="flex items-start gap-space-sm cursor-pointer select-none group"
                                        >
                                            <input
                                                class="mt-0.5 w-4 h-4 rounded text-primary focus:ring-primary/20 accent-primary cursor-pointer shrink-0"
                                                id="reg-terms"
                                                required=""
                                                type="checkbox"
                                            />
                                            <span
                                                class="font-body-md text-xs text-text-secondary leading-relaxed"
                                            >
                                                Saya menyetujui
                                                <a
                                                    class="text-primary font-body-semibold hover:underline"
                                                    data-path="syarat-dan-ketentuan"
                                                    href="#"
                                                    >Syarat &amp; Ketentuan</a
                                                >
                                                serta
                                                <a
                                                    class="text-primary font-body-semibold hover:underline"
                                                    data-path="kebijakan-privasi"
                                                    href="#"
                                                    >Kebijakan Privasi</a
                                                >
                                                servis AC CV. Tiga Putra Teknik
                                                Pangkep.
                                            </span>
                                        </label>
                                    </div>
                                    <!-- Submit CTA Button -->
                                    <button
                                        class="w-full min-h-[40px] h-10 bg-primary-container text-on-primary font-label-md text-label-md rounded-full flex items-center justify-center gap-space-xs shadow-md shadow-primary/20 hover:bg-primary transition-all duration-200 mt-space-xs cursor-pointer"
                                        type="submit"
                                    >
                                        <span
                                            class="material-symbols-outlined text-[18px]"
                                            >check_circle</span
                                        >
                                        <span class=""
                                            >Daftar</span
                                        >
                                    </button>
                                </form>
                            </div>
                            <!-- Form Footer / Links -->
                            <div
                                class="mt-space-md pt-space-md border-t border-border-subtle flex flex-col items-center gap-space-xs text-center"
                            >
                                <!-- Sudah Punya Akun Link -->
                                <p
                                    class="font-label-sm text-label-sm font-normal text-text-secondary"
                                >
                                    Sudah memiliki akun?
                                    <a
                                        class="font-body-semibold text-sm text-primary hover:underline inline-flex items-center gap-0.5"
                                        data-path="masuk"
                                        href="{{ route('login') }}"
                                    >
                                        Masuk di sini
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Assurance Badges Ribbon -->
                </section>
            </div>
            <script>
                function togglePasswordVisibility(fieldId, iconId) {
                    const field = document.getElementById(fieldId);
                    const icon = document.getElementById(iconId);
                    if (!field || !icon) return;

                    if (field.type === "password") {
                        field.type = "text";
                        icon.innerText = "visibility_off";
                    } else {
                        field.type = "password";
                        icon.innerText = "visibility";
                    }
                }

                function checkPasswordStrength(val) {
                    const bar1 = document.getElementById("strength-bar-1");
                    const bar2 = document.getElementById("strength-bar-2");
                    const bar3 = document.getElementById("strength-bar-3");
                    const label = document.getElementById("strength-label");

                    // Reset
                    bar1.className =
                        "h-full w-1/3 bg-transparent transition-colors";
                    bar2.className =
                        "h-full w-1/3 bg-transparent transition-colors ml-0.5";
                    bar3.className =
                        "h-full w-1/3 bg-transparent transition-colors ml-0.5";

                    if (!val || val.length === 0) {
                        label.innerText = "Masukkan minimal 8 karakter";
                        label.className =
                            "font-label-sm text-label-sm text-text-secondary";
                        return;
                    }

                    if (val.length < 6) {
                        bar1.className =
                            "h-full w-1/3 bg-status-danger transition-colors";
                        label.innerText = "Kata sandi terlalu pendek";
                        label.className =
                            "font-label-sm text-label-sm text-status-danger";
                    } else if (
                        val.length < 8 ||
                        !(/[0-9]/.test(val) && /[a-zA-Z]/.test(val))
                    ) {
                        bar1.className =
                            "h-full w-1/3 bg-status-warning transition-colors";
                        bar2.className =
                            "h-full w-1/3 bg-status-warning transition-colors ml-0.5";
                        label.innerText =
                            "Cukup kuat (tambahkan angka & huruf)";
                        label.className =
                            "font-label-sm text-label-sm text-status-warning";
                    } else {
                        bar1.className =
                            "h-full w-1/3 bg-status-success transition-colors";
                        bar2.className =
                            "h-full w-1/3 bg-status-success transition-colors ml-0.5";
                        bar3.className =
                            "h-full w-1/3 bg-status-success transition-colors ml-0.5";
                        label.innerText = "Sangat kuat & aman";
                        label.className =
                            "font-label-sm text-label-sm text-status-success font-semibold";
                    }
                    validateMatch();
                }

                function validateMatch() {
                    const pwd = document.getElementById("reg-password").value;
                    const confirm = document.getElementById(
                        "reg-password-confirm",
                    ).value;
                    const matchLabel = document.getElementById("match-label");

                    if (!confirm) {
                        matchLabel.innerText =
                            "Harus sama dengan sandi di kiri";
                        matchLabel.className =
                            "font-label-sm text-label-sm text-text-secondary";
                        return;
                    }

                    if (pwd === confirm) {
                        matchLabel.innerText = "Kata sandi cocok";
                        matchLabel.className =
                            "font-label-sm text-label-sm text-status-success font-semibold";
                    } else {
                        matchLabel.innerText = "Kata sandi belum sama";
                        matchLabel.className =
                            "font-label-sm text-label-sm text-status-danger";
                    }
                }

                function handleSubmit() {
                    const pwd = document.getElementById("reg-password").value;
                    const confirm = document.getElementById(
                        "reg-password-confirm",
                    ).value;
                    const name = document.getElementById("reg-name").value;

                    if (pwd !== confirm) {
                        alert(
                            "Mohon pastikan konfirmasi kata sandi telah sesuai.",
                        );
                        document.getElementById("reg-password-confirm").focus();
                        return;
                    }

                    // Interactive Feedback Simulation
                    const btn = document.querySelector(
                        '#customer-register-form button[type="submit"]',
                    );
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[20px]">sync</span><span>Mendaftarkan Akun Anda...</span>`;

                    setTimeout(() => {
                        btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">check</span><span>Pendaftaran Berhasil! Mengalihkan...</span>`;
                        btn.classList.remove(
                            "bg-primary-container",
                            "hover:bg-primary",
                        );
                        btn.classList.add("bg-status-success");

                        setTimeout(() => {
                            // Redirection trigger simulation
                            alert(
                                "Selamat datang " +
                                    name +
                                    " di CV. Tiga Putra Teknik! Akun Anda aktif.",
                            );
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                            btn.classList.remove("bg-status-success");
                            btn.classList.add("bg-primary-container");
                        }, 1000);
                    }, 1200);
                }
            </script>
        </main>

        <footer class="w-full bg-surface-container-low py-space-lg shadow-[0_-1px_6px_rgba(20,64,127,0.03)] shrink-0">
            <div class="max-w-[1280px] mx-auto px-margin flex flex-col sm:flex-row items-center justify-between gap-space-md text-center sm:text-left">
                <p class="font-label-sm text-label-sm text-text-secondary">
                    © 2026 CV. Tiga Putra Teknik Pangkep. Seluruh hak cipta
                    dilindungi.
                </p>
                <div
                    class="flex items-center gap-space-lg font-label-sm text-label-sm text-on-surface-variant"
                >
                    <a
                        class="hover:text-primary transition-colors"
                        data-path="kebijakan-privasi"
                        href="#"
                        >Kebijakan Privasi</a
                    ><span class="text-border-subtle">•</span
                    ><a
                        class="hover:text-primary transition-colors"
                        data-path="syarat-dan-ketentuan"
                        href="#"
                        >Syarat &amp; Ketentuan</a
                    ><span class="text-border-subtle">•</span
                    ><a
                        class="hover:text-primary transition-colors"
                        data-path="bantuan-teknis"
                        href="#"
                        >Bantuan Teknis</a
                    >
                </div>
            </div>
        </footer>
    </body>
</html>
