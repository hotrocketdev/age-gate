/**
 * Hot Rocket Age Gate - Frontend JavaScript
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Prevent body scroll
        $('body').addClass('hotrocket-age-gate-active');

        // Handle Yes button click
        $('#hotrocketAgeGateYes').on('click', function(e) {
            e.preventDefault();
            handleAgeVerification(true);
        });

        // Handle No button click
        $('#hotrocketAgeGateNo').on('click', function(e) {
            e.preventDefault();
            handleAgeVerification(false);
        });

        // Handle Enter key press
        $(document).on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                handleAgeVerification(true);
            }
        });

        // Prevent clicking outside modal
        $('#hotrocketAgeGateOverlay').on('click', function(e) {
            if (e.target === this) {
                // Shake animation when clicking outside
                $('.hotrocket-age-gate-modal').css('animation', 'none');
                setTimeout(function() {
                    $('.hotrocket-age-gate-modal').css('animation', 'shake 0.5s ease');
                }, 10);
            }
        });
    });

    /**
     * Handle age verification
     */
    function handleAgeVerification(verified) {
        if (verified) {
            // User confirmed they are of legal age
            var rememberMe = $('#hotrocketAgeGateRemember').is(':checked');
            var cookieDuration = rememberMe ? hotrocketAgeGate.cookieDuration : 1; // 1 day if not remembered

            // Set cookie
            setCookie('hotrocket_age_verified', '1', cookieDuration);

            // Fade out and remove overlay
            $('#hotrocketAgeGateOverlay').addClass('fade-out');
            setTimeout(function() {
                $('#hotrocketAgeGateOverlay').remove();
                $('body').removeClass('hotrocket-age-gate-active');
            }, 400);
        } else {
            // User is not of legal age - redirect
            if (hotrocketAgeGate.redirectUrl) {
                window.location.href = hotrocketAgeGate.redirectUrl;
            } else {
                // Fallback - show message and close window
                alert('You must be of legal age to enter this site.');
                window.close();
            }
        }
    }

    /**
     * Set cookie
     */
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/; SameSite=Lax";
    }

    /**
     * Get cookie
     */
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

})(jQuery);

// Add shake animation to CSS dynamically
var style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: scale(1) translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: scale(1) translateX(-5px); }
        20%, 40%, 60%, 80% { transform: scale(1) translateX(5px); }
    }
`;
document.head.appendChild(style);
