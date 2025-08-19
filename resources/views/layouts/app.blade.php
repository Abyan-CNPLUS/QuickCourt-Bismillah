<!-- <style>
    .dot-loader {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .dot-loader div {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #3498db;
        animation: bounce 0.6s infinite ease-in-out;
    }

    .dot-loader div:nth-child(2) {
        animation-delay: 0.2s;
    }

    .dot-loader div:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
</style> -->


</style>

<!-- Loading Overlay -->
<!-- Loading Overlay -->
<div id="loadingOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(255, 255, 255, 0.7);
    z-index: 9999;
    justify-content: center;
    align-items: center;
">
    <div class="dot-loader">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const overlay = document.getElementById('loadingOverlay');

        // Semua link
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (e) {
                // Bypass link yang mengarah ke #
                if (this.getAttribute('href') !== '#') {
                    overlay.style.display = 'flex';
                }
            });
        });

        // Semua form
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function () {
                overlay.style.display = 'flex';
            });
        });
    });
</script>
