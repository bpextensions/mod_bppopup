import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';
import Cookies from 'js-cookie';

// Store popup display event into a cookie to prevent from reappearing
window.BPPopup = {
    'cookieDisplayEvent': (module_id, expire_timestamp) => {
        Cookies.set('bppopup_' + module_id, 1, {expires: expire_timestamp});
    }
}
