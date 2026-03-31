<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
});

// Inject Bootstrap Icons font hanya untuk halaman ini, cabut saat unmount
let linkEl = null;
onMounted(() => {
    linkEl = document.createElement('link');
    linkEl.rel = 'stylesheet';
    linkEl.href = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';
    linkEl.id = 'welcome-bi-css';
    document.head.appendChild(linkEl);
});
onUnmounted(() => {
    const el = document.getElementById('welcome-bi-css');
    if (el) el.remove();
});
</script>

<template>
    <Head title="Welcome" />

    <div class="glow-wrap" aria-hidden="true">
        <div class="glow glow-blue"></div>
        <div class="glow glow-teal"></div>
    </div>

    <div class="page-wrap">

        <!-- Header -->
        <header class="page-header">
            <nav v-if="canLogin" class="nav-group">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="btn btn-dark">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')" class="btn btn-border">Log in</Link>
                    <Link v-if="canRegister" :href="route('register')" class="btn btn-primary">
                        Get Started <i class="bi bi-arrow-right"></i>
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Hero -->
        <main class="hero">

            <div class="badge-pill">
                <span class="dot-pulse"></span>
                Crafted by <strong>markumar</strong>
                <span class="sep">&middot;</span>
                <a href="https://github.com/markumarkntl" target="_blank" class="pill-link">
                    <i class="bi bi-github"></i> @markumarkntl
                </a>
            </div>

            <h1 class="hero-title">
                Welcome to the<br>
                <span class="grad-text">Next Generation</span>
            </h1>

            <p class="hero-sub">
                A beautifully designed, lightning-fast starting point for your web application.
                Powered by Vue, Metronic-style UI, and Laravel.
            </p>

            <div class="cta-row">
                <a href="https://laravel.com/docs" class="btn btn-white">
                    <i class="bi bi-book-half"></i> Read Documentation
                </a>
                <a href="https://laracasts.com" class="btn btn-outline">
                    <i class="bi bi-play-circle"></i> Watch Laracasts
                </a>
            </div>

            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon" style="color:#1b84ff"><i class="bi bi-lightning-charge-fill"></i></div>
                    <div class="stat-val">Fast</div>
                    <div class="stat-lbl">Performance</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color:#17c653"><i class="bi bi-shield-check"></i></div>
                    <div class="stat-val">Secure</div>
                    <div class="stat-lbl">By Default</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color:#f4931a"><i class="bi bi-layers-half"></i></div>
                    <div class="stat-val">Scalable</div>
                    <div class="stat-lbl">Architecture</div>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="page-footer">
            Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            &nbsp;&middot;&nbsp;
            Built with <a href="https://keenthemes.com/metronic" target="_blank">Metronic</a>
            &nbsp;&middot;&nbsp;
            by <a href="https://github.com/markumarkntl" target="_blank">@markumarkntl</a>
        </footer>

    </div>
</template>

<style scoped>
/* ─────────────────────────────────────────
   Semua style SCOPED — tidak bocor ke halaman lain
   ───────────────────────────────────────── */

/* Reset hanya untuk elemen dalam komponen ini */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

/* Ambient glow */
.glow-wrap {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
}
.glow {
    position: absolute;
    width: 520px;
    height: 520px;
    border-radius: 50%;
    filter: blur(80px);
}
.glow-blue {
    top: -15%;
    left: -10%;
    background: radial-gradient(circle, rgba(27,132,255,0.2) 0%, transparent 70%);
}
.glow-teal {
    bottom: -15%;
    right: -10%;
    background: radial-gradient(circle, rgba(0,210,200,0.15) 0%, transparent 70%);
}

/* Page wrapper */
.page-wrap {
    position: relative;
    z-index: 1;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    font-family: 'Inter', system-ui, sans-serif;
    background-color: #13141a;
    color: #c5c6d2;
}

