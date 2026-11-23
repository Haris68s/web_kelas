// Toggle menu navigasi di tampilan HP (halaman publik)
// Sembunyikan skeleton loading & tampilkan konten asli setelah halaman siap.
// Dijalankan sesegera mungkin (bukan menunggu DOMContentLoaded penuh) supaya
// terasa responsif, tapi tetap dikasih jeda minimum biar skeleton-nya kelihatan
// (bukan cuma kedip sepersekian detik).
(function () {
    var WAKTU_MINIMUM = 350; // ms
    var mulai = Date.now();

    function tampilkanKonten() {
        var body = document.body;
        var overlay = document.getElementById('skeletonOverlay');
        var sisaWaktu = Math.max(0, WAKTU_MINIMUM - (Date.now() - mulai));

        setTimeout(function () {
            body.classList.remove('is-loading');
            if (overlay) {
                overlay.style.transition = 'opacity 0.25s ease';
                overlay.style.opacity = '0';
                setTimeout(function () { overlay.remove(); }, 250);
            }
        }, sisaWaktu);
    }

    if (document.readyState === 'complete') {
        tampilkanKonten();
    } else {
        window.addEventListener('load', tampilkanKonten);
    }
})();

document.addEventListener('DOMContentLoaded', function () {
    var navToggle = document.getElementById('navToggle');
    var siteNav = document.getElementById('siteNav');

    if (navToggle && siteNav) {
        navToggle.addEventListener('click', function () {
            var isOpen = siteNav.classList.toggle('is-open');
            navToggle.classList.toggle('is-open', isOpen);
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // Toggle sidebar admin di tampilan HP
    var sidebarToggle = document.getElementById('sidebarToggle');
    var adminSidebar = document.getElementById('adminSidebar');
    var sidebarBackdrop = document.getElementById('sidebarBackdrop');

    if (sidebarToggle && adminSidebar) {
        function setSidebarOpen(isOpen) {
            adminSidebar.classList.toggle('is-open', isOpen);
            sidebarBackdrop.classList.toggle('is-visible', isOpen);
            sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            sidebarToggle.setAttribute('aria-label', isOpen ? 'Tutup menu' : 'Buka menu');
            sidebarToggle.textContent = isOpen ? '×' : '☰';
        }

        sidebarToggle.addEventListener('click', function () {
            setSidebarOpen(!adminSidebar.classList.contains('is-open'));
        });

        if (sidebarBackdrop) {
            sidebarBackdrop.addEventListener('click', function () {
                setSidebarOpen(false);
            });
        }

        adminSidebar.querySelectorAll('nav a').forEach(function (link) {
            link.addEventListener('click', function () { setSidebarOpen(false); });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') setSidebarOpen(false);
        });
    }

    // Konfirmasi sebelum menghapus data di panel admin
    document.querySelectorAll('.js-confirm-delete').forEach(function (el) {
        el.addEventListener('click', function (e) {
            var pesan = el.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
            if (!confirm(pesan)) {
                e.preventDefault();
            }
        });
    });

    // Preview gambar sebelum diupload (dipakai di form siswa/galeri)
    document.querySelectorAll('.js-preview-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var targetId = input.getAttribute('data-preview-target');
            var target = document.getElementById(targetId);
            if (target && input.files && input.files[0]) {
                target.src = URL.createObjectURL(input.files[0]);
            }
        });
    });

    // Sembunyikan alert otomatis setelah beberapa detik
    document.querySelectorAll('.alert[data-autohide]').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.4s ease';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 3500);
    });

    // ================= ANIMASI SAAT SCROLL =================
    // Tandai elemen yang mau dianimasikan (kartu, section, dsb) lalu
    // munculkan satu per satu dengan sedikit delay saat masuk layar.
    var elemenAnimasi = document.querySelectorAll(
        '.card, .hero-card, .gallery-item, .stat-card, .section-head, .admin-panel, .empty-state, .table-wrap'
    );

    elemenAnimasi.forEach(function (el, index) {
        el.classList.add('reveal-on-scroll');
        // Beri jeda singkat antar elemen yang berdekatan biar terasa "mengalir"
        el.style.transitionDelay = (Math.min(index % 8, 8) * 0.06) + 's';
    });

    if ('IntersectionObserver' in window && elemenAnimasi.length) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        elemenAnimasi.forEach(function (el) { observer.observe(el); });
    } else {
        // Fallback: browser lama tanpa IntersectionObserver, langsung tampilkan semua
        elemenAnimasi.forEach(function (el) { el.classList.add('is-visible'); });
    }

    // Sedikit bayangan pada header saat halaman mulai di-scroll
    var siteHeader = document.querySelector('.site-header');
    if (siteHeader) {
        window.addEventListener('scroll', function () {
            siteHeader.classList.toggle('is-scrolled', window.scrollY > 8);
        }, { passive: true });
    }

    // ================= INDIKATOR NAVBAR YANG GESER =================
    // Pil di belakang menu aktif "berpindah tempat" mengikuti halaman yang
    // sedang dibuka, termasuk saat pindah halaman (bukan cuma hover).
    (function () {
        var nav = document.getElementById('siteNav');
        var indikator = document.getElementById('navIndicator');
        if (!nav || !indikator) return;

        var KEY = 'navIndicatorPos';

        function posisi(el) {
            return { left: el.offsetLeft, width: el.offsetWidth };
        }

        function letakkan(target, animasi) {
            if (!animasi) indikator.style.transition = 'none';
            indikator.style.left = target.left + 'px';
            indikator.style.width = target.width + 'px';
            indikator.style.opacity = target.width ? '1' : '0';
            if (!animasi) {
                // paksa reflow lalu kembalikan transisi normal
                indikator.offsetHeight;
                indikator.style.transition = '';
            }
        }

        function pasangUlang() {
            var aktif = nav.querySelector('a.active');
            if (!aktif) { indikator.style.opacity = '0'; return; }

            var target = posisi(aktif);
            var tersimpan = null;
            try { tersimpan = JSON.parse(sessionStorage.getItem(KEY) || 'null'); } catch (e) {}

            if (window.innerWidth > 980) {
                if (tersimpan) {
                    letakkan(tersimpan, false); // mulai dari posisi terakhir (halaman sebelumnya)
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () { letakkan(target, true); }); // geser ke posisi baru
                    });
                } else {
                    // Belum ada riwayat posisi (baru buka web pertama kali di tab ini):
                    // mulai dari titik tengah menu aktif dengan lebar 0, lalu "tumbuh" melebar.
                    var titikTengah = { left: target.left + target.width / 2, width: 0 };
                    letakkan(titikTengah, false);
                    requestAnimationFrame(function () {
                        requestAnimationFrame(function () { letakkan(target, true); });
                    });
                }
            }

            sessionStorage.setItem(KEY, JSON.stringify(target));
        }

        pasangUlang();

        // Saat resize, langsung pindah tanpa animasi (hindari kesan "loncat aneh")
        window.addEventListener('resize', function () {
            var aktif = nav.querySelector('a.active');
            if (aktif && window.innerWidth > 980) {
                letakkan(posisi(aktif), false);
            } else if (window.innerWidth <= 980) {
                indikator.style.opacity = '0';
            }
        });

        // Simpan posisi link yang di-klik sesaat sebelum pindah halaman,
        // supaya halaman berikutnya tahu harus mulai geser dari mana.
        nav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth > 980) {
                    sessionStorage.setItem(KEY, JSON.stringify(posisi(link)));
                }
            });
        });
    })();
});
