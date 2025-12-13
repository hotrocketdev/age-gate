/**
 * HotRocket Age Gate - Frontend JavaScript
 * Handles age verification with simple and date input methods
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Prevent body scroll
        $('body').addClass('hotrocket-age-gate-active');

        // Handle Yes button click (simple method)
        $('#hotrocketAgeGateYes').on('click', function(e) {
            e.preventDefault();
            handleAgeVerification(true);
        });

        // Handle No button click
        $('#hotrocketAgeGateNo').on('click', function(e) {
            e.preventDefault();
            handleAgeVerification(false);
        });

        // Handle date verification button click
        $('#hotrocketAgeGateVerifyDate').on('click', function(e) {
            e.preventDefault();
            handleDateVerification();
        });

        // Handle Enter key press
        $(document).on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                if ($('#hotrocketAgeGateVerifyDate').length) {
                    handleDateVerification();
                } else {
                    handleAgeVerification(true);
                }
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

        // Auto-focus first date input if date method is active
        if ($('#hotrocketAgeGateDateInput').length) {
            $('#hotrocketAgeGateDateInput input:first').focus();
        }
    });

    /**
     * Handle date of birth verification
     */
    function handleDateVerification() {
        var day = parseInt($('#hotrocketAgeGateDay').val());
        var month = parseInt($('#hotrocketAgeGateMonth').val());
        var year = parseInt($('#hotrocketAgeGateYear').val());
        var errorElement = $('#hotrocketAgeGateDateError');

        // Clear previous errors
        errorElement.hide().text('');

        // Validate inputs
        if (!day || !month || !year) {
            errorElement.text(hotrocketAgeGate.translations?.enterDate || 'Please enter your complete date of birth.').fadeIn();
            return;
        }

        // Validate day
        if (day < 1 || day > 31) {
            errorElement.text(hotrocketAgeGate.translations?.invalidDay || 'Please enter a valid day (1-31).').fadeIn();
            return;
        }

        // Validate month
        if (month < 1 || month > 12) {
            errorElement.text(hotrocketAgeGate.translations?.invalidMonth || 'Please enter a valid month (1-12).').fadeIn();
            return;
        }

        // Validate year
        var currentYear = new Date().getFullYear();
        if (year < 1900 || year > currentYear) {
            errorElement.text(hotrocketAgeGate.translations?.invalidYear || 'Please enter a valid year.').fadeIn();
            return;
        }

        // Create date object
        var birthDate = new Date(year, month - 1, day);
        
        // Validate date exists (e.g., Feb 30 doesn't exist)
        if (birthDate.getDate() !== day || birthDate.getMonth() !== (month - 1) || birthDate.getFullYear() !== year) {
            errorElement.text(hotrocketAgeGate.translations?.invalidDate || 'Please enter a valid date.').fadeIn();
            return;
        }

        // Calculate age
        var today = new Date();
        var age = today.getFullYear() - birthDate.getFullYear();
        var monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        // Check if age meets requirement
        var ageLimit = hotrocketAgeGate.ageLimit || 18;
        
        if (age >= ageLimit) {
            handleAgeVerification(true);
        } else {
            errorElement.text(
                (hotrocketAgeGate.translations?.tooYoung || 'You must be at least {age} years old to enter this site.').replace('{age}', ageLimit)
            ).fadeIn();
            
            // Optionally redirect after showing error
            setTimeout(function() {
                handleAgeVerification(false);
            }, 3000);
        }
    }

    /**
     * Handle age verification
     */
    function handleAgeVerification(verified) {
        if (verified) {
            // User confirmed they are of legal age
            var rememberMe = $('#hotrocketAgeGateRemember').length ? $('#hotrocketAgeGateRemember').is(':checked') : true;
            var cookieDuration = rememberMe ? hotrocketAgeGate.cookieDuration : 1; // 1 day if not remembered

            // Set cookie
            setCookie('hotrocket_age_verified', '1', cookieDuration);

            // Fire verified action hook
            $(document).trigger('hotrocket_age_gate_verified');

            // Fade out and remove overlay
            $('#hotrocketAgeGateOverlay').addClass('fade-out');
            setTimeout(function() {
                $('#hotrocketAgeGateOverlay').remove();
                $('body').removeClass('hotrocket-age-gate-active');
            }, 400);
        } else {
            // User is not of legal age - redirect
            // Fire denied action hook
            $(document).trigger('hotrocket_age_gate_denied');
            
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