/* Header */
.page-header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 1.5rem 2rem;
    max-width: 1280px;
    width: 100%;
    margin: 0 auto;
}
.nav-group {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-family: inherit;
    font-size: 0.875rem;
    font-weight: 600;
    padding: 0.5rem 1.1rem;
    border-radius: 0.5rem;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    white-space: nowrap;
    line-height: 1.4;
}
.btn-ghost {
    color: #c5c6d2;
    background: transparent;
    border-color: transparent;
}
.btn-ghost:hover { color: #fff; background: rgba(255,255,255,0.06); }

.btn-border {
    color: #1b84ff;
    background: transparent;
    border-color: #1b84ff;
    box-shadow: 0 0 10px rgba(27,132,255,0.2);
}
.btn-border:hover {
    color: #00d2c8;
    background: rgba(27,132,255,0.1);
    border-color: #00d2c8;
    box-shadow: 0 0 16px rgba(0,210,200,0.25);
}

.btn-dark {
    color: #fff;
    background: rgba(255,255,255,0.07);
    border-color: rgba(255,255,255,0.1);
}
.btn-dark:hover { background: rgba(255,255,255,0.13); color: #fff; }

.btn-primary {
    color: #fff;
    background: #1b84ff;
    border-color: #1b84ff;
    box-shadow: 0 0 18px rgba(27,132,255,0.35);
}
.btn-primary:hover {
    background: #056ee9;
    border-color: #056ee9;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 0 26px rgba(27,132,255,0.5);
}

.btn-white {
    color: #111318;
    background: #fff;
    border-color: #fff;
    font-size: 0.9rem;
    padding: 0.7rem 1.8rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 18px rgba(255,255,255,0.1);
}
.btn-white:hover { background: #e5e7ef; color: #111318; transform: translateY(-2px); }

.btn-outline {
    color: #fff;
    background: rgba(255,255,255,0.04);
    border-color: rgba(255,255,255,0.14);
    font-size: 0.9rem;
    padding: 0.7rem 1.8rem;
    border-radius: 0.75rem;
}
.btn-outline:hover {
    background: rgba(255,255,255,0.09);
    border-color: rgba(255,255,255,0.24);
    color: #fff;
    transform: translateY(-2px);
}

/* Hero */
.hero {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 3rem 1.5rem;
}

/* Badge pill */
.badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.4rem 1rem;
    border-radius: 50px;
    border: 1px solid rgba(27,132,255,0.3);
    background: rgba(27,132,255,0.08);
    font-size: 0.8rem;
    font-weight: 500;
    color: #c5c6d2;
    margin-bottom: 2rem;
    backdrop-filter: blur(8px);
    transition: transform 0.2s;
}
.badge-pill:hover { transform: scale(1.03); }
.badge-pill strong { color: #fff; }

.dot-pulse {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #1b84ff;
    flex-shrink: 0;
    animation: dotpulse 1.8s infinite;
}
@keyframes dotpulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.4; transform: scale(0.65); }
}

.sep { margin: 0 0.2rem; color: #555; }

.pill-link {
    color: #1b84ff;
    text-decoration: none;
    font-weight: 600;
}
.pill-link:hover { text-decoration: underline; }

/* Title */
.hero-title {
    font-size: clamp(2.2rem, 6vw, 4.4rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -0.03em;
    color: #fff;
    max-width: 820px;
    margin: 0 auto 1.2rem;
}
.grad-text {
    background: linear-gradient(135deg, #1b84ff 0%, #00d2c8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Subtitle */
.hero-sub {
    max-width: 580px;
    margin: 0 auto 2.4rem;
    font-size: 1.05rem;
    line-height: 1.75;
    color: #9a9cae;
}

/* CTA */
.cta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
    margin-bottom: 3rem;
}

/* Stats */
.stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}
.stat-card {
    background: rgba(26,29,35,0.9);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 0.75rem;
    padding: 1rem 1.5rem;
    text-align: center;
    min-width: 128px;
    backdrop-filter: blur(10px);
}
.stat-icon { font-size: 1.3rem; margin-bottom: 0.35rem; }
.stat-val  { font-size: 1.35rem; font-weight: 700; color: #fff; }
.stat-lbl  { font-size: 0.72rem; color: #9a9cae; margin-top: 0.1rem; }

/* Footer */
.page-footer {
    padding: 1.4rem 1rem;
    text-align: center;
    font-size: 0.78rem;
    color: #9a9cae;
    border-top: 1px solid rgba(255,255,255,0.07);
}
.page-footer a {
    color: #1b84ff;
    text-decoration: none;
    font-weight: 600;
}
.page-footer a:hover { text-decoration: underline; }
</style>