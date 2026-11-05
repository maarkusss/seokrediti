<footer class="footer-eirokrediti" data-nosnippet>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <p>&copy; Eirokrediti.lv 2015 - {{ date('Y') }}. <a href="https://goodday.group" target="_blank" rel="noopener">goodday.group</a></p>
            </div>
            <div class="col-md-4 text-center">
                <p><a href="/par-mums">Par mums &amp; reklāma</a></p>
            </div>
            <div class="col-md-4 text-right social-icons">
                <p>
                    <a href="https://www.draugiem.lv/eirokrediti" target="_blank" rel="noopener" aria-label="Draugiem.lv"><img src="/images/draugiem-icon.png" alt="draugiem" height="25" width="25"></a>
                    <a href="https://www.twitter.com/eirokrediti" target="_blank" rel="noopener" aria-label="Twitter"><img src="/images/twitter-icon.png" alt="twitter" height="25" width="25"></a>
                    <a href="https://www.facebook.com/pages/Ātrie-kredīti/364212140429543" target="_blank" rel="noopener" aria-label="Facebook"><img src="/images/facebook-icon.png" alt="facebook" height="25" width="25"></a>
                    <a href="https://www.youtube.com/channel/UCbl_zLhEM6v51ufT_NXAPrw" target="_blank" rel="noopener" aria-label="YouTube"><img src="/images/youtube-icon.png" alt="youtube" height="25" width="25"></a>
                </p>
            </div>
        </div>
    </div>
</footer>

<div id="scroll-to-top" style="display: none; cursor: pointer;" role="button" aria-label="Ritiniet uz augšu" tabindex="0">
    <img src="/images/to-top.png" width="40" height="40" alt="to top">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollToTopBtn = document.getElementById('scroll-to-top');
        if (scrollToTopBtn) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 400) {
                    scrollToTopBtn.style.display = 'block';
                } else {
                    scrollToTopBtn.style.display = 'none';
                }
            });
            const doScrollTop = function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };
            scrollToTopBtn.addEventListener('click', doScrollTop);
            scrollToTopBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    doScrollTop();
                }
            });
        }
    });
</script>
